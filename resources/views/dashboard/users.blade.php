@extends('layouts.base')
@section('title', 'Admin – Users')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-5">
        <div class="card card-pastry">
            <div class="card-body p-4">
                <p class="text-uppercase mb-1" style="letter-spacing: 0.15em; color: var(--pastry-caramel); font-size: 0.85rem;">Admin</p>
                <h2 class="font-serif mb-3" style="color: var(--pastry-brown);">Registered users</h2>
                <p class="text-muted small">Searchable, sortable table: avatar, name, email, role, status, registered date.</p>

                <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 mb-4">
                    <div class="col-auto">
                        <label for="search" class="form-label visually-hidden">Search</label>
                        <input id="search" type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by name or email">
                    </div>
                    <input type="hidden" name="sort" value="{{ request('sort', 'created_at') }}">
                    <input type="hidden" name="dir" value="{{ request('dir', 'desc') }}">
                    <div class="col-auto">
                        <button type="submit" class="btn btn-pastry">Search</button>
                        @if(request('search')) <a href="{{ route('admin.users.index') }}" class="btn btn-outline-pastry">Clear</a> @endif
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead style="background: var(--pastry-cream); color: var(--pastry-brown);">
                            <tr>
                                <th>Avatar</th>
                                <th>
                                    @php $dir = (request('sort') === 'name' && request('dir') === 'asc') ? 'desc' : 'asc'; @endphp
                                    <a href="{{ route('admin.users.index', ['search' => request('search'), 'sort' => 'name', 'dir' => $dir]) }}" class="text-decoration-none" style="color: inherit;">Name</a>
                                </th>
                                <th>
                                    @php $dir = (request('sort') === 'email' && request('dir') === 'asc') ? 'desc' : 'asc'; @endphp
                                    <a href="{{ route('admin.users.index', ['search' => request('search'), 'sort' => 'email', 'dir' => $dir]) }}" class="text-decoration-none" style="color: inherit;">Email</a>
                                </th>
                                <th>Admin Status</th>
                                <th>Status</th>
                                <th>
                                    @php $dir = (request('sort') === 'created_at' && request('dir') === 'asc') ? 'desc' : 'asc'; @endphp
                                    <a href="{{ route('admin.users.index', ['search' => request('search'), 'sort' => 'created_at', 'dir' => $dir]) }}" class="text-decoration-none" style="color: inherit;">Registered</a>
                                </th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $u)
                                <tr>
                                    <td>
                                        @if($u->profile_photo)
                                            <img src="{{ asset('storage/' . $u->profile_photo) }}" alt="" class="rounded-circle" width="40" height="40" style="object-fit:cover;">
                                        @else
                                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-secondary text-white" style="width:40px;height:40px; font-size: 0.75rem;">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $u->name }}</td>
                                    <td>{{ $u->email }}</td>
                                    <td>
                                        <span class="badge {{ $u->is_admin ? 'bg-danger' : 'bg-primary' }}">
                                            {{ $u->is_admin ? 'Admin' : 'Customer' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $u->userStatus->name === 'active' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $u->userStatus->name ?? '—' }}
                                        </span>
                                    </td>
                                    <td>{{ $u->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        @if($u->id === auth()->id())
                                            <em class="text-muted small">Current user (cannot change self)</em>
                                        @else
                                            <form action="{{ route('admin.users.update-status', $u) }}" method="POST" class="d-inline-block me-1">
                                                @csrf
                                                <select name="user_status_id" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                                    @foreach($userStatuses as $us)
                                                        <option value="{{ $us->id }}" {{ $u->user_status_id == $us->id ? 'selected' : '' }}>{{ $us->name }}</option>
                                                    @endforeach
                                                </select>
                                            </form>
                                            <form action="{{ route('admin.users.update-role', $u) }}" method="POST" class="d-inline-block">
                                                @csrf
                                                <select name="is_admin" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                                    <option value="0" {{ !$u->is_admin ? 'selected' : '' }}>Customer</option>
                                                    <option value="1" {{ $u->is_admin ? 'selected' : '' }}>Admin</option>
                                                </select>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted py-4">No users found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $users->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
