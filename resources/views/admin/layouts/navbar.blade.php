<header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
    <!-- Page navbar -->
        <div class="navbar">
            <div class="container-xl">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}" >
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                <svg xmlns="{{asset('admin-assets/http://www.w3.org/2000/svg')}}" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                            </span>
                            <span class="nav-link-title">
                                Home
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('categories.index') }}" >
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                
                            </span>
                            <span class="nav-link-title">
                                Categories
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('sub-categories.index')}}" >
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                
                            </span>
                            <span class="nav-link-title">
                                Sub-Categories
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('brands.index')}}" >
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                
                            </span>
                            <span class="nav-link-title">
                                Brands
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('products.index')}}" >
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                
                            </span>
                            <span class="nav-link-title">
                                Products
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('shipping.index') }}" >
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                
                            </span>
                            <span class="nav-link-title">
                                Shipping
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('coupons.index') }}" >
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                
                            </span>
                            <span class="nav-link-title">
                                Coupons
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('orders.index') }}" >
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                
                            </span>
                            <span class="nav-link-title">
                                Orders
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('users.index') }}" >
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                
                            </span>
                            <span class="nav-link-title">
                                Users
                            </span>
                        </a>
                    </li>
                    
                    {{-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/package -->
                                <svg xmlns="{{asset('admin-assets/http://www.w3.org/2000/svg')}}" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12l0 9" /><path d="M12 12l-8 -4.5" /><path d="M16 5.25l-8 4.5" /></svg>
                            </span>
                            <span class="nav-link-title">
                                Interface
                            </span>
                        </a> --}}
                        <div class="dropdown-menu">
                            <div class="dropdown-menu-columns">
                                <div class="dropdown-menu-column">
                                    <a class="dropdown-item" href="{{ route('categories.index') }}">
                                        Categories
                                    </a>
                                    <a class="dropdown-item" href="{{route('sub-categories.index')}}">
                                        SubCategories
                                    </a>
                                    <a class="dropdown-item" href="{{route('brands.index')}}">
                                        Brands
                                    </a>
                                    <a class="dropdown-item" href="{{route('products.index')}}">
                                        Products
                                    </a>
                                </div>
                            </div>
                        </div>
                    {{-- </li> --}}
                </ul>
            </div>
        </div>
    </div>
</header>