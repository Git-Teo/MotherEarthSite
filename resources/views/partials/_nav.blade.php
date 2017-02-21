<!-- Navigation Bar -->
<nav class="navbar navbar-default">
  <div class="container-fluid" >
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="{{ Request::is('/') ? "#" : "/"}}">Mother Earth</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" >
      <ul class="nav navbar-nav">
        <li class="{{ Request::is('/') ? "active" : ""}}"><a href="{{ Request::is('/') ? "#" : "/"}}">Home</a></li>
        <li class="{{ Request::is('store') ? "active" : ""}}"><a href="{{ Request::is('store') ? "#" : "/store"}}">Store</a></li><li>
        <li class="{{ Request::is('about') ? "active" : ""}}"><a href="{{ Request::is('about') ? "#" : "/about"}}">About Us</a></li><li>
        <li class="{{ Request::is('contacts') ? "active" : ""}}"><a href="{{ Request::is('contacts') ? "#" : "/contacts"}}">Contacts</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="{{ Request::is('basket') ? "active" : ""}}"><a href="basket"><span class="glyphicon glyphicon-shopping-cart"></span> Basket</a></li>

        @if (Auth::guest())
            <li><a href="{{ url('/login') }}">Login</a></li>
            <li><a href="{{ url('/register') }}">Register</a></li>
        @else
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                    {{ Auth::user()->name }} <span class="caret"></span>
                </a>

                <ul class="dropdown-menu" role="menu">
                    <li>
                        <a href="{{ url('/logout') }}"
                            onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                            Logout
                        </a>

                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            </li>
        @endif
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
