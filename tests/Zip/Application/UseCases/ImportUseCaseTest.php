<?php

namespace Tests\Zip\Application\UseCases;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Zip\Application\DTOs\PostalCode as PostalCodeData;
use Zip\Application\Mappers\PostalCode as PostalCodeMapper;
use Zip\Application\UseCases\ImportUseCase;
use Zip\Domain\Entities\PostalCode as PostalCodeEntity;
use Zip\Domain\Repositories\PostalCodeRepositoryInterface;

class ImportUseCaseTest extends TestCase
{
    private PostalCodeRepositoryInterface|MockObject $repository;
    private ImportUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(PostalCodeRepositoryInterface::class);

        $this->useCase = new ImportUseCase($this->repository);
    }

    public function test_handle_with_empty_input(): void
    {
        $this->repository->expects($this->never())
            ->method('save');

        $this->useCase->handle([]);
    }

    public function test_handle_with_single_postal_code(): void
    {
        // NOTE: 13104,"162  ","1620825","トウキョウト","シンジュクク","カグラザカ","東京都","新宿区","神楽坂",0,0,1,0,0,0
        $dto = new PostalCodeData(
            jis_code: '13104',
            old_postal_code: '162  ',
            postal_code: '1620825',
            prefecture_kana: 'トウキョウト',
            city_kana: 'シンジュクク',
            town_kana: 'カグラザカ',
            prefecture: '東京都',
            city: '新宿区',
            town: '神楽坂'
        );

        $entity = PostalCodeMapper::toEntity($dto);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->equalTo($entity))
            ->willReturn($entity);

        $this->useCase->handle([$dto]);
    }

    public function test_handle_with_multiple_postal_codes(): void
    {
        // NOTE: 13104,"162  ","1620825","トウキョウト","シンジュクク","カグラザカ","東京都","新宿区","神楽坂",0,0,1,0,0,0
        // NOTE: 13104,"160  ","1600013","トウキョウト","シンジュクク","カスミガオカマチ","東京都","新宿区","霞ケ丘町",0,0,0,0,0,0
        $dto1 = new PostalCodeData(
            jis_code: '13104',
            old_postal_code: '162  ',
            postal_code: '1620825',
            prefecture_kana: 'トウキョウト',
            city_kana: 'シンジュクク',
            town_kana: 'カグラザカ',
            prefecture: '東京都',
            city: '新宿区',
            town: '神楽坂'
        );

        $dto2 = new PostalCodeData(
            jis_code: '13104',
            old_postal_code: '160  ',
            postal_code: '1600013',
            prefecture_kana: 'トウキョウト',
            city_kana: 'シンジュクク',
            town_kana: 'カスミガオカマチ',
            prefecture: '東京都',
            city: '新宿区',
            town: '霞ケ丘町'
        );

        $entity1 = PostalCodeMapper::toEntity($dto1);
        $entity2 = PostalCodeMapper::toEntity($dto2);

        $calls = [];

        $this->repository->expects($this->exactly(2))
            ->method('save')
            ->willReturnCallback(function ($entity) use (&$calls, $entity1, $entity2) {
                $calls[] = $entity;

                if ($entity->postal_code === $entity1->postal_code) {
                    return $entity1;
                } else {
                    return $entity2;
                }
            });

        $this->useCase->handle([$dto1, $dto2]);

        $this->assertEquals(2, count($calls));
        $this->assertInstanceOf(PostalCodeEntity::class, $calls[0]);
        $this->assertInstanceOf(PostalCodeEntity::class, $calls[1]);
        $this->assertEquals($entity1->postal_code, $calls[0]->postal_code);
        $this->assertEquals($entity2->postal_code, $calls[1]->postal_code);
    }
}
