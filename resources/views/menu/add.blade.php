@extends('layouts.app')

@section('content')

<section class="content mt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
            <div class="card card-light">
                <div class="card-header">
                    <h3 class="card-title">Add New Menu</h3>
                </div>    
                <form id="addMenuForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Menu Name</label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <input type="text" class="form-control" value="{{ old('name') }}" id="name" name="name" placeholder="Enter Menu Name">
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="nameError"></strong></small>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="imageFile">Menu Image</label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="imageFile" id="imageFile">
                                            <label class="custom-file-label" for="imageFile">Choose file</label>
                                        </div>
                                    </div>
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="imageFileError"></strong></small>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">Menu Description</label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <textarea type="text" class="form-control" rows="5" value="{{ old('description') }}" id="description" name="description" placeholder="Enter Menu Description"></textarea>
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="descriptionError"></strong></small>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">Price (RM)</label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <input type="text" class="form-control" value="{{ old('price') }}" id="price" name="price" placeholder="Enter Menu Price">
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="priceError"></strong></small>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="sides">Sides</label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sides" value="1">
                                        <label class="form-check-label">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sides" value="0">
                                        <label class="form-check-label">No</label>
                                    </div>
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="sidesError"></strong></small>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category">Menu Category</label>
                                    <div class="form-group">
                                        <select class="form-control" id="category" name="category" required>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="categoryError"></strong></small>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="time">Estimated Preparation Time (Minutes)</label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <input type="text" class="form-control" value="{{ old('time') }}" id="time" name="time" placeholder="Enter Estimated Time">
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="timeError"></strong></small>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-right" id="submitMenuButton">Add</button>
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
            $("#addMenuForm").submit(function(e){
                e.preventDefault();
                
                // document.getElementById("submitMenuButton").disabled = true;
                // let name = $("input[name='name']").val();
                // let description = $("input[name='description']").val();
                // let select = document.getElementById('category');
                // let category = select.options[select.selectedIndex].value;
                // let imageFile = $("input[name='imageFile']").val();
                // let price = $("input[name='price']").val();
                // let sides = $("input[name='sides']:checked").val();
                // let time = $("input[name='time']").val();

                // name: name,
                //         description: description,
                //         category: category,
                //         imageFile: imageFile,
                //         price: price,
                //         sides: sides,
                //         time: time,
                // "_token":"{{csrf_token()}}"

                let formData = new FormData($('#addMenuForm')[0]);

                $.ajax({
                    url: "add",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,

                    success:function(response) {
                        window.location.href = "/menu";
                    },
                    error:function(response){
                        document.getElementById("submitMenuButton").disabled = false;
                        if(response.responseJSON.errors.hasOwnProperty('name')){
                            let name_field = document.getElementById('name');
                            $('#nameError').text(response.responseJSON.errors.name[0]);
                            name_field.style.borderColor = "red";
                            name_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        if(response.responseJSON.errors.hasOwnProperty('description')){
                            let description_field = document.getElementById('description');
                            $('#descriptionError').text(response.responseJSON.errors.description[0]);
                            description_field.style.borderColor = "red";
                            description_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        if(response.responseJSON.errors.hasOwnProperty('category')){
                            let category_field = document.getElementById('category');
                            $('#categoryError').text(response.responseJSON.errors.category[0]);
                            category_field.style.borderColor = "red";
                            category_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        if(response.responseJSON.errors.hasOwnProperty('imageFile')){
                            let imageFile_field = document.getElementById('imageFile');
                            $('#imageFileError').text(response.responseJSON.errors.imageFile[0]);
                            imageFile_field.style.borderColor = "red";
                            imageFile_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        if(response.responseJSON.errors.hasOwnProperty('price')){
                            let price_field = document.getElementById('price');
                            $('#priceError').text(response.responseJSON.errors.price[0]);
                            price_field.style.borderColor = "red";
                            price_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        if(response.responseJSON.errors.hasOwnProperty('sides')){
                            let sides_field = document.getElementById('sides');
                            $('#sidesError').text(response.responseJSON.errors.sides[0]);
                            sides_field.style.borderColor = "red";
                            sides_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        if(response.responseJSON.errors.hasOwnProperty('time')){
                            let time_field = document.getElementById('time');
                            $('#timeError').text(response.responseJSON.errors.time[0]);
                            time_field.style.borderColor = "red";
                            time_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }
                });
            })
        })
</script>

@endpush