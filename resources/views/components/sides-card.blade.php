@props(['sides'])

<div class="row gy-4 pb-3">
  @foreach ($sides as $side)
  <div class="col-6 col-sm-4 col-md-3 col-lg-2" x-data="{ checked: false, open: false }">
    <input type="checkbox" class="checkbox" id="{{strtolower($side->name)}}" name="sides[]" {{--
      value="{{strtolower($side->name)}}" --}} value="{{$side->id}}" autocomplete="off" x-show="open" x-cloak>
    <label for="{{strtolower($side->name)}}" x-on:click="checked = !checked">
      <div class="card pointer" x-bind:class="checked ? 'bg-danger' : 'bg-dark'">
        <div x-show="checked">
          <span
            class="position-absolute top-0 start-100 translate-middle badge p-2 rounded-circle bg-danger border border-light">
            &#10003;
          </span>
        </div>
        <img style="aspect-ratio: 1 / 1;" src="{{ asset('menu_img/' . $side->image_name) }}"
          class="card-img-top rounded" alt="{{$side->name}}">
        <div class="card-body">
          <h5 class="card-title text-white">{{$side->name}}</h5>
        </div>
      </div>
    </label>
  </div>
  @endforeach
</div>