<x-customer-layout>
  <!-- Header -->
  <header class="fixed-top" style="background-color: #000">
    <div class="container-fluid py-3 d-flex flex-column align-items-center">
      <a href="/menus">
        <img src="{{ asset('img/red_card_cafe_logo-removebg-preview.png') }}" alt="red card logo" width="80">
      </a>
    </div>
  </header>

  <main class="bg-dark vh-100 p-5 text-white d-flex flex-column justify-content-center text-center gap-2">
    <!-- Phone number input -->
    {{-- <i class="icon fas fa-check"></i> --}}
    <div class="px-5 pb-3">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="color: #F7E7D8;">
        <!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
        <path
          d="M243.8 339.8C232.9 350.7 215.1 350.7 204.2 339.8L140.2 275.8C129.3 264.9 129.3 247.1 140.2 236.2C151.1 225.3 168.9 225.3 179.8 236.2L224 280.4L332.2 172.2C343.1 161.3 360.9 161.3 371.8 172.2C382.7 183.1 382.7 200.9 371.8 211.8L243.8 339.8zM512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256C0 114.6 114.6 0 256 0C397.4 0 512 114.6 512 256zM256 48C141.1 48 48 141.1 48 256C48 370.9 141.1 464 256 464C370.9 464 464 370.9 464 256C464 141.1 370.9 48 256 48z" />
      </svg>
    </div>
    <h1>Your order has been confirmed!</h1>
    <hr>
    <h4>Estimated Preparation Time:</h4>
    <h6>20 mins</h6>
    <h4>Total Price:</h4>
    <h6>RM60</h6>
  </main>
</x-customer-layout>