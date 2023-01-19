@props(['customer_id', 'menus', 'category', 'request', 'cart_menus'])

@php
$menus = $menus->where('category_id', $category->id);
@endphp

@unless ($menus->isEmpty())

@unless ($request->has('search'))
<h5 class="text-white py-1 category" id="{{strtolower($category->name)}}">{{$category->name}}</h5>
@endunless

<div class="row gy-4 pb-3">
  @foreach ($menus as $menu)

  @php
  $cart_menu = $cart_menus->where('id', $menu->id)->first();
  @endphp

  @if ($menu->availability==0)

  @if ($cart_menu)
  <div class="col-6 col-sm-4 col-lg-3">
    <div class="card bg-danger">
      <div class="ratio ratio-1x1">
        <img src="{{ asset('menu_img/' . $menu->image_name) }}" class="card-img-top rounded" alt="{{$menu->name}}">
      </div>
      <span
        class="position-absolute top-0 start-100 translate-middle badge p-2 rounded-circle bg-danger border border-light">
        &#10003;
      </span>
      <div class="card-body">
        <h5 class="card-title text-white">{{$menu->name}}</h5>
        <p class="card-text text-white">RM {{$menu->price}}</p>
      </div>
      <a href="/{{$customer_id}}/menus/{{$menu->id}}" class="stretched-link"></a>
    </div>
  </div>
  @else
  <div class="col-6 col-sm-4 col-lg-3">
    <div class="card" style="background-color: #F7E7D8;">
      <img style="aspect-ratio: 1 / 1;" src="{{ asset('menu_img/' . $menu->image_name) }}" class="card-img-top rounded"
        alt="{{$menu->name}}">
      <div class="card-body">
        <h5 class="card-title">{{$menu->name}}</h5>
        <p class="card-text">RM {{$menu->price}}</p>
      </div>
      <a href="/{{$customer_id}}/menus/{{$menu->id}}" class="stretched-link"></a>
    </div>
  </div>
  @endif

  @else

  @if ($cart_menu)
  <div class="col-6 col-sm-4 col-lg-3">
    <div class="card opacity-50" style="background-color: #F7E7D8;">
      <span
        class="position-absolute top-0 start-100 translate-middle badge p-2 rounded-circle bg-danger border border-light">
        &#10003;
      </span>
      <img style="aspect-ratio: 1 / 1;" src="{{ asset('menu_img/' . $menu->image_name) }}" class="card-img-top rounded"
        alt="{{$menu->name}}">
      <div class="card-body">
        <h5 class="card-title">{{$menu->name}}</h5>
        <p class="card-text">RM {{$menu->price}}</p>
      </div>
      <button type="button" class="btn btn-dark" disabled>SOLD OUT</button>
    </div>
  </div>
  @else
  <div class="col-6 col-sm-4 col-lg-3">
    <div class="card opacity-50" style="background-color: #F7E7D8;">
      <img style="aspect-ratio: 1 / 1;" src="{{ asset('menu_img/' . $menu->image_name) }}" class="card-img-top rounded"
        alt="{{$menu->name}}">
      <div class="card-body">
        <h5 class="card-title">{{$menu->name}}</h5>
        <p class="card-text">RM {{$menu->price}}</p>
      </div>
      <button type="button" class="btn btn-dark" disabled>SOLD OUT</button>
    </div>
  </div>
  @endif

  @endif

  @endforeach
</div>

@endunless