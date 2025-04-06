<div class="card shadow-sm">
<ul class="nav flex-column">
  <?php if( isset( auth()->user()->profile ) ): ?>
  <li class="nav-item nav-item-side">
    <a class="nav-link nav-side-link" href="/<?php echo e(auth()->user()->profile->username); ?>">
      <i class="far fa-meh-blank mr-1"></i>
      <?php echo app('translator')->get('dashboard.viewProfile'); ?>
    </a>
  </li>
  <?php endif; ?>
  <li class="nav-item nav-item-side">
    <a class="nav-link nav-side-link <?php if(isset($active) && $active == 'my-profile'): ?> side-active <?php endif; ?>" href="<?php echo e(route('startMyPage')); ?>">
      <i class="far fa-edit mr-1"></i>
      <?php echo app('translator')->get('dashboard.myProfile'); ?>
    </a>
  </li>
  <li class="nav-item nav-item-side">
    <a class="nav-link nav-side-link" href="/<?php echo e(auth()->user()->profile->username); ?>">
      <i class="fas fa-pen-alt mr-1"></i>
        <?php echo app('translator')->get('dashboard.createPost'); ?>
    </a>
  </li>
  <li class="nav-item nav-item-side">
    <a class="nav-link nav-side-link" href="<?php echo e(route('messages.inbox')); ?>">
      <i class="far fa-envelope mr-1"></i> 
      <?php echo app('translator')->get('navigation.messages'); ?>
    </a>
  </li>
  <li class="nav-item nav-item-side">
    <a class="nav-link nav-side-link <?php if(isset($active) && $active == 'my-subscribers'): ?> side-active <?php endif; ?>" href="<?php echo e(route('mySubscribers')); ?>">
      <i class="fas fa-user-lock"></i> 
      <?php echo app('translator')->get('navigation.my-subscribers'); ?>
    </a>
  </li>
  <li class="nav-item nav-item-side">
    <a class="nav-link nav-side-link <?php if(isset($active) && $active == 'my-subscriptions'): ?> side-active <?php endif; ?>" href="<?php echo e(route('mySubscriptions')); ?>">
      <i class="fas fa-user-edit"></i>
      <?php echo app('translator')->get('navigation.my-subscriptions'); ?>
    </a>
  </li>
  <li class="nav-item nav-item-side">
    <a class="nav-link nav-side-link" href="<?php echo e(route('billing.history')); ?>">
      <i class="fas fa-file-invoice-dollar mr-2"></i>
      <?php echo app('translator')->get('navigation.billing'); ?>
    </a>
  </li>
  <?php if( opt('stripeEnable', 'No') == 'Yes' OR opt('card_gateway', 'Stripe') == 'PayStack' ): ?>
  <li class="nav-item nav-item-side">
    <a class="nav-link nav-side-link" href="<?php echo e(route('billing.cards')); ?>">
      <i class="fas fa-credit-card mr-1"></i> 
      <?php echo app('translator')->get('navigation.cards'); ?>
    </a>
  </li>
  <?php endif; ?>
  <?php if(auth()->user()->profile->isVerified == 'Yes'): ?>
  <li class="nav-item nav-item-side">
    <a class="nav-link nav-side-link <?php if(isset($active) && $active == 'withdraw'): ?> side-active <?php endif; ?>" href="<?php echo e(route( 'profile.withdrawal' )); ?>">
      <i class="fas fa-coins mr-1"></i> <?php echo app('translator')->get('dashboard.withdrawal'); ?>
    </a>
  </li>
  <?php endif; ?>
  <li class="nav-item nav-item-side">
    <a class="nav-link nav-side-link <?php if(isset($active) && $active == 'set-fee'): ?> side-active <?php endif; ?>" href="<?php echo e(route( 'profile.setFee' )); ?>">
      <i class="fas fa-comment-dollar mr-1"></i> <?php echo app('translator')->get('dashboard.creatorSetup'); ?>
    </a>
  </li>
  <li class="nav-item nav-item-side">
    <a class="nav-link nav-side-link <?php if(isset($active) && $active == 'settings'): ?> side-active <?php endif; ?>" href="<?php echo e(route('accountSettings')); ?>">
      <i class="fas fa-cog mr-1"></i> <?php echo app('translator')->get('dashboard.accountSettings'); ?>
    </a>
  </li>
  <li class="nav-item nav-item-side">
    <a class="nav-link nav-side-link" href="<?php echo e(route('logout')); ?>" 
      onclick="event.preventDefault();document.getElementById('logout-form').submit();">
      <i class="fas fa-sign-out-alt mr-1"></i> <?php echo app('translator')->get( 'navigation.logout' ); ?>
    </a>
  </li>
</ul>
</div>
<br><?php /**PATH /Users/crivion/Sites/patrons/resources/views/partials/dashboardnavi.blade.php ENDPATH**/ ?>