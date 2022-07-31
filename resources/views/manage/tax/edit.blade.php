@extends('layouts.app')

@section('content')

<section class="content mt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
            <div class="card card-light">
                <div class="card-header">
                    <h3 class="card-title">Edit Tax</h3>
                </div>    
                <form id="editTaxForm">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="taxName">Tax Name</label>
                            <span style="font-size: 15px; color: red;">*</span>
                            <input type="text" class="form-control" value="{{ $tax->name }}" value="{{ old('taxName') }}" id="taxName" name="taxName" placeholder="Enter Tax Name">
                            <span class="text-danger mt-0" role="alert">
                                <small><strong id="taxNameError"></strong></small>
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="percentage">Percentage</label>
                            <span style="font-size: 15px; color: red;">*</span>
                            <input type="text" class="form-control" value="{{ $tax->percentage }}" value="{{ old('percentage') }}" id="percentage" name="percentage" placeholder="Percentage">
                            <span class="text-danger mt-0" role="alert">
                                <small><strong id="percentageError"></strong></small>
                            </span>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-secondary"><a href="/tax" class="text-white">Cancel</a></button>
                            <button type="submit" class="btn btn-primary float-right" id="submitTaxButton">Confirm</button>
                        </div>
                    </div>
                    <input type="hidden" id="idTax" name="idTax" value="{{ $tax->id }}">
                </form>
                <div class="card-footer">
                    <button type="button" class="btn btn-danger float-right" id="deleteTaxButton" data-toggle="modal" data-target="#deleteTaxModal">
                        Delete
                    </button> 
                    <div class="modal fade" id="deleteTaxModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger">
                                    <h4 class="modal-title">Warning</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this tax?</p>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <form action="{{ $tax->id }}" method="POST" enctype="multipart/form-data">
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
            $("#editTaxForm").submit(function(e){
                e.preventDefault();
                document.getElementById("submitTaxButton").disabled = true;
                let id = $("input[name='idTax']").val();
                let taxName = $("input[name='taxName']").val();
                let percentage = $("input[name='percentage']").val();
                let url = id + "/edit";
                $.ajax({
                    url: url,
                    type: "PUT",
                    data:{
                        url: url,
                        name: taxName,
                        percentage: percentage,
                        "_token":"{{csrf_token()}}"
                    },
                    success:function(response) {
                        window.location.href = "/tax";
                    },
                    error:function(response){
                        document.getElementById("submitTaxButton").disabled = false;
                        if(response.responseJSON.errors.hasOwnProperty('name')){
                            let tax_Name = document.getElementById('taxName');
                            $('#taxNameError').text(response.responseJSON.errors.name[0]);
                            tax_Name.style.borderColor = "red";
                            tax_Name.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        if(response.responseJSON.errors.hasOwnProperty('percentage')){
                            let percentage_field = document.getElementById('percentage');
                            $('#percentageError').text(response.responseJSON.errors.percentage[0]);
                            percentage_field.style.borderColor = "red";
                            percentage_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }
                });
            })
        })
</script>

@endpush