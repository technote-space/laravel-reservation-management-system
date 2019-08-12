<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\CreateRequest;
use App\Http\Requests\Reservation\ReservationCheckRequest;
use App\Http\Requests\Reservation\SearchRequest;
use App\Http\Requests\Reservation\UpdateRequest;
use App\Repositories\Crud\ReservationRepository;
use Illuminate\Http\JsonResponse;
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
     * @return JsonResponse
     */
    public function index(SearchRequest $request)
    {
        return response()->json($this->repository->all($request->getConditions()));
    }

    /**
     * @param  int  $primaryId
     *
     * @return JsonResponse
     */
    public function show($primaryId)
    {
        return response()->json($this->repository->get($primaryId));
    }

    /**
     * @param  CreateRequest  $request
     *
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(CreateRequest $request)
    {
        return response()->json($this->repository->create($request->getData()));
    }

    /**
     * @param  UpdateRequest  $request
     * @param  int  $primaryId
     *
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(UpdateRequest $request, $primaryId)
    {
        return response()->json($this->repository->update($primaryId, $request->getData()));
    }

    /**
     * @param  int  $primaryId
     *
     * @return JsonResponse
     */
    public function destroy($primaryId)
    {
        return response()->json($this->repository->delete($primaryId));
    }

    /**
     * @param  ReservationCheckRequest  $request
     *
     * @return JsonResponse
     */
    public function check(ReservationCheckRequest $request)
    {
        return response()->json($request->checkReservable());
    }
}
