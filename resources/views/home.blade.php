@extends('layouts.app')

@section('title', 'Home')

@section('content')
  <div class="container">
    <section class="card">
      <div class="card-header">
        @include('layouts.nav-tabs')
      </div>
      <div class="card-body">
        <div class="tab-content" id="tab-content">
            @include('metrics.run-metrics')
            @include('metrics.metric-history')
        </div>
      </div>
    </section>
  </div>
@endsection
