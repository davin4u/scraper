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
                    <a class="{{ Request::is('categories') ? 'active' : '' }} nav-link" href="{{ route('categories.index') }}">Product categories</a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::is('brands') ? 'active' : '' }} nav-link" href="{{ route('brands.index') }}">Product brands</a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::is('products') ? 'active' : '' }} nav-link" href="{{ route('products.index') }}">Products</a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::is('scraper/categories') ? 'active' : '' }} nav-link" href="{{ route('scraper.categories.index') }}">Scraping categories</a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::is('scraper-jobs') ? 'active' : '' }} nav-link" href="{{ route('scraper-jobs.index') }}">Scraping jobs</a>
                </li>
            </ul>
        </div>
    </nav>
</div>
