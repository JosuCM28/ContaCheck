<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CfdiConvertController extends Controller
{
    public function form()
    {
        return view('cfdi.upload');
    }


}
