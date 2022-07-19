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
      <form method="POST" action="/customers">
        @csrf
        <div class="form-floating pb-4">
          <input class="form-control" type="text" placeholder="Enter phone number" id="phone_num" name="phone_num">
          <label for="phone_num">Phone number</label>

          @error('phone_num')
          <div class="invalid-feedback">{{$message}}</div>
          @enderror
        </div>
        <button type="submit" class="btn btn-success">Confirm</button>
      </form>
    </div>
  </main>
</x-customer-layout>