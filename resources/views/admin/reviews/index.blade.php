@extends('layouts.base')
@section('title', 'Product Reviews')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="font-serif" style="color: var(--pastry-brown);">Product Reviews</h2>
        </div>

        <!-- Search and Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.reviews.index') }}">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Search by product name, reviewer, or comment..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="sort" class="form-select">
                                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date</option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating</option>
                                <option value="product.name" {{ request('sort') == 'product.name' ? 'selected' : '' }}>Product</option>
                                <option value="user.name" {{ request('sort') == 'user.name' ? 'selected' : '' }}>Reviewer</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="direction" class="form-select">
                                <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>Descending</option>
                                <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Ascending</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-pastry">Search</button>
                            <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary">Clear</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Reviews Table -->
        <div class="card">
            <div class="card-body">
                @if($reviews->isEmpty())
                    <p class="text-muted text-center py-4">No reviews found.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Reviewer</th>
                                    <th>Rating</th>
                                    <th>Comment</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reviews as $review)
                                    <tr>
                                        <td>
                                            <strong>{{ $review->product->name }}</strong>
                                            <br>
                                            <small class="text-muted">ID: {{ $review->product->id }}</small>
                                        </td>
                                        <td>
                                            {{ $review->user->name }}
                                            <br>
                                            <small class="text-muted">{{ $review->user->email }}</small>
                                        </td>
                                        <td>
                                            <span class="text-warning">{{ $review->formatted_rating }}</span>
                                            <br>
                                            <small class="text-muted">{{ $review->rating }}/5</small>
                                        </td>
                                        <td>
                                            @if($review->comment)
                                                <span class="text-truncate d-block" style="max-width: 200px;">
                                                    {{ Str::limit($review->comment, 100) }}
                                                </span>
                                            @else
                                                <span class="text-muted">No comment</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $review->created_at->format('M j, Y') }}
                                            <br>
                                            <small class="text-muted">{{ $review->created_at->format('g:i A') }}</small>
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $reviews->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">{{ $reviews->total() }}</h5>
                        <p class="card-text text-muted">Total Reviews</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">{{ \App\Models\Review::avg('rating') ? number_format(\App\Models\Review::avg('rating'), 1) : '0.0' }}</h5>
                        <p class="card-text text-muted">Average Rating</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">{{ \App\Models\Review::where('rating', 5)->count() }}</h5>
                        <p class="card-text text-muted">5-Star Reviews</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">{{ \App\Models\Review::distinct('product_id')->count() }}</h5>
                        <p class="card-text text-muted">Products Reviewed</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
