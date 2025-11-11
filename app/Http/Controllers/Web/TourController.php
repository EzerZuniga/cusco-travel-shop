<?php
// TourController

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class TourController extends Controller
{
    public function index()
    {
        return view('pages.tours');
    }
}
