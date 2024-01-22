<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CustomerImportService;

class ImportCustomersCommand extends Command
{
    protected $signature = 'import:customers';

    protected $description = 'Import customers from the data provider';

    public function handle(CustomerImportService $importService)
    {
        $importService->importCustomers();

        $this->info('Customers imported successfully!');
    }
}
