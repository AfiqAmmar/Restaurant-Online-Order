<x-customer-layout>
  <header class="sticky-top">
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-sm navbar-dark" style="background-color: #000">
      <div class="container-md">
        <div class="navbar-brand ps-2">
          <a href="/{{$customer_id}}/menus">
            <img src="{{ asset('img/red_card_cafe_logo-removebg-preview.png') }}" alt="red card logo" width="80">
          </a>
        </div>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <i class="fas fa-search"></i>
        </button>
        <div class="collapse navbar-collapse py-2" id="navbarNav">
          <form class="d-flex ms-auto" role="search" action="/{{$customer_id}}/menus">
            <input class="form-control me-2" type="search" placeholder="Search menus..." id="" aria-label="Search"
              name="search">
            <button class="btn btn-outline-success" type="submit">Search</button>
          </form>
        </div>
      </div>
    </nav>

    {{-- Categories on phone size --}}
    @unless ($request->has('search'))
    <div class="container-fluid bg-danger py-2 d-flex gap-2 overflow-auto d-md-none">
      @if ($fav_menu_col->isNotEmpty())
      <a class="btn btn-dark border border-white border-2" href="#favouriteMenus" role="button">FAVOURITE</a>
      @endif

      @if ($recommend_menu_col->isNotEmpty())
      <a class="btn btn-dark border border-white border-2" href="#recommendedMenus" role="button">RECOMMENDED</a>
      @endif

      <a class="btn btn-dark border border-white border-2" href="#trendingMenus" role="button">POPULAR</a>

      @foreach ($categories as $category)
      <a class="btn btn-dark border border-white border-2 text-nowrap" href="#{{strtolower($category->name)}}"
        role="button">{{strtoupper($category->name)}}</a>
      @endforeach
    </div>
    @endunless
  </header>

  <div class="bg-dark py-3 px-2 px-md-0 min-vh-100">
    <div class="container-md d-flex gap-4">
      {{-- Menus --}}
      <main>
        @unless ($request->has('search'))

        @if ($fav_menu_col->isNotEmpty())
        <h5 class="text-white py-1" id="favouriteMenus">Your Favourite</h5>
        <div class="row gy-4 pb-3">
          @foreach ($fav_menu_col as $fav)
          <div class="col-6 col-sm-4 col-lg-3">
            <div class="card" style="background-color: #F7E7D8;">
              <img style="aspect-ratio: 1 / 1;" src="{{ asset('menu_img/' . $fav->image_name) }}"
                class="card-img-top rounded" alt="{{$fav->name}}">
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
          <div class="col-6 col-sm-4 col-lg-3">
            <div class="card" style="background-color: #F7E7D8;">
              <img style="aspect-ratio: 1 / 1;" src="{{ asset('menu_img/' . $rec->image_name) }}"
                class="card-img-top rounded" alt="{{$rec->name}}">
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
          <div class="col-6 col-sm-4 col-lg-3">
            <div class="card" style="background-color: #F7E7D8;">
              <img style="aspect-ratio: 1 / 1;" src="{{ asset('menu_img/' . $trend->image_name) }}"
                class="card-img-top rounded" alt="{{$trend->name}}">
              <div class="card-body">
                <h5 class="card-title">{{$trend->name}}</h5>
                <p class="card-text">RM {{$trend->price}}</p>
              </div>
              <a href="/{{$customer_id}}/menus/{{$trend->id}}" class="stretched-link"></a>
            </div>
          </div>
          @endforeach
        </div>
        
        @endunless

        @unless ($menus->isEmpty())

        @foreach ($categories as $category)
        <x-menu-card :customer_id="$customer_id" :menus="$menus" :category="$category" :request="$request" />
        @endforeach

        @else

        <div class="position-absolute top-50 start-50 translate-middle text-center">
          <img src="{{ asset('img/empty-cart-red-simple.svg') }}" alt="empty cart" width='350'>
          <h3 class="text-white py-1">No menus found</h3>
        </div>

        @endunless
      </main>

      {{-- TODO: Fix sidebar scroll --}}
      <aside class="sidebar d-flex flex-column d-none d-md-block">
        {{-- Categories on tablet size --}}
        @unless ($menus->isEmpty() || $request->has('search'))
        <div class="container bg-danger rounded p-3 d-flex flex-column gap-3 mt-2 mb-4">
          @if ($fav_menu_col->isNotEmpty())
          <a class="btn btn-dark border border-white border-2" href="#favouriteMenus" role="button">FAVOURITE</a>
          @endif

          @if ($recommend_menu_col->isNotEmpty())
          <a class="btn btn-dark border border-white border-2" href="#recommendedMenus" role="button">RECOMMENDED</a>
          @endif

          <a class="btn btn-dark border border-white border-2" href="#trendingMenus" role="button">POPULAR</a>

          @foreach ($categories as $category)
          <a class="btn btn-dark border border-white border-2 text-nowrap" href="#{{strtolower($category->name)}}"
            role="button">{{strtoupper($category->name)}}</a>
          @endforeach
        </div>
        @endunless

        {{-- Footer on tablet size --}}
        <div class="container rounded px-3 pb-3 pt-2 d-flex flex-column gap-2" style="background-color: #000">
          <span class="h4 text-light pt-2"><strong>Total:</strong> RM {{$totalPrice}}</span>
          <a class="btn btn-danger" href="/{{$customer_id}}/cart/confirm" role="button">
            Order&nbsp;&nbsp;<i class="fas fa-cart-plus mr-2"></i>
          </a>
        </div>
      </aside>
    </div>
  </div>

  {{-- Footer at phone size --}}
  <footer class="footer px-2 py-3 sticky-bottom d-md-none" style="background-color: #000">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <span class="h4 text-light pt-2"><strong>Total:</strong> RM {{$totalPrice}}</span>
      <a class="btn btn-danger" href="/{{$customer_id}}/cart/confirm" role="button">
        Order&nbsp;&nbsp;<i class="fas fa-cart-plus mr-2"></i>
      </a>
    </div>
  </footer>

  {{-- Footer at tablet size upwards --}}
  <footer class="footer px-2 py-3 d-none d-md-block" style="background-color: #000">
    <div class="container-fluid text-center">
      <span class="h6 text-light pt-2"><strong>Copyright © 2022 Red Card Cafe.</strong> All rights reserved.</span>
    </div>
  </footer>

  <a href="#" class="top text-white text-decoration-none p-3 fs-6 rounded-pill d-none d-md-inline">Back to Top
    &#8593;</a>
</x-customer-layout>