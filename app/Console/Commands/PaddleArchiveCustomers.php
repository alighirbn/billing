<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class PaddleArchiveCustomers extends Command
{
    protected $signature = 'paddle:archive-customers 
                            {--force : Skip confirmation}';
    
    protected $description = 'Archive all Paddle sandbox customers by updating their names';

    public function handle()
    {
        $apiKey = env('PADDLE_API_KEY');

        if (!$apiKey || !str_starts_with($apiKey, 'pdl_sdbx_')) {
            $this->error('❌ Sandbox API key required');
            return Command::FAILURE;
        }

        $headers = [
            'Authorization' => 'Bearer ' . $apiKey,
            'Paddle-Version' => '1',
        ];

        $this->warn('⚠️  This will mark all customers as ARCHIVED in Paddle');
        $this->line('');

        if (!$this->option('force')) {
            if (!$this->confirm('Continue?', false)) {
                return Command::SUCCESS;
            }
        }

        $this->info('📋 Fetching customers...');
        
        $response = Http::withHeaders($headers)
            ->get('https://sandbox-api.paddle.com/customers', ['per_page' => 200]);

        if ($response->failed()) {
            $this->error('Failed: ' . $response->json('error.detail'));
            return Command::FAILURE;
        }

        $customers = $response->json('data', []);
        
        if (empty($customers)) {
            $this->info('No customers found.');
            return Command::SUCCESS;
        }

        $this->line("Found " . count($customers) . " customer(s)");
        
        $bar = $this->output->createProgressBar(count($customers));
        $archived = 0;

        foreach ($customers as $customer) {
            $id = $customer['id'];
            $currentName = $customer['name'] ?? '';
            
            // Skip if already archived
            if (str_starts_with($currentName, '[ARCHIVED]')) {
                $bar->advance();
                continue;
            }

            // Archive by updating name
            $updateResponse = Http::withHeaders($headers)
                ->patch("https://sandbox-api.paddle.com/customers/{$id}", [
                    'name' => '[ARCHIVED] ' . ($currentName ?: $customer['email']),
                ]);

            if ($updateResponse->successful()) {
                $archived++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->line('');
        $this->info("✅ Archived {$archived} customer(s)");

        return Command::SUCCESS;
    }
}