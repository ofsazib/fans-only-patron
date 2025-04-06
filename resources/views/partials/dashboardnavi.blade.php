<div class="card shadow-sm">
<ul class="nav flex-column">
  @if( isset( auth()->user()->profile ) )
  <li class="nav-item nav-item-side">
    <a class="nav-link nav-side-link" href="/{{ auth()->user()->profile->username }}">
      <i class="far fa-meh-blank mr-1"></i>
      @lang('dashboard.viewProfile')
    </a>
  </li>
  @endif
  <li class="nav-item nav-item-side">
    <a class="nav-link nav-side-link @if(isset($active) && $active == 'my-profile') side-active @endif" href="{{ route('startMyPage') }}">
      <i class="far fa-edit mr-1"></i>
      @lang('dashboard.myProfile')
    </a>
  </li>
  <li class="nav-item nav-item-side">
    <a class="nav-link nav-side-link" href="/{{ auth()->user()->profile->username }}">
      <i class="fas fa-pen-alt mr-1"></i>
        @lang('dashboard.createPost')
    </a>
  </li>
  <li class="nav-item nav-item-side">
    <a class="nav-link nav-side-link" href="{{ route('messages.inbox') }}">
      <i class="far fa-envelope mr-1"></i> 
      @lang('navigation.messages')
    </a>
  </li>
  <li class="nav-item nav-item-side">
    <a class="nav-link nav-side-link @if(isset($active) && $active == 'my-subscribers') side-active @endif" href="{{ route('mySubscribers') }}">
      <i class="fas fa-user-lock"></i> 
      @lang('navigation.my-subscribers')
    </a>
  </li>
  <li class="nav-item nav-item-side">
    <a class="nav-link nav-side-link @if(isset($active) && $active == 'my-subscriptions') side-active @endif" href="{{ route('mySubscriptions') }}">
      <i class="fas fa-user-edit"></i>
      @lang('navigation.my-subscriptions')
    </a>
  </li>
  <li class="nav-item nav-item-side">
    <a class="nav-link nav-side-link" href="{{ route('billing.history') }}">
      <i class="fas fa-file-invoice-dollar mr-2"></i>
      @lang('navigation.billing')
    </a>
  </li>
  @if( opt('stripeEnable', 'No') == 'Yes' OR opt('card_gateway', 'Stripe') == 'PayStack' )
  <li class="nav-item nav-item-side">
    <a class="nav-link nav-side-link" href="{{ route('billing.cards') }}">
      <i class="fas fa-credit-card mr-1"></i> 
      @lang('navigation.cards')
    </a>
  </li>
  @endif
  @if(auth()->user()->profile->isVerified == 'Yes')
  <li class="nav-item nav-item-side">
    <a class="nav-link nav-side-link @if(isset($active) && $active == 'withdraw') side-active @endif" href="{{ route( 'profile.withdrawal' )}}">
      <i class="fas fa-coins mr-1"></i> @lang('dashboard.withdrawal')
    </a>
  </li>
  @endif
  <li class="nav-item nav-item-side">
    <a class="nav-link nav-side-link @if(isset($active) && $active == 'set-fee') side-active @endif" href="{{ route( 'profile.setFee' )}}">
      <i class="fas fa-comment-dollar mr-1"></i> @lang('dashboard.creatorSetup')
    </a>
  </li>
  <li class="nav-item nav-item-side">
    <a class="nav-link nav-side-link @if(isset($active) && $active == 'settings') side-active @endif" href="{{ route('accountSettings') }}">
      <i class="fas fa-cog mr-1"></i> @lang('dashboard.accountSettings')
    </a>
  </li>
  <li class="nav-item nav-item-side">
    <a class="nav-link nav-side-link" href="{{ route('logout') }}" 
      onclick="event.preventDefault();document.getElementById('logout-form').submit();">
      <i class="fas fa-sign-out-alt mr-1"></i> @lang( 'navigation.logout' )
    </a>
  </li>
</ul>
</div>
<br>