{{-- Styling Example
    .sidebar-favorite .panel-heading { background-color: #3097D1; color: #ffffff; }
    .sidebar-favorite .panel-heading:hover { background-color: #ffffff; color: #3097D1; }
    .sidebar-favorite a:hover { text-decoration: none; }
--}}

{{-- FAVORITE PANEL --}}
@auth
    <div class="panel panel-primary sidebar-favorite">
        <a href="{{ route('posts.index', ['favorite' => true]) }}">
            <div class="panel-heading">
                    <span class="glyphicon glyphicon-star"></span>
                    <strong>Favorite</strong>
            </div>
        </a>
    </div>
@endauth
