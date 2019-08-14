<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * Class IndexController
 * @package App\Http\Controllers
 */
class IndexController extends Controller
{
    public function index(): View
    {
        return view('index');
    }

    /**
     * @return Admin|Authenticatable|null
     */
    public function user()
    {
        return Auth::user();
    }
}
