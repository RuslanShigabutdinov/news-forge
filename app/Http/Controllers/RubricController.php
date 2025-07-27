<?php

namespace App\Http\Controllers;

use App\Http\Requests\{
    StoreRubricRequest,
    UpdateRubricRequest
};
use App\Http\Resources\{
    RubricResource,
    NewsResource
};
use App\Models\{
    Rubric,
    News
};
use Illuminate\Http\JsonResponse;

class RubricController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $tree = Rubric::defaultOrder()->get()->toTree();
        return RubricResource::collection($tree)->response();
    }


    public function store(StoreRubricRequest $request): JsonResponse
    {
        $data = $request->validated();

        $rubric = Rubric::create($data);
        return (new RubricResource($rubric))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Rubric $rubric): JsonResponse
    {
        $rubric->load('parent', 'children', 'news');
        return (new RubricResource($rubric))->response();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRubricRequest $request, Rubric $rubric): JsonResponse
    {
        $rubric->update($request->validated());
        return (new RubricResource($rubric->fresh()))->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rubric $rubric): JsonResponse
    {
        $rubric->delete();
        return response()->json(null, 204);
    }

    public function getNewsWithChildren(Rubric  $rubric): JsonResponse {
        $news = News::query()
        ->select(['news.*'])
        ->distinct()
        ->whereHas('rubrics', fn ($q) =>
            $q->withinTree($rubric)
        )
        ->with([
            'author.user',
            'rubrics'
        ])
        ->latest('published_at')
        ->paginate(10);

        // 3. Отдаём JSON через ресурс‑обёртку
        return response()->json(NewsResource::collection($news));
    }
}
