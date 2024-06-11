Introduction:

This project is built on the Laravel framework and encompasses both an application and an API, hosted on different ports to prevent conflicts. The application allows users to perform CRUD operations on customer data via an intuitive interface. For user authentication, Laravel Breeze, a minimal authentication starter kit, is utilized, providing a streamlined authentication process out of the box. Additionally, Laravel Passport, an OAuth2 server implementation, is integrated into the API for secure authentication and authorization mechanisms. This combination ensures robust user authentication and API protection while maintaining ease of use and scalability.

CustomerController.php:

This controller manages CRUD operations for customer data within the Laravel application. It serves as the API controller, providing data for the CustomerListController to consume. The methods in this controller handle API requests for retrieving all customers, storing a new customer, fetching a specific customer by ID, updating a customer's details, and deleting a customer.

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


CustomerListController.php:

This controller facilitates the interaction between the application and the API. It includes methods for fetching the list of customers from the API, adding a new customer via the API, viewing a specific customer's details, updating a customer's information, and deleting a customer. It relies on the CustomerController API endpoints to perform these actions.

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
    // Set API endpoint URL
    $apiUrl = 'http://localhost:4001/api/customers'; // Change this URL to match your Laravel API endpoint

    // Get the user's personal access token from the request
    $accessToken = env('CLIENT_API');

    // Initialize cURL session
    $curl = curl_init();

    // Set cURL options
    curl_setopt_array($curl, [
        CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => false, // Exclude response headers
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $accessToken,
        ],
    ]);

    // Execute cURL request
    $response = curl_exec($curl);

    // Check for curl errors
    if ($response === false) {
        $errorMessage = curl_error($curl);
        $errorCode = curl_errno($curl);
        return response()->json(['error' => 'Curl error: ' . $errorMessage . ' (Code: ' . $errorCode . ')'], 500);
    }

    // Close cURL session
    curl_close($curl);

    // Decode JSON response
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

    // Set API endpoint URL
    $apiUrl = 'http://localhost:4001/api/customers'; // Change this URL to match your Laravel API endpoint

    // Get the user's personal access token from the request
    $accessToken = env('CLIENT_API');

    // Validate the incoming request data
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

    // Prepare the data to be sent in the request body
    $postData = [
        'first_name' => $request->input('first_name'),
        'last_name' => $request->input('last_name'),
        'age' => $request->input('age'),
        'dob' => $request->input('dob'),
        'email' => $request->input('email'),
    ];

    // Initialize cURL session
    $curl = curl_init();

    // Set cURL options
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

    // Execute cURL request
    $response = curl_exec($curl);

    // Check for cURL errors
    if ($response === false) {
        $errorMessage = curl_error($curl);
        $errorCode = curl_errno($curl);
        return response()->json(['error' => 'cURL error: ' . $errorMessage . ' (Code: ' . $errorCode . ')'], 500);
    }

    // Close cURL session
    curl_close($curl);

    // Decode JSON response
    $data = json_decode($response, true);

    // Return the response data
    return response()->json($data, 201);
}

public function ViewCustomer(Request $request)
{

    // Set API endpoint URL
    $apiUrl = 'http://localhost:4001/api/customers/'.$request->input('id'); // Change this URL to match your Laravel API endpoint

    // Get the user's personal access token from the request
    $accessToken = env('CLIENT_API');

    // Initialize cURL session
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ],
    ]);

    // Execute cURL request
    $response = curl_exec($curl);

    // Check for cURL errors
    if ($response === false) {
        $errorMessage = curl_error($curl);
        $errorCode = curl_errno($curl);
        return response()->json(['error' => 'cURL error: ' . $errorMessage . ' (Code: ' . $errorCode . ')'], 500);
    }

    // Close cURL session
    curl_close($curl);

    // Decode JSON response
    $data = json_decode($response, true);

    // Return the response data
    return response()->json($data, 200);
}

public function UpdateCustomer(Request $request)
{

    // Set API endpoint URL
    $apiUrl = 'http://localhost:4001/api/customers/'.$request->input('id'); // Change this URL to match your Laravel API endpoint

    // Get the user's personal access token from the request
    $accessToken = env('CLIENT_API');

    // Validate the incoming request data
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

    // Prepare the data to be sent in the request body
    $postData = [
        'id' => $request->input('id'),
        'first_name' => $request->input('first_name'),
        'last_name' => $request->input('last_name'),
        'age' => $request->input('age'),
        'dob' => $request->input('dob'),
        'email' => $request->input('email'),
    ];

    // Initialize cURL session
    $curl = curl_init();

    // Set cURL options
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

    // Execute cURL request
    $response = curl_exec($curl);

    // Check for cURL errors
    if ($response === false) {
        $errorMessage = curl_error($curl);
        $errorCode = curl_errno($curl);
        return response()->json(['error' => 'cURL error: ' . $errorMessage . ' (Code: ' . $errorCode . ')'], 500);
    }

    // Close cURL session
    curl_close($curl);

    // Decode JSON response
    $data = json_decode($response, true);

    // Return the response data
    return response()->json($data, 200);
}


