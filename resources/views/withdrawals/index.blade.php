@extends( 'account' )

@section('seo_title') @lang('dashboard.withdrawal') - @endsection

@section( 'account_section' )

<div class="shadow-sm card add-padding">
    <h2 class="ml-2">
        <i class="fas fa-coins mr-2 pt-1"></i> @lang('dashboard.withdrawal')
    </h2>
	<hr>

	@livewire( 'withdrawals' )

	<br>

</div>

@endsection

{{-- attention, this is dynamically appended using stack() and push() functions of BLADE --}}
@push('extraJS')
<script>
    // listen to livewire growl messages
    window.livewire.on('request-amount', function (response) {
        $.growl({ title: response.amount, message: '' });
    });
</script>
@endpush