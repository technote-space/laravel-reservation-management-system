<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\Room\CrudRequest;
use App\Http\Requests\Room\SearchRequest;
use App\Models\Room;
use Eloquent;
use Illuminate\Http\JsonResponse;
use Throwable;

class RoomController extends CrudController
{
    /**
     * @return string|Eloquent
     */
    protected function getTarget()
    {
        return Room::class;
    }

    /**
     * @return array
     */
    protected function getDetailEagerLoadingTargets(): array
    {
        return [
            'latestReservation',
            'latestUsage',
            'recentUsages',
        ];
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
     * @param  CrudRequest  $request
     *
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(CrudRequest $request)
    {
        return response()->json($request->store());
    }

    /**
     * @param  CrudRequest  $request
     * @param  int  $primaryId
     *
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(CrudRequest $request, $primaryId)
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
