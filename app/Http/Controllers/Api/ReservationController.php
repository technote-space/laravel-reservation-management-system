<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\CreateRequest;
use App\Http\Requests\Reservation\ReservationCheckRequest;
use App\Http\Requests\Reservation\SearchRequest;
use App\Http\Requests\Reservation\UpdateRequest;
use App\Models\Traits\Searchable;
use App\Repositories\Crud\ReservationRepository;
use Eloquent;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Throwable;

/**
 * Class ReservationController
 * @package App\Http\Controllers\Api
 */
class ReservationController extends Controller
{
    /** @var ReservationRepository $repository */
    private $repository;

    /**
     * ReservationController constructor.
     * @throws Throwable
     */
    public function __construct()
    {
        $this->repository = new ReservationRepository();
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
     * @param  CreateRequest  $request
     *
     * @return Eloquent|Model
     * @throws Throwable
     */
    public function store(CreateRequest $request)
    {
        return $this->repository->create($request->getData());
    }

    /**
     * @param  UpdateRequest  $request
     * @param  int  $primaryId
     *
     * @return Eloquent|Model
     * @throws Throwable
     */
    public function update(UpdateRequest $request, $primaryId)
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

    /**
     * @param  ReservationCheckRequest  $request
     *
     * @return array
     */
    public function check(ReservationCheckRequest $request)
    {
        return $request->checkReservable();
    }
}
