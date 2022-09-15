@props(['order', 'table', 'menus', 'allMenus', 'serveStatus'])

@php
foreach ($menus as $menu) {
if ($menu->pivot->menu_serve == 0) {
$serveStatus = 0;
break;
}
}
@endphp

<div class="col">
  <div class="card card-light">
    <div class="card-body">
      <div class="row">
        <div class="col">
          <h4>Order <b>#{{ $order->id }}</b></h4>
        </div>
        <div class="col">
          <h4>Table Number: <b>{{ $table->table_number }}</b></h4>
        </div>
        <div class="col">
          <h4>Order Time: <b>{{ $order->created_at->format('g:i a')}}</b></h4>
        </div>
      </div>
      <hr>
      <h4 class="mb-3">Menus</h4>
      <table id="orderQueueTable" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Name</th>
            <th>Quantity</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($menus as $menu)

          @php
          $sides = $menu->pivot->sides;
          if ($sides == null || $sides == 'null') {
          $output = 'N/A';
          } else if ($sides != 'N/A') {
          $output = explode(', ', $sides);
          } else {
          $output = $sides;
          }
          @endphp

          <tr>
            <td>
              {{$menu->name}}

              @if (!is_string($output))
              <br>
              <hr>
              <strong>Sides</strong>
              <ul>
                @foreach ($output as $side)
                <li>{{$allMenus->where('id', $side)->first()->name}}</li>
                @endforeach
              </ul>
              @endif

            </td>
            <td>{{$menu->pivot->quantity}}</td>
            <td>
              @if ($menu->pivot->menu_serve==1)
              <button type="button" class="btn btn-success">
                <a href="order-queue/{{$order->id}}/menu-served/{{$menu->id}}" class="text-white">
                  <i class="icon fas fa-check"></i>&nbsp;&nbsp;Served
                </a>
                @else
                <button type="button" class="btn btn-primary">
                  <a href="order-queue/{{$order->id}}/menu-served/{{$menu->id}}" class="text-white">Serve</a>
                  @endif
                </button>
            </td>
          </tr>

          @endforeach
        </tbody>
      </table>

      @if ($serveStatus==1)
      <hr>
      <button type="button" class="btn btn-danger float-right">
        <a href="order-queue/{{$order->id}}/served" class="text-white">
          Order Served
        </a>
      </button>
      @endif

    </div>
  </div>
</div>