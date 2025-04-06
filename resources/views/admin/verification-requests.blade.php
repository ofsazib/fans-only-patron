@extends('admin.base')

@section('section_title')
	<strong>Profile Verification Requests</strong>
@endsection

@section('section_body')

@if($vreq)
	<table class="table table-striped table-bordered table-responsive dataTable">
	<thead>
	<tr>
		<th>ID</th>
		<th>Email</th>
		<th>Name</th>
		<th>Location</th>
		<th>Photo</th>
		<th>Status</th>
		<th>Actions</th>
	</tr>
	</thead>
	<tbody>
		@foreach( $vreq as $v )
		<tr>
			<td>
				{{ $v->id }}
			</td>
			<td>
				{{ $v->user->email }}
			</td>
			<td>
				{{ $v->user->name }}<br>
			</td>
			<td>
				@if($v->user_meta)

					@isset($v->user_meta['address'])
						{{ $v->user_meta['address'] }}<br>
					@endisset

					@isset($v->user_meta['city'])
						{{ $v->user_meta['city'] }}, 
					@endisset
					@isset($v->user_meta['country'])
						{{ $v->user_meta['country'] }}<br>
					@endisset

				@else
					--
				@endif
			</td>
			<td>
				@if($v->user_meta)
					@isset($v->user_meta['id'])
						@if(isset($v->user_meta['verificationDisk']))
							@if($v->user_meta['verificationDisk'] == 'backblaze')
								<a href="{{ 'https://' . opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $v->user_meta['id'] }}" target="_blank">
									<img src="{{ 'https://' . opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $v->user_meta['id'] }}" width="100" class="img-responsive"/>
								</a>
							@else
								<a href="{{ \Storage::disk($v->user_meta['verificationDisk'])->url($v->user_meta['id']) }}" target="_blank">
									<img src="{{ \Storage::disk($v->user_meta['verificationDisk'])->url($v->user_meta['id']) }}" width="100" class="img-responsive"/>
								</a>
							@endif
						@else
						<a href="{{ asset('public/uploads/' . $v->user_meta['id']) }}" target="_blank">
							<img src="{{ asset('public/uploads/' . $v->user_meta['id']) }}" width="100" class="img-responsive"/>
						</a>
						@endif
					@else
						No ID Uploaded
					@endif
				@else
					--
				@endif
			</td>
			<td>
				@if($v->isVerified == 'Rejected')
					<span class="text-danger"><strong>{{ $v->isVerified }}</strong></span>
				@else
					<span class="text-info"><strong>{{ $v->isVerified }}</strong></span>
				@endif
			</td>
			<td>
				 <div class="btn-group">
    				<a href="/admin/approve/{{ $v->id }}" class="text-success">
						<strong>Approve</strong>
					</a><br>
					<a href="/admin/reject/{{ $v->id }}" class="text-danger" onclick="return confirm('are you sure?')">
						Reject
					</a>
				</div>
			</td>
		</tr>
		@endforeach
	</tbody>
	</table>
@else
	No verification requests in database.
@endif

@endsection