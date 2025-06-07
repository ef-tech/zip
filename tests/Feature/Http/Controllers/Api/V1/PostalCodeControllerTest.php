<?php

namespace Feature\Http\Controllers\Api\V1;

// この行を確認・追加してください
use App\Http\Controllers\Api\V1\PostalCodeController;
use App\Http\Requests\SearchRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Testing\TestCase;
use Zip\Application\UseCases\SearchUseCase;
use Zip\Domain\Entities\PostalCode;

class PostalCodeControllerTest extends TestCase
{
    public function test_returns_a_successful_response_for_valid_postal_code()
    {
        $mockSearchUseCase = $this->createMock(SearchUseCase::class);

        // NOTE: 13104,"162  ","1620825","トウキョウト","シンジュクク","カグラザカ","東京都","新宿区","神楽坂",0,0,1,0,0,0
        $mockSearchUseCase->method('handle')->with('1620825')->willReturn([
            new PostalCode(
                id: 1,
                jis_code: '13104',
                old_postal_code: '162  ',
                postal_code: '1620825',
                prefecture_kana: 'トウキョウト',
                city_kana: 'シンジュクク',
                town_kana: 'カグラザカ',
                prefecture: '東京都',
                city: '新宿区',
                town: '神楽坂'
            ),
        ]);

        $request = new SearchRequest(['postal_code' => '1620825']);
        $controller = new PostalCodeController();

        $response = $controller($request, $mockSearchUseCase);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals([
            [
                'id' => 1,
                'jis_code' => '13104',
                'old_postal_code' => '162  ',
                'postal_code' => '1620825',
                'prefecture_kana' => 'トウキョウト',
                'city_kana' => 'シンジュクク',
                'town_kana' => 'カグラザカ',
                'prefecture' => '東京都',
                'city' => '新宿区',
                'town' => '神楽坂',
            ]
        ], $response->getData(true));
    }

    public function test_handles_empty_postal_code_gracefully()
    {
        $mockSearchUseCase = $this->createMock(SearchUseCase::class);
        $mockSearchUseCase->method('handle')->with('')->willReturn([]);

        $request = new SearchRequest(['postal_code' => '']);
        $controller = new PostalCodeController();

        $response = $controller($request, $mockSearchUseCase);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals([], $response->getData(true));
    }

    public function test_handles_nonexistent_postal_code()
    {
        $mockSearchUseCase = $this->createMock(SearchUseCase::class);
        $mockSearchUseCase->method('handle')->with('9999999')->willReturn([]);

        $request = new SearchRequest(['postal_code' => '9999999']);
        $controller = new PostalCodeController();

        $response = $controller($request, $mockSearchUseCase);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals([], $response->getData(true));
    }
}
