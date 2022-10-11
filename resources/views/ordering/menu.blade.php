<x-customer-layout>
  <header class="sticky-top">
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-sm navbar-dark" style="background-color: #000">
      <div class="container-md">
        <div class="navbar-brand ps-2">
          <img src="{{ asset('img/red_card_cafe_logo-removebg-preview.png') }}" alt="red card logo" width="80">
        </div>
        <a class="btn btn-dark border-0" href="/{{$customer_id}}/menus" role="button" style="background-color: #000">
          <i class="fas fa-times"></i>
        </a>
      </div>
    </nav>
  </header>

  <form method="POST" action="/{{$customer_id}}/{{$menu->id}}/cart">
    @csrf
    <main class="py-3 px-2 px-md-0 min-vh-100" style="background-color: #F7E7D8;">
      {{-- Menu --}}
      <div class="container-md">
        <div class="d-md-flex gap-4 justify-content-between">
          {{-- Image for small screens --}}
          <img src="{{ asset('menu_img/' . $menu->image_name) }}" class="w-100 d-md-none rounded border border-dark"
            alt="{{$menu->name}}">
          {{-- Image for medium screens and up --}}
          <img src="{{ asset('menu_img/' . $menu->image_name) }}"
            class="d-none d-md-block w-25 rounded border border-dark" alt="{{$menu->name}}">

          {{-- <div class="flex-fill"> --}}
            <div class="flex-fill d-flex flex-column">
              <div class="d-flex align-items-center justify-content-between pt-3 pb-1">
                <h1>{{$menu->name}}</h1>
                <h4 class="bg-danger text-white rounded text-center px-2 py-1">RM {{$menu->price}}</h4>
              </div>
              <p>{{$menu->description}}</p>
            </div>

            <div class="flex-fill rounded p-3 d-none d-md-block" style="background-color: #000">
              <div class="d-flex flex-column gap-3 flex-lg-row justify-content-between align-items-center">
                {{-- Quantity --}}
                <div class="btn-group" role="group" aria-label="Quantity" x-data="{ quantity: 1 }">
                  <button type="button" class="btn btn-light" x-on:click="quantity = quantity-1"
                    :disabled="(quantity == 1) ? true : false">
                    <i class="fas fa-minus"></i>
                  </button>

                  <input type="text" id="quantity" name="quantity" class="btn btn-light disabled" x-model="quantity"
                    style="width: 3rem" />

                  <button type="button" class="btn btn-light" x-on:click="quantity = quantity+1"
                    :disabled="(quantity < {{$menu->available_quantity}}) ? false : true">
                    <i class="fas fa-plus"></i>
                  </button>
                </div>

                {{-- Add menu to cart --}}
                <button type="submit" class="btn btn-success">
                  Add&nbsp;&nbsp;<i class="fas fa-cart-plus mr-2"></i>
                </button>
              </div>
            </div>

            {{--
          </div> --}}
        </div>

        <hr class="text-dark">

        {{-- Sides --}}
        @if ($menu->sides)
        <h4>Sides</h4>
        <x-sides-card :sides="$sides" />
        @endif

        {{-- Remarks --}}
        <label for="remarks" class="form-label">
          <h4>Remarks</h4>
        </label>
        <textarea class="form-control" id="remarks" name="remarks" placeholder="Enter remarks if any..."
          rows="3"></textarea>
      </div>
    </main>

    {{-- Footer --}}
    <footer class="footer px-1 py-3 sticky-bottom d-md-none" style="background-color: #000">
      <div class="container-fluid d-flex justify-content-between align-items-center">
        {{-- Quantity --}}
        <div class="btn-group" role="group" aria-label="Quantity" x-data="{ quantity: 1 }">
          <button type="button" class="btn btn-light" x-on:click="quantity = quantity-1"
            :disabled="(quantity == 1) ? true : false">
            <i class="fas fa-minus"></i>
          </button>

          <input type="text" id="quantity" name="quantity" class="btn btn-light disabled" x-model="quantity"
            style="width: 3rem" />

          <button type="button" class="btn btn-light" x-on:click="quantity = quantity+1"
            :disabled="(quantity < {{$menu->available_quantity}}) ? false : true">
            <i class="fas fa-plus"></i>
          </button>
        </div>

        {{-- Add menu to cart --}}
        <button type="submit" class="btn btn-success">
          Add&nbsp;&nbsp;<i class="fas fa-cart-plus mr-2"></i>
        </button>
      </div>
    </footer>
    <footer class="footer px-2 py-3 d-none d-md-block" style="background-color: #000">
      <div class="container-fluid text-center">
        <span class="h6 text-light pt-2"><strong>Copyright © 2022 Red Card Cafe.</strong> All rights reserved.</span>
      </div>
    </footer>
  </form>
</x-customer-layout>