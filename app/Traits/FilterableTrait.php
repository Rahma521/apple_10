<?php

namespace App\Traits;

use App\Models\Courses\Course;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Http\Request;

trait FilterableTrait
{
    public function filterableColumns(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->filterableColumns
        );
    }

    public function scopeFilter(Builder $query, Request $request, array $filterableColumns = []): Builder
    {
        $filters = $request->except(['page_count']);

        foreach ($filterableColumns as $filter) {
            $this->applyFilter($query, $filter, $filters);
        }

        return $query;
    }

    private function applyFilter(Builder $query, array $filter, array $filters): void
    {

        $columns = $filter['columns'] ?? null;
        $type = $filter['type'] ?? null;
        $searchKey = $filter['search_key'] ?? null;
        $startKey = $filter['start_key'] ?? null;
        $endKey = $filter['end_key'] ?? null;
        $multiple = $filter['multiple'] ?? false;
        match (true) {
            $type === 'average_stars' => $this->averageStarsFilter($query, $filters),
            $type === 'range' && $startKey && $endKey => $this->rangeFilter($query, $columns, $filters, $startKey, $endKey),

            $searchKey && array_key_exists($searchKey, $filters) && $filters[$searchKey] => $this->searchFilter($query, $columns, $filters[$searchKey]),

            is_array($columns) => $this->likeFilter($query, $columns, $filters),

            is_string($columns) => $this->equalFilter($query, $columns, $filters, $multiple, $type),
            default => throw new \InvalidArgumentException('Invalid filter data'),
        };
    }

    private function averageStarsFilter(Builder $query, array $filters): void
    {

        if (isset($filters['average_stars']) && $filters['average_stars']) {
            $query->select('courses.*')
                ->leftJoin('reviews', 'reviews.reviewable_id', '=', 'courses.id')
                ->where('reviews.reviewable_type', Course::class)
                ->groupBy('courses.id')
                ->havingRaw('AVG(reviews.stars) = ?', [$filters['average_stars']]);
        }
    }

    private function rangeFilter(Builder $query, $columns, array $filters, $startKey, $endKey): void
    {
        $startDate = $filters[$startKey] ?? null;
        $endDate = $filters[$endKey] ?? null;

        if ($startDate || $endDate) {
            $query->where(function ($q) use ($columns, $startDate, $endDate) {
                if ($startDate) {
                    $q->where($columns, '>=', $startDate);
                }
                if ($endDate) {
                    $q->where($columns, '<=', $endDate);
                }
            });
        }
    }

    private function likeFilter(Builder $query, array $columns, array $filters): void
    {
        foreach ($columns as $column) {
            if (array_key_exists($column, $filters) && $filters[$column]) {
                $query->where(function ($q) use ($filters, $column) {
                    $q->where($column, 'LIKE', "%{$filters[$column]}%");
                });
            }
        }
    }

    private function searchFilter(Builder $query, $columns, $searchValue): void
    {

        $query->where(function ($q) use ($columns, $searchValue) {
            $columns = (array) $columns; // Ensure columns is an array
            foreach ($columns as $column) {
                if (str_contains($column, '.')) {
                    [$relation, $relationColumn] = explode('.', $column);
                    $q->orWhereHas($relation, function ($relQuery) use ($relationColumn, $searchValue) {
                        $relQuery->where($relationColumn, 'LIKE', "%{$searchValue}%");
                    });
                } else {
                    $q->orWhere($column, 'LIKE', "%{$searchValue}%");
                }
            }
        });
    }

    private function equalFilter(Builder $query, $column, array $filters, $multiple, $type): void
    {
        if (array_key_exists($column, $filters) && $filters[$column]) {
            $query->when(true, function ($q) use ($multiple, $filters, $column, $type) {
                if ($type === 'equals') {
                    if ($multiple && is_array($filters[$column])) {
                        $q->whereIn($column, $filters[$column]);
                    } else {
                        $q->where($column, $filters[$column]);
                    }
                }
            });
        }
    }
}
