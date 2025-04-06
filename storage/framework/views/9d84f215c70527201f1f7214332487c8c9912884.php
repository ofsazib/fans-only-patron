<div>
    
    <div class="card shadow-sm mb-3 pb-3">

    <h3 class="ml-3 mt-3"><?php echo app('translator')->get('navigation.myNotifications'); ?></h3>

    <div wire:ignore>
    <select wire:model="tab" class="form-control col-10 col-sm-10 col-md-6 col-lg-4 ml-3">
        <option value="All"><?php echo app('translator')->get('general.All'); ?></option>
        <option value="Likes"><?php echo app('translator')->get('general.Likes'); ?></option>
        <option value="Fans"><?php echo app('translator')->get('general.Fans'); ?></option>
        <option value="Followers"><?php echo app('translator')->get('general.Followers'); ?></option>
        <option value="Tips"><?php echo app('translator')->get('general.Tips'); ?></option>
        <option value="Unlocks"><?php echo app('translator')->get('v19.unlocks'); ?></option>
        <option value="Invoices"><?php echo app('translator')->get('general.Invoices'); ?></option>
        <option value="Payments"><?php echo app('translator')->get('general.Payments'); ?></option>
        <option value="Comments"><?php echo app('translator')->get('general.Comments'); ?></option>
        <option value="Mentions"><?php echo app('translator')->get('general.Mentions'); ?></option>
    </select>
    </div>

    <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

    <hr>

        <p class="pt-3 pl-3 pr-3 <?php if(is_null($n->read_at)): ?> text-bold <?php endif; ?>">
        <?php if($n->type == 'App\Notifications\ReceivedLike'): ?>
            <?php echo app('translator')->get('general.userLikesPost', [ 
                                            'user' => '<a href="'.route('profile.show', 
                                                      [ 
                                                          'username' => $n->data['user'] 
                                                      ]).'">@'.$n->data['user'].'</a>', 
                                            'postUrl' => '<a href='.route('single-post', 
                                                        [
                                                            'post' => $n->data['postId']
                                                        ]).'">'.route('single-post', 
                                                        [
                                                            'post' => $n->data['postId']
                                                        ]).'</a>'
                                            ]); ?>
        <?php elseif($n->type == 'App\Notifications\NewSubscriberNotification'): ?> 
        
        
            <?php echo app('translator')->get('general.newFan', [ 'user' => '<a href="'.route('profile.show', ['username' => $n->data['username']]).'">@'.$n->data['username'].'</a>' ]); ?>

        <?php elseif($n->type == 'App\Notifications\PaymentActionRequired'): ?>

            <?php echo app('translator')->get('general.paymentVerificationRequired', 
                    [
                        'amount' => opt('payment-settings.currency_symbol') .  $n->data['amount'],
                    ]); ?>
            <br>
            <a href="<?php echo e($n->data['invoice_url']); ?>" target="_blank"><?php echo app('translator')->get('general.fixVerification'); ?></a>

        <?php elseif($n->type == 'App\Notifications\InovicePaidNotification'): ?>

            <?php echo app('translator')->get('general.invoicePaidNotification', [
            'amount' => opt('payment-settings.currency_symbol') . $n->data['amount'],
            'creator' => '<a href="'.route('profile.show', ['username' => $n->data['to_creator']]).'">@'.$n->data['to_creator'].'</a>',
            'viewInvoice' => '<a href="'.$n->data['invoice_url'].'" target="_blank">'.__('general.view_invoice').'</a>',
            ]); ?>

        <?php elseif($n->type == 'App\Notifications\ReceivedComment'): ?> 

        <?php if(isset($n->data['commentator'])): ?>

            <?php echo app('translator')->get('general.userCommentsOnPost', [ 
                                                'user' => '<a href="'.route('profile.show', ['username' => $n->data['commentator']['profile']['username'] ]).'" class="d-inline">
                                                        @'.$n->data['commentator']['profile']['username'].'
                                                   </a>',
                                                'postUrl' => '<a href="'.route('single-post', 
                                                        [
                                                            'post' => $n->data['commentable_id']
                                                        ]).'">'.route('single-post', 
                                                        [
                                                            'post' => $n->data['commentable_id']
                                                        ]).'</a>'
                                                ]); ?>

        <?php else: ?>
            <?php echo app('translator')->get('general.userCommentsOnPost', [ 
                                                'user' => '<a href="'.route('profile.show', ['username' => $n->data['commentable']['user']['profile']['username'] ]).'" class="d-inline">
                                                        @'.$n->data['commentable']['user']['profile']['username'].'
                                                   </a>',
                                                'postUrl' => '<a href="'.route('single-post', 
                                                        [
                                                            'post' => $n->data['commentable_id']
                                                        ]).'">'.route('single-post', 
                                                        [
                                                            'post' => $n->data['commentable_id']
                                                        ]).'</a>'
                                                ]); ?>
        <?php endif; ?>

        <?php elseif($n->type == 'App\Notifications\ReceivedPostMentionNotification'): ?>

            <?php echo app('translator')->get('general.mentionNotification', [
                                                'user' => '<a href="'.route('profile.show', ['username' => $n->data[0]['user']['profile']['username'] ]).'" class="d-inline">
                                                        @'.$n->data[0]['user']['profile']['username'].'
                                                   </a>',
                                                'post' => '<a href="'.route('single-post', 
                                                        [
                                                            'post' => $n->data[0]['id']
                                                        ]).'">'.route('single-post', 
                                                        [
                                                            'post' => $n->data[0]['id']
                                                        ]).'</a>'
                                                 ]); ?>

        <?php elseif($n->type == 'App\Notifications\NewFollower'): ?>

            <?php echo app('translator')->get('general.newFreeFollowerNotification', [ 'user' => '<a href="'.route('profile.show', ['username' => $n->data['profile']['username']]).'" class="d-inline">@'.$n->data['profile']['username'].'</a>' ]); ?>

        <?php elseif($n->type == 'App\Notifications\TipReceivedNotification'): ?>
                                    
            <?php echo app('translator')->get('general.tipReceivedNotification', [
                'tipper' => '<a href="'.route('profile.show', ['username' => $n->data['from_user']]).'">'.$n->data['from_handle'].'</a>',
                'amount' => opt('payment-settings.currency_symbol') . $n->data['amount']
            ]); ?>

        <?php elseif($n->type == 'App\Notifications\UnlockedMessageNotification'): ?>
                                    
            <?php echo app('translator')->get('v19.unlockedMessageNotification', [
                'tipper' => '<a href="'.route('profile.show', ['username' => $n->data['from_user']]).'">'.$n->data['from_handle'].'</a>',
                'amount' => opt('payment-settings.currency_symbol') . $n->data['amount']
            ]); ?>                                    

        <?php endif; ?>

        <br>
        <small>
            <span class="text-secondary">
                <i class="fas fa-clock"></i> <?php echo e($n->created_at->diffForHumans()); ?>

            </span>
        </small>
    </p>
    
    <?php echo e($n->markAsRead()); ?>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>
    <?php echo e($notifications->links()); ?>


</div>
<?php /**PATH /Users/crivion/Sites/patrons/resources/views/livewire/notifications-page.blade.php ENDPATH**/ ?>