<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpaController extends Controller
{
    /**
     * Return the main Single Page Application view.
     */
    public function index()
    {
        return view('spa');
    }
}