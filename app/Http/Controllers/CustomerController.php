<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function select(Request $request)
    {
        $search = $request->input('term');

        $customers = Customer::query()
            ->when($search, fn($query) => $query->where('name', 'like', "%{$search}%"))
            ->select('id', 'name as text')
            ->limit(10)
            ->get();

        return response()->json(['results' => $customers]);
    }
}
