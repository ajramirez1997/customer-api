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
                event.preventDefault();

                $.ajax({
                    url: '{{ route("save-customer") }}',
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
                            alert('Customer successfully inserted.');
                            $('#addCustomerModal').modal('hide');
                            $('#customerTable').DataTable().ajax.reload();
                        } else {
                            console.error('Unexpected response status:', xhr.status);
                            alert('Unexpected response. Please try again later.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error adding customer:', xhr.responseText);
                        alert('Error adding customer. Please try again later.');
                    }
                });
            });
            
            $('#customerTable').on('click', '.customer-info', function() {
                
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
                event.preventDefault();

                $.ajax({
                    url: '{{ route("update-customer") }}',
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
                            alert('Customer successfully updated.');
                            $('#updateCustomerModal').modal('hide');
                            $('#customerTable').DataTable().ajax.reload();
                        } else {
                            console.error('Unexpected response status:', xhr.status);
                            alert('Unexpected response. Please try again later.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating data of customer:', xhr.responseText);
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
                        alert('Customer successfully deleted the customer.');
                        $('#deleteConfirmationModal').modal('hide');
                        $('#customerTable').DataTable().ajax.reload();
                    } else {
                        console.error('Unexpected response status:', xhr.status);
                        alert('Unexpected response. Please try again later.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error deleting data of customer:', xhr.responseText);
                    alert('Error deleting data of customer. Please try again later.');
                }
            });

        });

        });
    </script>
</body>
</html>
