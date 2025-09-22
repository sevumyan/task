<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\HasSort;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BaseListRequest extends FormRequest
{
    use HasSort;

    public const int PER_PAGE_DEFAULT = 25;
    public const int PAGE_DEFAULT = 1;
    public const string PER_PAGE = 'per_page';
    public const string PAGE = 'page';
    public const string Q = 'q';
    public const string SORT_ASC = 'asc';
    public const string SORT_DESC = 'desc';
    public const string SORTS = 'sort';
    public const string SORTS_KEY = 'sort.key.*';
    public const string SORTS_VALUE = 'sort.value.*';

    public function rules(): array
    {
        return [
            self::PER_PAGE => $this->getPerPageRule(),
            self::PAGE => [
                'integer',
                'nullable'
            ],
            self::Q => [
                'string',
                'nullable'
            ],
            self::SORTS => [
                'array',
                'nullable',
            ],
            self::SORTS_KEY => [
                'string',
                'nullable',
            ],
            self::SORTS_VALUE => [
                'string',
                'nullable',
                Rule::in([
                    self::SORT_ASC,
                    self::SORT_DESC,
                ]),
            ],
        ];
    }

    public function getPage(): int
    {
        return $this->get(self::PAGE) ?? self::PAGE_DEFAULT;
    }

    public function getPerPage(): int
    {
        return $this->get(self::PER_PAGE) ?? self::PER_PAGE_DEFAULT;
    }

    private function getPerPageRule(): string
    {
        return 'integer|max:100|min:1';
    }

    public function getSort(): ?array
    {
        $sort = $this->get(self::SORTS);

        return $this->transformSortKey($sort);
    }

    public function getQ(): ?string
    {
        return $this->get(self::Q);
    }
}
