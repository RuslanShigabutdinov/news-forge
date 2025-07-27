<?php

namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;

use App\Http\Requests\{
    StoreNewsRequest,
    UpdateNewsRequest,
    NewsSearchRequest
};
use App\Http\Resources\NewsResource;
use App\Models\News;

/**
 * @group Authenticated requests
 *
 * @authenticated
 */
class NewsController extends Controller
{
    public function __construct() {
        $this->authorizeResource(News::class, 'news');
    }

    public function index(): JsonResponse {
        $news = News::published()
                    ->with(['author'])
                    ->latest('published_at')
                    ->paginate(10);

        return NewsResource::collection($news)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewsRequest $request): JsonResponse {
        $data = $request->validated();

        $news = News::create($data);
        $news->rubrics()->sync($data['rubric_ids']);

        return (new NewsResource($news->load('author', 'rubrics')))
               ->response()->setStatusCode(201);
    }


    /**
     * Display the specified resource.
     */
    public function show(News $news): JsonResponse {
        $news->load(['author', 'rubrics']);
        return (new NewsResource($news))->response();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsRequest $request, News $news): JsonResponse {
        $data = $request->validated();

        $news->update(collect($data)->except('rubric_ids')->all());

        if (isset($data['rubric_ids'])) {
            $news->rubrics()->sync($data['rubric_ids']);
        }

        return (new NewsResource($news->load('author', 'rubrics')))->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news): JsonResponse
    {
        $news->delete();
        return response()->json(null, 204);
    }

    public function search(NewsSearchRequest $request) {
        $news = News::query()
            ->titleLike($request->validated('query'))
            ->with(['author.user', 'rubrics'])
            ->latest('published_at')
            ->paginate(10);

            return NewsResource::collection($news)->response();
    }
}
