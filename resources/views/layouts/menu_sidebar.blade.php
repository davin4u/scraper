<div class="container p-0 col-md-3 mb-4">
    <nav class="sidebar-sticky navbar navbar-light bg-white navbar-expand-md pl-4 shadow-sm">
        <button class="navbar-toggler" type="button"
                data-toggle="collapse"
                data-target="#navbarsExampleDefault"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto flex-column">
                <li class="nav-item">
                    <a class="{{ Request::is('categories*') ? 'active' : '' }} nav-link" href="{{ route('categories.index') }}">Product categories</a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::is('brands*') ? 'active' : '' }} nav-link" href="{{ route('brands.index') }}">Product brands</a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::is('products*') ? 'active' : '' }} nav-link" href="{{ route('products.index') }}">Products</a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::is('scraper/categories*') ? 'active' : '' }} nav-link" href="{{ route('scraper.categories.index') }}">Scraping categories</a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::is('scraper-jobs*') ? 'active' : '' }} nav-link" href="{{ route('scraper-jobs.index') }}">Scraping jobs</a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::is('search-statistics*') ? 'active' : '' }} nav-link" href="{{ route('search-statistics.index') }}">Search statistics</a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::is('users') ? 'active' : '' }} nav-link" href="{{ route('users.index') }}">Users</a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::is('reviews') ? 'active' : '' }} nav-link" href="{{ route('products.reviews.index') }}">Reviews</a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::is('overviews') ? 'active' : '' }} nav-link" href="{{ route('products.overviews.index') }}">Overviews</a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::is('authors') ? 'active' : '' }} nav-link" href="{{ route('authors.index') }}">Authors</a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::is('domains') ? 'active' : '' }} nav-link" href="{{ route('domains.index') }}">Domains</a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::is('matching-tool') ? 'active' : '' }} nav-link" href="{{ route('matching.index') }}">Matching Tool</a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::is('yml-import') ? 'active' : '' }} nav-link" href="{{ route('yml-import.index') }}">Yml import</a>
                </li>
            </ul>
        </div>
    </nav>
</div>
