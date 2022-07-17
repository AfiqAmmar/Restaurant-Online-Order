@extends('layouts.app')

@section('content')

<section class="content mt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
            <div class="card card-light">
                <div class="card-header">
                    <h3 class="card-title">Add New Tax</h3>
                </div>    
                <form id="addTaxForm">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="taxName">Tax Name</label>
                            <span style="font-size: 15px; color: red;">*</span>
                            <input type="text" class="form-control" value="{{ old('taxName') }}" id="taxName" name="taxName" placeholder="Enter Tax Name">
                            <span class="text-danger mt-0" role="alert">
                                <small><strong id="taxNameError"></strong></small>
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="percentage">Percentage</label>
                            <span style="font-size: 15px; color: red;">*</span>
                            <input type="text" class="form-control" value="{{ old('percentage') }}" id="percentage" name="percentage" placeholder="Percentage">
                            <span class="text-danger mt-0" role="alert">
                                <small><strong id="percentageError"></strong></small>
                            </span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-right" id="submitTaxButton">Add</button>
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
            $("#addTaxForm").submit(function(e){
                e.preventDefault();
                document.getElementById("submitTaxButton").disabled = true;
                let taxName = $("input[name='taxName']").val();
                let percentage = $("input[name='percentage']").val();
                $.ajax({
                    url: "add",
                    type: "POST",
                    data:{
                        taxName: taxName,
                        percentage: percentage,
                        "_token":"{{csrf_token()}}"
                    },
                    success:function(response) {
                        window.location.href = "/tax";
                    },
                    error:function(response){
                        document.getElementById("submitTaxButton").disabled = false;
                        if(response.responseJSON.errors.hasOwnProperty('taxName')){
                            let tax_Name = document.getElementById('taxName');
                            $('#taxNameError').text(response.responseJSON.errors.taxName[0]);
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