<?php

namespace Database\Seeders;

use App\Models\Invoice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sampleInvoices = [
            [
                'sevdesk_id' => 'INV001',
                'invoice_number' => '2024-001',
                'invoice_date' => '2024-01-15',
                'customer_name' => 'Acme Corporation',
                'currency' => 'EUR',
                'total_amount' => 2500.00,
                'paid_amount' => 2500.00,
                'status' => 'paid',
            ],
            [
                'sevdesk_id' => 'INV002',
                'invoice_number' => '2024-002',
                'invoice_date' => '2024-01-20',
                'customer_name' => 'Tech Solutions Ltd',
                'currency' => 'EUR',
                'total_amount' => 1800.50,
                'paid_amount' => 0.00,
                'status' => 'pending',
            ],
            [
                'sevdesk_id' => 'INV003',
                'invoice_number' => '2024-003',
                'invoice_date' => '2024-02-01',
                'customer_name' => 'Global Industries',
                'currency' => 'EUR',
                'total_amount' => 3200.75,
                'paid_amount' => 1600.38,
                'status' => 'partial',
            ],
            [
                'sevdesk_id' => 'INV004',
                'invoice_number' => '2024-004',
                'invoice_date' => '2024-02-10',
                'customer_name' => 'Startup Ventures',
                'currency' => 'EUR',
                'total_amount' => 950.25,
                'paid_amount' => 950.25,
                'status' => 'paid',
            ],
            [
                'sevdesk_id' => 'INV005',
                'invoice_number' => '2024-005',
                'invoice_date' => '2024-02-15',
                'customer_name' => 'Enterprise Solutions',
                'currency' => 'EUR',
                'total_amount' => 4500.00,
                'paid_amount' => 0.00,
                'status' => 'overdue',
            ],
        ];

        foreach ($sampleInvoices as $invoice) {
            Invoice::create($invoice);
        }
    }
}
