@extends('layouts.base')
@section('title', 'Admin – Users')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-4">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Registered users</h2>
                <p class="text-muted">Searchable, sortable datatable. Columns: avatar, name, email, role, status, registered date.</p>

                <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 mb-3">
                    <div class="col-auto">
                        <label for="search" class="form-label visually-hidden">Search</label>
                        <input id="search" type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by name or email">
                    </div>
                    <input type="hidden" name="sort" value="{{ request('sort', 'created_at') }}">
                    <input type="hidden" name="dir" value="{{ request('dir', 'desc') }}">
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Search</button>
                        @if(request('search')) <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Clear</a> @endif
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Avatar</th>
                                <th>
                                    @php $dir = (request('sort') === 'name' && request('dir') === 'asc') ? 'desc' : 'asc'; @endphp
                                    <a href="{{ route('admin.users.index', ['search' => request('search'), 'sort' => 'name', 'dir' => $dir]) }}">Name</a>
                                </th>
                                <th>
                                    @php $dir = (request('sort') === 'email' && request('dir') === 'asc') ? 'desc' : 'asc'; @endphp
                                    <a href="{{ route('admin.users.index', ['search' => request('search'), 'sort' => 'email', 'dir' => $dir]) }}">Email</a>
                                </th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>
                                    @php $dir = (request('sort') === 'created_at' && request('dir') === 'asc') ? 'desc' : 'asc'; @endphp
                                    <a href="{{ route('admin.users.index', ['search' => request('search'), 'sort' => 'created_at', 'dir' => $dir]) }}">Registered</a>
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
                                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-secondary text-white" style="width:40px;height:40px;">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $u->name }}</td>
                                    <td>{{ $u->email }}</td>
                                    <td>{{ $u->role->name ?? '—' }}</td>
                                    <td>
                                        <span class="badge {{ $u->userStatus->name === 'active' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $u->userStatus->name ?? '—' }}
                                        </span>
                                    </td>
                                    <td>{{ $u->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        @if($u->id === auth()->id())
                                            <em class="text-muted">Current user (cannot change self)</em>
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
                                                <select name="role_id" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                                    @foreach($roles as $r)
                                                        <option value="{{ $r->id }}" {{ $u->role_id == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center">No users found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $users->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
