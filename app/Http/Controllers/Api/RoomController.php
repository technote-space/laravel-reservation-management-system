<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Room\CrudRequest;
use App\Http\Requests\Room\SearchRequest;
use App\Repositories\Crud\RoomRepository;
use Illuminate\Http\JsonResponse;
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
        return response()->json($this->repository->create($request->getData()));
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
}
