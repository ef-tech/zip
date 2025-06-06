<?php

namespace Tests\Zip\Infrastructure\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Zip\Domain\Entities\PostalCode;
use Zip\Infrastructure\Repositories\EloquentPostalCodeRepository;

class EloquentPostalCodeRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = new EloquentPostalCodeRepository();
    }

    public function test_save(): void
    {
        $input = [
            'id' => null,
            'jis_code' => '13104',
            'old_postal_code' => '162  ',
            'postal_code' => '1620825',
            'prefecture_kana' => 'トウキョウト',
            'city_kana' => 'シンジュクク',
            'town_kana' => 'カグラザカ',
            'prefecture' => '東京都',
            'city' => '新宿区',
            'town' => '神楽坂',
        ];

        $postalCode = new PostalCode(...$input);

        $savedPostalCode = $this->repository->save($postalCode);

        $this->assertInstanceOf(PostalCode::class, $savedPostalCode);
        $this->assertDatabaseHas('postal_codes', ['postal_code' => '1620825']);
    }

    public function test_find_by_postal_code_returns_matching_entities(): void
    {
        // NOTE: 13104,"162  ","1620825","トウキョウト","シンジュクク","カグラザカ","東京都","新宿区","神楽坂",0,0,1,0,0,0
        $input1 = [
            'id' => null,
            'jis_code' => '13104',
            'old_postal_code' => '162  ',
            'postal_code' => '1620825',
            'prefecture_kana' => 'トウキョウト',
            'city_kana' => 'シンジュクク',
            'town_kana' => 'カグラザカ',
            'prefecture' => '東京都',
            'city' => '新宿区',
            'town' => '神楽坂',
        ];

        // NOTE: 22304,"41505","4150533","シズオカケン","カモグンミナミイズチョウ","メラ","静岡県","賀茂郡南伊豆町","妻良",0,0,0,1,0,0
        // NOTE: 22304,"41505","4150533","シズオカケン","カモグンミナミイズチョウ","ヨシダ","静岡県","賀茂郡南伊豆町","吉田",0,0,0,1,0,0
        // NOTE: 22304,"41505","4150533","シズオカケン","カモグンミナミイズチョウ","タテイワ","静岡県","賀茂郡南伊豆町","立岩",0,0,0,1,0,0
        $input2 = [
            'id' => null,
            'jis_code' => '22304',
            'old_postal_code' => '41505',
            'postal_code' => '4150533',
            'prefecture_kana' => 'シズオカケン',
            'city_kana' => 'カモグンミナミイズチョウ',
            'town_kana' => 'メラ',
            'prefecture' => '静岡県',
            'city' => '賀茂郡南伊豆町',
            'town' => '妻良',
        ];
        $input3 = [
            'id' => null,
            'jis_code' => '22304',
            'old_postal_code' => '41505',
            'postal_code' => '4150533',
            'prefecture_kana' => 'シズオカケン',
            'city_kana' => 'カモグンミナミイズチョウ',
            'town_kana' => 'ヨシダ',
            'prefecture' => '静岡県',
            'city' => '賀茂郡南伊豆町',
            'town' => '吉田',
        ];
        $input4 = [
            'id' => null,
            'jis_code' => '22304',
            'old_postal_code' => '41505',
            'postal_code' => '4150533',
            'prefecture_kana' => 'シズオカケン',
            'city_kana' => 'カモグンミナミイズチョウ',
            'town_kana' => 'タテイワ',
            'prefecture' => '静岡県',
            'city' => '賀茂郡南伊豆町',
            'town' => '立岩',
        ];


        $postalCode1 = new PostalCode(...$input1);
        $postalCode2 = new PostalCode(...$input2);
        $postalCode3 = new PostalCode(...$input3);
        $postalCode4 = new PostalCode(...$input4);

        $this->repository->save($postalCode1);
        $this->repository->save($postalCode2);
        $this->repository->save($postalCode3);
        $this->repository->save($postalCode4);

        $foundPostalCodes = $this->repository->findByPostalCode('1620825');

        $this->assertCount(1, $foundPostalCodes);
        $this->assertInstanceOf(PostalCode::class, $foundPostalCodes[0]);
        $this->assertEquals('1620825', $foundPostalCodes[0]->postal_code);
        
        $foundPostalCodes = $this->repository->findByPostalCode('4150533');
        $this->assertCount(3, $foundPostalCodes);
        $this->assertInstanceOf(PostalCode::class, $foundPostalCodes[0]);
        $this->assertEquals('4150533', $foundPostalCodes[0]->postal_code);
        $this->assertEquals('4150533', $foundPostalCodes[1]->postal_code);
        $this->assertEquals('4150533', $foundPostalCodes[1]->postal_code);
        $this->assertEquals('妻良', $foundPostalCodes[0]->town);
        $this->assertEquals('吉田', $foundPostalCodes[1]->town);
        $this->assertEquals('立岩', $foundPostalCodes[2]->town);
    }

    public function test_find_by_postal_code_returns_empty_array_when_no_matches(): void
    {
        $foundPostalCodes = $this->repository->findByPostalCode('9999999');

        $this->assertIsArray($foundPostalCodes);
        $this->assertCount(0, $foundPostalCodes);
    }
}
