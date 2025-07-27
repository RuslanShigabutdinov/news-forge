<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'announcement' => $this->announcement,
            'body'         => $this->when($this->isDetailRoute($request), $this->body),
            'published_at' => $this->when(
                                $this->published_at,
                                fn () => $this->published_at->toIso8601String()
                              ),
            'author'  => new AuthorResource($this->whenLoaded('author')),
            'rubrics' => RubricResource::collection($this->whenLoaded('rubrics')),
        ];
    }
    private function isDetailRoute(Request $request): bool
    {
        return $request->routeIs('news.show');
    }
}
