<?php

namespace App\Http\Controllers\APi;

use App\Http\Controllers\Controller;
use App\Models\SstockBrg;

class itemController extends Controller
{
    public function index()
    {
        $items = SstockBrg::all();
        return response()->json([
            'success'=> true,
            'data'=> $items
        ]);
    }
}
