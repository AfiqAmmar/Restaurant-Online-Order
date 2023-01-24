@extends('layouts.customer-layout')

@section('content')

<div class="bg-dark vh-100 p-5">
  <main class="d-flex flex-column flex-md-row align-items-center gap-5
    text-center position-absolute top-50 start-50 translate-middle">
    <img src="{{ asset('img/red_card_cafe_logo-removebg-preview.png') }}" alt="red card logo" width="250">
    <div class="container rounded p-4 d-flex flex-column gap-3" style="background-color: #F7E7D8; width: 18rem">
      <h3>Enter the following before proceeding:</h3>
      <form id="indexForm">
        @csrf

        {{-- Enter phone number --}}
        <div class="form-floating pb-4">
          <input class="form-control" type="text" placeholder="Enter phone number" id="phone_num" name="phone_num">
          <label for="phone_num">Phone Number</label>

          {{-- @error('phone_num') --}}
          <div id="phone_num_message" class="d-none invalid-feedback">Please enter a valid phone number.</div>
          {{-- @enderror --}}
        </div>

        {{-- Enter table number --}}
        <div class="form-floating pb-4">
          <select class="form-select" id="table_number" name="table_number" aria-label="Table number">
            <option selected>Choose table number</option>
            @foreach ($tables as $table)
            <option value="{{$table->table_number}}">{{$table->table_number}}</option>
            @endforeach
          </select>
          <label for="table_number">Table Number</label>

          {{-- @error('table_number') --}}
          <div id="table_number_message" class="d-none invalid-feedback">Please choose a table number.</div>
          {{-- @enderror --}}
        </div>

        <button type="submit" class="btn btn-success">Confirm</button>
      </form>
    </div>
  </main>
</div>

@endsection

@push('script')

<script>
  $("#indexForm").submit(function(e) {
    var phone_num = $("#phone_num");
    var table_number = $("#table_number");
    phone_num.removeClass("is-invalid");
    table_number.removeClass("is-invalid");
    $(".invalid-feedback").addClass("d-none");

    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "/",
      type: 'POST',
      data: {
        phone_num: phone_num.val(),
        table_number: table_number.val(),
      },
      success: function(response) {
        window.location.href = "/" + response.customer_id + "/menus";
      },
      error: function(response){
        if (response.responseJSON.errors.phone_num) {
          phone_num.addClass("is-invalid");
          $("#phone_num_message").removeClass("d-none");
        }

        if (response.responseJSON.errors.table_number) {
          table_number.addClass("is-invalid");
          $("#table_number_message").removeClass("d-none");
        }
      }
    });

    e.preventDefault();
  });
</script>

@endpush