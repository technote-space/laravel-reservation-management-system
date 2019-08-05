<?php
declare(strict_types=1);

namespace App\Repositories;

use Eloquent;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class CrudRepository
{
    /** @var string|Eloquent $model */
    private $model;

    /** @var string[] $listEagerLoading */
    private $listEagerLoading;

    /** @var string[] $detailEagerLoading */
    private $detailEagerLoading;

    /**
     * CrudRepository constructor.
     *
     * @param  string  $model
     * @param  array  $listEagerLoading
     * @param  array  $detailEagerLoading
     *
     * @throws Throwable
     */
    public function __construct(string $model, array $listEagerLoading, array $detailEagerLoading)
    {
        throw_if(! class_exists($model), Exception::class, "Class not exists: [{$model}]");
        throw_if($model instanceof Model, Exception::class, "Class is not Model: [{$model}]");
        $this->model              = $model;
        $this->listEagerLoading   = $listEagerLoading;
        $this->detailEagerLoading = $detailEagerLoading;
    }

    /**
     * @param  array  $eagerLoading
     *
     * @return Builder|Builder|string
     */
    private function with(array $eagerLoading)
    {
        return $this->model::with($eagerLoading);
    }

    /**
     * @param  int|null  $perPage
     *
     * @return LengthAwarePaginator
     */
    public function all(?int $perPage = null)
    {
        return $this->with($this->listEagerLoading)->paginate($perPage);
    }

    /**
     * @param  mixed  $primaryId
     *
     * @return Eloquent|Eloquent[]|Collection|Model
     */
    public function get($primaryId)
    {
        return $this->with($this->detailEagerLoading)->findOrFail((int) $primaryId);
    }

    /**
     * @param  mixed  $primaryId
     *
     * @return array
     */
    public function delete($primaryId)
    {
        return ['result' => $this->model::destroy((int) $primaryId)];
    }
}
