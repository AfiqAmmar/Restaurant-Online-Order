@props(['customer_id', 'recMenu', 'cart_menus'])

@php
$cart_menu = $cart_menus->where('id', $recMenu->id)->first();
@endphp

@if ($cart_menu)
<div class="col-6 col-sm-4 col-lg-3">
  <div class="card bg-danger">
    <span
      class="position-absolute top-0 start-100 translate-middle badge p-2 rounded-circle bg-danger border border-light">
      &#10003;
    </span>
    <img style="aspect-ratio: 1 / 1;" src="{{ asset('menu_img/' . $recMenu->image_name) }}" class="card-img-top rounded"
      alt="{{$recMenu->name}}">
    <div class="card-body">
      <h5 class="card-title text-white">{{$recMenu->name}}</h5>
      <p class="card-text text-white">RM {{$recMenu->price}}</p>
    </div>
    <a href="/{{$customer_id}}/menus/{{$recMenu->id}}" class="stretched-link"></a>
  </div>
</div>
@else
<div class="col-6 col-sm-4 col-lg-3">
  <div class="card" style="background-color: #F7E7D8;">
    <img style="aspect-ratio: 1 / 1;" src="{{ asset('menu_img/' . $recMenu->image_name) }}" class="card-img-top rounded"
      alt="{{$recMenu->name}}">
    <div class="card-body">
      <h5 class="card-title">{{$recMenu->name}}</h5>
      <p class="card-text">RM {{$recMenu->price}}</p>
    </div>
    <a href="/{{$customer_id}}/menus/{{$recMenu->id}}" class="stretched-link"></a>
  </div>
</div>
@endif