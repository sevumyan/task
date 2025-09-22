<?php

namespace App\Http\Requests\Task;

use App\Enums\Task\TaskPriority;
use App\Enums\Task\TaskStatus;
use App\Http\Requests\BaseListRequest;
use Illuminate\Validation\Rule;

final class IndexTaskRequest extends BaseListRequest
{
    private const string STATUS = 'status';
    private const string PRIORITY = 'priority';
    private const string USER_ID = 'user_id';

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            self::STATUS => [
                'nullable',
                'string',
                Rule::in(array_column(TaskStatus::cases(), 'value')),
            ],
            self::PRIORITY => [
                'nullable',
                'string',
                Rule::in(array_column(TaskPriority::cases(), 'value')),
            ],
            self::USER_ID => [
                'nullable',
                'integer',
                'exists:users,id',
            ],
        ]);
    }

    public function getStatus(): ?string
    {
        return $this->input(self::STATUS);
    }

    public function getPriority(): ?string
    {
        return $this->input(self::PRIORITY);
    }

    public function getUserId(): ?int
    {
        return $this->input(self::USER_ID);
    }
}
