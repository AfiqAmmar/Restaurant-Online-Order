@props(['cartMenu', 'menus', 'customer_id'])

@php
$menu_id = $cartMenu->menu_id;
$menu = $menus->where('id', $menu_id)->first();
@endphp

<div class="card mb-3" style="background-color: #F7E7D8;">
  <div class="row g-0 align-items-center">
    <div class="col-4">
      <img src="{{ asset('menu_img/' . $menu->image_name) }}" class="img-fluid rounded" alt="bowl of salad">
    </div>
    <div class="col-6">
      <div class="card-body">
        <h5 class="card-title">{{$menu->name}}</h5>
        <p class="card-text">RM {{$menu->price}}</p>
      </div>
    </div>
    <div class="col-2 text-center">
      <span class="badge rounded-circle text-bg-primary mb-3">{{$cartMenu->quantity}}</span>
      <a class="btn btn-danger" href="/{{$customer_id}}/cart/{{$menu_id}}/delete" role="button">
        <i class="fas fa-trash"></i>
      </a>
    </div>
  </div>
</div>