<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                LeBook
            </a>
        </div>

        @if($signedIn)

            <form class="navbar-form navbar-nav" role="search">
                <div class="form-group">
                    <typeahead
                            async="{{ url('/api/users') }}?term="
                            placeholder="Search users"
                            key="data"
                            template-name="async"
                            :template="asyncTemplate"
                            :on-hit="selectUser">
                    </typeahead>
                    {{--<input type="text" class="form-control" placeholder="Search">--}}
                </div>
                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
            </form>

        @endif

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                @include('nav.left')
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                @include('nav.right')
            </ul>
        </div>
    </div>
</nav>