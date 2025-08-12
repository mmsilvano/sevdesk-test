<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sampleTransactions = [
            [
                'sevdesk_id' => 'TRX001',
                'amount' => '1500.00',
                'currency' => 'EUR',
                'purpose' => 'Invoice payment for web development services',
            ],
            [
                'sevdesk_id' => 'TRX002',
                'amount' => '750.50',
                'currency' => 'EUR',
                'purpose' => 'Monthly hosting fee',
            ],
            [
                'sevdesk_id' => 'TRX003',
                'amount' => '2500.00',
                'currency' => 'EUR',
                'purpose' => 'Consulting services for project management',
            ],
            [
                'sevdesk_id' => 'TRX004',
                'amount' => '120.00',
                'currency' => 'EUR',
                'purpose' => 'Software license renewal',
            ],
            [
                'sevdesk_id' => 'TRX005',
                'amount' => '890.25',
                'currency' => 'EUR',
                'purpose' => 'Design and branding services',
            ],
        ];

        foreach ($sampleTransactions as $transaction) {
            Transaction::create($transaction);
        }
    }
}
