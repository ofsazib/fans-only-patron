<?php if( $post->media_type == 'Image' ): ?>

<?php if($post->postmedia->count()): ?>

<div id="post-carousel-<?php echo e($post->id); ?>" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <?php if( $post->disk == 'backblaze' ): ?>
            <a href="javascript:void(0);" data-toggle="lightbox"
                data-remote="https://<?php echo e(opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $post->media_content); ?>"
                data-gallery="post-<?php echo e($post->id); ?>">
                <img src="https://<?php echo e(opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $post->media_content); ?>"
                    alt="" class="img-fluid" />
            </a>
            <?php else: ?>
            <a href="javascript:void(0);" data-toggle="lightbox"
                data-remote="<?php echo e(\Storage::disk($post->disk)->url($post->media_content)); ?>"
                data-gallery="post-<?php echo e($post->id); ?>">
                <img src="<?php echo e(\Storage::disk($post->disk)->url($post->media_content)); ?>" alt="" class="img-fluid" />
            </a>
            <?php endif; ?>
        </div>
        <?php $__currentLoopData = $post->postmedia; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $extraMedia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="carousel-item">
            <?php if( $post->disk == 'backblaze' ): ?>
            <a href="javascript:void(0);" data-toggle="lightbox"
                data-remote="https://<?php echo e(opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $extraMedia->media_content); ?>"
                data-gallery="post-<?php echo e($post->id); ?>">
                <img src="https://<?php echo e(opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $extraMedia->media_content); ?>"
                    alt="" class="img-fluid" />
            </a>
            <?php else: ?>
            <a href="javascript:void(0);" data-toggle="lightbox"
                data-remote="<?php echo e(\Storage::disk($extraMedia->disk)->url($extraMedia->media_content)); ?>"
                data-gallery="post-<?php echo e($post->id); ?>">
                <img src="<?php echo e(\Storage::disk($extraMedia->disk)->url($extraMedia->media_content)); ?>" alt=""
                    class="img-fluid" />
            </a>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <a class="carousel-control-prev" href="#post-carousel-<?php echo e($post->id); ?>" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#post-carousel-<?php echo e($post->id); ?>" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>



<?php else: ?>

<?php if( $post->disk == 'backblaze' ): ?>
<a href="javascript:void(0);" data-toggle="lightbox"
    data-remote="https://<?php echo e(opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $post->media_content); ?>">
    <img src="https://<?php echo e(opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $post->media_content); ?>" alt=""
        class="img-fluid" />
</a>
<?php else: ?>
<a href="javascript:void(0);" data-toggle="lightbox"
    data-remote="<?php echo e(\Storage::disk($post->disk)->url($post->media_content)); ?>">
    <img src="<?php echo e(\Storage::disk($post->disk)->url($post->media_content)); ?>" alt="" class="img-fluid" />
</a>
<?php endif; ?>

<?php endif; ?>

<?php elseif( $post->media_type == 'Video' ): ?>

<div class="embed-responsive embed-responsive-16by9">
    <video controls <?php if(opt('enableMediaDownload', 'No' )=='No' ): ?> controlsList="nodownload" <?php endif; ?> preload="metadata"
        disablePictureInPicture>
        <?php if( $post->disk == 'backblaze' ): ?>
        <source
            src="https://<?php echo e(opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $post->media_content); ?>#t=0.5"
            type="video/mp4" />
        <?php else: ?>
        <source src="<?php echo e(\Storage::disk($post->disk)->url($post->video_url)); ?>#t=0.5" type="video/mp4" />
        <?php endif; ?>
        <?php echo app('translator')->get('post.videoTag'); ?>
    </video>
</div>

<?php elseif( $post->media_type == 'Audio' ): ?>

<div class="p-2">
    <audio class="w-100 mb-4" controls <?php if(opt('enableMediaDownload', 'No' )=='No' ): ?> controlsList="nodownload" <?php endif; ?>>
        <?php if( $post->disk == 'backblaze' ): ?>
        <source src="https://<?php echo e(opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $post->media_content); ?>"
            type="audio/mp3">
        <?php else: ?>
        <source src="<?php echo e(\Storage::disk($post->disk)->url($post->audio_url)); ?>" type="audio/mp3">
        <?php endif; ?>
        <?php echo app('translator')->get('post.audioTag'); ?>
    </audio>
</div>

<?php elseif( $post->media_type == 'ZIP' ): ?>

<h5>
    <a href="<?php echo e(route('downloadZip', ['post' => $post])); ?>" target="_blank" class="ml-4 mb-3">
        <i class="fas fa-file-archive"></i> <?php echo app('translator')->get('v16.zipDownload'); ?>
    </a>
</h5><br>

<?php endif; ?>

<?php $__env->startPush('extraCSS'); ?>
<style>
    .ekko-lightbox-nav-overlay a {
        opacity: 1;
        color: black;
    }
</style>
<?php $__env->stopPush(); ?><?php /**PATH /var/www/works.crivion.com/resources/views/posts/post-media.blade.php ENDPATH**/ ?>