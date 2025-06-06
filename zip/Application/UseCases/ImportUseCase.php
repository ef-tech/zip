<?php

namespace Zip\Application\UseCases;

use Zip\Application\DTOs\PostalCode;
use Zip\Application\Mappers\PostalCode as PostalCodeMapper;
use Zip\Domain\Repositories\PostalCodeRepositoryInterface;

class ImportUseCase
{
    private PostalCodeRepositoryInterface $repository;

    public function __construct(PostalCodeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param  iterable<PostalCode>  $postalCodes
     * @return void
     */
    public function handle(iterable $postalCodes): void
    {
        foreach ($postalCodes as $postalCode) {
            // TODO: バルクインサートに変更
            $entity = PostalCodeMapper::toEntity($postalCode);

            $this->repository->save($entity);
        }
    }
}
