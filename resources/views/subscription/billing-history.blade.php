<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('subscription.manage') }}" class="inline-flex items-center text-sm text-gray-400 hover:text-white mb-4">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Manage Subscription
                </a>
                <h1 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-300 mb-2">
                    Billing History
                </h1>
                <p class="text-gray-400 text-lg">
                    View all your past transactions and invoices.
                </p>
            </div>

            <!-- Transactions List -->
            <div class="bg-gray-900/80 backdrop-blur-2xl rounded-3xl shadow-2xl overflow-hidden border border-gray-700/50">
                <div class="h-2 bg-gradient-to-r from-cyan-600 to-blue-600"></div>
                
                @if(count($transactions) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-700/50">
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Invoice</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700/50">
                                @foreach($transactions as $transaction)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            {{ \Carbon\Carbon::parse($transaction['created_at'])->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-white">
                                            @if(isset($transaction['items'][0]['price']['description']))
                                                {{ $transaction['items'][0]['price']['description'] }}
                                            @else
                                                Subscription Payment
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-white">
                                            @if(isset($transaction['details']['totals']['grand_total']))
                                                ${{ number_format($transaction['details']['totals']['grand_total'] / 100, 2) }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @php
                                                $status = $transaction['status'] ?? 'unknown';
                                                $statusColors = [
                                                    'completed' => 'text-green-400 bg-green-500/20 border-green-500/30',
                                                    'paid' => 'text-green-400 bg-green-500/20 border-green-500/30',
                                                    'billed' => 'text-blue-400 bg-blue-500/20 border-blue-500/30',
                                                    'canceled' => 'text-red-400 bg-red-500/20 border-red-500/30',
                                                    'past_due' => 'text-yellow-400 bg-yellow-500/20 border-yellow-500/30',
                                                ];
                                                $colorClass = $statusColors[$status] ?? 'text-gray-400 bg-gray-500/20 border-gray-500/30';
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $colorClass }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if(isset($transaction['invoice_number']))
                                                <a href="#" class="text-cyan-400 hover:text-cyan-300 font-medium">
                                                    Download
                                                </a>
                                            @else
                                                <span class="text-gray-500">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-gray-700/30 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">No Billing History</h3>
                        <p class="text-gray-400">You don't have any transactions yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>