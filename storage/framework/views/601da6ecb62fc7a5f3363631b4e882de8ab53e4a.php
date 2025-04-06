<div>
    
    <form method="POST" action="/admin/save-payments-settings">
        <?php echo e(csrf_field()); ?>

        
        <div class="row">
        <div class="col-xs-6">
        <dl>
        <dt>Enable PayPal Payments?</dt>
        <dd>
            <input type="radio" name="paypalEnable" value="No" <?php if('No' == opt('paypalEnable')): ?> checked <?php endif; ?>> No 
            <input type="radio" name="paypalEnable" value="Yes" <?php if('Yes' == opt('paypalEnable')): ?> checked <?php endif; ?>> Yes
        </dd>
        <br>
        <dt>Paypal Email</dt>
        <dd>
            <input type="text" name="paypal_email" value="<?php echo e(opt('paypal_email', 'you@paypal.com')); ?>" class="form-control">
        </dd>
        <br>
        <hr>
        <br>
        <dt>Credit Card Gateway</dt>
        <dd wire:ignore>
        <select name="card_gateway" class="form-control" style="width: 250px;" wire:model="gateway">
            <option value="None" <?php if($gateway == 'None'): ?> selected <?php endif; ?>>None - Without CC Payments</option>
            <option value="Crypto" <?php if($gateway == 'Crypto'): ?> selected <?php endif; ?>>Crypto (CoinPayments.net)</option>
            <option value="Stripe" <?php if($gateway == 'Stripe'): ?> selected <?php endif; ?>>Stripe</option>
            <option value="PayStack" <?php if($gateway == 'PayStack'): ?> selected <?php endif; ?>>PayStack</option>
            <option value="CCBill" <?php if($gateway == 'CCBill'): ?> selected <?php endif; ?>>CCBill</option>
            <option value="TransBank" <?php if($gateway == 'TransBank'): ?> selected <?php endif; ?>>TransBank</option>
            
        </select>
        </dd>

        <?php if($gateway == 'Crypto'): ?>
        <br>
        <hr>
        <br>
        <span class="text-danger">Complete below if you selected <strong>Crypto (CoinPayments.net) as Gateway</strong></span><br><br>
        <dt>Your merchant ID (<a href="https://www.coinpayments.net/acct-settings" target="_blank">https://www.coinpayments.net/acct-settings</a></dt>
        <dd>
            <input type="text" name="COIN_MERCHANT_ID" value="<?php echo e(opt('COIN_MERCHANT_ID')); ?>" class="form-control">
        <br>
        <dt>Your IPN Secret Key (CoinPayments.net -> Account Settings -> Merchant Settings-> IPN Secret)</dt>
        <dd>
            <input type="text" name="COIN_IPN_SECRET" value="<?php echo e(opt('COIN_IPN_SECRET')); ?>" class="form-control">
        </dd>
        <br>

        <?php endif; ?>

        <?php if($gateway == 'Stripe'): ?>
        <br>
        <hr>
        <br>
        <span class="text-danger">Complete below if you selected <strong>Stripe as Card Gateway</strong></span><br><br>
        <dt>Enable Stripe Payments?</dt>
        <dd>
            <input type="radio" name="stripeEnable" value="No" <?php if('No' == opt('stripeEnable')): ?> checked <?php endif; ?>> No 
            <input type="radio" name="stripeEnable" value="Yes" <?php if('Yes' == opt('stripeEnable')): ?> checked <?php endif; ?>> Yes 
        </dd>
        <br>
        <dt>Stripe Public Key</dt>
        <dd>
            <input type="text" name="STRIPE_PUBLIC_KEY" value="<?php echo e(opt('STRIPE_PUBLIC_KEY')); ?>" class="form-control">
        <br>
        <dt>Stripe Secret Key</dt>
        <dd>
            <input type="text" name="STRIPE_SECRET_KEY" value="<?php echo e(opt('STRIPE_SECRET_KEY')); ?>" class="form-control">
        <br>
        <dt>Stripe Webhooks Secret Key</dt>
        <dd>
            <input type="text" name="STRIPE_WEBHOOK_SECRET" value="<?php echo e(opt('STRIPE_WEBHOOK_SECRET')); ?>" class="form-control">
        </dd>
        <?php endif; ?>

        <?php if($gateway == 'PayStack'): ?>
        <br>
        <hr>
        <br>
        <span class="text-danger">Complete below if you selected <strong>PayStack as Card Gateway</strong></span>
        <br><br>
        <dt>PayStack Public Key</dt>
        <dd>
            <input type="text" name="PAYSTACK_PUBLIC_KEY" value="<?php echo e(opt('PAYSTACK_PUBLIC_KEY')); ?>" class="form-control">
        <br>
        <dt>PayStack Secret Key</dt>
        <dd>
            <input type="text" name="PAYSTACK_SECRET_KEY" value="<?php echo e(opt('PAYSTACK_SECRET_KEY')); ?>" class="form-control">
        <br>
        <dt> Enter this url as your webhooks url (leave callback empty): 
            <strong><em><span class="text-danger"><?php echo e(route('paystack-hooks')); ?></span></em></strong>
        </dt>
        <?php endif; ?>

        <?php if($gateway == 'MercadoPago'): ?>
        <br>
        <hr>
        <br>
        <span class="text-danger">Complete below if you selected <strong>MercadoPago as Card Gateway</strong></span>
        <br><br>
        <dt>MercadoPago Public Key</dt>
        <dd>
            <input type="text" name="MERCADOPAGO_PUBLIC_KEY" value="<?php echo e(opt('MERCADOPAGO_PUBLIC_KEY')); ?>" class="form-control">
        <br>
        <dt>MercadoPago Secret Key</dt>
        <dd>
            <input type="text" name="MERCADOPAGO_SECRET_KEY" value="<?php echo e(opt('MERCADOPAGO_SECRET_KEY')); ?>" class="form-control">
        <br>
      
        <?php endif; ?>

        <?php if($gateway == 'CCBill'): ?>
        <br>
        <hr>
        <br>
        <span class="text-danger">Complete below if you selected <strong>CCBill as Card Gateway</strong> (<a href="https://support.ccbill.com/" target="_blank">CCBill Support</a> to obtain these)</span><br>
        <span class="text-info">CCBill Webhooks URL is: <strong><em><?php echo e(route('ccbill-hooks')); ?></em></strong><span> - refer to documentation on how to use
        <br><br>
        <dt>CCBill Account Number</dt>
        <dd>
            <input type="text" name="ccbill_clientAccnum" value="<?php echo e(opt('ccbill_clientAccnum')); ?>" class="form-control">
        <br>
        <dt>CCBill Subscriptions SubAccount Number</dt>
        <dd>
            <input type="text" name="ccbill_Subacc" value="<?php echo e(opt('ccbill_Subacc')); ?>" class="form-control">
        <br>
        <dt>CCBill Tips SubAccount Number</dt>
        <dd>
            <input type="text" name="ccbill_Subacc_tips" value="<?php echo e(opt('ccbill_Subacc_tips')); ?>" class="form-control">
        <br>
        <dt>CCBill Flex Form ID</dt>
        <dd>
            <input type="text" name="ccbill_flexid" value="<?php echo e(opt('ccbill_flexid')); ?>" class="form-control">
        <br>
        <dt>CCBill Salt Key</dt>
        <dd>
            <input type="text" name="ccbill_salt" value="<?php echo e(opt('ccbill_salt')); ?>" class="form-control">
        <br>
        </dl>
        <?php endif; ?>
        
        <?php if($gateway == 'TransBank'): ?>
        <br>
        <hr>
        <br>
        <span class="text-danger">Complete below if you selected <strong>TransBank as Card Gateway</strong> (Contact TransBank Support to obtain these)</span><br>
        <br><br>
        <dt>TransBank Environment</dt>
        <dd>
            <select name="TransBank_ENV" class="form-control">
                <option value="Testing" <?php if(opt('TransBank_ENV', 'Testing') == 'Testing'): ?> selected <?php endif; ?>>Testing</option>
                <option value="Production" <?php if(opt('TransBank_ENV', 'Testing') == 'Production'): ?> selected <?php endif; ?>>Production</option>
            </select>
        <br>
        <dt>Commerce Code Number</dt>
        <dd>
            <input type="text" name="TransBank_CC" value="<?php echo e(opt('TransBank_CC')); ?>" class="form-control">
        <br>
        <dt>Secret API Key</dt>
        <dd>
            <input type="text" name="TransBank_Key" value="<?php echo e(opt('TransBank_Key')); ?>" class="form-control">
        <br>
        <?php endif; ?>

        </dl>
        
        </div>
        
        <div class="col-xs-6">
        <dl>
        <dt>Currency Symbol</dt>
        <dd>
            <input type="text" name="payment-settings.currency_symbol" value="<?php echo e(opt('payment-settings.currency_symbol')); ?>" class="form-control">
        </dd>
        <br>
        <dt>Currency ISO Code <small><a href="https://www.xe.com/iso4217.php" target="_blank">ISO List</a></small></dt>
        <dd>
            <input type="text" name="payment-settings.currency_code" value="<?php echo e(opt('payment-settings.currency_code')); ?>" class="form-control">
        </dd>
        <br>
        <dt>
            <span class="text-danger">Site Fee %</span>
        <dd>
            <input type="number" name="payment-settings.site_fee" value="<?php echo e(opt('payment-settings.site_fee')); ?>" class="form-control">
        </dd>
        <br>
        <dt>Min. Withdrawal Amount</dt>
        <dd>
            <input type="text" name="withdraw_min" value="<?php echo e(opt('withdraw_min')); ?>" class="form-control">
        <br>
        <dt>Min. Membership Price</dt>
        <dd>
            <input type="text" name="minMembershipFee" value="<?php echo e(opt('minMembershipFee')); ?>" class="form-control">
        <br>
        <dt>Max. Membership Price</dt>
        <dd>
            <input type="text" name="maxMembershipFee" value="<?php echo e(opt('maxMembershipFee')); ?>" class="form-control">
        <br>
        <dt>Min. Tip Amount</dt>
        <dd>
            <input type="text" name="minTipAmount" value="<?php echo e(opt('minTipAmount')); ?>" class="form-control">
        <br>
        <dt>Max. Tip Amount</dt>
        <dd>
            <input type="text" name="maxTipAmount" value="<?php echo e(opt('maxTipAmount')); ?>" class="form-control">
        <br>
        </dl>
        </div>
        </div>
        
        <hr>
        <div class="text-center">
                <input type="submit" name="sb_settings" value="Save Payment Settings" class="btn btn-primary">	
        </div>
        
        </form>

</div>
<?php /**PATH /Users/crivion/Sites/fansonly/resources/views/livewire/admin-payment-settings.blade.php ENDPATH**/ ?>