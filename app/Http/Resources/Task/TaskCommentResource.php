<?php

namespace App\Http\Resources\Task;

use App\Http\Resources\User\UserResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskCommentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'task_id' => $this->resource->task_id,
            'user_id' => $this->resource->user_id,
            'comment' => $this->resource->comment,
            'user' => new UserResource($this->whenLoaded('user')),
            'created_at' => Carbon::parse($this->resource->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
