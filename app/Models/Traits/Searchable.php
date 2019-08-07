<?php
declare(strict_types=1);

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait Searchable
 * @package App\Models\Traits
 * @mixin Model
 * @mixin Builder
 */
trait Searchable
{
    /** @var array $joined */
    private $joined = [];

    /** @var array $conditions */
    private $conditions;

    /**
     * @param  array  $conditions
     *
     * @return Model|Builder|Searchable
     */
    public function search(array $conditions)
    {
        $table = $this->getTable();
        $query = $this->newQuery();
        //        $this->setIdConditions($query, $conditions);
        $this->setConditions($query, $conditions);
        $this->joinTables($query);

        $selectTables = collect();
        foreach ($this->getOrderBy() as $k => $v) {
            $this->orderByRaw("$k $v");
            if (preg_match_all('#(\w+)\.\w+#', $k, $matches) > 0) {
                $selectTables = $selectTables->concat($matches[1]);
            }
        }

        $query->select($selectTables->concat([$table])->unique()->map(function ($table) {
            return "{$table}.*";
        })->toArray());

        return $query->distinct();
    }

    //    /**
    //     * @param  Builder  $query
    //     * @param  array  $conditions
    //     */
    //    private function setIdConditions(Builder $query, array $conditions)
    //    {
    //        $table = $this->getTable();
    //        if (! empty($conditions['id'])) {
    //            if (is_array($conditions['id'])) {
    //                $conditions['ids'] = $conditions['id'];
    //            } else {
    //                $query->where("{$table}.id", $conditions['id']);
    //            }
    //        }
    //        if (! empty($conditions['ids'])) {
    //            $query->whereIn("{$table}.id", $conditions['ids']);
    //        }
    //        if (! empty($conditions['not_id'])) {
    //            if (is_array($conditions['not_id'])) {
    //                $conditions['not_ids'] = $conditions['not_id'];
    //            } else {
    //                $query->where("{$table}.id", '!=', $conditions['not_id']);
    //            }
    //        }
    //        if (! empty($conditions['not_ids'])) {
    //            $query->whereNotIn("{$table}.id", $conditions['not_ids']);
    //        }
    //    }

    /**
     * @param  Builder  $query
     * @param  array  $conditions
     */
    abstract protected function setConditions(Builder $query, array $conditions);

    /**
     * @return array
     */
    protected function getJoinData(): array
    {
        return [];
    }

    /**
     * @return array
     */
    abstract protected function getOrderBy(): array;

    /**
     * @param  Builder  $query
     */
    private function joinTables(Builder $query)
    {
        foreach ($this->getJoinData() as $table => $join) {
            if (! empty($join['first'])) {
                if (empty($this->joined[$table])) {
                    $this->joinTable($query, $table, $join);
                    $this->joined[$table] = true;
                }
            }
        }
    }

    /**
     * @param  Builder  $query
     * @param  string  $table
     * @param  array  $join
     */
    private function joinTable(Builder $query, string $table, array $join)
    {
        $query->join(
            $table,
            $join['first'],
            $join['operator'] ?? (isset($join['second']) ? '=' : null),
            $join['second'] ?? null,
            $join['type'] ?? 'left',
            $join['where'] ?? false
        );
    }
}
