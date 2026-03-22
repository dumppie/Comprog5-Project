@extends('layouts.base')
@section('title', 'Shop')
@push('styles')
<style>
.filter-section {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}
.filter-badge {
    background: var(--pastry-caramel);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    margin-right: 0.5rem;
    display: inline-flex;
    align-items: center;
}
.filter-badge .remove-filter {
    margin-left: 0.5rem;
    cursor: pointer;
    opacity: 0.8;
}
.filter-badge .remove-filter:hover {
    opacity: 1;
}
.price-range-container {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.price-range-container input {
    width: 100px;
}
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255,255,255,0.8);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}
</style>
@endpush

@section('body')
    @include('layouts.flash-messages')
    
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="text-uppercase mb-1" style="letter-spacing: 0.15em; color: var(--pastry-caramel); font-size: 0.85rem;">Shop</p>
                <h2 class="font-serif" style="color: var(--pastry-brown);">Our pastries</h2>
            </div>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">← Back to Home</a>
        </div>

        <!-- Search Section -->
        <div class="row mb-4">
            <div class="col-lg-8 mx-auto">
                <form method="GET" action="{{ route('shop.index') }}" id="searchForm">
                    <div class="input-group input-group-lg">
                        <input type="text" name="search" class="form-control" placeholder="Search for pastries..." value="{{ $searchTerm ?? '' }}">
                        <button type="submit" class="btn btn-pastry">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="filter-section">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Price Range</label>
                    <div class="price-range-container">
                        <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min" value="{{ $minPrice ?? '' }}" min="0" step="0.01">
                        <span>-</span>
                        <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Max" value="{{ $maxPrice ?? '' }}" min="0" step="0.01">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Category</label>
                    <select name="category" class="form-select form-select-sm">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ ($category ?? '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="button" id="applyFilters" class="btn btn-pastry btn-sm w-100">Apply Filters</button>
                </div>
            </div>
            
            <!-- Active Filters Display -->
            @if($searchTerm || $minPrice || $maxPrice || $category)
                <div class="mt-3">
                    <small class="text-muted fw-bold">Active Filters:</small>
                    <div class="mt-2">
                        @if($searchTerm)
                            <span class="filter-badge">
                                Search: {{ $searchTerm }}
                                <span class="remove-filter" data-filter="search">×</span>
                            </span>
                        @endif
                        @if($minPrice || $maxPrice)
                            <span class="filter-badge">
                                Price: ₱{{ $minPrice ?? '0' }} - ₱{{ $maxPrice ?? '∞' }}
                                <span class="remove-filter" data-filter="price">×</span>
                            </span>
                        @endif
                        @if($category)
                            <span class="filter-badge">
                                Category: {{ $category }}
                                <span class="remove-filter" data-filter="category">×</span>
                            </span>
                        @endif
                        <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary btn-sm ms-2">Clear All</a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Products Section -->
        <div id="productsContainer">
            @if($products->isEmpty())
                <div class="text-center py-5">
                    <p class="text-muted">No products found matching your criteria.</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-outline-pastry">View All Products</a>
                </div>
            @else
                <div class="row g-4 mb-5">
                    @foreach($products as $product)
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card card-pastry h-100">
                                @if($product->thumbnail_photo)
                                    <a href="{{ route('products.show', $product) }}" class="d-block">
                                        <img src="{{ asset('storage/' . $product->thumbnail_photo) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 180px; object-fit: cover;">
                                    </a>
                                @else
                                    <a href="{{ route('products.show', $product) }}" class="d-block card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 180px; color: var(--pastry-caramel); text-decoration: none;">
                                        <i class="fas fa-bread-slice fa-3x"></i>
                                    </a>
                                @endif
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title font-serif" style="color: var(--pastry-brown);"><a href="{{ route('products.show', $product) }}" class="text-decoration-none text-reset">{{ $product->name }}</a></h5>
                                    <p class="card-text text-muted small flex-grow-1">{{ Str::limit($product->description, 60) }}</p>
                                    <p class="mb-2"><strong>{{ $product->formatted_price ?? '₱' . number_format($product->price, 2) }}</strong></p>
                                    @auth
                                    @if (!Auth::user()->isAdmin())
                                        <form action="{{ route('cart.store') }}" method="POST" class="mt-auto">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <div class="input-group input-group-sm">
                                                <input type="number" name="quantity" value="1" min="1" class="form-control" style="max-width: 4rem;">
                                                <button type="submit" class="btn btn-pastry">Add to cart</button>
                                            </div>
                                        </form>
                                    @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-outline-pastry btn-sm">Sign in to add to cart</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadingOverlay = document.getElementById('loadingOverlay');
    const searchForm = document.getElementById('searchForm');
    const applyFiltersBtn = document.getElementById('applyFilters');
    const removeFilterBtns = document.querySelectorAll('.remove-filter');
    
    function showLoading() {
        loadingOverlay.style.display = 'flex';
    }
    
    function hideLoading() {
        loadingOverlay.style.display = 'none';
    }
    
    function buildUrl() {
        const url = new URL(window.location);
        const params = new URLSearchParams(url.search);
        
        // Get search value
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput.value.trim()) {
            params.set('search', searchInput.value.trim());
        } else {
            params.delete('search');
        }
        
        // Get price range values
        const minPriceInput = document.querySelector('input[name="min_price"]');
        const maxPriceInput = document.querySelector('input[name="max_price"]');
        if (minPriceInput.value) {
            params.set('min_price', minPriceInput.value);
        } else {
            params.delete('min_price');
        }
        if (maxPriceInput.value) {
            params.set('max_price', maxPriceInput.value);
        } else {
            params.delete('max_price');
        }
        
        // Get category value
        const categorySelect = document.querySelector('select[name="category"]');
        if (categorySelect.value) {
            params.set('category', categorySelect.value);
        } else {
            params.delete('category');
        }
        
        return url.pathname + (params.toString() ? '?' + params.toString() : '');
    }
    
    function loadProducts() {
        showLoading();
        const url = buildUrl();
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newProductsContainer = doc.getElementById('productsContainer');
            const currentProductsContainer = document.getElementById('productsContainer');
            
            if (newProductsContainer && currentProductsContainer) {
                currentProductsContainer.innerHTML = newProductsContainer.innerHTML;
            }
            
            // Update URL without page reload
            history.pushState({}, '', url);
            hideLoading();
        })
        .catch(error => {
            console.error('Error loading products:', error);
            hideLoading();
        });
    }
    
    // Handle search form submission
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            loadProducts();
        });
    }
    
    // Handle apply filters button
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', loadProducts);
    }
    
    // Handle individual filter removal
    removeFilterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filterType = this.dataset.filter;
            
            switch(filterType) {
                case 'search':
                    document.querySelector('input[name="search"]').value = '';
                    break;
                case 'price':
                    document.querySelector('input[name="min_price"]').value = '';
                    document.querySelector('input[name="max_price"]').value = '';
                    break;
                case 'category':
                    document.querySelector('select[name="category"]').value = '';
                    break;
            }
            
            loadProducts();
        });
    });
    
    // Handle browser back/forward buttons
    window.addEventListener('popstate', function() {
        location.reload();
    });
});
</script>
@endpush
