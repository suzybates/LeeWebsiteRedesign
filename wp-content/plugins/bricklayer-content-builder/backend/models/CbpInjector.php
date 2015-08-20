<?php
/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class CbpInjector
{
    private $_sanitizer;

    public function __construct()
    {
        $this->_sanitizer = new CbpSanitizer();
    }

    public function inject($what, $where = array())
    {
        foreach ($where as $value) {
            $hook = $value == 'backend' ? 'admin_head' : 'wp_head';
            add_action($hook, array($this, $what . 'Callback'));
        }
    }
    
    public function customCssCallback()
    {
        $customCss = CbpUtils::getOption('custom_css');
        if ($customCss) echo '<style>' . $customCss . '</style>' . "\n";
    }
    
    public function backendJsGlobalObjectCallback()
    {
        ?>
        <script>
            var cbp_content_builder = cbp_content_builder ||
                { 
                prefix: "<?php echo CBP_APP_PREFIX; ?>",
                pluginName: "<?php echo CBP_APP_NAME; ?>",
                data: {
                    widgets:<?php echo json_encode(CbpWidgets::getRegisteredWidgets()); ?>,
                    temp: {
                        widget_element_items: {
                            use_save_all_item: <?php echo json_encode($this->getUseSaveAllItems()); ?>
                        }
                    },
                    bl_is_enabled: <?php echo json_encode($this->getBricklayerIsEnabled()); ?>,
                    helpers: {},
                    widthClasses: <?php echo json_encode(CbpWidgets::getWidthClassesKeys()); ?>,
                    widthClassesObj: <?php echo json_encode(CbpWidgets::getWidthClasses()); ?>
                }
            };
        </script>
        <?php
    }
    
    public function frontJsGlobalObjectCallback()
    {
        ?>
        <script>
            var cbp_content_builder = cbp_content_builder ||
                { 
                prefix: "<?php echo CBP_APP_PREFIX; ?>",
                pluginName: "<?php echo CBP_APP_NAME; ?>",
                data: {
                    temp: {},
                    helpers: {},
                    useScrollToTop: <?php echo (int) CbpUtils::getOption('use_scroll_to_top'); ?>,
                    scrollToTopText: '<?php echo CbpUtils::getOption('scroll_to_top_text'); ?>'
                }
            };
        </script>
        <?php
    }
    
    public function getUseSaveAllItems()
    {
        return apply_filters('cbp_form_items_use_save_all', true);
    }

    public function getBricklayerIsEnabled()
    {
        global $post;
        
        if ($post) {
            return (int) CbpUtils::getMeta($post->ID, 'bricklayer_enabled');
        }
        return false;
    }

}
