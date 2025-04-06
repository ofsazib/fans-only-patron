@extends('admin.base')

@section('section_title')
<strong>Transactions Overview</strong>
@endsection

@section('section_body')
	
	<table class="table dataTable">
    <thead>
    <tr>
        <th>ID</th>
        <th>Subscriber</th>
        <th>Creator</th>
        <th>Amount</th>
        <th>Status</th>
        <th>Gateway</th>
        <th>Date</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @foreach( $tx as $t )
    @if(is_null($t->subscription->creator) OR is_null($t->subscription->subscriber))

    @else
        <tr>
            <td>{{ $t->id }}</td>
            <td>
                {{ $t->subscription->subscriber->name }}
                <br>
                <a href="/{{ $t->subscription->subscriber->profile->username }}">
                    {{ $t->subscription->subscriber->profile->handle }}
                </a>
            </td>
            <td>
                {{ $t->subscription->creator->name }}
                <br>
                <a href="/{{ $t->subscription->creator->profile->username }}">
                    {{ $t->subscription->creator->profile->handle }}
                </a>
            </td>
            <td>
                {{ opt('payment-settings.currency_symbol') . $t->amount }}
            </td>
            <td>
                {{ $t->payment_status }}
            </td>
            <td>
                {{ $t->subscription->gateway }}
            </td>
            <td>{{ $t->created_at->format( 'jS F Y' ) }}</td>
            <td>
                <a href="{{ $t->invoice_url }}" target="_blank">View Invoice</a>
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