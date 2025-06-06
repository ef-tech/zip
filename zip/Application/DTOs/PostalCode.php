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
    ) {
        // NOTE: $town_kana, $town の "（*）" を削除する
        $this->town_kana = preg_replace('/（.*）/', '', $town_kana);
        $this->town = preg_replace('/（.*）/', '', $town);
    }
}
