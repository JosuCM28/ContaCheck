<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientPortalController extends Controller
{
    public function index() {
        return view('consumers.dashboard');
    }

    public function receipts() {
        return view('consumers.receipts');
    }

    public function files() {
        return view('consumers.files');
    }
}
