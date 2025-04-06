<?php $__env->startSection('section_title'); ?>
	<strong>Profile Verification Requests</strong>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('section_body'); ?>

<?php if($vreq): ?>
	<table class="table table-striped table-bordered table-responsive dataTable">
	<thead>
	<tr>
		<th>ID</th>
		<th>Email</th>
		<th>Name</th>
		<th>Location</th>
		<th>Photo</th>
		<th>Status</th>
		<th>Actions</th>
	</tr>
	</thead>
	<tbody>
		<?php $__currentLoopData = $vreq; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<tr>
			<td>
				<?php echo e($v->id); ?>

			</td>
			<td>
				<?php echo e($v->user->email); ?>

			</td>
			<td>
				<?php echo e($v->user->name); ?><br>
			</td>
			<td>
				<?php if($v->user_meta): ?>

					<?php if(isset($v->user_meta['address'])): ?>
						<?php echo e($v->user_meta['address']); ?><br>
					<?php endif; ?>

					<?php if(isset($v->user_meta['city'])): ?>
						<?php echo e($v->user_meta['city']); ?>, 
					<?php endif; ?>
					<?php if(isset($v->user_meta['country'])): ?>
						<?php echo e($v->user_meta['country']); ?><br>
					<?php endif; ?>

				<?php else: ?>
					--
				<?php endif; ?>
			</td>
			<td>
				<?php if($v->user_meta): ?>
					<?php if(isset($v->user_meta['id'])): ?>
						<?php if(isset($v->user_meta['verificationDisk'])): ?>
							<?php if($v->user_meta['verificationDisk'] == 'backblaze'): ?>
								<a href="<?php echo e('https://' . opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $v->user_meta['id']); ?>" target="_blank">
									<img src="<?php echo e('https://' . opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $v->user_meta['id']); ?>" width="100" class="img-responsive"/>
								</a>
							<?php else: ?>
								<a href="<?php echo e(\Storage::disk($v->user_meta['verificationDisk'])->url($v->user_meta['id'])); ?>" target="_blank">
									<img src="<?php echo e(\Storage::disk($v->user_meta['verificationDisk'])->url($v->user_meta['id'])); ?>" width="100" class="img-responsive"/>
								</a>
							<?php endif; ?>
						<?php else: ?>
						<a href="<?php echo e(asset('public/uploads/' . $v->user_meta['id'])); ?>" target="_blank">
							<img src="<?php echo e(asset('public/uploads/' . $v->user_meta['id'])); ?>" width="100" class="img-responsive"/>
						</a>
						<?php endif; ?>
					<?php else: ?>
						No ID Uploaded
					<?php endif; ?>
				<?php else: ?>
					--
				<?php endif; ?>
			</td>
			<td>
				<?php if($v->isVerified == 'Rejected'): ?>
					<span class="text-danger"><strong><?php echo e($v->isVerified); ?></strong></span>
				<?php else: ?>
					<span class="text-info"><strong><?php echo e($v->isVerified); ?></strong></span>
				<?php endif; ?>
			</td>
			<td>
				 <div class="btn-group">
    				<a href="/admin/approve/<?php echo e($v->id); ?>" class="text-success">
						<strong>Approve</strong>
					</a><br>
					<a href="/admin/reject/<?php echo e($v->id); ?>" class="text-danger" onclick="return confirm('are you sure?')">
						Reject
					</a>
				</div>
			</td>
		</tr>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</tbody>
	</table>
<?php else: ?>
	No verification requests in database.
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/crivion/Sites/patrons/resources/views/admin/verification-requests.blade.php ENDPATH**/ ?>