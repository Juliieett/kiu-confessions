<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfessionResource extends JsonResource
{
    /**
     * Transform the confession into a JSON-friendly array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                       => $this->id,
            'post_number'              => $this->postNumber(),
            'title'                    => $this->title,
            'description'              => $this->description,
            'status'                   => $this->status,
            'category'                 => new CategoryResource($this->whenLoaded('category')),
            'tags'                     => TagResource::collection($this->whenLoaded('tags')),
            'referenced_confession_id' => $this->referenced_confession_id,
            'likes_count'              => $this->when(isset($this->likes_count), $this->likes_count),
            'comments_count'           => $this->when(isset($this->comments_count), $this->comments_count),
            'deadline'                 => $this->deadline?->toDateString(),
            'image_url'                => $this->imageUrl(),
            'created_at'               => $this->created_at?->toIso8601String(),
            'updated_at'               => $this->updated_at?->toIso8601String(),
        ];
    }
}
