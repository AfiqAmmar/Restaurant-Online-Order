<x-customer-layout>
  <header class="sticky-top">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #000">
      <div class="container-fluid">
        <a class="navbar-brand ps-2" href="/menus">
          <img src="{{ asset('img/red_card_cafe_logo-removebg-preview.png') }}" alt="red card logo" width="80">
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <i class="fas fa-search"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <form class="d-flex" role="search">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Search</button>
          </form>
        </div>
      </div>
    </nav>

    <!-- Categories -->
    <div class="container bg-danger py-2 d-flex flex-nowrap gap-2 overflow-auto">
      <a class="btn btn-dark border border-white border-2" href="#predictedMenus" role="button">PREDICTED</a>
      <a class="btn btn-dark border border-white border-2" href="#trendingMenus" role="button">TRENDING</a>
      <a class="btn btn-dark border border-white border-2" href="#" role="button">CATEGORY</a>
      <a class="btn btn-dark border border-white border-2" href="#" role="button">CATEGORY</a>
      <a class="btn btn-dark border border-white border-2" href="#" role="button">CATEGORY</a>
      <a class="btn btn-dark border border-white border-2" href="#" role="button">CATEGORY</a>
    </div>
  </header>

  <main>
    <!-- Menus -->
    <div class="container bg-dark py-2 px-3">
      <h5 class="text-white py-1" id="predictedMenus">Predicted Menus</h5>
      <h5 class="text-white py-1" id="trendingMenus">Trending Menus</h5>
      <h5 class="text-white py-1">Category</h5>
      <div class="row gy-4 pb-3">
        <div class="col-6">
          <div class="card" style="background-color: #F7E7D8;">
            <img src="{{ asset('img/anh-nguyen-kcA-c3f_3FE-unsplash.jpg') }}" class="card-img-top rounded"
              alt="bowl of salad">
            <div class="card-body">
              <h5 class="card-title">Bowl of Salad</h5>
              <p class="card-text">RM 10</p>
            </div>
            <a href="/menus/menu" class="stretched-link"></a>
          </div>
        </div>
        <div class="col-6">
          <div class="card" style="background-color: #F7E7D8;">
            <img src="{{ asset('img/anh-nguyen-kcA-c3f_3FE-unsplash.jpg') }}" class="card-img-top rounded"
              alt="bowl of salad">
            <div class="card-body">
              <h5 class="card-title">Bowl of Salad</h5>
              <p class="card-text">RM 10</p>
            </div>
            <a href="/menus/{menu}" class="stretched-link"></a>
          </div>
        </div>
        <div class="col-6">
          <div class="card" style="background-color: #F7E7D8;">
            <img src="{{ asset('img/anh-nguyen-kcA-c3f_3FE-unsplash.jpg') }}" class="card-img-top rounded"
              alt="bowl of salad">
            <div class="card-body">
              <h5 class="card-title">Bowl of Salad</h5>
              <p class="card-text">RM 10</p>
            </div>
            <a href="/menus/{menu}" class="stretched-link"></a>
          </div>
        </div>
        <div class="col-6">
          <div class="card" style="background-color: #F7E7D8;">
            <img src="{{ asset('img/anh-nguyen-kcA-c3f_3FE-unsplash.jpg') }}" class="card-img-top rounded"
              alt="bowl of salad">
            <div class="card-body">
              <h5 class="card-title">Bowl of Salad</h5>
              <p class="card-text">RM 10</p>
            </div>
            <a href="/menus/{menu}" class="stretched-link"></a>
          </div>
        </div>
      </div>

      <h5 class="text-white py-1">Category</h5>
      <div class="row gy-4 pb-3">
        <div class="col-6">
          <div class="card" style="background-color: #F7E7D8;">
            <img src="{{ asset('img/anh-nguyen-kcA-c3f_3FE-unsplash.jpg') }}" class="card-img-top rounded"
              alt="bowl of salad">
            <div class="card-body">
              <h5 class="card-title">Bowl of Salad</h5>
              <p class="card-text">RM 10</p>
            </div>
            <a href="/menus/{menu}" class="stretched-link"></a>
          </div>
        </div>
        <div class="col-6">
          <div class="card" style="background-color: #F7E7D8;">
            <img src="{{ asset('img/anh-nguyen-kcA-c3f_3FE-unsplash.jpg') }}" class="card-img-top rounded"
              alt="bowl of salad">
            <div class="card-body">
              <h5 class="card-title">Bowl of Salad</h5>
              <p class="card-text">RM 10</p>
            </div>
            <a href="/menus/{menu}" class="stretched-link"></a>
          </div>
        </div>
        <div class="col-6">
          <div class="card" style="background-color: #F7E7D8;">
            <img src="{{ asset('img/anh-nguyen-kcA-c3f_3FE-unsplash.jpg') }}" class="card-img-top rounded"
              alt="bowl of salad">
            <div class="card-body">
              <h5 class="card-title">Bowl of Salad</h5>
              <p class="card-text">RM 10</p>
            </div>
            <a href="/menus/{menu}" class="stretched-link"></a>
          </div>
        </div>
        <div class="col-6">
          <div class="card" style="background-color: #F7E7D8;">
            <img src="{{ asset('img/anh-nguyen-kcA-c3f_3FE-unsplash.jpg') }}" class="card-img-top rounded"
              alt="bowl of salad">
            <div class="card-body">
              <h5 class="card-title">Bowl of Salad</h5>
              <p class="card-text">RM 10</p>
            </div>
            <a href="/menus/{menu}" class="stretched-link"></a>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="footer px-2 py-3 sticky-bottom" style="background-color: #000">
    <div class="container d-flex justify-content-between align-items-center">
      <span class="h4 text-light pt-2"><strong>Total:</strong> RM0.00</span>
      <a class="btn btn-danger" href="/confirm-order" role="button">
        Order&nbsp;&nbsp;<i class="fas fa-cart-plus mr-2"></i>
      </a>
    </div>
  </footer>
</x-customer-layout>