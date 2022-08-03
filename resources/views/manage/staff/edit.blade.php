@extends('layouts.app')

@section('content')

@if(session()->has('success'))
    <div class="alert alert-success d-flex justify-content-center message" id="message">
        {{ session()->get('success') }}
    </div>

    <script>
        setTimeout(function(){
        if ($('#message').length > 0) {
            $('#message').remove();
            }
        }, 3000)
    </script>
@endif

<section class="content mt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
            <h4 class="mb-3">Staff: {{ $staff->fname }} {{ $staff->lname }}</h4>
            <div class="card card-light">
                <div class="card-header">
                    <button class="btn btn-success"><a href="/staff" class="text-white" id="backButton">Back</a></button>
                    <button class="btn btn-success float-right" id="editButton" onclick="able()">Click to Edit</button>
                </div>    
                <form id="editStaffForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fname">First Name</label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <input type="text" class="form-control" value="{{ $staff->fname }}" id="fname" name="fname" disabled>
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="fnameError"></strong></small>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lname">Last Name</label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <input type="text" class="form-control" value="{{ $staff->lname }}" id="lname" name="lname" disabled>
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="lnameError"></strong></small>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <input type="date" class="form-control" value="{{ $staff->start_date }}" id="start_date" name="start_date" disabled>
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="start_dateError"></strong></small>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <input type="text" class="form-control" value="{{ $staff->phone_number }}" id="phone" name="phone" disabled>
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="phoneError"></strong></small>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <textarea type="text" class="form-control" rows="5" value="{{ old('address') }}" id="address" name="address" disabled>{{ $staff->address }}</textarea>
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="addressError"></strong></small>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="role">Role</label>
                                <div class="form-group">
                                    <select class="form-control" name="role" id="role" disabled>
                                        <option value="Master-Admin" id="master_admin">Master-Admin</option>
                                        <option value="Cashier" id="cashier">Cashier</option>
                                        <option value="Waiter" id="waiter">Waiter</option>
                                        <option value="Kitchen Staff" id="kitchen_staff">Kitchen Staff</option>
                                    </select>
                                    @if ($staff->role == "Master-Admin")
                                        <script>
                                            document.getElementById("master_admin").selected = true;
                                        </script>
                                    @elseif ($staff->role == "Cashier")
                                        <script>
                                            document.getElementById("cashier").selected = true;
                                        </script>
                                    @elseif ($staff->role == "Waiter")
                                        <script>
                                            document.getElementById("waiter").selected = true;
                                        </script>
                                    @else
                                        <script>
                                            document.getElementById("kitchen_staff").selected = true;
                                        </script>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="start_date">Gender</label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" value="0" id="male" disabled>
                                        <label class="form-check-label" id="gender">Male</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" value="1" id="female" disabled>
                                        <label class="form-check-label">Female</label>
                                    </div>
                                    @if ( $staff->gender == "0" )
                                        <script>
                                            document.getElementById("male").checked = true;
                                        </script>
                                    @endif
                                    @if ( $staff->gender == "1" )
                                        <script>
                                            document.getElementById("female").checked = true;
                                        </script>
                                    @endif
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="genderError"></strong></small>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <input type="text" class="form-control" value="{{ $staff->email }}" id="email" name="email" disabled>
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="emailError"></strong></small>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="salary">Salary (RM)</label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <input type="text" class="form-control" value="{{ $staff->salary }}" id="salary" name="salary" disabled>
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="salaryError"></strong></small>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="idStaff" name="idStaff" value="{{ $staff->id }}">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-success float-right" id="tempPasswordButton" disabled data-toggle="modal" data-target="#tempPasswordModal">
                                    Click to give temporary password
                                </button> 
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-secondary text-white" disabled id="cancelButton" onclick="disable()">Cancel</button>
                                    <button type="submit" class="btn btn-primary float-right" disabled id="editStaffButton">Confirm</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="card-footer">
                    <button type="button" class="btn btn-danger float-right" id="deleteStaffButton" data-toggle="modal" data-target="#deleteStaffModal">
                        Remove
                    </button> 
                    <div class="modal fade" id="deleteStaffModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger">
                                    <h4 class="modal-title">Warning</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to remove this staff?</p>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <form action="{{ $staff->id }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger" type="submit">Confirm</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="modal fade" id="tempPasswordModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-default">
                                <h5 class="modal-title">Temporary Password</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="tempPasswordForm" method="POST" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="password">Temporary Password</label>
                                                <span style="font-size: 15px; color: red;">*</span>
                                                <input type="password" class="form-control" value="{{ old('password') }}" id="password" name="password" placeholder="Enter Temporary Password">
                                                <span class="text-danger mt-0" role="alert">
                                                    <small><strong id="passwordError"></strong></small>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="cpassword">Confirm Temporary Password</label>
                                                <span style="font-size: 15px; color: red;">*</span>
                                                <input type="password" class="form-control" value="{{ old('cpassword') }}" id="cpassword" name="cpassword" placeholder="Confirm Temporary Password">
                                                <span class="text-danger mt-0" role="alert">
                                                    <small><strong id="cpasswordError"></strong></small>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="idStaff" name="idStaff" value="{{ $staff->id }}">
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary float-right" id="tempPasswordSubmit">Confirm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> 
        </div>      
    </div>
</section>

@endsection

@push('script')

