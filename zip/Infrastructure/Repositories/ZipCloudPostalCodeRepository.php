<?php

namespace Zip\Infrastructure\Repositories;

use GuzzleHttp\Client;
use Zip\Domain\Entities\PostalCode;
use Zip\Domain\Repositories\PostalCodeRepositoryInterface;

class ZipCloudPostalCodeRepository implements PostalCodeRepositoryInterface
{
    public function __construct(private ?Client $client = null)
    {
        $this->client = $this->client ?? new Client();
    }

    public function save(PostalCode $entity): ?PostalCode
    {
        // NOTE: ZipCloud API does not support saving postal codes, so we return null.
        return null;
    }

    /**
     * @return array<PostalCode>
     */
    public function findByPostalCode(string $postalCode): array
    {
        $result = $this->client->get('https://zipcloud.ibsnet.co.jp/api/search', [
            'query' => [
                'zipcode' => $postalCode,
            ],
        ]);

        $postalCodes = json_decode($result->getBody()->getContents())->results ?? [];

        return array_map(function ($postalCode) {
            return new PostalCode(
                id: null,
                jis_code: '',
                old_postal_code: '',
                postal_code: $postalCode->zipcode,
                prefecture_kana: mb_convert_kana($postalCode->kana1, 'KVC', 'UTF-8'),
                city_kana: mb_convert_kana($postalCode->kana2, 'KVC', 'UTF-8'),
                town_kana: mb_convert_kana($postalCode->kana3, 'KVC', 'UTF-8'),
                prefecture: $postalCode->address1,
                city: $postalCode->address2,
                town: $postalCode->address3
            );
        }, $postalCodes);
    }
}
