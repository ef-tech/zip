<?php

namespace Tests\Zip\Infrastructure\Repositories;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Zip\Domain\Entities\PostalCode;
use Zip\Infrastructure\Repositories\ZipCloudPostalCodeRepository;

class ZipCloudPostalCodeRepositoryTest extends TestCase
{
    public function test_save_method_returns_null()
    {
        $repository = new ZipCloudPostalCodeRepository();
        $postalCode = new PostalCode(
            id: null,
            jis_code: '13104',
            old_postal_code: '162  ',
            postal_code: '1620825',
            prefecture_kana: 'トウキョウト',
            city_kana: 'シンジュクク',
            town_kana: 'カグラザカ',
            prefecture: '東京都',
            city: '新宿区',
            town: '神楽坂',
        );

        $result = $repository->save($postalCode);

        $this->assertNull($result);
    }

    public function test_find_by_postal_code_returns_empty_array_on_no_results()
    {
        $mockClient = $this->createMock(Client::class);
        $mockClient->method('get')->willReturn(new Response(200, [], json_encode(['results' => null])));
        $repository = new ZipCloudPostalCodeRepository($mockClient);

        $result = $repository->findByPostalCode('0000000');

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function test_find_by_postal_code_returns_postal_codes()
    {
        $mockResponseData = [
            'message' => null,
            'results' => [
                [
                    'zipcode' => '1620825',
                    'address1' => '東京都',
                    'address2' => '新宿区',
                    'address3' => '神楽坂',
                    'kana1' => 'ﾄｳｷｮｳﾄ',
                    'kana2' => 'ｼﾝｼﾞｭｸｸ',
                    'kana3' => 'ｶｸﾞﾗｻﾞｶ',
                ],
            ],
            'status' => 200,
        ];
        $mockClient = $this->createMock(Client::class);
        $mockClient->method('get')->willReturn(new Response(200, [], json_encode($mockResponseData)));
        $repository = new ZipCloudPostalCodeRepository($mockClient);

        $result = $repository->findByPostalCode('1620825');

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(PostalCode::class, $result[0]);
        $this->assertEquals('1620825', $result[0]->postal_code);
        $this->assertEquals('トウキョウト', $result[0]->prefecture_kana);
        $this->assertEquals('シンジュクク', $result[0]->city_kana);
        $this->assertEquals('カグラザカ', $result[0]->town_kana);
        $this->assertEquals('東京都', $result[0]->prefecture);
        $this->assertEquals('新宿区', $result[0]->city);
        $this->assertEquals('神楽坂', $result[0]->town);
    }
}
