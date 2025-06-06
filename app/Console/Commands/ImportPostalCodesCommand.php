<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Zip\Application\DTOs\PostalCode as PostalCodeDTO;
use Zip\Application\UseCases\ImportUseCase;

class ImportPostalCodesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:postal-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import postal codes from utf_ken_all.csv';

    /**
     * Create a new command instance.
     */
    public function __construct(private ImportUseCase $importUseCase)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = storage_path('import/utf_ken_all.csv');

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $this->info('Importing postal codes from CSV file...');

        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            $this->error("Could not open file: {$filePath}");
            return 1;
        }

        $count = 0;
        $postalCodes = [];

        while (($data = fgetcsv($handle)) !== false) {
            $postalCodes[] = new PostalCodeDTO(
                jis_code: $data[0],
                old_postal_code: trim($data[1]),
                postal_code: $data[2],
                prefecture_kana: $data[3],
                city_kana: $data[4],
                town_kana: $data[5],
                prefecture: $data[6],
                city: $data[7],
                town: $data[8]
            );

            $count++;

            // Process in batches to avoid memory issues
            if (count($postalCodes) >= 1000) {
                $this->importUseCase->handle($postalCodes);
                $this->info("Processed {$count} records...");
                $postalCodes = [];
            }
        }

        // Process any remaining records
        if (count($postalCodes) > 0) {
            $this->importUseCase->handle($postalCodes);
        }

        fclose($handle);

        $this->info("Import completed. Total records: {$count}");

        return 0;
    }
}
