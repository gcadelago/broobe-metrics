@extends('layouts.app')

@section('title', 'Home')

@section('content')
  <div class="container">
      <div class="card-header">
        @include('metrics.partials.nav-tabs')
      </div>
      <div class="card-body px-2 py-4">
        <div class="tab-content" id="tab-content">
            @include('metrics.run-metrics')
            @include('metrics.metric-history')
        </div>
      </div>
  </div>
@endsection
