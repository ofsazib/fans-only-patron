@extends('admin.base')

@section('section_title')
<strong>Users Management</strong>
@endsection

@section('section_body')

<table class="table dataTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Followers</th>
            <th>Fans</th>
            <th>Type</th>
            <th>Is Admin</th>
            <th>IP Address</th>
            <th>Join Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach( $users as $u )
        <tr>
            <td>{{ $u->id }}</td>
            <td>{{ $u->name }}</td>
            <td>{{ $u->email }}</td>
            <td>
                {{ $u->followers_count }}
            </td>
            <td>
                {{ $u->subscribers_count }}
            </td>
            <td>
                @if($u->profile->isVerified == 'Yes')
                Creator
                <br />
                <span
                    class="@if($u->userFee == opt('payment-settings.site_fee')) text-success @else text-warning @endif">Fee
                    @if($u->userFee == opt('payment-settings.site_fee'))
                    (Default)
                    @else
                    (Custom)
                    @endif
                    : {{ $u->userFee }}%
                </span>
                <br />
                <a href="/admin/users/custom-fee/{{ $u->id }}">Adjust Fee</a>
                @else
                User
                @endif
            </td>
            <td>
                {{ $u->isAdmin }}
                <br>
                @if($u->isAdmin == 'Yes')
                <a href="/admin/users/unsetadmin/{{ $u->id }}">Unset Admin Role</a>
                @elseif($u->isAdmin == 'No')
                <a href="/admin/users/setadmin/{{ $u->id }}">Set Admin Role</a>
                @endif
            </td>
            <td>
                {{ $u->ip ? $u->ip : 'N/A' }}
                <br>

                @if($u->isBanned == 'No')
                <a href="/admin/users/ban/{{ $u->id }}">
                    Ban
                </a>
                @elseif($u->isBanned == 'Yes')
                <a href="/admin/users/unban/{{ $u->id }}">
                    Unban
                </a>
                @endif
            </td>
            <td>{{ $u->created_at->format( 'jS F Y' ) }}</td>
            <td>
                <a href="/admin/add-plan/{{ $u->id}}">Add Plan Manually</a><br>

                <a href="/admin/loginAs/{{ $u->id }}"
                    onclick="return confirm('This will log you out as an admin and login as a vendor. Continue?')">Login
                    as User</a>

                <br>
                <br>
                <a href="/admin/users?remove={{ $u->id }}"
                    onclick="return confirm('Are you sure you want to delete this user and his data? This is irreversible!!!')"
                    class="text-danger">Delete User & His Data</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('extra_bottom')
@if (count($errors) > 0)
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@endsection