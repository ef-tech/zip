<?php

namespace Tests\Zip\Domain\Repositories;

use GuzzleHttp\Client;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Zip\Domain\Repositories\PostalCodeRepository;
use Zip\Domain\Repositories\PostalCodeRepositoryInterface;
use Zip\Infrastructure\Repositories\EloquentPostalCodeRepository;
use Zip\Infrastructure\Repositories\ZipCloudPostalCodeRepository;

class PostalCodeRepositoryTest extends TestCase
{
    public function test_create_returns_eloquent_repository_instance(): void
    {
        $repository = PostalCodeRepository::create('eloquent');

        $this->assertInstanceOf(EloquentPostalCodeRepository::class, $repository);
        $this->assertInstanceOf(PostalCodeRepositoryInterface::class, $repository);
    }

    public function test_create_returns_zipcloud_repository_instance(): void
    {
        $repository = PostalCodeRepository::create('zipCloud');

        $this->assertInstanceOf(ZipCloudPostalCodeRepository::class, $repository);
        $this->assertInstanceOf(PostalCodeRepositoryInterface::class, $repository);
    }

    public function test_create_with_constructor_arguments(): void
    {
        $mockClient = $this->createMock(Client::class);
        $repository = PostalCodeRepository::create('zipCloud', [$mockClient]);

        $this->assertInstanceOf(ZipCloudPostalCodeRepository::class, $repository);
    }

    public function test_create_throws_exception_for_invalid_repository_type(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Repository class Zip\Infrastructure\Repositories\InvalidPostalCodeRepository does not exist.');

        PostalCodeRepository::create('invalid');
    }
}
