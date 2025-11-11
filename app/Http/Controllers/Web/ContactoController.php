<?php
// ContactoController

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class ContactoController extends Controller
{
    public function send()
    {
        return view('pages.contacto');
    }
}
