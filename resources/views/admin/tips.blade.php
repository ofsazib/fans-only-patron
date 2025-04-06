@extends('admin.base')

@section('section_title')
<strong>Tips Overview</strong>
@endsection

@section('section_body')
	
	<table class="table dataTable">
    <thead>
    <tr>
        <th>ID</th>
        <th>Tipper</th>
        <th>Creator</th>
        <th>Date</th>
        <th>Creator Earnings</th>
        <th>Admin Earnings</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach( $tips as $t )
    @if(is_null($t->tipper) OR is_null($t->tipped))

    @else
        <tr>
            <td>{{ $t->id }}</td>
            <td>
                {{ $t->tipper->name }}
                <br>
                <a href="/{{ $t->tipper->profile->username }}">
                    {{ $t->tipper->profile->handle }}
                </a>
            </td>
            <td>
                {{ $t->tipped->name }}
                <br>
                <a href="/{{ $t->tipped->profile->username }}">
                    {{ $t->tipped->profile->handle }}
                </a>
            </td>
            <td>{{ $t->created_at->format( 'jS F Y' ) }}</td>
            <td>
                {{ opt('payment-settings.currency_symbol') . $t->creator_amount }}
            </td>
            <td>
                {{ opt('payment-settings.currency_symbol') . $t->admin_amount }}
            </td>
            <td>
                {{ opt('payment-settings.currency_symbol') . $t->amount }}
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