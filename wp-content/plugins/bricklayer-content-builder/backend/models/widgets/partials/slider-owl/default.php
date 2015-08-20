<div class="owl-carousel-item">
    <?php if ((int) $show_featured_image): ?>

        <?php $imageArgs = array('post_id' => $post->ID, 'echo' => false, 'size' => $thumbnail_dimensions); ?>
        <?php $image     = cbp_get_the_image($imageArgs); ?>
        <?php if (isset($image) && $image): ?>
            <div class="<?php echo $this->getPrefix(); ?>-widget-post-image">
                <?php echo $image; ?>
            </div>
            <div class="<?php echo $this->getPrefix(); ?>-widget-post-description">
                <?php echo $post->post_content; ?>
            </div>
        <?php endif; ?>

    <?php endif; ?>
</div>
