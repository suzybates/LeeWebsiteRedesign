<div class="cbp-row cbp_widget_row ch-footer-top">
    <div class="cbp-container">
        <?php get_sidebar('footer-top'); ?>
    </div>
</div>
<div class="cbp-row cbp_widget_row ch-footer-bottom">
    <div class="cbp-container">
        <div class="cbp_widget_box two thirds double-padded">
            <?php
            $defaults = array(
                'theme_location' => 'secondary-menu',
                'menu_class'     => 'sf-menu ch-menu-footer',
                'depth'          => 1
            );
            ?>
            <div id="cbp-menu-5285524473c74" class="left cbp_widget_menu">
                <?php wp_nav_menu($defaults); ?>
            </div>
        </div>
        <?php $footerText      = ChtUtils::getOption('footer_text'); ?>
        <?php if ($footerText): ?>
            <div class="cbp_widget_box one third double-padded">  
                <div class="cbp_widget_text align-right">
                    <?php echo $footerText; ?>
                </div> 
            </div>
        <?php endif; ?>
    </div>
</div>
