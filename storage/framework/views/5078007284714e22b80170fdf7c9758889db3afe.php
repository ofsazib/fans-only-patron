<?php $__env->startSection('extra_top'); ?>
<div class="row">
<div class="col-lg-2 col-xs-6">
  <!-- small box -->
  <div class="small-box bg-yellow">
    <div class="inner">
      <h3><?php echo e($totalVendors); ?></h3>
      <p>Creators</p>
    </div>
    <div class="icon">
      <i class="fa fa-users"></i>
    </div>
  </div>
</div>
<div class="col-lg-2 col-xs-6">
  <!-- small box -->
  <div class="small-box bg-red">
    <div class="inner">
      <h3><?php echo e($payingFans); ?></h3>
      <p>Subscribers</p>
    </div>
    <div class="icon">
      <i class="fa fa-money"></i>
    </div>
  </div>
</div>

<div class="col-lg-2 col-xs-6">
  <!-- small box -->
  <div class="small-box bg-gray">
    <div class="inner">
      <h3><?php echo e($totalTips); ?></h3>
      <p>Total Tips</p>
    </div>
    <div class="icon">
      <i class="fa fa-money"></i>
    </div>
  </div>
</div>

<div class="col-lg-2 col-xs-6">
  <!-- small box -->
  <div class="small-box bg-aqua">
    <div class="inner">
      <h3><?php echo e($totalUsers); ?></h3>
      <p>Total Users</p>
    </div>
    <div class="icon">
      <i class="fa fa-shopping-cart"></i>
    </div>
  </div>
</div>
<div class="col-lg-2 col-xs-6">
  <!-- small box -->
  <div class="small-box bg-green">
    <div class="inner">
      <h3><?php echo e(opt('payment-settings.currency_symbol') . number_format($monthEarnings,2)); ?></h3>
      <p>Month Income</p>
    </div>
    <div class="icon">
      <i class="fa fa-money"></i>
    </div>
  </div>
</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header with-border"><strong>Past 30 Days</strong></div>
			<div class="box-body">
				<!-- LINE CHART -->
				<div class="chart-responsive">
          <div class="chart" id="past-30-days"></div>
          
				  <script>
				  new Morris.Line({
					  // ID of the element in which to draw the chart.
					  element: 'past-30-days',
					  // Chart data records -- each entry in this array corresponds to a point on
					  // the chart.
					  data: [
					  	<?php if( isset($earnings) AND count($earnings) ): ?>
                <?php $__currentLoopData = $earnings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								{ 
                  date: '<?php echo e($d['date']); ?>', 
                  earnings: '<?php echo e($d['total']); ?>', 
                  platform: '<?php echo e($d['platform']); ?>', 
                  creators: '<?php echo e($d['creators']); ?>', 
                  fans: <?php echo e($d['fansCount']); ?>,
                  tips: <?php echo e($d['tipsCount']); ?>,
                  unlocks: <?php echo e($d['unlocksCount']); ?>

                },
						    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					    <?php else: ?>
					    	{ date: '<?php echo e(date( 'jS F Y' )); ?>', earnings: 0, fans: 0, platform: 0, creators: 0, tips: 0, unlocks: 0 }
					    <?php endif; ?>
					  ],
					  // The name of the data record attribute that contains x-values.
					  xkey: 'date',
					  // A list of names of data record attributes that contain y-values.
            ykeys: ['earnings', 'platform', 'creators', 'fans', 'tips', 'unlocks'],
					  // Labels for the ykeys -- will be displayed when you hover over the
					  // chart.
					  labels: ['Income', 'Platform Earnings', 'Creators Earnings', 'Subscriptions', 'Tips', 'Message Unlocks']
				  });
				  </script>

				</div><!-- subscription earnings -->
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('section_title'); ?>
	<strong>All Users</strong>
<?php $__env->stopSection(); ?>

<?php $__env->startSection( 'section_body' ); ?>

<a href="/admin/users" class="btn btn-primary">View Users</a>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/crivion/Sites/patrons/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>