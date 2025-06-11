<?php

declare(strict_types=1);

namespace App\Services\Database;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Grammars\PostgresGrammar as IlluminatePostgresGrammar;
use Illuminate\Support\Collection;

class PostgresGrammar extends IlluminatePostgresGrammar
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

        $columns = (new Collection($where['columns']))->map(function ($column) use ($language) {
            return "to_tsvector('{$language}', {$this->wrap($column)})";
        })->implode(' || ');

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
