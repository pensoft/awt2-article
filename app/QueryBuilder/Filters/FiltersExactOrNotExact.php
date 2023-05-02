<?php
namespace App\QueryBuilder\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\Filters\FiltersExact;

class FiltersExactOrNotExact extends FiltersExact
{
    const DEFAULT_NEGATIVE_CHAR = '!';

    protected string $prefix = self::DEFAULT_NEGATIVE_CHAR;

    public function __construct(bool $addRelationConstraint = true, string $prefix = self::DEFAULT_NEGATIVE_CHAR)
    {
        $this->addRelationConstraint = $addRelationConstraint;
        $this->prefix = $prefix;
    }

    public function __invoke(Builder $query, $value, string $property)
    {
        if ($this->addRelationConstraint) {
            if ($this->isRelationProperty($query, $property)) {
                $this->withRelationConstraint($query, $value, $property);

                return;
            }
        }

        if (is_array($value)) {
            // If our first value is prefixed with a negative key, treat as Not Exact
            if (count($value) > 0 && $this->isNegative($value[0])) {
                // Sanitise the entire array, all of our values could be prefixed
                $value = array_map(fn ($value) => $this->sanitise($value), $value);
                $query->whereNotIn($query->qualifyColumn($property), $value);
                return;
            }

            $query->whereIn($query->qualifyColumn($property), $value);
            return;
        }

        if ($this->isNegative($value)) {
            $query->where($query->qualifyColumn($property), '!=', $this->sanitise($value));

            return;
        }

        $query->where($query->qualifyColumn($property), '=', $value);
    }

    protected function isNegative(string $value): bool
    {
        return Str::startsWith($value, $this->prefix);
    }

    protected function sanitise(string $value): string
    {
        return Str::after($value, $this->prefix);
    }
}
