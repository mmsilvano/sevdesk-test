<?php
namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TransactionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Transaction::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/transaction');
        CRUD::setEntityNameStrings('transaction', 'transactions');
    }

    protected function setupListOperation()
    {
        // Define columns
        CRUD::addColumns([
            [
                'name' => 'id',
                'label' => 'ID',
                'type' => 'text',
            ],
            [
                'name' => 'valueDate',
                'label' => 'Value Date',
                'type' => 'text',
            ],
            [
                'name' => 'amount',
                'label' => 'Amount',
                'type' => 'text',
            ],
            [
                'name' => 'paymtPurpose',
                'label' => 'Purpose',
                'type' => 'text',
            ],
            [
                'name' => 'payeePayerName',
                'label' => 'Payee/Payer',
                'type' => 'text',
            ],
            [
                'name' => 'status',
                'label' => 'Status',
                'type' => 'text',
            ],
        ]);

        // Disable create/update/delete since this is read-only API data
        CRUD::denyAccess(['create', 'update', 'delete']);
    }

    /**
     * Override the search method to return API data
     */
    public function search()
    {
        $this->crud->hasAccessOrFail('list');

        // Get the API data
        $apiData = $this->fetchApiData();
        
        // Convert to the format DataTables expects
        $formattedData = [];
        foreach ($apiData as $item) {
            $row = [];
            foreach ($this->crud->columns() as $column) {
                $columnName = $column['name'];
                $value = $item[$columnName] ?? '';
                
                // Format specific columns
                if ($columnName === 'amount' && is_numeric($value)) {
                    $value = '€' . number_format($value, 2);
                } elseif ($columnName === 'valueDate' && $value) {
                    $value = date('Y-m-d', strtotime($value));
                }
                
                $row[] = $value;
            }
            // Add action buttons column
            $row[] = '<a href="#" class="btn btn-sm btn-link disabled">View</a>';
            $formattedData[] = $row;
        }

        return response()->json([
            'data' => $formattedData,
            'recordsTotal' => count($formattedData),
            'recordsFiltered' => count($formattedData),
        ]);
    }

    /**
     * Fetch data from SevDesk API
     */
    private function fetchApiData()
    {
        try {
            Log::info('Fetching data from SevDesk API');
            
            
            $response = Http::timeout(30)->withHeaders([
                'Authorization' => config('services.sevdesk.token'),
                'Content-Type' => 'application/json',
            ])->get('https://my.sevdesk.de/api/v1/CheckAccountTransaction');

            if ($response->successful()) {
                $data = $response->json();
                $objects = $data['objects'] ?? [];
                Log::info('API Response received', ['data_count' => count($objects)]);
                return $objects;
            }

            Log::error('SevDesk API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            

            return [];

        } catch (\Exception $e) {
            Log::error('Exception fetching SevDesk data', [
                'message' => $e->getMessage(),
            ]);
            return [];
        }
    }
}