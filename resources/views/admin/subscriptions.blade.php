@extends('admin.base')

@section('section_title')
<strong>Subscriptions Overview</strong>
@endsection

@section('section_body')
	
	<table class="table dataTable">
    <thead>
    <tr>
        <th>ID</th>
        <th>Subscriber</th>
        <th>Creator</th>
        <th>Started</th>
        <th>Expires</th>
        <th>Creator Earnings</th>
        <th>Admin Earnings</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach( $subscriptions as $t )
    @if(is_null($t->creator) OR is_null($t->subscriber))

    @else
        <tr>
            <td>{{ $t->id }}</td>
            <td>
                {{ $t->subscriber->name }}
                <br>
                <a href="/{{ $t->subscriber->profile->username }}">
                    {{ $t->subscriber->profile->handle }}
                </a>
            </td>
            <td>
                {{ $t->creator->name }}
                <br>
                <a href="/{{ $t->creator->profile->username }}">
                    {{ $t->creator->profile->handle }}
                </a>
            </td>
            <td>{{ $t->subscription_date->format( 'jS F Y' ) }}</td>
            <td>
                {{ $t->subscription_expires->format( 'jS F Y' ) }}
                @if($t->gateway == 'Admin')
                <br>
                <small>
                    Created manually by admin, <a href="/admin/subscriptions?delete={{ $t->id }}" class="text-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </small>
                @endif
            </td>
            <td>
                {{ opt('payment-settings.currency_symbol') . $t->creator_amount }}
            </td>
            <td>
                {{ opt('payment-settings.currency_symbol') . $t->admin_amount }}
            </td>
            <td>
                {{ opt('payment-settings.currency_symbol') . $t->subscription_price }}
            </td>
        </tr>
        @endif
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