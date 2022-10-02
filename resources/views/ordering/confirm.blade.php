<x-customer-layout>
  <header class="sticky-top" style="background-color: #000">
    <!-- Navbar -->
    <div class="container-fluid py-3">
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
    <div class="container bg-dark py-2 min-vh-100">
      @if ($cartMenus->isEmpty())
      <div class="position-absolute top-50 start-50 translate-middle text-center">
        {{-- <img src="{{ asset('img/empty-cart-red-hidden.svg') }}" alt="empty cart" width='350'> --}}
        <img src="{{ asset('img/empty-cart-red-simple.svg') }}" alt="empty cart" width='350'>
        <h3 class="text-white py-1">No menus selected</h3>
      </div>
      @else
      <!-- Selected Menus -->
      <h5 class="text-white py-1">Selected Menus</h5>

      @foreach ($cartMenus as $cartMenu)
      <x-confirm-menu-card :cartMenu="$cartMenu" :customer_id="$customer_id" />
      @endforeach

      <hr style="color: #F7E7D8">

      <!-- Cart Description -->
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="text-white">Total Price</h5>
        <h5 class="text-white lead">RM {{$totalPrice}}</h5>
      </div>
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="text-white">Estimated Preparation Time</h5>
        <h5 class="text-white lead">{{$estimatedTime}} mins</h5>
      </div>
      @endif
    </div>
  </main>

  @unless ($cartMenus->isEmpty())
  <!-- Footer -->
  <footer class="footer py-3 fixed-bottom" style="background-color: #000">
    <div class="container d-flex justify-content-between align-items-center">
      <a class="btn btn-danger" href="/{{$customer_id}}/cart/clear" role="button">
        <i class="fas fa-trash"></i>&nbsp;&nbsp;Clear
      </a>
      <a class="btn btn-success" href="/{{$customer_id}}/cart/confirmed" role="button">
        Confirm Order
      </a>
    </div>
  </footer>
  @endunless
</x-customer-layout>