@props(['cartMenu', 'customer_id'])

<div class="card mb-3" style="background-color: #F7E7D8;">
  <div class="row g-0 align-items-center">
    <div class="col-4">
      <img src="{{ asset('menu_img/' . $cartMenu->image_name) }}" class="img-fluid rounded" alt="{{$cartMenu->name}}">
    </div>
    <div class="col-6">
      <div class="card-body">
        <h5 class="card-title">{{$cartMenu->name}}</h5>
        <p class="card-text">RM {{$cartMenu->price}}</p>
      </div>
    </div>
    <div class="col-2 text-center">
      <span class="badge rounded-circle text-bg-primary mb-3">{{$cartMenu->pivot->quantity}}</span>
      <a class="btn btn-danger" href="/{{$customer_id}}/cart/{{$cartMenu->id}}/delete" role="button">
        <i class="fas fa-trash"></i>
      </a>
    </div>
  </div>
</div>