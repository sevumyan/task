<?php

namespace App\Http\Requests\Concerns;

trait HasSort
{
    public function transformSortKey(?array $sort): array
    {
        if (!$sort) {
            return [];
        }

        if (!empty($sort) && !isset($sort['key'])) {
            return [];
        }

        $result = [];
        foreach ($sort['key'] as $key => $field) {
            $result[] = [
                'key' => $sort['value'][$key] ?? self::SORT_ASC,
                'value' => $field,
            ];
        }

        return $result;
    }
}
