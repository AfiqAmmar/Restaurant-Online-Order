@extends('layouts.app')

@section('content')

<section class="content mt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
            <div class="card card-light">
                <div class="card-header">
                    <h3 class="card-title">Edit Table</h3>
                </div>    
                <form id="editTableForm">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="table_num">Table Number</label>
                            <span style="font-size: 15px; color: red;">*</span>
                            <input type="text" class="form-control" value="{{ $table->table_number }}" value="{{ old('table_num') }}" id="table_num" name="table_num" placeholder="table_num">
                            <span class="text-danger mt-0" role="alert">
                                <small><strong id="table_numError"></strong></small>
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="capacity">Capacity</label>
                            <span style="font-size: 15px; color: red;">*</span>
                            <input type="text" class="form-control" value="{{ $table->seats }}" value="{{ old('capacity') }}" id="capacity" name="capacity" placeholder="capacity">
                            <span class="text-danger mt-0" role="alert">
                                <small><strong id="capacityError"></strong></small>
                            </span>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-secondary"><a href="/table" class="text-white">Cancel</a></button>
                            <button type="submit" class="btn btn-primary float-right" id="submitTableButton">Confirm</button>
                        </div>
                    </div>
                    <input type="hidden" id="idTable" name="idTable" value="{{ $table->id }}">
                </form>
                <div class="card-footer">
                    <button type="button" class="btn btn-danger float-right" id="deleteTableButton" data-toggle="modal" data-target="#deleteTableModal">
                        Delete
                    </button> 
                    <div class="modal fade" id="deleteTableModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger">
                                    <h4 class="modal-title">Warning</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this table?</p>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <form action="{{ $table->id }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger" type="submit">Confirm</button>
                                </form>
                            </div>
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
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $("#editTableForm").submit(function(e){
                e.preventDefault();
                document.getElementById("submitTableButton").disabled = true;
                let id = $("input[name='idTable']").val();
                let table_num = $("input[name='table_num']").val();
                let capacity = $("input[name='capacity']").val();
                let url = id + "/edit";
                $.ajax({
                    url: url,
                    type: "PUT",
                    data:{
                        url: url,
                        table_num: table_num,
                        capacity: capacity,
                        "_token":"{{csrf_token()}}"
                    },
                    success:function(response) {
                        window.location.href = "/table";
                    },
                    error:function(response){
                        document.getElementById("submitTableButton").disabled = false;
                        if(response.responseJSON.errors.hasOwnProperty('table_num')){
                            let table_num_field = document.getElementById('table_num');
                            $('#table_numError').text(response.responseJSON.errors.table_num[0]);
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