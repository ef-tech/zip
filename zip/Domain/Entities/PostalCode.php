<?php

namespace Zip\Domain\Entities;

class PostalCode
{
    public function __construct(
        public readonly ?int $id,
        public string $jis_code,
        public string $old_postal_code,
        public string $postal_code,
        public string $prefecture_kana,
        public string $city_kana,
        public string $town_kana,
        public string $prefecture,
        public string $city,
        public string $town,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'jis_code' => $this->jis_code,
            'old_postal_code' => $this->old_postal_code,
            'postal_code' => $this->postal_code,
            'prefecture_kana' => $this->prefecture_kana,
            'city_kana' => $this->city_kana,
            'town_kana' => $this->town_kana,
            'prefecture' => $this->prefecture,
            'city' => $this->city,
            'town' => $this->town,
        ];
    }
}
