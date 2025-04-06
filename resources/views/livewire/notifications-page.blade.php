<div>
    
    <div class="card shadow-sm mb-3 pb-3">

    <h3 class="ml-3 mt-3">@lang('navigation.myNotifications')</h3>

    <div wire:ignore>
    <select wire:model="tab" class="form-control col-10 col-sm-10 col-md-6 col-lg-4 ml-3">
        <option value="All">@lang('general.All')</option>
        <option value="Likes">@lang('general.Likes')</option>
        <option value="Fans">@lang('general.Fans')</option>
        <option value="Followers">@lang('general.Followers')</option>
        <option value="Tips">@lang('general.Tips')</option>
        <option value="Unlocks">@lang('v19.unlocks')</option>
        <option value="Invoices">@lang('general.Invoices')</option>
        <option value="Payments">@lang('general.Payments')</option>
        <option value="Comments">@lang('general.Comments')</option>
        <option value="Mentions">@lang('general.Mentions')</option>
    </select>
    </div>

    @foreach($notifications as $n)

    <hr>

        <p class="pt-3 pl-3 pr-3 @if(is_null($n->read_at)) text-bold @endif">
        @if($n->type == 'App\Notifications\ReceivedLike')
            @lang('general.userLikesPost', [ 
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
                                            ])
        @elseif($n->type == 'App\Notifications\NewSubscriberNotification') 
        
        
            @lang('general.newFan', [ 'user' => '<a href="'.route('profile.show', ['username' => $n->data['username']]).'">@'.$n->data['username'].'</a>' ])

        @elseif($n->type == 'App\Notifications\PaymentActionRequired')

            @lang('general.paymentVerificationRequired', 
                    [
                        'amount' => opt('payment-settings.currency_symbol') .  $n->data['amount'],
                    ])
            <br>
            <a href="{{ $n->data['invoice_url'] }}" target="_blank">@lang('general.fixVerification')</a>

        @elseif($n->type == 'App\Notifications\InovicePaidNotification')

            @lang('general.invoicePaidNotification', [
            'amount' => opt('payment-settings.currency_symbol') . $n->data['amount'],
            'creator' => '<a href="'.route('profile.show', ['username' => $n->data['to_creator']]).'">@'.$n->data['to_creator'].'</a>',
            'viewInvoice' => '<a href="'.$n->data['invoice_url'].'" target="_blank">'.__('general.view_invoice').'</a>',
            ])

        @elseif($n->type == 'App\Notifications\ReceivedComment') 

        @if(isset($n->data['commentator']))

            @lang('general.userCommentsOnPost', [ 
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
                                                ])

        @else
            @lang('general.userCommentsOnPost', [ 
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
                                                ])
        @endif

        @elseif($n->type == 'App\Notifications\ReceivedPostMentionNotification')

            @lang('general.mentionNotification', [
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
                                                 ])

        @elseif($n->type == 'App\Notifications\NewFollower')

            @lang('general.newFreeFollowerNotification', [ 'user' => '<a href="'.route('profile.show', ['username' => $n->data['profile']['username']]).'" class="d-inline">@'.$n->data['profile']['username'].'</a>' ])

        @elseif($n->type == 'App\Notifications\TipReceivedNotification')
                                    
            @lang('general.tipReceivedNotification', [
                'tipper' => '<a href="'.route('profile.show', ['username' => $n->data['from_user']]).'">'.$n->data['from_handle'].'</a>',
                'amount' => opt('payment-settings.currency_symbol') . $n->data['amount']
            ])

        @elseif($n->type == 'App\Notifications\UnlockedMessageNotification')
                                    
            @lang('v19.unlockedMessageNotification', [
                'tipper' => '<a href="'.route('profile.show', ['username' => $n->data['from_user']]).'">'.$n->data['from_handle'].'</a>',
                'amount' => opt('payment-settings.currency_symbol') . $n->data['amount']
            ])                                    

        @endif

        <br>
        <small>
            <span class="text-secondary">
                <i class="fas fa-clock"></i> {{ $n->created_at->diffForHumans() }}
            </span>
        </small>
    </p>
    
    {{ $n->markAsRead() }}
    @endforeach

    </div>
    {{ $notifications->links() }}

</div>
