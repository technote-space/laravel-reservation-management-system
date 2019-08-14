<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SummaryRequest;
use App\Http\Services\SummaryService;

/**
 * Class SummaryController
 * @package App\Http\Controllers\Api
 */
class SummaryController extends Controller
{
    /** @var SummaryService $service */
    private $service;

    public function __construct()
    {
        $this->service = new SummaryService();
    }

    /**
     * @param  SummaryRequest  $request
     *
     * @return array
     */
    public function index(SummaryRequest $request)
    {
        return $this->service->getSummary($request->getConditions());
    }
}
