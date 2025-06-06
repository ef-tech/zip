<?php

namespace Zip\Application\UseCases;

use Zip\Domain\Repositories\PostalCodeRepositoryInterface;

class SearchUseCase
{
    private PostalCodeRepositoryInterface $repository;

    public function __construct(PostalCodeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(string $postalCode): array
    {
        return $this->repository->findByPostalCode($postalCode);
    }
}
