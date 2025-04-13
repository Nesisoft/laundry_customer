<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocalConfigController extends Controller
{
    /**
     * Display the dashboard page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('settings.index');
    }
}
