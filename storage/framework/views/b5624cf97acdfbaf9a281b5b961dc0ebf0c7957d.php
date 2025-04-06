<?php $__env->startSection('extra_top'); ?>
<div class="col-xs-12">
<div class="box">
    <div class="box-header with-border"><strong>Report form entries</strong></div>
    <div class="box-body">

    <table class="table dataTable">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>URL <small>only click if it's your own domain</small></th>
            <th>Message</th>
            <th>IP</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><?php echo e($r->reporter_name); ?></td>
            <td><a href="mailto:<?php echo e($r->reporter_email); ?>"><?php echo e($r->reporter_email); ?></a></td>
            <td>
                <a href="<?php echo e($r->reported_url); ?>" target="_blank">
                    <?php echo e($r->reported_url); ?>

                </a>
            </td>
            <td><?php echo e(empty($r->report_message) ? '--' : $r->report_message); ?></td>
            <td><?php echo e($r->reporter_ip); ?></td>
            <td>
                <a href="/admin/content-moderation/delete-report/<?php echo e($r->id); ?>" class="text-danger" onclick="return confirm('Delete report?')">
                    Delete
                </a>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

        <?php endif; ?>
    </tbody>
    </table>

    </div>
</div>
</div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('section_title'); ?>
	<strong>Content Moderation</strong>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('section_body'); ?>

    - note : you can login as any of these <a href="/admin/users">admin &raquo; users</a> to edit their content - <br>
    <br>

    
    <ul class="nav nav-tabs" role="tablist">
        <li <?php if($content_type == 'Image'): ?> class="active" <?php endif; ?>>
            <a href="/admin/moderation/Image">Images (<?php echo e($counts['image']); ?>)</a>
        </li>
        <li <?php if($content_type == 'Video'): ?> class="active" <?php endif; ?>>
            <a href="/admin/moderation/Video">Videos (<?php echo e($counts['video']); ?>)</a>
        </li>
        <li <?php if($content_type == 'Audio'): ?> class="active" <?php endif; ?>>
            <a href="/admin/moderation/Audio">Audios (<?php echo e($counts['audio']); ?>)</a>
        </li>
        <li <?php if($content_type == 'ZIP'): ?> class="active" <?php endif; ?>>
            <a href="/admin/moderation/ZIP">ZIP Files (<?php echo e($counts['zip']); ?>)</a>
        </li>
        <li <?php if($content_type == 'None'): ?> class="active" <?php endif; ?>>
            <a href="/admin/moderation/None">Text Posts (<?php echo e($counts['text']); ?>)</a>
        </li>
    </ul>

    <div class="table-responsive">
    <table class="table table-responsive">
    <tr>
        <td>ID</td>
        <td>User</td>
        <td>Lock</td>
        <td>Date</td>
        <td>Text</td>
        <td>Media</td>
        <td>Delete</td>
    </tr>
    <?php $__empty_1 = true; $__currentLoopData = $contents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?> 
    <tr>
        <td><?php echo e($p->id); ?></td>
        <td>
            <a href="<?php echo e(route('profile.show', ['username' => $p->profile->username])); ?>" target="_blank">
                <?php echo e($p->profile->handle); ?>

            </a>
        </td>
        <td>
            <?php if($p->lock_type == 'Paid'): ?>
                <span class="text text-danger">Locked</span>
            <?php else: ?>
                <span class="text text-success">Free</span>
            <?php endif; ?>
        </td>
        <td>
            <?php echo e($p->created_at->format('jS F Y')); ?><br>
            <?php echo e($p->created_at->format('H:i a')); ?>

        </td>
        <td>
            <article style="max-height: 200px; overflow: scroll;">
                <?php echo clean($p->text_content); ?>

            </article>
        </td>
        <td>
            <?php if($p->media_type == 'Image'): ?>
                
                <?php if( $p->disk == 'backblaze' ): ?>
                    <a href="https://<?php echo e(opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $p->media_content); ?>">
                        <img src="https://<?php echo e(opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $p->media_content); ?>" alt="" class="img-responsive" width="200"/>
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(\Storage::disk($p->disk)->url($p->media_content)); ?>" data-toggle="lightbox">
                        <img src="<?php echo e(\Storage::disk($p->disk)->url($p->media_content)); ?>" alt="" class="img-responsive" width="200"/>
                    </a>
                <?php endif; ?>

            <?php elseif($p->media_type == 'Video'): ?> 

                <?php if( $p->disk == 'backblaze' ): ?>
                    <video width="420" height="340" controls controlsList="nodownload" disablePictureInPicture>
                        <source src="https://<?php echo e(opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $p->media_content); ?>" type="video/mp4" />
                    </video>
                <?php else: ?>
                    <video width="420" height="340" controls controlsList="nodownload" disablePictureInPicture>
                        <source src="<?php echo e(\Storage::disk($p->disk)->url($p->video_url)); ?>" type="video/mp4" />
                    </video>
                <?php endif; ?>

            <?php elseif($p->media_type == 'Audio'): ?> 

                <?php if( $p->disk == 'backblaze' ): ?>
                    <audio class="w-100 mb-4" controls controlsList="nodownload">
                        <source src="https://<?php echo e(opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $p->media_content); ?>" type="audio/mp3" />
                    </audio>
                <?php else: ?>
                    <audio class="w-100 mb-4" controls controlsList="nodownload">
                        <source src="<?php echo e(\Storage::disk($p->disk)->url($p->audio_url)); ?>" type="audio/mp3">
                    </audio>
                <?php endif; ?>

            <?php elseif($p->media_type == 'ZIP'): ?>

                <?php if( $p->disk == 'backblaze' ): ?>
                    <a href="https://<?php echo e(opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $p->media_content); ?>">
                        Download ZIP
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(\Storage::disk($p->disk)->url($p->media_content)); ?>" data-toggle="lightbox">
                        Download ZIP
                    </a>
                <?php endif; ?>
                
            <?php else: ?>
                No media
            <?php endif; ?>
        </td>
        <td>
            <a href="/admin/moderation/<?php echo e($content_type); ?>?delete=<?php echo e($p->id); ?>" class="text-danger" onclick="return confirm('Confirm delete?')">
                Delete
            </a>
        </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        There are no contents of this type in database.
    <?php endif; ?>
    </div>
    </table>

    <?php echo e($contents->links()); ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/crivion/Sites/patrons/resources/views/admin/content-moderation.blade.php ENDPATH**/ ?>