<?php

namespace Tests\Zip\Application\UseCases;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Zip\Application\UseCases\SearchUseCase;
use Zip\Domain\Repositories\PostalCodeRepositoryInterface;

class SearchUseCaseTest extends TestCase
{
    private PostalCodeRepositoryInterface|MockObject $repository;
    private SearchUseCase $useCase;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(PostalCodeRepositoryInterface::class);

        $this->useCase = new SearchUseCase($this->repository);
    }

    public function testHandleReturnsPostalCodes()
    {
        // NOTE: 13104,"162  ","1620825","トウキョウト","シンジュクク","カグラザカ","東京都","新宿区","神楽坂",0,0,1,0,0,0
        $postalCode = '1620825';
        $expectedResult = [
            [
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
        ];

        $this->repository->expects($this->once())
            ->method('findByPostalCode')
            ->with($postalCode)
            ->willReturn($expectedResult);

        $result = $this->useCase->handle($postalCode);

        $this->assertEquals($expectedResult, $result);
    }
}
