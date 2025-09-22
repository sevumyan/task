<?php

namespace App\Http\Requests\Task;

use App\Enums\Task\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateTaskStatusRequest extends FormRequest
{
    private const string STATUS = 'status';
    private const string USER_ID = 'user_id';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::STATUS => [
                'required',
                'string',
                Rule::in(array_column(TaskStatus::cases(), 'value')),
            ],
            self::USER_ID => [
                'required',
                'integer',
                'exists:users,id',
            ],
        ];
    }

    public function getStatus(): string
    {
        return $this->input(self::STATUS);
    }

    public function getUserId(): int
    {
        return $this->input(self::USER_ID);
    }
}