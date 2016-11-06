<?php namespace App\Http\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class UserController
 * @package ApiControllers
 */
class UserController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Show the application user details.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $request->user();
    }
}