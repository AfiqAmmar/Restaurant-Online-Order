@props(['sides'])

<div class="row gy-4 pb-3">
  @foreach ($sides as $side)
  <div class="col-6">
    <div class="card bg-dark">
      <img src="{{ asset('menu_img/' . $side->image_name) }}" class="card-img-top rounded" alt="{{$side->name}}">
      <div class="card-body">
        <h5 class="card-title text-white">{{$side->name}}</h5>
        <p class="card-text text-white">RM {{$side->price}}</p>
      </div>
      <div class="d-flex justify-content-center pb-3">
        <input type="checkbox" class="btn-check" id="{{strtolower($side->name)}}" name="sides"
          value="{{strtolower($side->name)}}" autocomplete="off">
        <label class="btn btn-danger" style="width: 5rem" for="{{strtolower($side->name)}}">Select</label>
      </div>
    </div>
  </div>
  @endforeach
</div>