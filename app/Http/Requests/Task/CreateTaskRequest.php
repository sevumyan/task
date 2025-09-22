<?php

namespace App\Http\Requests\Task;

use App\Enums\Task\TaskPriority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class CreateTaskRequest extends FormRequest
{
    private const string TITLE = 'title';
    private const string DESCRIPTION = 'description';
    private const string USER_ID = 'user_id';
    private const string PRIORITY = 'priority';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::TITLE => [
                'required',
                'string',
                'min:5',
                'max:100',
            ],
            self::DESCRIPTION => [
                'nullable',
                'string',
            ],
            self::USER_ID => [
                'nullable',
                'integer',
                'exists:users,id',
            ],
            self::PRIORITY => [
                'nullable',
                'string',
                Rule::in(array_column(TaskPriority::cases(), 'value')),
            ],
        ];
    }

    public function getTitle(): string
    {
        return $this->input(self::TITLE);
    }

    public function getDescription(): ?string
    {
        return $this->input(self::DESCRIPTION);
    }

    public function getUserId(): ?int
    {
        return $this->input(self::USER_ID);
    }

    public function getPriority(): ?string
    {
        return  $this->input(self::PRIORITY);

    }
}
