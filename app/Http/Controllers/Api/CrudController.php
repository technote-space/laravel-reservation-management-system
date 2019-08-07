<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\CrudRepository;
use Eloquent;
use Throwable;

abstract class CrudController extends Controller
{
    protected $repository;

    /**
     * CrudController constructor.
     * @throws Throwable
     */
    public function __construct()
    {
        $this->repository = new CrudRepository($this->getTarget(), $this->getListEagerLoadingTargets(), $this->getDetailEagerLoadingTargets());
    }

    /**
     * @return string|Eloquent
     */
    abstract protected function getTarget();

    /**
     * @return array
     */
    protected function getListEagerLoadingTargets(): array
    {
        // @codeCoverageIgnoreStart
        return [];
        // @codeCoverageIgnoreEnd
    }

    /**
     * @return array
     */
    protected function getDetailEagerLoadingTargets(): array
    {
        // @codeCoverageIgnoreStart
        return [];
        // @codeCoverageIgnoreEnd
    }
}
