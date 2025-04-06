<?php $__env->startSection('seo_title'); ?> <?php echo app('translator')->get('dashboard.creatorSetup'); ?> - <?php $__env->stopSection(); ?>


<?php $__env->startSection( 'account_section' ); ?>

<div>
<form method="POST" action="<?php echo e(route( 'saveMembershipFee' )); ?>">
<?php echo csrf_field(); ?>
<div class="shadow-sm card add-padding">
<br/>
<h2 class="ml-2 mb-3"><i class="fas fa-comment-dollar mr-2"></i><?php echo app('translator')->get('dashboard.creatorSetup'); ?></h2>
<hr>

<label><strong><?php echo app('translator')->get( 'profile.feeAmount' ); ?></strong></label>
<div class="row">
	<div class="col-xs-12 col-sm-6">
		<div class="input-group">
			<div class="input-group-prepend">
    			<span class="input-group-text" id="basic-addon3"><?php echo e(opt( 'payment-settings.currency_symbol' )); ?></span>
  			</div>
			<input type="text" name="monthlyFee" class="form-control" value="<?php echo e($p->monthlyFee ?: ''); ?>">
			<div class="input-group-append">
    			<span class="input-group-text" id="basic-addon3"><?php echo app('translator')->get('profile.perMonth'); ?></span>
  			</div>
		</div>
	</div>
</div>
<br>

<label><strong><?php echo app('translator')->get( 'profile.minTipAmount' ); ?></strong></label>
<div class="row">
	<div class="col-xs-12 col-sm-6">
		<div class="input-group">
			<div class="input-group-prepend">
    			<span class="input-group-text" id="basic-addon3"><?php echo e(opt( 'payment-settings.currency_symbol' )); ?></span>
  			</div>
			<input type="text" name="minTipAmount" class="form-control" value="<?php echo e($p->minTip ?: ''); ?>">
			<div class="input-group-append">
    			<span class="input-group-text" id="basic-addon3"><?php echo app('translator')->get('profile.perUnlock'); ?></span>
  			</div>
		</div>
	</div>
</div>
<br>

<label><strong><?php echo app('translator')->get( 'profile.payoutMethod' ); ?></strong></label>
<div class="row">
	<div class="col-xs-12 col-sm-6">
		<select name="payout_gateway" class="form-control">
			<option value="None" <?php if(isset($p) AND $p->payout_gateway == 'None' ): ?> selected <?php endif; ?>><?php echo app('translator')->get('profile.None'); ?></option>
			<option value="PayPal" <?php if(isset($p) AND $p->payout_gateway == 'PayPal' ): ?> selected <?php endif; ?>>PayPal</option>
			<option value="Bank Transfer" <?php if(isset($p) AND $p->payout_gateway == 'Bank Transfer' ): ?> selected <?php endif; ?>><?php echo app('translator')->get('profile.bankTransfer'); ?></option>

			<?php if(opt('card_gateway', 'Stripe') == 'Crypto'): ?>
				<option value="Crypto" <?php if(isset($p) AND $p->payout_gateway == 'Crypto' ): ?> selected <?php endif; ?>><?php echo app('translator')->get('v192.crypto'); ?></option>
			<?php endif; ?>

		</select>
	</div>
</div>
<br>

<label><strong><?php echo app('translator')->get( 'profile.paypalEmail' ); ?> <small><?php echo app('translator')->get('profile.ifPayPal'); ?></small></strong></label>
<div class="row">
	<div class="col-xs-12 col-sm-6">
		<input type="email" class="form-control" name="paypal_email" value="<?php if(isset($p) AND $p->payout_gateway == 'PayPal'): ?> <?php echo e($p->payout_details); ?> <?php endif; ?>"/>
	</div>
</div>
<br>

	<label><strong><?php echo app('translator')->get( 'profile.bankDetails' ); ?> <small><?php echo app('translator')->get('profile.ifBank'); ?></small></strong></label>

	<?php if(opt('card_gateway', 'Stripe') == 'Crypto'): ?>
		<label><strong><?php echo app('translator')->get( 'v192.transferDetails' ); ?> <small><?php echo app('translator')->get('v192.ifTransfer'); ?></small></strong></label>
	<?php endif; ?>


<div class="row">
	<div class="col-xs-12 col-sm-10">
		<textarea class="form-control" name="bank_details" rows="5"><?php if(isset($p) AND $p->payout_gateway == 'Bank Transfer'): ?> <?php echo e($p->payout_details); ?> <?php endif; ?></textarea>
	</div>
</div>

</div><!-- /.white-bg -->
<br>

<div class="text-center">
  <input type="submit" name="sbStoreProfile" class="btn btn-lg btn-primary" value="<?php echo app('translator')->get('profile.savePayoutDetails'); ?>">
</div><!-- /.white-bg add-padding -->

</form>
<br/><br/>
</div><!-- /.white-smoke-bg -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make( 'account' , \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/crivion/Sites/patrons/resources/views/profile/set-fee.blade.php ENDPATH**/ ?>