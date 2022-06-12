@extends('layouts.app')

@section('content')

<section class="content mt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
            <div class="card card-light">
                <div class="card-header">
                    <h3 class="card-title">Menu: {{ $menu->name }}</h3>
                </div>    
                <form id="editMenuForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Menu Name</label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <input type="text" class="form-control" value="{{ $menu->name }}" id="name" name="name">
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
                                            <label class="custom-file-label" for="imageFile">{{ $menu->image_name }}</label>
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
                                    <textarea type="text" class="form-control" rows="5" value="" id="description" name="description"></textarea>
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="descriptionError"></strong></small>
                                    </span>
                                    <script>
                                        document.getElementById("description").value = "{{ $menu->description }}";
                                    </script>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">Price (RM)</label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <input type="text" class="form-control" value="{{ $menu->price }}" id="price" name="price">
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="priceError"></strong></small>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="sides">Sides</label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sides" id="yesRadio" value="1">
                                        <label class="form-check-label">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sides" id="noRadio" value="0">
                                        <label class="form-check-label">No</label>
                                    </div>
                                    @if ( $menu->sides == "0" )
                                        <script>
                                            document.getElementById("noRadio").checked = true;
                                        </script>
                                    @endif
                                    @if ( $menu->sides == "1" )
                                        <script>
                                            document.getElementById("yesRadio").checked = true;
                                        </script>
                                    @endif
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
                                                @if ( $menu->category_id == $category->id)
                                                    <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                                @else
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endif
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
                                    <input type="text" class="form-control" value="{{ $menu->preparation_time }}" id="time" name="time" >
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="timeError"></strong></small>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="availability">Availability</label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="availability" id="yesAvailability" value="0">
                                        <label class="form-check-label">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="availability" id="noAvailability" value="1">
                                        <label class="form-check-label">No</label>
                                    </div>
                                    @if ( $menu->availability == "0" )
                                        <script>
                                            document.getElementById("yesAvailability").checked = true;
                                        </script>
                                    @endif
                                    @if ( $menu->availability == "1" )
                                        <script>
                                            document.getElementById("noAvailability").checked = true;
                                        </script>
                                    @endif
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="availabilityError"></strong></small>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity">Available Quantity <span style="font-size: 10px;">*Enter negative integer if this field is not applicable</span></label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <input type="text" class="form-control" id="quantity" name="quantity" >
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="quantityError"></strong></small>
                                    </span>
                                    @if ( $menu->available_quantity == null)
                                        <script>
                                            document.getElementById("quantity").value = "N/A";
                                        </script>
                                    @else
                                        <script>
                                            document.getElementById("quantity").value = "{{ $menu->available_quantity }}";
                                        </script>
                                    @endif       
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-right" id="editMenuButton">Confirm</button>
                    </div>
                    <input type="hidden" id="idMenu" name="idMenu" value="{{ $menu->id }}">
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
            $("#editMenuForm").submit(function(e){
                e.preventDefault();
                document.getElementById("editMenuButton").disabled = true;
                let id = $("input[name='idMenu']").val();
                let formData = new FormData($('#editMenuForm')[0]);
                let url = id + "/edit";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,

                    success:function(response) {
                        window.location.href = "/menu";
                    },
                    error:function(response){
                        document.getElementById("editMenuButton").disabled = false;
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
                        }
                        if(response.responseJSON.errors.hasOwnProperty('time')){
                            let time_field = document.getElementById('time');
                            $('#timeError').text(response.responseJSON.errors.time[0]);
                            time_field.style.borderColor = "red";
                            time_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        if(response.responseJSON.errors.hasOwnProperty('availability')){
                            let availability_field = document.getElementById('availability');
                            $('#availabilityError').text(response.responseJSON.errors.availability[0]);
                        }
                        if(response.responseJSON.errors.hasOwnProperty('quantity')){
                            let quantity_field = document.getElementById('quantity');
                            $('#quantityError').text(response.responseJSON.errors.quantity[0]);
                            quantity_field.style.borderColor = "red";
                            quantity_field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }
                });
            })
        })
</script>

@endpush