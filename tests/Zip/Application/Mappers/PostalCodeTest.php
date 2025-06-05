<?php

namespace Tests\Zip\Application\Mappers;

use PHPUnit\Framework\TestCase;
use Zip\Application\DTOs\PostalCode as PostalCodeData;
use Zip\Application\Mappers\PostalCode as PostalCodeMapper;
use Zip\Domain\Entities\PostalCode as PostalCodeEntity;

class PostalCodeTest extends TestCase
{
    public function test_to_entity(): void
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

        $this->assertInstanceOf(PostalCodeEntity::class, $entity);
        $this->assertNull($entity->id);
        $this->assertEquals('13104', $entity->jis_code);
        $this->assertEquals('162  ', $entity->old_postal_code);
        $this->assertEquals('1620825', $entity->postal_code);
        $this->assertEquals('トウキョウト', $entity->prefecture_kana);
        $this->assertEquals('シンジュクク', $entity->city_kana);
        $this->assertEquals('カグラザカ', $entity->town_kana);
        $this->assertEquals('東京都', $entity->prefecture);
        $this->assertEquals('新宿区', $entity->city);
        $this->assertEquals('神楽坂', $entity->town);
    }

    public function test_to_entity_list(): void
    {
        // NOTE: 13104,"162  ","1620825","トウキョウト","シンジュクク","カグラザカ","東京都","新宿区","神楽坂",0,0,1,0,0,0
        // NOTE: 13104,"160  ","1600013","トウキョウト","シンジュクク","カスミガオカマチ","東京都","新宿区","霞ケ丘町",0,0,0,0,0,0
        $dtos = [
            new PostalCodeData(
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
            new PostalCodeData(
                jis_code: '13104',
                old_postal_code: '160  ',
                postal_code: '1600013',
                prefecture_kana: 'トウキョウト',
                city_kana: 'シンジュクク',
                town_kana: 'カスミガオカマチ',
                prefecture: '東京都',
                city: '新宿区',
                town: '霞ケ丘町'
            )
        ];

        $entities = PostalCodeMapper::toEntityList($dtos);

        $this->assertCount(2, $entities);
        $this->assertInstanceOf(PostalCodeEntity::class, $entities[0]);
        $this->assertInstanceOf(PostalCodeEntity::class, $entities[1]);

        $this->assertEquals('13104', $entities[0]->jis_code);
        $this->assertEquals('162  ', $entities[0]->old_postal_code);
        $this->assertEquals('1620825', $entities[0]->postal_code);
        $this->assertEquals('トウキョウト', $entities[0]->prefecture_kana);
        $this->assertEquals('シンジュクク', $entities[0]->city_kana);
        $this->assertEquals('カグラザカ', $entities[0]->town_kana);
        $this->assertEquals('東京都', $entities[0]->prefecture);
        $this->assertEquals('新宿区', $entities[0]->city);
        $this->assertEquals('神楽坂', $entities[0]->town);

        $this->assertEquals('13104', $entities[1]->jis_code);
        $this->assertEquals('160  ', $entities[1]->old_postal_code);
        $this->assertEquals('1600013', $entities[1]->postal_code);
        $this->assertEquals('トウキョウト', $entities[1]->prefecture_kana);
        $this->assertEquals('シンジュクク', $entities[1]->city_kana);
        $this->assertEquals('カスミガオカマチ', $entities[1]->town_kana);
        $this->assertEquals('東京都', $entities[1]->prefecture);
        $this->assertEquals('新宿区', $entities[1]->city);
        $this->assertEquals('霞ケ丘町', $entities[1]->town);
    }
}
