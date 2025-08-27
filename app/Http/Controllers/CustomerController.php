<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
     public function index()
    {
        return response()->json(Customer::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string',
            'email'   => 'nullable|email|unique:customers',
            'phone'   => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $customer = Customer::create($validated);

        return response()->json($customer, 201);
    }

    public function show(Customer $customer)
    {
        return response()->json($customer, 200);
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name'    => 'required|string',
            'email'   => 'nullable|email|unique:customers,email,' . $customer->id,
            'phone'   => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $customer->update($validated);

        return response()->json($customer, 200);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->json(['message' => 'Cliente eliminado'], 200);
    }
}
