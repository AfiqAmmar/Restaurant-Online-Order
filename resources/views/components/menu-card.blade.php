@props(['customer_id', 'menus', 'category'])

@php
$menus = $menus->where('category_id', $category->id);
@endphp

<h5 class="text-white py-1" id="{{strtolower($category->name)}}">{{$category->name}}</h5>
<div class="row gy-4 pb-3">
  @foreach ($menus as $menu)
  <div class="col-6">
    <div class="card" style="background-color: #F7E7D8;">
      <img src="{{ asset('menu_img/' . $menu->image_name) }}" class="card-img-top rounded" alt="bowl of salad">
      <div class="card-body">
        <h5 class="card-title">{{$menu->name}}</h5>
        <p class="card-text">RM {{$menu->price}}</p>
      </div>
      <a href="/{{$customer_id}}/menus/{{$menu->id}}" class="stretched-link"></a>
    </div>
  </div>
  @endforeach
</div>