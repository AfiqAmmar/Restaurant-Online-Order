@extends('layouts.app')

@section('content')

<section class="content mt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
            <div class="card card-light">
                <div class="card-header">
                    <h3 class="card-title">Add New Menu Category</h3>
                </div>    
                <form id="addCategoryForm">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="categoryName">Name</label>
                            <span style="font-size: 15px; color: red;">*</span>
                            <input type="text" class="form-control" value="{{ old('categoryName') }}" id="categoryName" name="categoryName" placeholder="Enter Name">
                            <span class="text-danger mt-0" role="alert">
                                <small><strong id="categoryNameError"></strong></small>
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <div class="form-group">
                                <select class="form-control" id="category" name="category" required>
                                    <option value="0">Food</option>
                                    <option value="1">Drinks</option>
                                </select>
                            </div>
                            <span class="text-danger mt-0" role="alert">
                                <small><strong id="categoryError"></strong></small>
                            </span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-right" id="submitCategoryButton">Add</button>
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
            $("#addCategoryForm").submit(function(e){
                e.preventDefault();
                document.getElementById("submitCategoryButton").disabled = true;
                let name = $("input[name='categoryName']").val();
                let select = document.getElementById('category');
                let category = select.options[select.selectedIndex].value;
                $.ajax({
                    url: "add",
                    type: "POST",
                    data:{
                        name: name,
                        category: category,
                        "_token":"{{csrf_token()}}"
                    },
                    success:function(response) {
                        window.location.href = "/category";
                    },
                    error:function(response){
                        document.getElementById("submitCategoryButton").disabled = false;
                        if(response.responseJSON.errors.hasOwnProperty('name')){
                            let category_Name = document.getElementById('categoryName');
                            $('#categoryNameError').text(response.responseJSON.errors.name[0]);
                            category_Name.style.borderColor = "red";
                            category_Name.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        if(response.responseJSON.errors.hasOwnProperty('category')){
                            let category_field = document.getElementById('category');
                            $('#categoryError').text(response.responseJSON.errors.category[0]);
                            category_field.style.borderColor = "red";
                            category_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }
                });
            })
        })
</script>

@endpush