@extends('layouts.app')
@section('title', 'Admin – Users')
@section('content')
<div class="card">
    <h2>Registered users</h2>
    <p>Searchable, sortable datatable. Columns: avatar, name, email, role, status, registered date.</p>

    <form method="GET" action="{{ route('admin.users.index') }}" style="margin-bottom:1rem;">
        <div class="form-group" style="margin-bottom:0.5rem;">
            <label for="search">Search by name or email</label>
            <input id="search" type="text" name="search" value="{{ request('search') }}" placeholder="Search...">
        </div>
        <input type="hidden" name="sort" value="{{ request('sort', 'created_at') }}">
        <input type="hidden" name="dir" value="{{ request('dir', 'desc') }}">
        <button type="submit" class="btn btn-primary">Search</button>
        @if(request('search')) <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Clear</a> @endif
    </form>

    <div style="overflow-x:auto;">
        <table>
            <thead>
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
                                <img src="{{ asset('storage/' . $u->profile_photo) }}" alt="" class="avatar">
                            @else
                                <span class="avatar" style="background:#ddd;display:inline-flex;align-items:center;justify-content:center;">—</span>
                            @endif
                        </td>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->role->name ?? '—' }}</td>
                        <td>
                            <span class="badge {{ $u->userStatus->name === 'active' ? 'badge-active' : 'badge-inactive' }}">
                                {{ $u->userStatus->name ?? '—' }}
                            </span>
                        </td>
                        <td>{{ $u->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            @if($u->id === auth()->id())
                                <em>Current user (cannot change self)</em>
                            @else
                                <form action="{{ route('admin.users.update-status', $u) }}" method="POST" style="display:inline-block; margin-right:0.5rem;">
                                    @csrf
                                    <select name="user_status_id" onchange="this.form.submit()">
                                        @foreach($userStatuses as $us)
                                            <option value="{{ $us->id }}" {{ $u->user_status_id == $us->id ? 'selected' : '' }}>{{ $us->name }}</option>
                                        @endforeach
                                    </select>
                                </form>
                                <form action="{{ route('admin.users.update-role', $u) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <select name="role_id" onchange="this.form.submit()">
                                        @foreach($roles as $r)
                                            <option value="{{ $r->id }}" {{ $u->role_id == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $users->withQueryString()->links() }}
    </div>
</div>
@endsection
