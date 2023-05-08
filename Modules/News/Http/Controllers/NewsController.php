<?php

namespace Modules\News\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\News\Services\NewsService;
use Modules\News\Transformers\NewsApiResource;
use Modules\News\Transformers\NewYorkTimesApiResource;
use Modules\News\Transformers\TheGuardianApiResource;

class NewsController extends Controller
{
    public function __construct(
        protected NewsService $newsService
    ) {}

    public function getNewsFromNewsApi(Request $request) {
        try {

            $page = data_get($request->all(), 'page', 1);

            $theGuardianApi = $this->newsService->theGuardianWebhook($request->all());

            $newsApi = $this->newsService->newsApiWebhook($request->all());

            $theNewYorkTimesApi = $this->newsService->newYorkTimesWebhook($request->all());

            $theNewYorkTimesValue = array_slice($theNewYorkTimesApi->response->docs, ($page - 1) * 5, 5);

            $total = $theGuardianApi->response->total + $newsApi->totalResults + count($theNewYorkTimesApi->response->docs);

            $news = TheGuardianApiResource::collection($theGuardianApi->response->results)->merge(
                NewsApiResource::collection($newsApi->articles)
            );

            $news = $news->merge(
                NewYorkTimesApiResource::collection($theNewYorkTimesValue)
            );

            return response()->json([
                'total' => $total,
                'current_page' => $page,
                'news' => $news,
            ], JsonResponse::HTTP_OK);
        } catch (\Throwable $throwable) {
            return response()->json([
                "message" => $throwable->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
