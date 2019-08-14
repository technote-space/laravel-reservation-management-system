<?php
declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Traits\Searchable;
use Eloquent;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface CrudRepository
 * @package App\Repositories\Contracts
 */
interface CrudRepository
{
    /**
     * @param  array  $conditions
     *
     * @return Searchable[]|LengthAwarePaginator|Builder[]|Collection|Model[]
     */
    public function all(array $conditions);

    /**
     * @param  mixed  $primaryId
     *
     * @return Eloquent|Eloquent[]|Collection|Model
     */
    public function get($primaryId);

    /**
     * @param  \Illuminate\Support\Collection  $data
     *
     * @return Eloquent|Model
     */
    public function create(\Illuminate\Support\Collection $data);

    /**
     * @param $primaryId
     * @param  \Illuminate\Support\Collection  $data
     *
     * @return Eloquent|Model
     */
    public function update($primaryId, \Illuminate\Support\Collection $data);

    /**
     * @param  mixed  $primaryId
     *
     * @return array
     */
    public function delete($primaryId);
}
