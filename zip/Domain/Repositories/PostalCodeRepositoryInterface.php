<?php

namespace Zip\Domain\Repositories;

use Zip\Domain\Entities\PostalCode;

interface PostalCodeRepositoryInterface
{
    public function save(PostalCode $entity): ?PostalCode;

    public function findByPostalCode(string $postalCode): array;
}
