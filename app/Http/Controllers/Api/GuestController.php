<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guest\CrudRequest;
use App\Http\Requests\Guest\SearchRequest;
use App\Repositories\Crud\GuestRepository;
use Illuminate\Http\JsonResponse;
use Throwable;

/**
 * Class GuestController
 * @package App\Http\Controllers\Api
 */
class GuestController extends Controller
{
    /** @var GuestRepository $repository */
    private $repository;

    /**
     * GuestController constructor.
     * @throws Throwable
     */
    public function __construct()
    {
        $this->repository = new GuestRepository();
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
