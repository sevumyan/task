<?php

namespace App\Http\Services\Task;

use App\Dto\Resource\GenericResourceDto;
use App\Http\Services\Task\Command\CreateTaskCommand;
use App\Http\Services\Task\Command\CreateTaskCommentCommand;
use App\Http\Services\Task\Command\Dto\CreateTaskCommentDto;
use App\Http\Services\Task\Command\Dto\CreateTaskDto;
use App\Http\Services\Task\Command\Dto\UpdateTaskStatusDto;
use App\Http\Services\Task\Command\UpdateTaskStatusCommand;
use App\Http\Services\Task\Query\Dto\IndexTaskDto;
use App\Http\Services\Task\Query\Dto\ShowTaskDto;
use App\Http\Services\Task\Query\IndexTaskQuery;
use App\Http\Services\Task\Query\ShowTaskQuery;

final class TaskService
{
    public function index(IndexTaskDto $dto): GenericResourceDto
    {
        return IndexTaskQuery::execute($dto);
    }

    public function store(CreateTaskDto $dto): GenericResourceDto
    {
        return CreateTaskCommand::execute($dto);
    }

    public function show(ShowTaskDto $dto): GenericResourceDto
    {
        return ShowTaskQuery::execute($dto);
    }

    public function updateStatus(UpdateTaskStatusDto $dto): GenericResourceDto
    {
        return UpdateTaskStatusCommand::execute($dto);
    }

    public function addComment(CreateTaskCommentDto $dto): GenericResourceDto
    {
        return CreateTaskCommentCommand::execute($dto);
    }
}
