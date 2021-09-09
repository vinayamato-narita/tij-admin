<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Log;

class DashboardController extends BaseController
{
    public function index()
    {
        return view('dashboard.index', [
            'title' => TITLE_HOME,
        ]);
    }
}
