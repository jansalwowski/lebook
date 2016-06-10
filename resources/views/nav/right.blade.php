<!-- Authentication Links -->
@if (Auth::guest())
    <li><a href="{{ url('/login') }}">Login</a></li>
    <li><a href="{{ url('/register') }}">Register</a></li>
@else
    <li class="navbar-avatar">
        <a href="{{ url('/'.$user->username) }}">
            <img src="{{ action('ImagesController@avatar', ['user' => $user->username]) }}" alt="">
            <span class="user">
                {{ $user->name }}
            </span>
        </a>
    </li>
    <li>
        <dropdown>
            <button class="btn btn-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                <span class="caret"></span>
            </button>

            <ul name="dropdown-menu" class="dropdown-menu" role="menu">
                <li><a href="{{ url('/profile') }}"><i class="fa fa-btn fa-user"></i>Profile</a></li>
                <li><a href="{{ url('/settings') }}"><i class="fa fa-btn fa-gear"></i>Settings</a></li>
                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
            </ul>
        </dropdown>
    </li>
@endif