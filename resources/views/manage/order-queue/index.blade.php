@extends('layouts.app')

@section('content')

<section class="content mt-4">
  <div class="container-fluid">
    <div class="row">
      <div class="col" x-data="{ open: false }">
        <h4 class="mb-3">Order Queue</h4>
        <div class="card">
          @role('master-admin')
          <div class="card-header">
            <div class="btn-group" role="group" aria-label="Toggle between waiter and kitchen staff order queue">

              {{-- Waiter button --}}
              <template x-if="open">
                <button type="button" class="btn btn-outline-primary card-title"
                  x-on:click="open = false">Waiter</button>
              </template>
              <template x-if="!open">
                <button type="button" class="btn btn-outline-primary card-title active"
                  aria-current="page">Waiter</button>
              </template>

              {{-- Kitchen staff button --}}
              <template x-if="open">
                <button type="button" class="btn btn-outline-primary card-title active" aria-current="page">Kitchen
                  Staff</button>
              </template>
              <template x-if="!open">
                <button type="button" class="btn btn-outline-primary card-title" x-on:click="open = true">Kitchen
                  Staff</button>
              </template>

            </div>

          </div>
          @endrole
          <div class="card-body">
            @if ($ordersNotServed->isNotEmpty())
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3">
              @foreach ($ordersNotServed as $order)

              @php
              $table = $tables->where('id', $order->table_id)->first();
              $menus = $order->menus()->get();
              $serveStatus = 1;
              $prepareStatus = 1;
              @endphp

              @role('master-admin')
              <div x-show="!open">
                <x-waiter-queue :order="$order" :table="$table" :menus="$menus" :allMenus="$allMenus"
                  :serveStatus="$serveStatus" />
              </div>
              @endrole

              @role('waiter')
              <x-waiter-queue :order="$order" :table="$table" :menus="$menus" :allMenus="$allMenus"
                :serveStatus="$serveStatus" />
              @endrole

              @endforeach
            </div>
            @else

            @role('master-admin')
            <div x-show="!open" x-cloak>
              <div>No orders in the serving queue.</div>
            </div>
            @endrole

            @role('waiter')
            <div>No orders in the serving queue.</div>
            @endrole

            @endif

            @if ($ordersNotPrepared->isNotEmpty())
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3">
              @foreach ($ordersNotPrepared as $order)

              @php
              $table = $tables->where('id', $order->table_id)->first();
              $menus = $order->menus()->get();
              $serveStatus = 1;
              $prepareStatus = 1;
              @endphp

              @role('master-admin')
              <div x-show="open">
                <x-kitchen-staff-queue :order="$order" :table="$table" :menus="$menus" :allMenus="$allMenus"
                  :prepareStatus="$prepareStatus" />
              </div>
              @endrole

              @role('kitchen-staff')
              <x-kitchen-staff-queue :order="$order" :table="$table" :menus="$menus" :allMenus="$allMenus"
                :prepareStatus="$prepareStatus" />
              @endrole

              @endforeach
            </div>
            @else

            @role('master-admin')
            <div x-show="open" x-cloak>
              <div>No orders in the preparing queue.</div>
            </div>
            @endrole

            @role('kitchen-staff')
            <div>No orders in the preparing queue.</div>
            @endrole

            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection

@push('script')
<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
@endpush