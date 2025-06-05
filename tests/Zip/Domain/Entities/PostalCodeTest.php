<?php

namespace Tests\Zip\Domain\Entities;

use PHPUnit\Framework\TestCase;
use Zip\Domain\Entities\PostalCode;

class PostalCodeTest extends TestCase
{
    public function test_to_array(): void
    {
        // NOTE: 13104,"162  ","1620825","トウキョウト","シンジュクク","カグラザカ","東京都","新宿区","神楽坂",0,0,1,0,0,0
        $expected = [
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

        $postalCode = new PostalCode(
            ...$expected
        );

        $this->assertEquals($expected, $postalCode->toArray());
    }
}
