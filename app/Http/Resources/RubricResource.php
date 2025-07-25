<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RubricResource extends JsonResource
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
            'body'         => $this->when($this->isDetail($request), $this->body),
            'published_at' => optional($this->published_at)->toDateTimeString(),
            'author'       => new AuthorResource($this->whenLoaded('author')),
            'rubrics'      => RubricResource::collection($this->whenLoaded('rubrics')),
        ];
    }
    private function isDetail($request): bool {
        return $request->routeIs('news.show');
    }
}
