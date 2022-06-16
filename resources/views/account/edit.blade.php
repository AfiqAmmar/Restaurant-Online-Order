@extends('layouts.app')

@section('content')

@if(session()->has('success'))
    <div class="alert alert-success d-flex justify-content-center message">
        {{ session()->get('success') }}
    </div>
@endif

<section class="content mt-4">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-light">
                    <div class="card-header">
                        <button class="btn btn-success float-right" id="editButton" onclick="ableAccount()">Click to Edit</button>
                    </div>    
                    <form id="editAccountForm">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="fname">First Name</label>
                                <span style="font-size: 15px; color: red;">*</span>
                                <input type="text" class="form-control" value="{{ $user->fname }}" value="{{ old('fname') }}" id="fname" name="fname" disabled>
                                <span class="text-danger mt-0" role="alert">
                                    <small><strong id="fnameError"></strong></small>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="lname">Last Name</label>
                                <span style="font-size: 15px; color: red;">*</span>
                                <input type="text" class="form-control" value="{{ $user->lname }}" value="{{ old('lname') }}" id="lname" name="lname" disabled>
                                <span class="text-danger mt-0" role="alert">
                                    <small><strong id="lnameError"></strong></small>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <span style="font-size: 15px; color: red;">*</span>
                                <textarea type="text" class="form-control" rows="3" value="" id="address" name="address" disabled>{{ $user->address }}</textarea>
                                <span class="text-danger mt-0" role="alert">
                                    <small><strong id="addressError"></strong></small>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <span style="font-size: 15px; color: red;">*</span>
                                <input type="text" class="form-control" value="{{ $user->phone_number }}" id="phone" name="phone" disabled>
                                <span class="text-danger mt-0" role="alert">
                                    <small><strong id="phoneError"></strong></small>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <span style="font-size: 15px; color: red;">*</span>
                                <div class="form-group">
                                    <select class="form-control" id="gender" name="gender" required disabled>
                                        @if ($user->gender == "Male")
                                            <option value="Male" selected>Male</option>
                                            <option value="Female">Female</option>
                                        @else
                                            <option value="Male">Male</option>
                                            <option value="Female" selected>Female</option>
                                        @endif
                                    </select>
                                </div>
                                <span class="text-danger mt-0" role="alert">
                                    <small><strong id="genderError"></strong></small>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <span style="font-size: 15px; color: red;">*</span>
                                <input type="text" class="form-control" id="email" value="{{ $user->email }}" name="email" disabled>
                                <span class="text-danger mt-0" role="alert">
                                    <small><strong id="emailError"></strong></small>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="password">Password<span style="font-size: 10px;">*Fill this field to change password</span></label>
                                <span style="font-size: 15px; color: red;">*</span>
                                <input type="password" class="form-control" id="password" name="password" disabled>
                                <span class="text-danger mt-0" role="alert">
                                    <small><strong id="passwordError"></strong></small>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="conpassword">Confirm Password</label>
                                <span style="font-size: 15px; color: red;">*</span>
                                <input type="password" class="form-control" id="conpassword" name="conpassword" disabled>
                                <span class="text-danger mt-0" role="alert">
                                    <small><strong id="conpasswordError"></strong></small>
                                </span>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-secondary text-white" disabled id="cancelButton" onclick="disableAccount()">Cancel</button>
                                <button type="submit" class="btn btn-primary float-right" id="editAccountButton" disabled>Confirm</button>
                            </div>
                        </div>
                        <input type="hidden" id="idUser" name="idUser" value="{{ $user->id }}">
                    </form>
                    <div class="card-footer">
                        
                    </div>
                </div>
            </div>
        </div>      
    </div>
</section>

@endsection

@push('script')

<script>

    function ableAccount(){
        document.getElementById("editButton").disabled = true;
        document.getElementById("editAccountButton").disabled = false;
        document.getElementById("cancelButton").disabled = false;
        document.getElementById("fname").disabled = false;
        document.getElementById("lname").disabled = false;
        document.getElementById("address").disabled = false;
        document.getElementById("phone").disabled = false;
        document.getElementById("gender").disabled = false;
        document.getElementById("email").disabled = false;
        document.getElementById("password").disabled = false;
        document.getElementById("conpassword").disabled = false;
    }

    function disableAccount(){
        document.getElementById("editButton").disabled = false;
        document.getElementById("editAccountButton").disabled = true;
        document.getElementById("cancelButton").disabled = true;
        document.getElementById("fname").disabled = true;
        document.getElementById("lname").disabled = true;
        document.getElementById("address").disabled = true;
        document.getElementById("phone").disabled = true;
        document.getElementById("gender").disabled = true;
        document.getElementById("email").disabled = true;
        document.getElementById("password").disabled = true;
        document.getElementById("conpassword").disabled = true;
    }
    
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $("#editAccountForm").submit(function(e){
                e.preventDefault();
                document.getElementById("editAccountButton").disabled = true;
                let id = $("input[name='idUser']").val();
                let fname = $("input[name='fname']").val();
                let lname = $("input[name='lname']").val();
                let address = $("#address").val();
                let phone = $("input[name='phone']").val();
                let select = document.getElementById('gender');
                let gender = select.options[select.selectedIndex].value;
                let email = $("input[name='email']").val();
                let password = $("input[name='password']").val();
                let password_confirmation = $("input[name='conpassword']").val();
                let url = "account/edit";
                console.log(address);
                $.ajax({
                    url: url,
                    type: "PUT",
                    data:{
                        url: url,
                        fname: fname,
                        lname: lname,
                        address: address,
                        phone: phone,
                        gender: gender,
                        email: email,
                        password: password,
                        password_confirmation: password_confirmation,
                        "_token":"{{csrf_token()}}"
                    },
                    success:function(response) {
                        window.location.href = "/account";
                    },
                    error:function(response){
                        document.getElementById("editAccountButton").disabled = false;
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
                        if(response.responseJSON.errors.hasOwnProperty('address')){
                            let address_field = document.getElementById('address');
                            $('#addressError').text(response.responseJSON.errors.address[0]);
                            address_field.style.borderColor = "red";
                            address_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        if(response.responseJSON.errors.hasOwnProperty('phone')){
                            let phone_field = document.getElementById('phone');
                            $('#phoneError').text(response.responseJSON.errors.phone[0]);
                            phone_field.style.borderColor = "red";
                            phone_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        if(response.responseJSON.errors.hasOwnProperty('gender')){
                            let gender_field = document.getElementById('gender');
                            $('#genderError').text(response.responseJSON.errors.gender[0]);
                            gender_field.style.borderColor = "red";
                            gender_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        if(response.responseJSON.errors.hasOwnProperty('email')){
                            let email_field = document.getElementById('email');
                            $('#emailError').text(response.responseJSON.errors.email[0]);
                            email_field.style.borderColor = "red";
                            email_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        if(response.responseJSON.errors.hasOwnProperty('password')){
                            let password_field = document.getElementById('password');
                            $('#passwordError').text(response.responseJSON.errors.password[0]);
                            password_field.style.borderColor = "red";
                            password_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        if(response.responseJSON.errors.hasOwnProperty('password')){
                            let conpassword_field = document.getElementById('conpassword');
                            $('#conpasswordError').text(response.responseJSON.errors.password[0]);
                            conpassword_field.style.borderColor = "red";
                            conpassword_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }
                });
            })
        })
</script>

@endpush