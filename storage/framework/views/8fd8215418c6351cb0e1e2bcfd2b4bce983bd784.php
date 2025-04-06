<?php $__env->startSection('section_title'); ?>
<strong>Users Management</strong>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('section_body'); ?>
	
	<table class="table dataTable">
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Followers</th>
		<th>Fans</th>
        <th>Type</th>
        <th>Is Admin</th>
        <th>IP Address</th>
        <th>Join Date</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td><?php echo e($u->id); ?></td>
        <td><?php echo e($u->name); ?></td>
        <td><?php echo e($u->email); ?></td>
        <td>
            <?php echo e($u->followers_count); ?>

        </td>
        <td>
            <?php echo e($u->subscribers_count); ?>

		</td>
		<td>
			<?php if($u->profile->isVerified == 'Yes'): ?>
				Creator
			<?php else: ?>
				User
			<?php endif; ?>
        </td>
        <td>
            <?php echo e($u->isAdmin); ?>

            <br>
            <?php if($u->isAdmin == 'Yes'): ?> 
                <a href="/admin/users/unsetadmin/<?php echo e($u->id); ?>">Unset Admin Role</a>
            <?php elseif($u->isAdmin == 'No'): ?>
                <a href="/admin/users/setadmin/<?php echo e($u->id); ?>">Set Admin Role</a>
            <?php endif; ?>
        </td>
        <td>
            <?php echo e($u->ip ? $u->ip : 'N/A'); ?>

            <br>
            
            <?php if($u->isBanned == 'No'): ?>
                <a href="/admin/users/ban/<?php echo e($u->id); ?>">
                    Ban
                </a>
            <?php elseif($u->isBanned == 'Yes'): ?>
                <a href="/admin/users/unban/<?php echo e($u->id); ?>">
                    Unban
                </a>
            <?php endif; ?>
        </td>
		<td><?php echo e($u->created_at->format( 'jS F Y' )); ?></td>
        <td>
            <a href="/admin/add-plan/<?php echo e($u->id); ?>">Add Plan Manually</a><br>

            <a href="/admin/loginAs/<?php echo e($u->id); ?>" onclick="return confirm('This will log you out as an admin and login as a vendor. Continue?')">Login as User</a>

            <br>
            <br>
            <a href="/admin/users?remove=<?php echo e($u->id); ?>" onclick="return confirm('Are you sure you want to delete this user and his data? This is irreversible!!!')" class="text-danger">Delete User & His Data</a>
        </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
	</table>
	
<?php $__env->stopSection(); ?>

<?php $__env->startSection('extra_bottom'); ?>
	<?php if(count($errors) > 0): ?>
	    <div class="alert alert-danger">
	        <ul>
	            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	                <li><?php echo e($error); ?></li>
	            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	        </ul>
	    </div>
	<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/crivion/Sites/patrons/resources/views/admin/users.blade.php ENDPATH**/ ?>