@props(['sides'])

@php
// $menus = $menus->where('category_id', '5');
@endphp

<div class="row gy-4 pb-3">
  @foreach ($sides as $side)
  <div class="col-6">
    <div class="card bg-dark">
      <img src="{{ asset('menu_img/' . $side->image_name) }}" class="card-img-top rounded" alt="bowl of salad">
      <div class="card-body">
        <h5 class="card-title text-white">{{$side->name}}</h5>
        <p class="card-text text-white">RM {{$side->price}}</p>
      </div>
      {{-- <a href="/menus/{{$menu->id}}" class="stretched-link"></a> --}}
    </div>
  </div>
  @endforeach
</div>