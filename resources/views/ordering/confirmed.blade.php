<x-customer-layout>
  <!-- Header -->
  <header class="fixed-top" style="background-color: #000">
    <div class="container-fluid py-3 d-flex flex-column align-items-center">
      <img src="{{ asset('img/red_card_cafe_logo-removebg-preview.png') }}" alt="red card logo" width="80">
    </div>
  </header>

  <main class="bg-dark vh-100 p-5 text-white d-flex flex-column justify-content-center text-center gap-2">
    <div class="px-5 pb-3">
      <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-check-circle"
        viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
        <path
          d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z" />
      </svg>
    </div>
    <h1>Your order has been confirmed!</h1>
    <hr>
    <h4>Estimated Preparation Time:</h4>
    <h5>?? mins</h5>
    <h4>Total Price:</h4>
    <h5>RM {{$totalPrice}}</h5>
    <a class="btn btn-primary mx-auto" href="/" role="button">Order again?</a>
  </main>
</x-customer-layout>