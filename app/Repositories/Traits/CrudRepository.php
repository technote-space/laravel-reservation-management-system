<?php
declare(strict_types=1);

namespace App\Repositories\Traits;

use App\Models\Contracts\Searchable as SearchableContract;
use App\Models\Traits\Searchable;
use Eloquent;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Throwable;

/**
 * Trait CrudRepository
 * @package App\Repositories\Traits
 */
trait CrudRepository
{
    /**
     * CrudRepository constructor.
     *
     * @throws Throwable
     */
    public function __construct()
    {
        $model = $this->getModel();
        throw_if(! class_exists($model), Exception::class, "Class not exists: [{$model}]");
        throw_if($model instanceof Model, Exception::class, "Class is not Model: [{$model}]");
        throw_if($model instanceof SearchableContract, Exception::class, "Class is not Searchable: [{$model}]");
    }

    /**
     * @return string|Eloquent
     */
    abstract protected function getModel(): string;

    /**
     * @return array
     */
    abstract protected function getListEagerLoading(): array;

    /**
     * @return array
     */
    abstract protected function getDetailEagerLoading(): array;

    /**
     * @return Eloquent|Model|SearchableContract
     */
    private function instance()
    {
        return $this->getModel()::newModelInstance();
    }

    /**
     * @return string
     */
    private function getForeignKey()
    {
        return $this->instance()->getForeignKey();
    }

    /**
     * @param  array  $conditions
     *
     * @return Searchable[]|LengthAwarePaginator|Builder[]|Collection|Model[]
     */
    public function all(array $conditions)
    {
        if (isset($conditions['count'])) {
            return $this->instance()->search($conditions)->with($this->getListEagerLoading())->get();
        }

        return $this->instance()->search($conditions)->with($this->getListEagerLoading())->paginate($conditions['per_page'] ?? null);
    }

    /**
     * @param  mixed  $primaryId
     *
     * @return Eloquent|Eloquent[]|Collection|Model
     */
    public function get($primaryId)
    {
        return $this->instance()->with($this->getDetailEagerLoading())->findOrFail((int) $primaryId);
    }

    /**
     * @param  \Illuminate\Support\Collection  $data
     *
     * @return Eloquent|Model
     */
    public function create(\Illuminate\Support\Collection $data)
    {
        $record = $this->getModel()::create($data->shift());
        if ($data->isNotEmpty()) {
            $foreignKey = $this->getForeignKey();
            $data->each(function ($data) use ($record, $foreignKey) {
                $relation = $data['relation'];
                $record->$relation()->create(array_merge($data['attributes'], [$foreignKey => $record->getAttribute('id')]));
            });
        }

        return $record;
    }

    /**
     * @param $primaryId
     * @param  \Illuminate\Support\Collection  $data
     *
     * @return Eloquent|Model
     */
    public function update($primaryId, \Illuminate\Support\Collection $data)
    {
        $record = $this->getModel()::findOrFail($primaryId);
        $record->fill($data->shift())->save();
        if ($data->isNotEmpty()) {
            $foreignKey = $this->getForeignKey();
            $data->each(function ($data) use ($record, $foreignKey) {
                $relation = $data['relation'];
                $record->$relation()->save($data['target']::updateOrCreate([$foreignKey => $record->getAttribute('id')], $data['attributes']));
            });
        }

        return $record;
    }

    /**
     * @param  mixed  $primaryId
     *
     * @return array
     */
    public function delete($primaryId)
    {
        return ['result' => $this->getModel()::destroy((int) $primaryId)];
    }
}
