<?php $stickyHeaderClass = ChtUtils::getOption('header_menu_sticky_use') == 'true' ? 'ch-sticky-header-enabled' : ''; ?>
<div class="cbp-row cbp_widget_row ch-header <?php echo $stickyHeaderClass; ?>">
    <div class="cbp-container">
        <div class="cbp_widget_box one third double-padded">
            <div class="chp_widget_logo two-up-mobile">
                <?php $logo = ChtUtils::getOption('logo'); ?>
                <?php if (!empty($logo)): ?>
                    <a href="<?php echo home_url(); ?>">
                        <img src="<?php echo $logo; ?>" alt="logo">
                    </a>
                <?php else: ?>
                    <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                    <h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
                <?php endif; ?>

            </div>
        </div>
        <div class="cbp_widget_box two thirds double-padded">

            <?php
            $defaults = array(
                'theme_location' => 'primary-menu',
                'menu_class'     => 'sf-menu ch-menu-main',
                'depth'          => 3
            );
            ?>

            <div id="cbp-menu-main" class="pull-right cbp_widget_menu">
                <?php wp_nav_menu($defaults); ?>
            </div>
            <style>
                @media screen and (max-width:767px) { 
                    #cbp-menu-main { width: 100%; } 
                    .ch-header { display: none; }  
                }
            </style>
            <script>
                
                var cbp_content_builder = cbp_content_builder || {};
                cbp_content_builder.data = cbp_content_builder.data || {};
                
                if (!cbp_content_builder.data.respmenus) {
                    cbp_content_builder.data.respmenus = [];
                }
                                                                                                                                                                                    
                cbp_content_builder.data.respmenus.push({
                    id: 'cbp-menu-main',
                    options: {
                        id: 'cbp-menu-main-respmenu',
                        submenuToggle: {
                            className: 'cbp-respmenu-more',
                            html: '<i class="fa fa-chevron-down"></i>'
                        },
                        logo: {
                            src: '<?php echo $logo; ?>',
                            link: '<?php echo home_url(); ?>'
                        },
                        prependTo: 'body'
                    }
                });    
                                        
            </script>
        </div>
    </div>
</div>
