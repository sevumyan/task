<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\CreateTaskCommentRequest;
use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Requests\Task\IndexTaskRequest;
use App\Http\Requests\Task\UpdateTaskStatusRequest;
use App\Http\Resources\Task\TaskCommentResource;
use App\Http\Resources\Task\TaskResource;
use App\Http\Services\Task\Command\Dto\CreateTaskCommentDto;
use App\Http\Services\Task\Command\Dto\CreateTaskDto;
use App\Http\Services\Task\Command\Dto\UpdateTaskStatusDto;
use App\Http\Services\Task\Query\Dto\IndexTaskDto;
use App\Http\Services\Task\Query\Dto\ShowTaskDto;
use App\Http\Services\Task\TaskService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{
    public function __construct(
       private readonly TaskService $taskService
    ) {
    }

    public function index(IndexTaskRequest $request): AnonymousResourceCollection
    {
        return TaskResource::collection(
            $this->taskService->index(
                new IndexTaskDto(
                    page: $request->getPage(),
                    perPage: $request->getPerPage(),
                    status: $request->getStatus(),
                    priority: $request->getPriority(),
                    userId: $request->getUserId()
                )
            )->value
        );
    }

    public function store(CreateTaskRequest $request): TaskResource
    {
        return new TaskResource(
            $this->taskService->store(
                new CreateTaskDto(
                    title: $request->getTitle(),
                    description: $request->getDescription(),
                    userId: $request->getUserId(),
                    priority: $request->getPriority()
                )
            )->value
        );
    }

    public function show(int $id): TaskResource
    {
        return new TaskResource(
            $this->taskService->show(
                new ShowTaskDto($id)
            )->value
        );
    }

    public function updateStatus(int $id, UpdateTaskStatusRequest $request): TaskResource
    {
        return new TaskResource(
            $this->taskService->updateStatus(
                new UpdateTaskStatusDto(
                    taskId: $id,
                    status: $request->getStatus(),
                    userId: $request->getUserId()
                )
            )->value
        );
    }

    public function addComment(int $id, CreateTaskCommentRequest $request): TaskCommentResource
    {
        return new TaskCommentResource(
            $this->taskService->addComment(
                new CreateTaskCommentDto(
                    taskId: $id,
                    comment: $request->getComment(),
                    userId: $request->getUserId()
                )
            )->value
        );
    }
}
