<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\NewsResource;

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
            'id'       => $this->id,
            'name'     => $this->name,
            'children' => RubricResource::collection($this->whenLoaded('children')),
            'news' => NewsResource::collection($this->whenLoaded('news')),
        ];
    }
    private function isDetail($request): bool {
        return $request->routeIs('news.show');
    }
}
