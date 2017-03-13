@extends('main')

@section('title', 'Mother Earth | Homepage')

@section('stylesheets')

  <link rel='stylesheet' type='text/css' href='css/parsley.css' media='all' />
  <link rel='stylesheet' type='text/css' href='css/shop.css' media='all' />

@endsection

@section('content')

    @include('partials._shop')

@endsection

@section('scripts')

  <script src="js/parsley.min.js"></script>
  <script src="js/formvalidation.js"></script>

@endsection
