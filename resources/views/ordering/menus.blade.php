<x-customer-layout>
  <header class="sticky-top">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #000">
      <div class="container-fluid">
        <a class="navbar-brand ps-2" href="/menus">
          <img src="{{ asset('img/red_card_cafe_logo-removebg-preview.png') }}" alt="red card logo" width="80">
        </a>
        <x-search />
      </div>
    </nav>

    <!-- Categories -->
    <div class="container bg-danger py-2 d-flex flex-nowrap gap-2 overflow-auto">
      <a class="btn btn-dark border border-white border-2" href="#predictedMenus" role="button">PREDICTED</a>
      <a class="btn btn-dark border border-white border-2" href="#trendingMenus" role="button">TRENDING</a>

      @unless (count($categories)==0)

      @foreach ($categories as $category)
      <a class="btn btn-dark border border-white border-2" href="#{{strtolower($category->name)}}"
        role="button">{{strtoupper($category->name)}}</a>
      @endforeach

      @endunless
    </div>
  </header>

  <main>
    <!-- Menus -->
    <div class="container bg-dark py-2 px-3">
      <h5 class="text-white py-1" id="predictedMenus">Predicted Menus</h5>
      <h5 class="text-white py-1" id="trendingMenus">Trending Menus</h5>

      @foreach ($categories as $category)
      <x-menu-card :menus="$menus" :category="$category" />
      @endforeach
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