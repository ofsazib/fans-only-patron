@extends('admin.base')

@section('section_title')
	<strong>Payment Requests (Payouts)</strong>
@endsection

@section('section_body')

<div class="alert alert-secondary">
    When you mark a payment request as paid, you have to actually pay the user first manually to their bank account or paypal. This does NOT happen automatically.
</div>

@if($reqs)
	<table class="table table-striped table-bordered table-responsive dataTable">
	<thead>
	<tr>
		<th>Email</th>
		<th>Name</th>
		<th>Profile</th>
        <th>Amount</th>
        <th>Payout Gateway</th>
        <th>Payout Details</th>
		<th>Date</th>
		<th>Actions</th>
	</tr>
	</thead>
	<tbody>
		@foreach( $reqs as $v )
		<tr>
			<td>
				{{ $v->user->email }}
			</td>
			<td>
				{{ $v->user->name }}<br>
			</td>
			<td>
                <a href="{{ route('profile.show', ['username' => $v->user->profile->username]) }}" target="_blank">
                    {{ $v->user->profile->handle }}
                </a>
			</td>
			<td>
				{{ opt('payment-settings.currency_symbol') . $v->amount }}
			</td>
			<td>
				{{ $v->user->profile->payout_gateway }}
            </td>
            <td>
                {{-- please note, escaped using "e()" laravel function --}}
				{!! nl2br(e($v->user->profile->payout_details)) !!}
			</td>
			<td>
                 {{ $v->created_at->format('jS F Y') }}<br>
                 {{ $v->created_at->format('H:ia') }}
            </td>
            <td>
                <a href="/admin/payment-requests/markAsPaid/{{ $v->id }}">
                    Mark as Paid
                </a>
            </td>
		</tr>
		@endforeach
	</tbody>
	</table>
@else
	No verification requests in database.
@endif

@endsection