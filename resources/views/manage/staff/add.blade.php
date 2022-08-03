@extends('layouts.app')

@section('content')

<section class="content mt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
            <div class="card card-light">
                <div class="card-header">
                    <h3 class="card-title">Add New Staff</h3>
                </div>    
                <form id="addStaffForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fname">First Name</label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <input type="text" class="form-control" value="{{ old('fname') }}" id="fname" name="fname" placeholder="Enter Staff First Name">
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="fnameError"></strong></small>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lname">Last Name</label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <input type="text" class="form-control" value="{{ old('lname') }}" id="lname" name="lname" placeholder="Enter Staff Last Name">
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
                                    <input type="date" class="form-control" value="{{ old('start_date') }}" id="start_date" name="start_date">
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="start_dateError"></strong></small>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <input type="text" class="form-control" value="{{ old('phone') }}" id="phone" name="phone" placeholder="Enter Staff Phone Number">
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
                                    <textarea type="text" class="form-control" rows="5" value="{{ old('address') }}" id="address" name="address" placeholder="Enter Staff Address"></textarea>
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="addressError"></strong></small>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="role">Role</label>
                                <div class="form-group">
                                    <select class="form-control" name="role" id="role">
                                        <option value="Master-Admin">Master-Admin</option>
                                        <option value="Cashier">Cashier</option>
                                        <option value="Waiter">Waiter</option>
                                        <option value="Kitchen Staff">Kitchen Staff</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="start_date">Gender</label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" value="0">
                                        <label class="form-check-label" id="gender">Male</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" value="1">
                                        <label class="form-check-label">Female</label>
                                    </div>
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
                                    <input type="text" class="form-control" value="{{ old('email') }}" id="email" name="email" placeholder="Enter Staff Email">
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="emailError"></strong></small>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="salary">Salary (RM)</label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <input type="text" class="form-control" value="{{ old('salary') }}" id="salary" name="salary" placeholder="Enter Staff Salary">
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="salaryError"></strong></small>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Temporary Password</label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <input type="password" class="form-control" value="{{ old('password') }}" id="password" name="password" placeholder="Enter Temporary Password">
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="passwordError"></strong></small>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
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
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-right" id="submitStaffButton">Confirm</button>
                    </div>
                </form>
            </div> 
        </div>      
    </div>
</section>

@endsection

@push('script')

<script>
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $("#addStaffForm").submit(function(e){
                e.preventDefault();
                document.getElementById("submitStaffButton").disabled = true;
                let fname = $("input[name='fname']").val();
                let lname = $("input[name='lname']").val();
                let start_date = $("input[name='start_date']").val();
                let address = $.trim($("#address").val());
                let phone_number = $("input[name='phone']").val();
                let gender = $("input[name='gender']:checked").val();
                let salary = $("input[name='salary']").val();
                let role = $( "#role" ).val();
                let email = $("input[name='email']").val();
                let password = $("input[name='password']").val();
                let password_confirmation = $("input[name='cpassword']").val();
                
                $.ajax({
                    url: "add",
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
                        password: password,
                        password_confirmation: password_confirmation,
                        "_token":"{{csrf_token()}}"
                    },
                    success:function(response) {
                        window.location.href = "/staff";
                    },
                    error:function(response){
                        document.getElementById("submitStaffButton").disabled = false;
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