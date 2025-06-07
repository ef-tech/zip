<?php

namespace Zip\Infrastructure\Repositories;

use App\Models\PostalCode as EloquentPostalCode;
use Zip\Domain\Entities\PostalCode;
use Zip\Domain\Repositories\PostalCodeRepositoryInterface;

class EloquentPostalCodeRepository implements PostalCodeRepositoryInterface
{
    public function save(PostalCode $entity): ?PostalCode
    {
        $eloquent = new EloquentPostalCode();
        $eloquent->forceFill($entity->toArray());
        $eloquent->save();

        $eloquent->refresh();

        // EloquentPostalCode から PostalCode エンティティに変換（created_at と updated_at は除外）
        // TODO: trait を使って timestamps を除外する方法を検討
        //       or
        // TODO: Mapper を使って変換する方法を検討
        return new PostalCode(...collect($eloquent->toArray())->except(['created_at', 'updated_at'])->all());
    }

    /**
     * @return array<PostalCode>
     */
    public function findByPostalCode(string $postalCode): array
    {
        $collection = EloquentPostalCode::where('postal_code', $postalCode)->get();

        return $collection->map(function (EloquentPostalCode $eloquent) {
            // EloquentPostalCode から PostalCode エンティティに変換（created_at と updated_at は除外）
            // TODO: trait を使って timestamps を除外する方法を検討
            //       or
            // TODO: Mapper を使って変換する方法を検討
            return new PostalCode(...collect($eloquent->toArray())->except(['created_at', 'updated_at'])->all());
        })->all();
    }
}
