@extends('layouts.app')

@section('content')

<section class="content mt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
            <h4 class="mb-3">Menu: {{ $menu->name }}</h4>
            <div class="card card-light">
                <div class="card-header">
                    <button class="btn btn-secondary"><a href="/menu" class="text-white" id="backButton">Back to Menu</a></button>
                    <button class="btn btn-success float-right" id="editButton" onclick="able()">Click to Edit</button>
                </div>    
                <form id="editMenuForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <img src="{{ asset('menu_img/' . $menu->image_name) }}" class="mx-auto d-block p-2" alt="Menu Image" height="450px" width="400px">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Menu Name</label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <input type="text" class="form-control" value="{{ $menu->name }}" id="name" name="name" disabled>
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="nameError"></strong></small>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="imageFile">Menu Image</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="imageFile" id="imageFile" disabled>
                                            <label class="custom-file-label" for="imageFile">Choose if want to update image</label>
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
                                    <textarea type="text" class="form-control" rows="5" value="" id="description" name="description" disabled></textarea>
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
                                    <input type="text" class="form-control" value="{{ $menu->price }}" id="price" name="price" disabled>
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="priceError"></strong></small>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="sides">Sides</label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sides" id="yesRadio" value="1" disabled>
                                        <label class="form-check-label">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sides" id="noRadio" value="0" disabled>
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
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <div class="form-group">
                                        <select class="form-control" id="category" name="category" required disabled>
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
                                    <input type="text" class="form-control" value="{{ $menu->preparation_time }}" id="time" name="time" disabled>
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
                                        <input class="form-check-input" type="radio" name="availability" id="yesAvailability" value="0" disabled>
                                        <label class="form-check-label">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="availability" id="noAvailability" value="1" disabled>
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
                                    <label for="quantity">Available Quantity <span style="font-size: 10px;">*Ignore this field if not applicable</span></label>
                                    <span style="font-size: 15px; color: red;">*</span>
                                    <input type="text" class="form-control" id="quantity" name="quantity" disabled>
                                    <span class="text-danger mt-0" role="alert">
                                        <small><strong id="quantityError"></strong></small>
                                    </span>
                                    @if ( $menu->available_quantity == null)
                                        <script>
                                            document.getElementById("quantity").value = -1;
                                        </script>
                                    @else
                                        <script>
                                            document.getElementById("quantity").value = "{{ $menu->available_quantity }}";
                                        </script>
                                    @endif       
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-secondary text-white" disabled id="cancelButton" onclick="disable()">Cancel</button>
                            <button type="submit" class="btn btn-primary float-right" disabled id="editMenuButton">Confirm</button>
                        </div>
                    </div>
                    <input type="hidden" id="idMenu" name="idMenu" value="{{ $menu->id }}">
                </form>
                <div class="card-footer">
                    <button type="button" class="btn btn-danger float-right" id="deleteMenuButton" data-toggle="modal" data-target="#deleteMenuModal">
                        Delete
                    </button> 
                    <div class="modal fade" id="deleteMenuModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger">
                                    <h4 class="modal-title">Warning</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete this menu?</p>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <form action="{{ $menu->id }}" method="POST" enctype="multipart/form-data">
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
    </div>
</section>

@endsection

@push('script')

<script>

    function able(){
        document.getElementById("editButton").disabled = true;
        document.getElementById("backButton").disabled = true;
        document.getElementById("editMenuButton").disabled = false;
        document.getElementById("cancelButton").disabled = false;
        document.getElementById("name").disabled = false;
        document.getElementById("imageFile").disabled = false;
        document.getElementById("description").disabled = false;
        document.getElementById("price").disabled = false;
        document.getElementById("yesRadio").disabled = false;
        document.getElementById("noRadio").disabled = false;
        document.getElementById("category").disabled = false;
        document.getElementById("time").disabled = false;
        document.getElementById("quantity").disabled = false;
        document.getElementById("yesAvailability").disabled = false;
        document.getElementById("noAvailability").disabled = false;
    }

    function disable(){
        document.getElementById("editButton").disabled = false;
        document.getElementById("backButton").disabled = false;
        document.getElementById("editMenuButton").disabled = true;
        document.getElementById("cancelButton").disabled = true;
        document.getElementById("name").disabled = true;
        document.getElementById("imageFile").disabled = true;
        document.getElementById("description").disabled = true;
        document.getElementById("price").disabled = true;
        document.getElementById("yesRadio").disabled = true;
        document.getElementById("noRadio").disabled = true;
        document.getElementById("category").disabled = true;
        document.getElementById("time").disabled = true;
        document.getElementById("quantity").disabled = true;
        document.getElementById("yesAvailability").disabled = true;
        document.getElementById("noAvailability").disabled = true;
    }

    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $("#editMenuForm").submit(function(e){
                if($("input[name='quantity']").val().localeCompare("'N/A'") == 0){
                    $("input[name='quantity']").val() == -1;
                }
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