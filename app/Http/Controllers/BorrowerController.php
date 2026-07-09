<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use Illuminate\Http\Request;

class BorrowerController extends Controller
{
    public function storeAjax(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'divisi' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:255',
        ]);

        $borrower = Borrower::create($request->only('nama', 'divisi', 'telepon'));

        return response()->json([
            'success' => true,
            'data' => $borrower
        ]);
    }
}

