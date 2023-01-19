@extends('layouts.customer-layout')

@section('content')

<header class="sticky-top" style="background-color: #000">
  <!-- Navbar -->
  <div class="container-md py-3">
    <div class="row">
      <div class="col">
        <a class="btn btn-dark border-0" href="/{{$customer_id}}/menus" role="button" style="background-color: #000">
          <span class="fa fa-chevron-left"></span>
        </a>
      </div>
      <div class="col">
        <h4 class="text-white text-center">CART</h4>
      </div>
      <div class="col"></div>
    </div>
  </div>
</header>

<main>
  <div id="confirmPageContainer" class="container-fluid bg-dark py-2 min-vh-100">
    @if ($cartMenus->isEmpty())
    <div class="position-absolute top-50 start-50 translate-middle text-center">
      {{-- <img src="{{ asset('img/empty-cart-red-hidden.svg') }}" alt="empty cart" width='350'> --}}
      <img src="{{ asset('img/empty-cart-red-simple.svg') }}" alt="empty cart" width='350'>
      <h3 class="text-white py-1">No menus selected</h3>
    </div>
    @else
    <!-- Selected Menus -->
    <div class="d-md-flex gap-4">
      <div class="w-100 d-md-none">
        <h5 class="text-white py-1">Selected Menus</h5>

        @foreach ($cartMenus as $cartMenu)
        <x-confirm-menu-card :cartMenu="$cartMenu" :customer_id="$customer_id" :menus="$menus" />
        @endforeach
      </div>

      <div class="d-none d-md-block w-50">
        <h5 class="text-white py-1">Selected Menus</h5>

        @foreach ($cartMenus as $cartMenu)
        <x-confirm-menu-card :cartMenu="$cartMenu" :customer_id="$customer_id" :menus="$menus" />
        @endforeach
      </div>

      <div class="flex-fill">
        <!-- Cart Description -->
        <div class="py-2">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="text-white">Food Est. Prep Time</h5>
            <h5 class="text-white lead">{{$estimatedTimeFoods}} mins</h5>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="text-white">Drinks Est. Prep Time</h5>
            <h5 class="text-white lead">{{$estimatedTimeDrinks}} mins</h5>
          </div>
          <hr style="color: #F7E7D8">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="text-white">Total Price</h5>
            <h5 class="text-white lead" id="totalPrice">RM {{$totalPrice}}</h5>
          </div>
        </div>
        <div class="rounded p-3 d-none d-md-block" style="background-color: #000">
          <div class="d-flex justify-content-between align-items-center">
            <a class="btn btn-danger" href="/{{$customer_id}}/cart/clear" role="button">
              <i class="fas fa-trash"></i>&nbsp;&nbsp;Clear
            </a>
            <a class="btn btn-success" href="/{{$customer_id}}/cart/confirm" role="button">
              Confirm Order
            </a>
          </div>
        </div>
      </div>
    </div>
    @endif
  </div>
</main>

@unless ($cartMenus->isEmpty())
{{-- Footer on phone size --}}
<footer class="footer py-3 fixed-bottom d-md-none" style="background-color: #000">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    <a class="btn btn-danger" href="/{{$customer_id}}/cart/clear" role="button">
      <i class="fas fa-trash"></i>&nbsp;&nbsp;Clear
    </a>
    <a class="btn btn-success" href="/{{$customer_id}}/cart/confirm" role="button">
      Confirm Order
    </a>
  </div>
</footer>

{{-- Footer on tablet size and above --}}
<footer class="footer px-2 py-3 d-none d-md-block" style="background-color: #000">
  <div class="container-fluid text-center">
    <span class="h6 text-light pt-2"><strong>Copyright © 2022 Red Card Cafe.</strong> All rights reserved.</span>
  </div>
</footer>
@endunless

@endsection

@push('script')

<script>
  $(".plusButton").click(function() {
    var customer_id = parseInt("<?php echo $customer_id; ?>");
    var $parent = $(this).closest('.d-flex');
    var menu_id = parseInt($parent.find('.menu_id').text());
    var max_quantity = parseInt($parent.find('.menu_available_quantity').text());
    var quantity = parseInt($parent.find('.quantity').val());
    quantity += 1;

    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "cart",
      type: 'POST',
      data: {
        customer_id: customer_id,
        menu_id: menu_id,
        quantity: quantity
      },
      success: function(response) {
        if (response.success) {
          $parent.find(".quantity").val(quantity);
          $("#totalPrice").text("RM " + response.success);

          if (quantity < max_quantity) {
            $parent.find('.plusButton').prop('disabled', false);
          } else {
            $parent.find('.plusButton').prop('disabled', true);
          }

          if (quantity > 1) {
            $parent.find('.minusButton').prop('disabled', false);
          } else {
            $parent.find('.minusButton').prop('disabled', true);
          }
        } else {
          alert("Error saving data");
        }
      }
    });
  });

  $(".minusButton").click(function() {
    var customer_id = "<?php echo $customer_id; ?>";
    var $parent = $(this).closest('.d-flex');
    var menu_id = parseInt($parent.find('.menu_id').text());
    var max_quantity = parseInt($parent.find('.menu_available_quantity').text());
    var quantity = parseInt($parent.find('.quantity').val());
    quantity -= 1;

    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "cart",
      type: "POST",
      data: {
        customer_id: customer_id,
        menu_id: menu_id,
        quantity: quantity
      },
      success: function(response) {
        if (response.success) {
          $parent.find(".quantity").val(quantity);
          $("#totalPrice").text("RM " + response.success);

          if (quantity > 1) {
            $parent.find('.minusButton').prop('disabled', false);
          } else {
            $parent.find('.minusButton').prop('disabled', true);
          }

          if (quantity < max_quantity) {
            $parent.find('.plusButton').prop('disabled', false);
          } else {
            $parent.find('.plusButton').prop('disabled', true);
          }
        } else {
          alert("Error saving data");
        }
      }
    });
  });
</script>

@endpush