<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use DataTables;
use App\Models\Customer;

class CustomerListController extends Controller
{
    public function fetchCustomerList(Request $request)
{

    $apiUrl = 'http://customer.api.local/api/customers';

    $accessToken = env('CLIENT_API');

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => false,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $accessToken,
        ],
    ]);

    $response = curl_exec($curl);

    if ($response === false) {
        $errorMessage = curl_error($curl);
        $errorCode = curl_errno($curl);
        return response()->json(['error' => 'Curl error: ' . $errorMessage . ' (Code: ' . $errorCode . ')'], 500);
    }

    curl_close($curl);

    $data = json_decode($response, true);

    return Datatables::of($data)
    ->addColumn('action', function($row){
        $btn = '<button type="button" class="btn btn-primary btn-sm customer-info" data-id="'. $row['id'] .'">View</button>
                <button type="button" class="btn btn-info btn-sm customer-update" data-id="'. $row['id'] .'">Update</button>
                <button type="button" class="btn btn-danger btn-sm customer-delete" data-id="'. $row['id'] .'">Delete</button>';
        return $btn;
    })
    ->rawColumns(['action'])
    ->make(true);
}

public function AddCustomer(Request $request)
{

    $apiUrl = 'http://customer.api.local/api/customers';

    $accessToken = env('CLIENT_API');

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

    $postData = [
        'first_name' => $request->input('first_name'),
        'last_name' => $request->input('last_name'),
        'age' => $request->input('age'),
        'dob' => $request->input('dob'),
        'email' => $request->input('email'),
    ];

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ],
        CURLOPT_POSTFIELDS => json_encode($postData),
    ]);

    $response = curl_exec($curl);

    if ($response === false) {
        $errorMessage = curl_error($curl);
        $errorCode = curl_errno($curl);
        return response()->json(['error' => 'cURL error: ' . $errorMessage . ' (Code: ' . $errorCode . ')'], 500);
    }

    curl_close($curl);

    $data = json_decode($response, true);

    return response()->json($data, 201);
}

public function ViewCustomer(Request $request)
{

    $apiUrl = 'http://customer.api.local/api/customers/'.$request->input('id');

    $accessToken = env('CLIENT_API');

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ],
    ]);

    $response = curl_exec($curl);

    if ($response === false) {
        $errorMessage = curl_error($curl);
        $errorCode = curl_errno($curl);
        return response()->json(['error' => 'cURL error: ' . $errorMessage . ' (Code: ' . $errorCode . ')'], 500);
    }

    curl_close($curl);

    $data = json_decode($response, true);

    return response()->json($data, 200);
}

public function UpdateCustomer(Request $request)
{

    $apiUrl = 'http://customer.api.local/api/customers/'.$request->input('id');

    $accessToken = env('CLIENT_API');

    $validator = Validator::make($request->all(), [
        'first_name' => 'required|string|max:50',
        'last_name' => 'required|string|max:50',
        'age' => 'required|integer|min:0|max:150',
        'dob' => 'required|date',
        'email' => 'required|email|max:50|unique:customers,email,' . $request->input('id'),
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $postData = [
        'id' => $request->input('id'),
        'first_name' => $request->input('first_name'),
        'last_name' => $request->input('last_name'),
        'age' => $request->input('age'),
        'dob' => $request->input('dob'),
        'email' => $request->input('email'),
    ];

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ],
        CURLOPT_POSTFIELDS => json_encode($postData),
    ]);

    $response = curl_exec($curl);

    if ($response === false) {
        $errorMessage = curl_error($curl);
        $errorCode = curl_errno($curl);
        return response()->json(['error' => 'cURL error: ' . $errorMessage . ' (Code: ' . $errorCode . ')'], 500);
    }

    curl_close($curl);

    $data = json_decode($response, true);

    return response()->json($data, 200);
}


public function DeleteCustomer(Request $request)
{

    $apiUrl = 'http://customer.api.local/api/customers/'.$request->input('id');

    $accessToken = env('CLIENT_API');

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'delete',
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ],
    ]);

    $response = curl_exec($curl);

    if ($response === false) {
        $errorMessage = curl_error($curl);
        $errorCode = curl_errno($curl);
        return response()->json(['error' => 'cURL error: ' . $errorMessage . ' (Code: ' . $errorCode . ')'], 500);
    }

    curl_close($curl);

    $data = json_decode($response, true);

    return response()->json($data, 200);
}

}
