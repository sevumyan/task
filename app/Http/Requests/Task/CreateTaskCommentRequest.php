<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

final class CreateTaskCommentRequest extends FormRequest
{
    private const string COMMENT = 'comment';
    private const string USER_ID = 'user_id';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::COMMENT => [
                'required',
                'string',
                'min:3',
            ],
            self::USER_ID => [
                'required',
                'integer',
                'exists:users,id',
            ],
        ];
    }

    public function getComment(): string
    {
        return $this->input(self::COMMENT);
    }

    public function getUserId(): int
    {
        return $this->input(self::USER_ID);
    }
}