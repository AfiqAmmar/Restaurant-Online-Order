@props(['cartMenu', 'customer_id', 'menus'])

@php
$menu = $menus->where('id', $cartMenu->id)->first();
$remaining_quantity = $menu->available_quantity;
$cartMenuQuantity = $cartMenu->pivot->quantity;
$available_quantity = $remaining_quantity + $cartMenuQuantity;
@endphp

<div class="card mb-3" style="background-color: #F7E7D8;">
  <div class="row g-0 align-items-center">
    <div class="col-4">
      <div class="ratio ratio-1x1">
        <img src="{{ asset('menu_img/' . $cartMenu->image_name) }}" class="img-fluid rounded" alt="{{$cartMenu->name}}">
      </div>
    </div>

    <div class="col-8 px-3">
      <div class="row">
        <div class="col">
          <h5 class="card-title">{{$cartMenu->name}}</h5>
        </div>
        <div class="col-5">
          <p class="fs-5 text-end">RM {{$cartMenu->price}}</p>
        </div>
      </div>

      <div class="d-flex justify-content-between">
        <div class="d-none menu_id">{{$cartMenu->id}}</div>
        <div class="d-none menu_available_quantity">{{$available_quantity}}</div>

        <div class="btn-group" role="group" aria-label="Quantity" x-data="{ quantity: {{$cartMenu->pivot->quantity}} }">
          <button type="button" class="btn btn-primary minusButton" :disabled="(quantity == 1) ? true : false">
            <i class="fas fa-minus"></i>
          </button>

          <input type="text" id="quantity" name="quantity" class="btn btn-primary quantity" x-model="quantity"
            style="width: 3rem" disabled />

          <button type="button" class="btn btn-primary plusButton"
            :disabled="(quantity < {{$available_quantity}}) ? false : true">
            <i class="fas fa-plus"></i>
          </button>
        </div>

        <a class="btn btn-danger" href="/{{$customer_id}}/cart/{{$cartMenu->id}}/delete" role="button">
          <i class="fas fa-trash"></i>
        </a>
      </div>
    </div>
  </div>
</div>