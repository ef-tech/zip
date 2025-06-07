<?php

use App\Http\Controllers\Api\V1\PostalCodeController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/postal-code', PostalCodeController::class);
});
