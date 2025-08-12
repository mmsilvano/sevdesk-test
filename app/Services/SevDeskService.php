<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SevDeskService
{
    private string $baseUrl = 'https://my.sevdesk.de/api/v1';

    /**
     * Fetch transactions from SevDesk API
     *
     * @return array|null
     */
    // In your SevDeskService.php or similar service class

    public function fetchTransactions(): ?array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => config('services.sevdesk.token'),
                'Content-Type' => 'application/json',
            ])->get('https://my.sevdesk.de/api/v1/CheckAccountTransaction');

            if ($response->successful()) {
                return $response->json('objects'); // return only the objects array
            }

            Log::error('SevDesk API error fetching transactions', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('SevDesk API exception fetching transactions', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }


    /**
     * Fetch invoices from SevDesk API
     *
     * @return array|null
     */
    public function fetchInvoices(): ?array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->getBearerToken(),
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . '/Invoice');

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('SevDesk API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('SevDesk API exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * Get the bearer token from config
     *
     * @return string
     */
    public function getBearerToken(): string
    {
        return config('services.sevdesk.token') ?? '4911e95da8c3a352c632576e7b7156d7';
    }
} 