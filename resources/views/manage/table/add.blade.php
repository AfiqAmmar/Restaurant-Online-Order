@extends('layouts.app')

@section('content')

<section class="content mt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
            <div class="card card-light">
                <div class="card-header">
                    <h3 class="card-title">Add New Table</h3>
                </div>    
                <form id="addTableForm">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="table_num">Table Number</label>
                            <span style="font-size: 15px; color: red;">*</span>
                            <input type="text" class="form-control" value="{{ old('table_num') }}" id="table_num" name="table_num" placeholder="Table Number">
                            <span class="text-danger mt-0" role="alert">
                                <small><strong id="table_numError"></strong></small>
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="capacity">Table Capacity</label>
                            <span style="font-size: 15px; color: red;">*</span>
                            <input type="text" class="form-control" value="{{ old('capacity') }}" id="capacity" name="capacity" placeholder="Table Capacity">
                            <span class="text-danger mt-0" role="alert">
                                <small><strong id="capacityError"></strong></small>
                            </span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-right" id="submitTableButton">Add</button>
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
            $("#addTableForm").submit(function(e){
                e.preventDefault();
                document.getElementById("submitTableButton").disabled = true;
                let table_num = $("input[name='table_num']").val();
                let capacity = $("input[name='capacity']").val();
                $.ajax({
                    url: "add",
                    type: "POST",
                    data:{
                        table_number: table_num,
                        capacity: capacity,
                        "_token":"{{csrf_token()}}"
                    },
                    success:function(response) {
                        window.location.href = "/table";
                    },
                    error:function(response){
                        document.getElementById("submitTableButton").disabled = false;
                        if(response.responseJSON.errors.hasOwnProperty('table_number')){
                            let table_num_field = document.getElementById('table_num');
                            $('#table_numError').text(response.responseJSON.errors.table_number[0]);
                            table_num_field.style.borderColor = "red";
                            table_num_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        if(response.responseJSON.errors.hasOwnProperty('capacity')){
                            let capacity_field = document.getElementById('capacity');
                            $('#capacityError').text(response.responseJSON.errors.capacity[0]);
                            capacity_field.style.borderColor = "red";
                            capacity_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }
                });
            })
        })
</script>

@endpush