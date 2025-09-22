<?php

namespace App\Http\Resources\Task;

use App\Http\Resources\User\UserResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'user_id' => $this->resource->user_id,
            'status' => $this->resource->status,
            'priority' => $this->resource->priority,
            'user' => new UserResource($this->whenLoaded('user')),
            'comments' => TaskCommentResource::collection($this->whenLoaded('comments')),
            'created_at' => Carbon::parse($this->resource->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::parse($this->resource->updated_at)->format('Y-m-d H:i:s')
        ];
    }
}
