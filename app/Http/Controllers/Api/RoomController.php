<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Room\CrudRequest;
use App\Http\Requests\Room\SearchRequest;
use App\Models\Traits\Searchable;
use App\Repositories\Crud\RoomRepository;
use Eloquent;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Throwable;

/**
 * Class RoomController
 * @package App\Http\Controllers\Api
 */
class RoomController extends Controller
{
    /** @var RoomRepository $repository */
    private $repository;

    /**
     * RoomController constructor.
     * @throws Throwable
     */
    public function __construct()
    {
        $this->repository = new RoomRepository();
    }

    /**
     * @param  SearchRequest  $request
     *
     * @return Searchable[]|LengthAwarePaginator|Builder[]|Collection|Model[]
     */
    public function index(SearchRequest $request)
    {
        return $this->repository->all($request->getConditions());
    }

    /**
     * @param  int  $primaryId
     *
     * @return Eloquent|Eloquent[]|Collection|Model
     */
    public function show($primaryId)
    {
        return $this->repository->get($primaryId);
    }

    /**
     * @param  CrudRequest  $request
     *
     * @return Eloquent|Model
     * @throws Throwable
     */
    public function store(CrudRequest $request)
    {
        return $this->repository->create($request->getData());
    }

    /**
     * @param  CrudRequest  $request
     * @param  int  $primaryId
     *
     * @return Eloquent|Model
     * @throws Throwable
     */
    public function update(CrudRequest $request, $primaryId)
    {
        return $this->repository->update($primaryId, $request->getData());
    }

    /**
     * @param  int  $primaryId
     *
     * @return array
     */
    public function destroy($primaryId)
    {
        return $this->repository->delete($primaryId);
    }
}