public function DeleteCustomer(Request $request)
{

    $apiUrl = 'http://localhost:4001/api/customers/'.$request->input('id');

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

customer.blade.php:

This Blade template file contains the HTML structure and JavaScript code for rendering the customer data table, adding new customers, viewing customer details, updating customer information, and deleting customers. It utilizes DataTables for data rendering and Bootstrap for styling.

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Data</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="mb-3">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCustomerModal">Add Customer</button>
        </div>
        <table id="customerTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Age</th>
                    <th>DOB</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCustomerModalLabel">Add Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addCustomerForm">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name">
                        </div>
                        <div class="form-group">
                            <label for="age">Age</label>
                            <input type="number" class="form-control" id="age" name="age">
                        </div>
                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" class="form-control" id="dob" name="dob">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveCustomer">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewCustomerModal" tabindex="-1" role="dialog" aria-labelledby="viewCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewCustomerModalLabel">View Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="viewCustomerForm">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="view_first_name" readonly>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" id="view_last_name" readonly>
                        </div>
                        <div class="form-group">
                            <label for="age">Age</label>
                            <input type="number" class="form-control" id="view_age" readonly>
                        </div>
                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" class="form-control" id="view_dob" readonly>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="view_email" readonly>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="updateCustomerModal" tabindex="-1" role="dialog" aria-labelledby="updateCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateCustomerModalLabel">Update Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="updateCustomerForm">
                        
                    <input type="text" class="form-control" id="update_id" hidden>
                        <div class="form-group">
                            <label for="update_first_name">First Name</label>
                            <input type="text" class="form-control" id="update_first_name">
                        </div>
                        <div class="form-group">
                            <label for="update_last_name">Last Name</label>
                            <input type="text" class="form-control" id="update_last_name">
                        </div>
                        <div class="form-group">
                            <label for="update_age">Age</label>
                            <input type="number" class="form-control" id="update_age">
                        </div>
                        <div class="form-group">
                            <label for="update_dob">Date of Birth</label>
                            <input type="date" class="form-control" id="update_dob" >
                        </div>
                        <div class="form-group">
                            <label for="update_email">Email</label>
                            <input type="email" class="form-control" id="update_email">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="updateCustomer">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this record?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            
            $('#customerTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {

                    url: '{{route("customer-list")}}',
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                },
                columns: [
                    {
                        data: 'first_name'
                    },
                    {
                        data: 'last_name'
                    },
                    {
                        data: 'age'
                    },
                    {
                        data: 'dob'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'action'
                    },
                ],
            });
            

            $('#saveCustomer').click(function(event) {
                // Prevent default form submission
                event.preventDefault();

                // Send AJAX request to add customer
                $.ajax({
                    url: '{{ route("save-customer") }}', // Replace with your route
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        first_name: $('#first_name').val(),
                        last_name: $('#last_name').val(),
                        age: $('#age').val(),
                        dob: $('#dob').val(),
                        email: $('#email').val(),
                    },
                    dataType: 'json',
                    success: function(response, status, xhr) {
                        if (xhr.status === 201) {
                            // Customer added successfully
                            alert('Customer successfully inserted.');
                            $('#addCustomerModal').modal('hide');
                            // Optionally, you can reload the datatable or update it with the new data
                            $('#customerTable').DataTable().ajax.reload();
                        } else {
                            // Handle other success responses if necessary
                            console.error('Unexpected response status:', xhr.status);
                            alert('Unexpected response. Please try again later.');
                        }
                    },
                    error: function(xhr, status, error) {
                        // An error occurred, handle it appropriately (e.g., display error message)
                        console.error('Error adding customer:', xhr.responseText);
                        // Display error message to the user (you can customize this part)
                        alert('Error adding customer. Please try again later.');
                    }
                });
            });
            
            $('#customerTable').on('click', '.customer-info', function() {
                
                var data_id = $(this).data('id');
                $.ajax({
                    url: '{{ route("view-customer") }}', // Replace with your route
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: data_id,
                    },
                    dataType: 'json',
                    success: function(data) {

                        $('#view_first_name').val(data.first_name ?? '---');
                        $('#view_last_name').val(data.last_name ?? '---');
                        $('#view_age').val(data.age ?? '---');
                        $('#view_dob').val(data.dob ?? '---');
                        $('#view_email').val(data.email ?? '---');
                        $('#viewCustomerModal').modal('show');

                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });

            });

            $('#customerTable').on('click', '.customer-update', function() {
                
                var data_id = $(this).data('id');
                $.ajax({
                    url: '{{ route("view-customer") }}',
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: data_id,
                    },
                    dataType: 'json',
                    success: function(data) {

                        $('#update_id').val(data_id);
                        $('#update_first_name').val(data.first_name ?? '');
                        $('#update_last_name').val(data.last_name ?? '');
                        $('#update_age').val(data.age ?? '');
                        $('#update_dob').val(data.dob ?? '');
                        $('#update_email').val(data.email ?? '');
                        $('#updateCustomerModal').modal('show');

                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });

            });
            
            $('#updateCustomer').click(function(event) {
                // Prevent default form submission
                event.preventDefault();

                // Send AJAX request to add customer
                $.ajax({
                    url: '{{ route("update-customer") }}', // Replace with your route
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: $('#update_id').val(),
                        first_name: $('#update_first_name').val(),
                        last_name: $('#update_last_name').val(),
                        age: $('#update_age').val(),
                        dob: $('#update_dob').val(),
                        email: $('#update_email').val(),
                    },
                    dataType: 'json',
                    success: function(response, status, xhr) {
                        if (xhr.status === 200) {
                            // Customer added successfully
                            alert('Customer successfully updated.');
                            $('#updateCustomerModal').modal('hide');
                            // Optionally, you can reload the datatable or update it with the new data
                            $('#customerTable').DataTable().ajax.reload();
                        } else {
                            // Handle other success responses if necessary
                            console.error('Unexpected response status:', xhr.status);
                            alert('Unexpected response. Please try again later.');
                        }
                    },
                    error: function(xhr, status, error) {
                        // An error occurred, handle it appropriately (e.g., display error message)
                        console.error('Error updating data of customer:', xhr.responseText);
                        // Display error message to the user (you can customize this part)
                        alert('Error updating data of customer. Please try again later.');
                    }
                });
            });

            $('#customerTable').on('click', '.customer-delete', function() {
                var data_id = $(this).data('id');
                $('#confirmDeleteBtn').data('customer-id', data_id);
                var customer = $('#confirmDeleteBtn').data('customer-id');
                $('#deleteConfirmationModal').modal('show');
            });

        // Handle click event on confirm delete button
        $('#confirmDeleteBtn').on('click', function() {

            var data_id = $(this).data('customer-id');

            $.ajax({
                url: '{{ route("delete-customer") }}',
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: data_id,
                },
                dataType: 'json',
                success: function(response, status, xhr) {
                    if (xhr.status === 200) {
                        // Customer added successfully
                        alert('Customer successfully deleted the customer.');
                        $('#deleteConfirmationModal').modal('hide');
                        // Optionally, you can reload the datatable or update it with the new data
                        $('#customerTable').DataTable().ajax.reload();
                    } else {
                        // Handle other success responses if necessary
                        console.error('Unexpected response status:', xhr.status);
                        alert('Unexpected response. Please try again later.');
                    }
                },
                error: function(xhr, status, error) {
                    // An error occurred, handle it appropriately (e.g., display error message)
                    console.error('Error deleting data of customer:', xhr.responseText);
                    // Display error message to the user (you can customize this part)
                    alert('Error deleting data of customer. Please try again later.');
                }
            });

        });

        });
    </script>
</body>
</html>

routes/api.php:

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::middleware('auth:api')->group(function () {
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::post('/customers', [CustomerController::class, 'store']);
    Route::get('/customers/{id}', [CustomerController::class, 'show']);
    Route::put('/customers/{id}', [CustomerController::class, 'update']);
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy']);
});

routes/web.php:

<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('customers');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::post('/customer-list', [App\Http\Controllers\CustomerListController::class, 'fetchCustomerList'])->name('customer-list');
Route::post('/save-customer', [App\Http\Controllers\CustomerListController::class, 'AddCustomer'])->name('save-customer');
Route::post('/view-customer', [App\Http\Controllers\CustomerListController::class, 'ViewCustomer'])->name('view-customer');
Route::post('/update-customer', [App\Http\Controllers\CustomerListController::class, 'UpdateCustomer'])->name('update-customer');
Route::post('/delete-customer', [App\Http\Controllers\CustomerListController::class, 'DeleteCustomer'])->name('delete-customer');

require __DIR__.'/auth.php';


Conclusion:

This Laravel project integrates an application and an API to manage customer data effectively. The separation of concerns between the application and the API allows for scalability and maintainability. The provided controllers, views, and routes offer a solid foundation for building upon and extending the functionality as needed.