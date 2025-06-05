<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('postal_codes', function (Blueprint $table) {
            $table->id();
            $table->string('jis_code')->comment('全国地方公共団体コード（JIS）');
            $table->string('old_postal_code')->comment('旧郵便番号（5桁）');
            $table->string('postal_code')->comment('現行郵便番号（7桁）');
            $table->string('prefecture_kana')->comment('都道府県名（カナ）');
            $table->string('city_kana')->comment('市区町村名（カナ）');
            $table->string('town_kana')->comment('町域名（カナ）');
            $table->string('prefecture')->comment('都道府県名（漢字）');
            $table->string('city')->comment('市区町村名（漢字）');
            $table->string('town')->comment('町域名（漢字）');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postal_codes');
    }
};
