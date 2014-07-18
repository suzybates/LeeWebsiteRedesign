<?php
/**
 * @package WordPress
 * @subpackage Carry Hill
 */
?>
<?php if (is_active_sidebar('ch-regular-sidebar')) : ?>
    <?php dynamic_sidebar('ch-regular-sidebar'); ?>
<?php else : ?>
    <?php // This content shows up if there are no widgets defined in the backend.  ?>
    <div class="alert alert-help">
        <p><?php _e('Please activate some Widgets.', CHT_APP_TEXT_DOMAIN); ?></p>
    </div>
<?php endif; ?>