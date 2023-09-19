<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function store(Request $request)
    {
        // sample ---- search=type:customer&name=Customer02&email=email@gamix.co.nz&tax_number=5235667&currency_code=NZD&phone=567890&website=&address=20 Here Street&enabled=1&reference=84890&type=customer&city=Auckland&post_code=2016&country=New Zealand
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'tax_number' => 'nullable|numeric',
            'currency_code' => 'required|in:INR,USD',
            'phone' => 'nullable',
            'address' => 'nullable',
            'enabled' => 'nullable',
            'reference' => 'nullable',
            'website' => 'nullable',
            'city' => 'nullable',
            'post_code' => 'nullable',
            'country' => 'nullable',
        ]);
        $data = array_merge($validated, [
            'type' => 'customer',
            'search' => 'type:customer'
        ]);
        $users = app('akaunting')->post('contacts', $data);
        return response()->json($users);
    }

    public function index()
    {
        $users = app('akaunting')->get('contacts', [
            'search' => 'type:customer'
        ]);
        return response()->json($users);
    }
}
