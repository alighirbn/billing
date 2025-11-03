<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class PaddleSandboxPurge extends Command
{
    protected $signature = 'paddle:sandbox-purge 
                            {--force : Skip confirmation}
                            {--local : Also clear local database}';
    
    protected $description = 'Cancel all Paddle sandbox subscriptions and optionally clear local database';

    private $apiKey;
    private $headers;
    private $stats = [
        'subscriptions_cancelled' => 0,
        'subscriptions_failed' => 0,
        'subscriptions_already_cancelled' => 0,
        'local_subscriptions_deleted' => 0,
        'local_users_updated' => 0,
    ];

    public function handle()
    {
        $this->apiKey = env('PADDLE_API_KEY');

        if (!$this->apiKey) {
            $this->error('❌ Missing PADDLE_API_KEY in .env');
            return Command::FAILURE;
        }

        // Verify it's a sandbox key
        if (!str_starts_with($this->apiKey, 'pdl_sdbx_')) {
            $this->error('❌ This command only works with sandbox keys (pdl_sdbx_...)');
            $this->error('Your key starts with: ' . substr($this->apiKey, 0, 10));
            return Command::FAILURE;
        }

        $this->headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Paddle-Version' => '1',
        ];

        // Show warning
        $this->warn('⚠️  WARNING: This will cancel all subscriptions in Paddle Sandbox!');
        $this->line('');
        
        $clearLocal = $this->option('local');
        
        $this->info('This will:');
        $this->line('  ✓ Cancel all active subscriptions');
        if ($clearLocal) {
            $this->line('  ✓ Clear local database subscriptions');
            $this->line('  ✓ Clear local user paddle_customer_id fields');
        }
        $this->line('');
        $this->comment('Note: Paddle does not allow deleting customers with transaction history.');
        $this->comment('Customers will remain in Paddle but all subscriptions will be cancelled.');
        $this->line('');

        if (!$this->option('force')) {
            if (!$this->confirm('Do you want to continue?', false)) {
                $this->info('Cancelled.');
                return Command::SUCCESS;
            }
        }

        $this->line('');
        $this->info('🚀 Starting Paddle Sandbox cleanup...');
        $this->line('');

        // Cancel all subscriptions
        $this->cancelSubscriptions();

        // Clear local database (if requested)
        if ($clearLocal) {
            $this->clearLocalDatabase();
        }

        // Show summary
        $this->showSummary();

        return Command::SUCCESS;
    }

    private function cancelSubscriptions()
    {
        $this->info('📋 Fetching subscriptions...');
        
        try {
            $response = Http::withHeaders($this->headers)
                ->get('https://sandbox-api.paddle.com/subscriptions', [
                    'per_page' => 200,
                ]);

            if ($response->failed()) {
                $this->error('Failed to fetch subscriptions: ' . $response->json('error.detail'));
                return;
            }

            $subscriptions = $response->json('data', []);

            if (empty($subscriptions)) {
                $this->line('  └─ No subscriptions found.');
                return;
            }

            $this->line("  └─ Found " . count($subscriptions) . " subscription(s)");
            
            $bar = $this->output->createProgressBar(count($subscriptions));
            $bar->setFormat('  Processing: %current%/%max% [%bar%] %percent:3s%%');
            $bar->start();

            foreach ($subscriptions as $sub) {
                $id = $sub['id'];
                $status = $sub['status'] ?? 'unknown';

                // Skip if already cancelled or past_due
                if (in_array($status, ['canceled', 'past_due', 'paused'])) {
                    $this->stats['subscriptions_already_cancelled']++;
                    if ($this->option('verbose')) {
                        $this->newLine();
                        $this->line("  ⏭ Skipping {$id} (already {$status})");
                    }
                    $bar->advance();
                    continue;
                }

                // Cancel if active
                $cancelResponse = Http::withHeaders($this->headers)
                    ->post("https://sandbox-api.paddle.com/subscriptions/{$id}/cancel", [
                        'effective_from' => 'immediately'
                    ]);

                if ($cancelResponse->successful()) {
                    $this->stats['subscriptions_cancelled']++;
                    if ($this->option('verbose')) {
                        $this->newLine();
                        $this->line("  ✓ Cancelled {$id}");
                    }
                } else {
                    $this->stats['subscriptions_failed']++;
                    $errorDetail = $cancelResponse->json('error.detail', 'Unknown error');
                    
                    if ($this->option('verbose')) {
                        $this->newLine();
                        $this->error("  ✗ Failed to cancel {$id}: {$errorDetail}");
                    }
                }

                $bar->advance();
            }

            $bar->finish();
            $this->line('');
            
        } catch (\Exception $e) {
            $this->error('Error processing subscriptions: ' . $e->getMessage());
        }
    }

    private function clearLocalDatabase()
    {
        $this->info('🗄️  Clearing local database...');
        
        try {
            // Delete all subscriptions
            $deletedSubscriptions = DB::table('subscriptions')->delete();
            $this->stats['local_subscriptions_deleted'] = $deletedSubscriptions;
            $this->line("  └─ Deleted {$deletedSubscriptions} subscription record(s)");

            // Clear paddle_customer_id from users
            $updatedUsers = DB::table('users')
                ->whereNotNull('paddle_customer_id')
                ->update(['paddle_customer_id' => null]);
            $this->stats['local_users_updated'] = $updatedUsers;
            $this->line("  └─ Cleared paddle_customer_id from {$updatedUsers} user(s)");

        } catch (\Exception $e) {
            $this->error('Error clearing local database: ' . $e->getMessage());
        }
    }

    private function showSummary()
    {
        $this->line('');
        $this->info('✅ Cleanup complete!');
        $this->line('');
        
        $totalProcessed = $this->stats['subscriptions_cancelled'] + 
                          $this->stats['subscriptions_already_cancelled'] + 
                          $this->stats['subscriptions_failed'];
        
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Subscriptions Processed', $totalProcessed],
                ['Successfully Cancelled', $this->stats['subscriptions_cancelled']],
                ['Already Cancelled/Inactive', $this->stats['subscriptions_already_cancelled']],
                ['Failed to Cancel', $this->stats['subscriptions_failed']],
                ['─────────────────────────', '─────'],
                ['Local Subscriptions Deleted', $this->stats['local_subscriptions_deleted']],
                ['Local Users Updated', $this->stats['local_users_updated']],
            ]
        );

        if ($this->stats['subscriptions_cancelled'] > 0) {
            $this->info('✨ Successfully cancelled ' . $this->stats['subscriptions_cancelled'] . ' subscription(s)!');
        }

        if ($this->stats['subscriptions_failed'] > 0) {
            $this->warn('⚠️  ' . $this->stats['subscriptions_failed'] . ' subscription(s) failed to cancel. Run with --verbose for details.');
        }

        if ($this->stats['subscriptions_already_cancelled'] > 0) {
            $this->comment('ℹ️  ' . $this->stats['subscriptions_already_cancelled'] . ' subscription(s) were already cancelled.');
        }
    }
}