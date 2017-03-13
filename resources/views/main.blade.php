<!DOCTYPE html>
<html lang="en">
  <head>

    @include('partials._head')
    @include('partials._javascript')

    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
  </head>
  <body>

    @include('partials._nav')

    <div class="container-fluid">

      @yield('content')

    </div>

    @yield('scripts')

  </body>
</html>
