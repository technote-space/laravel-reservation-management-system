<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

/**
 * Class IndexController
 * @package App\Http\Controllers
 */
class IndexController extends Controller
{
    public function index(): View
    {
        return view('index', [
            'params' => [

            ],
        ]);
    }
}
