<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  \App\Http\Requests\Ferramenta1Request;

class MainController extends Controller
{
    public function ferramenta1_form()
    {
        return view('ferramenta1');
    }
    public function ferramenta1_action(Ferramenta1Request $request)
    {
        $validated = $request->validated();
        dd("Teste", $validated);
    }
}
