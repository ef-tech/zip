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
}
