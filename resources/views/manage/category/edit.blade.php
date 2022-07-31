@extends('layouts.app')

@section('content')

<section class="content mt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
            <div class="card card-light">
                <div class="card-header">
                    <h3 class="card-title">Edit Menu Category</h3>
                </div>    
                <form id="editCategoryForm">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="categoryName">Name</label>
                            <span style="font-size: 15px; color: red;">*</span>
                            <input type="text" class="form-control" value="{{ $category->name }}" value="{{ old('categoryName') }}" id="categoryName" name="categoryName" placeholder="Enter Name">
                            <span class="text-danger mt-0" role="alert">
                                <small><strong id="categoryNameError"></strong></small>
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <div class="form-group">
                                <select class="form-control" id="category" name="category" required>
                                    @if ($category->category == 0)
                                        <option value="0" selected>Food</option>
                                        <option value="1">Drinks</option>
                                    @else
                                        <option value="0">Food</option>
                                        <option value="1" selected>Drinks</option>
                                    @endif
                                </select>
                            </div>
                            <span class="text-danger mt-0" role="alert">
                                <small><strong id="categoryError"></strong></small>
                            </span>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-secondary"><a href="/category" class="text-white">Cancel</a></button>
                            <button type="submit" class="btn btn-primary float-right" id="submitCategoryButton">Confirm</button>
                        </div>
                    </div>
                    <input type="hidden" id="idCategory" name="idCategory" value="{{ $category->id }}">
                </form>
                <div class="card-footer">
                    <button type="button" class="btn btn-danger float-right" id="deleteCategoryButton" data-toggle="modal" data-target="#deleteCategoryModal">
                        Delete
                    </button> 
                    <div class="modal fade" id="deleteCategoryModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger">
                                    <h4 class="modal-title">Warning</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this menu category? The menu related with this menu category will be deleted as well.</p>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <form action="{{ $category->id }}" method="POST" enctype="multipart/form-data">
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
            $("#editCategoryForm").submit(function(e){
                e.preventDefault();
                document.getElementById("submitCategoryButton").disabled = true;
                let id = $("input[name='idCategory']").val();
                let name = $("input[name='categoryName']").val();
                let select = document.getElementById('category');
                let category = select.options[select.selectedIndex].value;
                let url = id + "/edit";
                $.ajax({
                    url: url,
                    type: "PUT",
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