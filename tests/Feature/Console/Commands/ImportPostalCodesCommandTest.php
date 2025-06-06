<?php

namespace Tests\Feature\Console\Commands;

use App\Console\Commands\ImportPostalCodesCommand;
use Illuminate\Support\Facades\File;
use Mockery;
use Tests\TestCase;
use Zip\Application\DTOs\PostalCode as PostalCodeDTO;
use Zip\Application\UseCases\ImportUseCase;

class ImportPostalCodesCommandTest extends TestCase
{
    /**
     * Test the command when the CSV file doesn't exist
     */
    public function test_command_fails_when_file_not_found(): void
    {
        // Ensure the file doesn't exist
        $filePath = storage_path('import/utf_ken_all.csv');
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        // Mock the ImportUseCase
        $this->mock(ImportUseCase::class, function ($mock) {
            $mock->shouldNotReceive('handle');
        });

        // Run the command
        $this->artisan('import:postal-codes')
            ->expectsOutput('File not found: ' . storage_path('import/utf_ken_all.csv'))
            ->assertExitCode(1);
    }

    /**
     * Test the command with a valid CSV file
     */
    public function test_command_imports_postal_codes(): void
    {
        // Create a test CSV file with 2 records
        $csvContent = "01101,\"060  \",\"0600000\",\"ホッカイドウ\",\"サッポロシチュウオウク\",\"イカニケイサイガナイバアイ\",\"北海道\",\"札幌市中央区\",\"以下に掲載がない場合\",0,0,0,0,0,0\n";
        $csvContent .= "01101,\"064  \",\"0640941\",\"ホッカイドウ\",\"サッポロシチュウオウク\",\"アサヒガオカ\",\"北海道\",\"札幌市中央区\",\"旭ケ丘\",0,0,1,0,0,0\n";

        // Ensure the directory exists
        $directory = storage_path('import');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Write the test CSV file
        $filePath = storage_path('import/utf_ken_all.csv');
        File::put($filePath, $csvContent);

        // Mock the ImportUseCase
        $this->mock(ImportUseCase::class, function ($mock) {
            $mock->shouldReceive('handle')
                ->once()
                ->withArgs(function ($postalCodes) {
                    // Verify that the correct number of PostalCodeDTOs are passed
                    $this->assertCount(2, $postalCodes);

                    // Verify the first PostalCodeDTO
                    $this->assertEquals('01101', $postalCodes[0]->jis_code);
                    $this->assertEquals('060', trim($postalCodes[0]->old_postal_code));
                    $this->assertEquals('0600000', $postalCodes[0]->postal_code);
                    $this->assertEquals('ホッカイドウ', $postalCodes[0]->prefecture_kana);
                    $this->assertEquals('サッポロシチュウオウク', $postalCodes[0]->city_kana);
                    $this->assertEquals('イカニケイサイガナイバアイ', $postalCodes[0]->town_kana);
                    $this->assertEquals('北海道', $postalCodes[0]->prefecture);
                    $this->assertEquals('札幌市中央区', $postalCodes[0]->city);
                    $this->assertEquals('以下に掲載がない場合', $postalCodes[0]->town);

                    return true;
                });
        });

        // Run the command
        $this->artisan('import:postal-codes')
            ->expectsOutput('Importing postal codes from CSV file...')
            ->expectsOutput('Import completed. Total records: 2')
            ->assertExitCode(0);

        // Clean up
        if (File::exists($filePath)) {
            File::delete($filePath);
        }
    }
}
