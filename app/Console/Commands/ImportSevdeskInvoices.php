<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Services\SevDeskService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ImportSevdeskInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:sevdesk-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import invoices from SevDesk API';

    /**
     * Execute the console command.
     */
    public function handle(SevDeskService $sevDeskService)
    {
        $this->info('Starting SevDesk invoices import...');

        $invoicesData = $sevDeskService->fetchInvoices();

        if (!$invoicesData || !isset($invoicesData['objects'])) {
            $this->error('Failed to fetch invoices or no invoices found');
            return 1;
        }

        $invoices = $invoicesData['objects'];

        $importedCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;

        foreach ($invoices as $invoiceData) {
            try {
                $invoice = Invoice::updateOrCreate(
                    ['sevdesk_id' => $invoiceData['id'] ?? ''],
                    [
                        'invoice_number' => $invoiceData['invoiceNumber'] ?? null,
                        'invoice_date' => $invoiceData['invoiceDate'] ?? null,
                        'customer_name' => $invoiceData['addressName'] ?? null,
                        'currency' => $invoiceData['currency'] ?? null,
                        'total_amount' => $invoiceData['sumGross'] ?? null,
                        'paid_amount' => $invoiceData['paidAmount'] ?? null,
                        'status' => $invoiceData['status'] ?? null,
                    ]
                );

                if ($invoice->wasRecentlyCreated) {
                    $importedCount++;
                } else {
                    $updatedCount++;
                }
            } catch (\Exception $e) {
                Log::error('Failed to import invoice', [
                    'invoice_data' => $invoiceData,
                    'error' => $e->getMessage(),
                ]);

                $this->warn("Failed to import invoice ID: " . ($invoiceData['id'] ?? 'unknown'));
                $skippedCount++;
            }
        }

        $this->info("Import completed!");
        $this->info("Imported: {$importedCount} invoices");
        $this->info("Updated: {$updatedCount} invoices");
        $this->info("Skipped: {$skippedCount} invoices");

        return 0;
    }

}
