<x-customer-layout>
  <header class="fixed-top" style="background-color: #000">
    <div class="container-fluid py-3 d-flex flex-column align-items-center">
      <img src="{{ asset('img/red_card_cafe_logo-removebg-preview.png') }}" alt="red card logo" width="80">
    </div>
  </header>

  <main class="bg-dark vh-100 p-5">
    <div
      class="container rounded p-4 d-flex flex-column align-items-center gap-3 text-center position-absolute top-50 start-50 translate-middle"
      style="background-color: #F7E7D8; width: 18rem">
      <h3>Enter phone number before proceeding:</h3>
      <div class="form-floating">
        <textarea class="form-control" placeholder="Enter phone number" id="floatingPhoneNumber"></textarea>
        <label for="floatingPhoneNumber">Phone number</label>
      </div>
      <a class="btn btn-success" href="/menus" role="button">
        Confirm
      </a>
    </div>
  </main>
</x-customer-layout>