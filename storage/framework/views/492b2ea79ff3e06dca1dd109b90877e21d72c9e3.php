<?php if($post->profile->monthlyFee && $post->profile->minTip): ?>

<h4 class="d-inline">
    <a href="<?php if(auth()->check()): ?> javascript:void(0); <?php else: ?> <?php echo e(route( 'login' )); ?> <?php endif; ?>" class="noHover <?php if(auth()->check()): ?> sendTip <?php endif; ?> text-secondary" data-post="<?php echo e($post->id); ?>">
    <i class="fas fa-coins"></i> 
        <small><small><?php echo app('translator')->get('general.tip'); ?></small></small>
    </a>
</h4>

<div class="leave-tip mt-2 d-none" data-post="<?php echo e($post->id); ?>">
    <form method="POST" action="<?php echo e(route('sendTip', ['post' => $post->id])); ?>" name="tipFrm-<?php echo e($post->id); ?>">
        <?php echo csrf_field(); ?>
        <div class="row no-gutters">
        <div class="col-6 col-sm-6 col-md-3">
            <input name="tipAmountForPost-<?php echo e($post->id); ?>" type="number" class="form-control" placeholder="<?php echo app('translator')->get('general.minTip'); ?> <?php echo e(opt('payment-settings.currency_symbol') . number_format($post->profile->minTip,2)); ?>" required>
        </div>
        <div class="col-6 col-sm-6 col-md-3">

        <div class="dropdown show z-9999 d-inline">
            <a href="javascript:void(0)" class="ml-2 pt-1 d-block dropdown-toggle" id="tipPaymentMethodLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php echo app('translator')->get('general.payWith'); ?>
            </a>
            <div class="dropdown-menu" aria-labelledBy="tipPaymentMethodLink">

                
                <?php if(opt('card_gateway', 'Stripe') == 'Stripe' OR opt('card_gateway', 'Stripe') == 'PayStack' ): ?>
                    <?php if(auth()->check() && ( opt('stripeEnable', 'No') == 'Yes' OR opt('card_gateway', 'Stripe') == 'PayStack') && auth()->user()->paymentMethods()->count()): ?>
                        <a class="dropdown-item submitTipBtn" href="#tipViaCard" data-id="<?php echo e($post->id); ?>" data-gateway="Card">
                            <?php echo app('translator')->get('general.creditCard'); ?>
                        </a>
                    <?php elseif( auth()->check() && !auth()->user()->paymentMethods()->count() && ( opt('stripeEnable', 'No') == 'Yes' OR opt('card_gateway','Stripe') == 'PayStack')): ?> 
                        <a class="dropdown-item" href="<?php echo e(route('billing.cards')); ?>">
                            <?php echo app('translator')->get('general.addNewCard'); ?>
                        </a>
                    <?php elseif(opt('stripeEnable', 'No') == 'Yes'): ?>
                        <a class="dropdown-item" href="<?php echo e(route('login')); ?>">
                            <?php echo app('translator')->get('general.creditCard'); ?>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>

                
                <?php if(opt('card_gateway', 'Stripe') == 'CCBill'): ?>
                    <a class="dropdown-item submitTipBtn" href="#tipViaCard" data-id="<?php echo e($post->id); ?>" data-gateway="Card">
                        <?php echo app('translator')->get('general.creditCard'); ?>
                    </a>
                <?php endif; ?>

                
                <?php if(opt('card_gateway','Stripe') == 'MercadoPago'): ?>
                    <a class="dropdown-item submitTipBtn" href="#tipViaCard" data-id="<?php echo e($post->id); ?>" data-gateway="Card">
                        <?php echo app('translator')->get('general.creditCard'); ?>
                    </a>
                <?php endif; ?>

                
                <?php if(opt('card_gateway', 'Stripe') == 'TransBank'): ?>
                    <a class="dropdown-item submitTipBtn" href="#tipViaCard" data-id="<?php echo e($post->id); ?>" data-gateway="Card">
                        <?php echo app('translator')->get('general.creditCard'); ?>
                    </a>
                <?php endif; ?>

                
                <?php if(opt('paypalEnable', 'No') == 'Yes'): ?>
                <a class="dropdown-item submitTipBtn" href="#tipViaPayPal" data-id="<?php echo e($post->id); ?>" data-gateway="PayPal">
                    <?php echo app('translator')->get('general.PayPal'); ?>
                </a>
                <?php endif; ?>

                
                <?php if(opt('card_gateway', 'Stripe') == 'Crypto'): ?>
                    <a class="dropdown-item submitTipBtn" href="#tipViaCrypto" data-id="<?php echo e($post->id); ?>" data-gateway="Card">
                        <?php echo app('translator')->get('v192.crypto'); ?>
                    </a>
                <?php endif; ?>
            </div>
            </div>
        </div>
        </div><!-- ./row -->
    </form>
</div>

<?php endif; ?><?php /**PATH /Users/crivion/Sites/patrons/resources/views/tips/tip-form.blade.php ENDPATH**/ ?>