<?php

declare(strict_types=1);

namespace App\Services\Database;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Tpetry\PostgresqlEnhanced\Query\Grammar;

class PostgresGrammar extends Grammar
{
    /**
     * @param  array<string, mixed>  $where
     */
    #[\Override]
    public function whereFullText(Builder $query, $where): string
    {
        $language = $where['options']['language'] ?? 'english';

        if (! in_array($language, $this->validFullTextLanguages(), true)) {
            $language = 'english';
        }

        $columns = new Collection($where['columns'])
            ->map(fn ($column): string => "to_tsvector('{$language}', {$this->wrap($column)})")
            ->implode(' || ');

        $mode = 'plainto_tsquery';

        if (($where['options']['mode'] ?? []) === 'phrase') {
            $mode = 'phraseto_tsquery';
        }

        if (($where['options']['mode'] ?? []) === 'websearch') {
            $mode = 'websearch_to_tsquery';
        }

        if (($where['options']['mode'] ?? []) === 'fuzzy_websearch') {
            return "({$columns}) @@ to_tsquery('{$language}', websearch_to_tsquery('simple', {$this->parameter($where['value'])})::text || ':*')";
        }

        return "({$columns}) @@ {$mode}('{$language}', {$this->parameter($where['value'])})";
    }
}
