<x-customer-layout>
  <header class="sticky-top">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #000">
      <div class="container-fluid">
        <div class="navbar-brand ps-2">
          <img src="{{ asset('img/red_card_cafe_logo-removebg-preview.png') }}" alt="red card logo" width="80">
        </div>
        <x-search />
      </div>
    </nav>

    <!-- Categories -->
    <div class="container bg-danger py-2 d-flex gap-2 overflow-auto">
      <a class="btn btn-dark border border-white border-2" href="#recommendedMenus" role="button">RECOMMENDED</a>
      <a class="btn btn-dark border border-white border-2" href="#trendingMenus" role="button">TRENDING</a>

      @unless (count($categories)==0)

      @foreach ($categories as $category)
      <a class="btn btn-dark border border-white border-2 text-nowrap" href="#{{strtolower($category->name)}}"
        role="button">{{strtoupper($category->name)}}</a>
      @endforeach

      @endunless
    </div>
  </header>

  <main>
    <!-- Menus -->
    <div class="container bg-dark py-2 px-3">
      <h5 class="text-white py-1" id="recommendedMenus">Recommended Menus</h5>
      <h5 class="text-white py-1" id="trendingMenus">Trending Menus</h5>

      @foreach ($categories as $category)
      <x-menu-card :customer_id="$customer_id" :menus="$menus" :category="$category" />
      @endforeach
    </div>
  </main>

  <!-- Footer -->
  <footer class="footer px-2 py-3 sticky-bottom" style="background-color: #000">
    <div class="container d-flex justify-content-between align-items-center">
      <span class="h4 text-light pt-2"><strong>Total:</strong> RM {{$totalPrice}}</span>
      <a class="btn btn-danger" href="/{{$customer_id}}/cart/confirm" role="button">
        Order&nbsp;&nbsp;<i class="fas fa-cart-plus mr-2"></i>
      </a>
    </div>
  </footer>
</x-customer-layout>