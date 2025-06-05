<?php

namespace Zip\Application\DTOs;

class PostalCode
{
    public function __construct(
        public string $jis_code,
        public string $old_postal_code,
        public string $postal_code,
        public string $prefecture_kana,
        public string $city_kana,
        public string $town_kana,
        public string $prefecture,
        public string $city,
        public string $town,
    ) {}
}