<script>

    function able(){
        document.getElementById("editButton").disabled = true;
        document.getElementById("backButton").disabled = true;
        document.getElementById("editStaffButton").disabled = false;
        document.getElementById("cancelButton").disabled = false;
        document.getElementById("fname").disabled = false;
        document.getElementById("lname").disabled = false;
        document.getElementById("start_date").disabled = false;
        document.getElementById("phone").disabled = false;
        document.getElementById("address").disabled = false;
        document.getElementById("role").disabled = false;
        document.getElementById("male").disabled = false;
        document.getElementById("female").disabled = false;
        document.getElementById("email").disabled = false;
        document.getElementById("salary").disabled = false;
        document.getElementById("tempPasswordButton").disabled = false;
    }

    function disable(){
        document.getElementById("editButton").disabled = false;
        document.getElementById("backButton").disabled = false;
        document.getElementById("editStaffButton").disabled = true;
        document.getElementById("cancelButton").disabled = true;
        document.getElementById("fname").disabled = true;
        document.getElementById("lname").disabled = true;
        document.getElementById("start_date").disabled = true;
        document.getElementById("phone").disabled = true;
        document.getElementById("address").disabled = true;
        document.getElementById("role").disabled = true;
        document.getElementById("male").disabled = true;
        document.getElementById("female").disabled = true;
        document.getElementById("email").disabled = true;
        document.getElementById("salary").disabled = true;
        document.getElementById("tempPasswordButton").disabled = true;
    }

    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $("#editStaffForm").submit(function(e){
                e.preventDefault();
                document.getElementById("editStaffButton").disabled = true;
                let fname = $("input[name='fname']").val();
                let lname = $("input[name='lname']").val();
                let start_date = $("input[name='start_date']").val();
                let address = $.trim($("#address").val());
                let phone_number = $("input[name='phone']").val();
                let gender = $("input[name='gender']:checked").val();
                let salary = $("input[name='salary']").val();
                let role = $( "#role" ).val();
                let email = $("input[name='email']").val();
                let id = $("input[name='idStaff']").val();
                let url = id + "/edit";
                
                $.ajax({
                    url: url,
                    type: "POST",
                    data:{
                        fname: fname,
                        lname: lname,
                        start_date: start_date,
                        address: address,
                        phone_number: phone_number,
                        gender: gender,
                        salary: salary,
                        role: role,
                        email: email,
                        "_token":"{{csrf_token()}}"
                    },
                    success:function(response) {
                        // window.location.href = "/staff";
                    },
                    error:function(response){
                        document.getElementById("editStaffButton").disabled = false;
                        if(response.responseJSON.errors.hasOwnProperty('fname')){
                            let fname_field = document.getElementById('fname');
                            $('#fnameError').text(response.responseJSON.errors.fname[0]);
                            fname_field.style.borderColor = "red";
                            fname_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        if(response.responseJSON.errors.hasOwnProperty('lname')){
                            let lname_field = document.getElementById('lname');
                            $('#lnameError').text(response.responseJSON.errors.lname[0]);
                            lname_field.style.borderColor = "red";
                            lname_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        if(response.responseJSON.errors.hasOwnProperty('start_date')){
                            let start_date_field = document.getElementById('start_date');
                            $('#start_dateError').text(response.responseJSON.errors.start_date[0]);
                            start_date_field.style.borderColor = "red";
                            start_date_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        if(response.responseJSON.errors.hasOwnProperty('phone_number')){
                            let phone_number_field = document.getElementById('phone');
                            $('#phoneError').text(response.responseJSON.errors.phone_number[0]);
                            phone_number_field.style.borderColor = "red";
                            phone_number_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        if(response.responseJSON.errors.hasOwnProperty('address')){
                            let address_field = document.getElementById('address');
                            $('#addressError').text(response.responseJSON.errors.address[0]);
                            address_field.style.borderColor = "red";
                            address_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        if(response.responseJSON.errors.hasOwnProperty('role')){
                            let role_field = document.getElementById('role');
                            $('#roleError').text(response.responseJSON.errors.role[0]);
                            role_field.style.borderColor = "red";
                            role_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        if(response.responseJSON.errors.hasOwnProperty('gender')){
                            let gender_field = document.getElementById('gender');
                            $('#genderError').text(response.responseJSON.errors.gender[0]);
                            gender_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        if(response.responseJSON.errors.hasOwnProperty('email')){
                            let email_field = document.getElementById('email');
                            $('#emailError').text(response.responseJSON.errors.email[0]);
                            email_field.style.borderColor = "red";
                            email_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        if(response.responseJSON.errors.hasOwnProperty('salary')){
                            let salary_field = document.getElementById('salary');
                            $('#salaryError').text(response.responseJSON.errors.salary[0]);
                            salary_field.style.borderColor = "red";
                            salary_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }
                });
            })

            $("#tempPasswordForm").submit(function(e){
                e.preventDefault();
                document.getElementById("tempPasswordSubmit").disabled = true;
                let password = $("input[name='password']").val();
                let password_confirmation = $("input[name='cpassword']").val();
                let id = $("input[name='idStaff']").val();
                let url = id + "/tempPassword";
                let redirect = "/staff/" + id;
                
                $.ajax({
                    url: url,
                    type: "POST",
                    data:{
                        password: password,
                        password_confirmation: password_confirmation,
                        "_token":"{{csrf_token()}}"
                    },
                    success:function(response) {
                        window.location.href = redirect;
                    },
                    error:function(response){
                        document.getElementById("tempPasswordSubmit").disabled = false;
                        if(response.responseJSON.errors.hasOwnProperty('password')){
                            let password_field = document.getElementById('password');
                            $('#passwordError').text(response.responseJSON.errors.password[0]);
                            password_field.style.borderColor = "red";
                            password_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        if(response.responseJSON.errors.hasOwnProperty('password')){
                            let cpassword_field = document.getElementById('cpassword');
                            $('#cpasswordError').text(response.responseJSON.errors.password[0]);
                            cpassword_field.style.borderColor = "red";
                            cpassword_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }
                });
            })
        })
        
</script>

@endpush