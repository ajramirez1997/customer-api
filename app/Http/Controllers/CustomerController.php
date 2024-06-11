<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
        return Customer::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'age' => 'required|integer|min:0|max:150',
            'dob' => 'required|date',
            'email' => 'required|email|max:50|unique:customers,email',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $customer = Customer::create($request->all());
        return response()->json($customer, 201);
    }

    public function show($id)
    {
        return Customer::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'age' => 'required|integer|min:0|max:150',
            'dob' => 'required|date',
            'email' => 'required|email|max:50|unique:customers,email,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $customer = Customer::findOrFail($id);
        $customer->update($request->all());
        return response()->json($customer, 200);
    }

    public function destroy($id)
    {
        Customer::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
