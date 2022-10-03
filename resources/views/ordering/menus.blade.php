<x-customer-layout>
  <header class="sticky-top">
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #000">
      <div class="container-fluid">
        <div class="navbar-brand ps-2">
          <a href="/{{$customer_id}}/menus">
            <img src="{{ asset('img/red_card_cafe_logo-removebg-preview.png') }}" alt="red card logo" width="80">
          </a>
        </div>
        <x-search :customer_id="$customer_id" />
      </div>
    </nav>

    {{-- Categories --}}
    <div class="container bg-danger py-2 d-flex gap-2 overflow-auto">
      <a class="btn btn-dark border border-white border-2" href="#favouriteMenus" role="button">FAVOURITE</a>
      <a class="btn btn-dark border border-white border-2" href="#recommendedMenus" role="button">RECOMMENDED</a>
      <a class="btn btn-dark border border-white border-2" href="#trendingMenus" role="button">POPULAR</a>

      @foreach ($categories as $category)
      <a class="btn btn-dark border border-white border-2 text-nowrap" href="#{{strtolower($category->name)}}"
        role="button">{{strtoupper($category->name)}}</a>
      @endforeach
    </div>
  </header>

  <main>
    {{-- Menus --}}
    <div class="container bg-dark py-2 px-3 min-vh-100">
      @if ($fav_menu_col->isNotEmpty())
        <h5 class="text-white py-1" id="favouriteMenus">Your Favourite</h5>
        <div class="row gy-4 pb-3">
          @foreach ($fav_menu_col as $fav)
            <div class="col-6">
              <div class="card" style="background-color: #F7E7D8;">
                <img src="{{ asset('menu_img/' . $fav->image_name) }}" class="card-img-top rounded" alt="{{$fav->name}}">
                <div class="card-body">
                  <h5 class="card-title">{{$fav->name}}</h5>
                  <p class="card-text">RM {{$fav->price}}</p>
                </div>
                <a href="/{{$customer_id}}/menus/{{$fav->id}}" class="stretched-link"></a>
              </div>
            </div>
          @endforeach
        </div>
      @endif
      @if ($recommend_menu_col->isNotEmpty())
        <h5 class="text-white py-1" id="recommendedMenus">Recommended Menus</h5>
        <div class="row gy-4 pb-3">
          @foreach ($recommend_menu_col as $rec)
            <div class="col-6">
              <div class="card" style="background-color: #F7E7D8;">
                <img src="{{ asset('menu_img/' . $rec->image_name) }}" class="card-img-top rounded" alt="{{$rec->name}}">
                <div class="card-body">
                  <h5 class="card-title">{{$rec->name}}</h5>
                  <p class="card-text">RM {{$rec->price}}</p>
                </div>
                <a href="/{{$customer_id}}/menus/{{$rec->id}}" class="stretched-link"></a>
              </div>
            </div>
          @endforeach
        </div>
      @endif
      <h5 class="text-white py-1" id="trendingMenus">Popular Menus</h5>
      <div class="row gy-4 pb-3">
        @foreach ($trend_menus as $trend)
          <div class="col-6">
            <div class="card" style="background-color: #F7E7D8;">
              <img src="{{ asset('menu_img/' . $trend->image_name) }}" class="card-img-top rounded" alt="{{$trend->name}}">
              <div class="card-body">
                <h5 class="card-title">{{$trend->name}}</h5>
                <p class="card-text">RM {{$trend->price}}</p>
              </div>
              <a href="/{{$customer_id}}/menus/{{$trend->id}}" class="stretched-link"></a>
            </div>
          </div>
        @endforeach
      </div>

      @foreach ($categories as $category)
      <x-menu-card :customer_id="$customer_id" :menus="$menus" :category="$category" />
      @endforeach
    </div>
  </main>

  {{-- Footer --}}
  <footer class="footer px-2 py-3 sticky-bottom" style="background-color: #000">
    <div class="container d-flex justify-content-between align-items-center">
      <span class="h4 text-light pt-2"><strong>Total:</strong> RM {{$totalPrice}}</span>
      <a class="btn btn-danger" href="/{{$customer_id}}/cart/confirm" role="button">
        Order&nbsp;&nbsp;<i class="fas fa-cart-plus mr-2"></i>
      </a>
    </div>
  </footer>
</x-customer-layout>