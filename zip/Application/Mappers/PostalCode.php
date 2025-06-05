<?php

namespace Zip\Application\Mappers;

use Zip\Application\DTOs\PostalCode as DTO;
use Zip\Domain\Entities\PostalCode as Entity;

class PostalCode
{
    public static function toEntity(DTO $dto): Entity
    {
        return new Entity(
            id: null, // ID is not part of the DTO, so we set it to null
            jis_code: $dto->jis_code,
            old_postal_code: $dto->old_postal_code,
            postal_code: $dto->postal_code,
            prefecture_kana: $dto->prefecture_kana,
            city_kana: $dto->city_kana,
            town_kana: $dto->town_kana,
            prefecture: $dto->prefecture,
            city: $dto->city,
            town: $dto->town
        );
    }

    public static function toEntityList(iterable $dtos): array
    {
        return collect($dtos)
            ->map(fn(DTO $dto) => self::toEntity($dto))
            ->all();
    }
}
