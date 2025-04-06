@extends( 'account' )

@section('seo_title') @lang('dashboard.creatorSetup') - @endsection


@section( 'account_section' )

<div>



	<form method="POST" action="{{ route( 'saveMembershipFee' ) }}">
		@csrf
		<div class="shadow-sm card add-padding">
			<br />
			<h2 class="ml-2 mb-3"><i class="fas fa-comment-dollar mr-2"></i>@lang('dashboard.creatorSetup')</h2>
			<hr>


			<strong><i class="fas fa-info-circle mr-1"></i>@lang( 'v23.customFee' )</strong>
			<span
				style="background-color: purple; color: white; font-weight: bold; display: inline; width: 50px; text-align: center; border-radius: 16px; font-size: 14px;">
				{{ auth()->user()->userFee }}%
			</span>
			<hr />


			<label><strong>@lang( 'profile.feeAmount' )</strong></label>
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon3">{{ opt( 'payment-settings.currency_symbol'
								) }}</span>
						</div>
						<input type="text" name="monthlyFee" class="form-control" value="{{ $p->monthlyFee ?: '' }}">
						<div class="input-group-append">
							<span class="input-group-text" id="basic-addon3">@lang('profile.perMonth')</span>
						</div>
					</div>
				</div>
			</div>
			<br>

			<label><strong>@lang( 'profile.minTipAmount' )</strong></label>
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon3">{{ opt( 'payment-settings.currency_symbol'
								) }}</span>
						</div>
						<input type="text" name="minTipAmount" class="form-control" value="{{ $p->minTip ?: '' }}">
						<div class="input-group-append">
							<span class="input-group-text" id="basic-addon3">@lang('profile.perUnlock')</span>
						</div>
					</div>
				</div>
			</div>
			<br>

			<label><strong>@lang( 'profile.payoutMethod' )</strong></label>
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<select name="payout_gateway" class="form-control">
						<option value="None" @if(isset($p) AND $p->payout_gateway == 'None' ) selected
							@endif>@lang('profile.None')</option>
						<option value="PayPal" @if(isset($p) AND $p->payout_gateway == 'PayPal' ) selected @endif>PayPal
						</option>
						<option value="Bank Transfer" @if(isset($p) AND $p->payout_gateway == 'Bank Transfer' ) selected
							@endif>@lang('profile.bankTransfer')</option>

						@if(opt('card_gateway', 'Stripe') == 'Crypto')
						<option value="Crypto" @if(isset($p) AND $p->payout_gateway == 'Crypto' ) selected
							@endif>@lang('v192.crypto')</option>
						@endif

					</select>
				</div>
			</div>
			<br>

			<label><strong>@lang( 'profile.paypalEmail' ) <small>@lang('profile.ifPayPal')</small></strong></label>
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<input type="email" class="form-control" name="paypal_email"
						value="@if(isset($p) AND $p->payout_gateway == 'PayPal') {{ $p->payout_details }} @endif" />
				</div>
			</div>
			<br>

			<label><strong>@lang( 'profile.bankDetails' ) <small>@lang('profile.ifBank')</small></strong></label>

			@if(opt('card_gateway', 'Stripe') == 'Crypto')
			<label><strong>@lang( 'v192.transferDetails' ) <small>@lang('v192.ifTransfer')</small></strong></label>
			@endif


			<div class="row">
				<div class="col-xs-12 col-sm-10">
					<textarea class="form-control" name="bank_details"
						rows="5">@if(isset($p) AND $p->payout_gateway == 'Bank Transfer') {{ $p->payout_details }} @endif</textarea>
				</div>
			</div>

		</div><!-- /.white-bg -->
		<br>

		<div class="text-center">
			<input type="submit" name="sbStoreProfile" class="btn btn-lg btn-primary"
				value="@lang('profile.savePayoutDetails')">
		</div><!-- /.white-bg add-padding -->

	</form>
	<br /><br />
</div><!-- /.white-smoke-bg -->
@endsection