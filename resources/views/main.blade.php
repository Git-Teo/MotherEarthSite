<!DOCTYPE html>
<html lang="en">
  <head>

    @include('partials._head')
    @include('partials._javascript')

  </head>
  <body>

    @include('partials._nav')

    <div class="container-fluid">

      @yield('content')

    </div>

    @yield('scripts')

  </body>
</html>
