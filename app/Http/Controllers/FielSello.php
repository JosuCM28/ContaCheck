<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FielSello extends Controller
{
    public function indexfiel(){
        return view('expirations.fiel');


    }

    public function indexsello(){
        return view('expirations.sello');


    }

}
