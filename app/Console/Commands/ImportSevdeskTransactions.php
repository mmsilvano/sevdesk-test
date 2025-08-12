<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Services\SevDeskService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ImportSevdeskTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:sevdesk-transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import transactions from SevDesk API';

    /**
     * Execute the console command.
     */
    public function handle(SevDeskService $sevDeskService)
    {
        $this->info('Starting SevDesk transactions import...');

        $transactions = $sevDeskService->fetchTransactions();

        if (!$transactions) {
            $this->error('Failed to fetch transactions from SevDesk API');
            return 1;
        }

        $importedCount = 0;
        $skippedCount = 0;

        foreach ($transactions as $transactionData) {
            // Check if transaction already exists
            $existingTransaction = Transaction::where('sevdesk_id', $transactionData['id'] ?? null)->first();
            
            if ($existingTransaction) {
                $skippedCount++;
                continue;
            }

            try {
                Transaction::create([
                    'sevdesk_id' => $transactionData['id'] ?? '',
                    'amount' => $transactionData['amount'] ?? '',
                    'currency' => $transactionData['currency'] ?? null,
                    'purpose' => $transactionData['purpose'] ?? null,
                ]);

                $importedCount++;
            } catch (\Exception $e) {
                Log::error('Failed to import transaction', [
                    'transaction_data' => $transactionData,
                    'error' => $e->getMessage(),
                ]);
                
                $this->warn("Failed to import transaction ID: " . ($transactionData['id'] ?? 'unknown'));
            }
        }

        $this->info("Import completed!");
        $this->info("Imported: {$importedCount} transactions");
        $this->info("Skipped: {$skippedCount} transactions (already exist)");

        return 0;
    }
}
