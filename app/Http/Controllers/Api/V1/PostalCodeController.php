<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use Illuminate\Http\JsonResponse;
use Zip\Application\UseCases\SearchUseCase;

class PostalCodeController extends Controller
{
    public function __invoke(SearchRequest $request, SearchUseCase $useCase): JsonResponse
    {
        return response()->json($useCase->handle($request->input('postal_code')));
    }
}
