<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\Reservation\CreateRequest;
use App\Http\Requests\Reservation\UpdateRequest;
use App\Models\Reservation;
use Eloquent;
use Illuminate\Http\JsonResponse;
use Throwable;

class ReservationController extends CrudController
{
    /**
     * @return string|Eloquent
     */
    protected function getTarget()
    {
        return Reservation::class;
    }

    /**
     * @return array
     */
    protected function getListEagerLoadingTargets(): array
    {
        return [
            'guest',
            'room',
        ];
    }

    /**
     * @return array
     */
    protected function getDetailEagerLoadingTargets(): array
    {
        return [
            'guest',
            'room',
        ];
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json($this->repository->all());
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
        return response()->json($request->store());
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
        return response()->json($request->update($primaryId));
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
}
