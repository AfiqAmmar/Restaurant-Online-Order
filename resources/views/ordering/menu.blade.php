<x-customer-layout>
  <header class="sticky-top">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #000">
      <div class="container-fluid">
        <a class="navbar-brand ps-2" href="/menus">
          <img src="{{ asset('img/red_card_cafe_logo-removebg-preview.png') }}" alt="red card logo" width="80">
        </a>
        <a class="btn btn-dark border-0" href="/menus" role="button" style="background-color: #000">
          <i class="fas fa-times"></i>
        </a>
      </div>
    </nav>
  </header>

  <main>
    <!-- Menu -->
    <div class="container p-3 min-vh-100" style="background-color: #F7E7D8;">
      <img src="{{ asset('img/anh-nguyen-kcA-c3f_3FE-unsplash.jpg') }}" class="card-img-top rounded border border-dark"
        alt="bowl of salad">
      <div class="d-flex align-items-center justify-content-between pt-3 pb-1">
        <h1>Bowl of Salad</h1>
        <h4 class="bg-danger text-white rounded text-center px-2 py-1">RM 10</h4>
      </div>
      <p>Just a bowl of salad.</p>

      <hr class="text-dark">

      <label for="remarks" class="form-label">
        <h4>Remarks</h4>
      </label>
      <textarea class="form-control" id="remarks" rows="3"></textarea>
    </div>
  </main>

  <!-- Footer -->
  <footer class="footer px-1 py-3 sticky-bottom" style="background-color: #000">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="btn-group btn-group-sm" role="group" aria-label="Quantity">
        <button type="button" class="btn btn-light"><i class="fas fa-minus"></i></button>
        <button type="button" class="btn btn-light disabled">0</button>
        <button type="button" class="btn btn-light"><i class="fas fa-plus"></i></button>
      </div>
      <a class="btn btn-success" href="/menus" role="button">
        Add&nbsp;&nbsp;<i class="fas fa-cart-plus mr-2"></i>
      </a>
    </div>
  </footer>
</x-customer-layout>