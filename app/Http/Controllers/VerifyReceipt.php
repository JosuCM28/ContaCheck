<?php

namespace App\Http\Controllers;
use App\Models\Receipt;
use Illuminate\Http\Request;

class VerifyReceipt extends Controller
{
    public function __invoke($identificator){
        $receipt = Receipt::where('identificator', $identificator)->first();
        if(!$receipt){
            abort(404);
    }
    return view('receipts.verify',compact('receipt'));
}}