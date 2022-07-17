<x-customer-layout>
  <header class="sticky-top" style="background-color: #000">
    <!-- Navbar -->
    <div class="container-fluid py-3">
      <div class="row">
        <div class="col">
          <a class="btn btn-dark border-0" href="/menus" role="button" style="background-color: #000">
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
      <!-- Selected Menus -->
      <h5 class="text-white py-1">Selected Menus</h5>
      <div class="card mb-3" style="background-color: #F7E7D8;">
        <div class="row g-0 align-items-center">
          <div class="col-4">
            <img src="{{ asset('img/anh-nguyen-kcA-c3f_3FE-unsplash.jpg') }}" class="img-fluid rounded"
              alt="bowl of salad">
          </div>
          <div class="col-6">
            <div class="card-body">
              <h5 class="card-title">Bowl of Salad</h5>
              <p class="card-text">RM 10</p>
            </div>
          </div>
          <div class="col-1">
            <p class="bg-danger rounded-circle text-center text-white">2</p>
          </div>
        </div>
      </div>
      <div class="card mb-3" style="background-color: #F7E7D8;">
        <div class="row g-0 align-items-center">
          <div class="col-4">
            <img src="{{ asset('img/anh-nguyen-kcA-c3f_3FE-unsplash.jpg') }}" class="img-fluid rounded"
              alt="bowl of salad">
          </div>
          <div class="col-6">
            <div class="card-body">
              <h5 class="card-title">Bowl of Salad</h5>
              <p class="card-text">RM 10</p>
            </div>
          </div>
          <div class="col-1">
            <p class="bg-danger rounded-circle text-center text-white">2</p>
          </div>
        </div>
      </div>
      <div class="card mb-3" style="background-color: #F7E7D8;">
        <div class="row g-0 align-items-center">
          <div class="col-4">
            <img src="{{ asset('img/anh-nguyen-kcA-c3f_3FE-unsplash.jpg') }}" class="img-fluid rounded"
              alt="bowl of salad">
          </div>
          <div class="col-6">
            <div class="card-body">
              <h5 class="card-title">Bowl of Salad</h5>
              <p class="card-text">RM 10</p>
            </div>
          </div>
          <div class="col-1">
            <p class="bg-danger rounded-circle text-center text-white">2</p>
          </div>
        </div>
      </div>

      <hr style="color: #F7E7D8">

      <!-- Cart Description -->
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="text-white">Total Price</h5>
        <h5 class="text-white lead">RM60</h5>
      </div>
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="text-white">Estimated Preparation Time</h5>
        <h5 class="text-white lead">20 mins</h5>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="footer py-3 fixed-bottom" style="background-color: #000">
    <div class="container d-flex justify-content-between align-items-center">
      <a class="btn btn-danger" href="/menus" role="button">
        <i class="fas fa-trash"></i>&nbsp;Clear
      </a>
      <a class="btn btn-success" href="/order-confirmed" role="button">
        Confirm Order
      </a>
    </div>
  </footer>
</x-customer-layout>