<?php
if (!class_exists('CbpWidgetMap')):

    class CbpWidgetMap extends CbpWidget
    {

        private static $_isFirstRun = true;

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_map',
                    /* Name */ 'Map', array('description' => 'This is a Map brick', 'icon'        => 'fa fa-map-marker fa-3x'));
        }

        public function registerFormElements($elements)
        {
            $elements['marker_lat'] = '48.85782582717226';
            $elements['marker_lng'] = '2.2945117950439453';
            $elements['zoom_level'] = '3';
            $elements['content']    = '';
            $elements['height']     = '400';
            $elements['img_src']    = '';

            return parent::registerFormElements($elements);
        }

        public function form($instance)
        {
            parent::form($instance);
            ?>
            <div class="row padded">
                <div id="map" style="height: 450px"></div>
            </div>
            <?php
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('marker_lat'),
                'value'             => $instance['marker_lat'],
                'description_title' => $this->translate('Marker Lat'),
            ));

            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('marker_lng'),
                'value'             => $instance['marker_lng'],
                'description_title' => $this->translate('Marker Lng'),
            ));

            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('zoom_level'),
                'value'             => $instance['zoom_level'],
                'description_title' => $this->translate('Zoom Level'),
            ));
            CbpWidgetFormElements::text(array(
                'name'              => $this->getIdString('height'),
                'value'             => $instance['height'],
                'description_title' => $this->translate('Height'),
            ));

            CbpWidgetFormElements::mediaUpload(array(
                'name'              => $this->getIdString('img_src'),
                'value'             => $instance['img_src'],
                'description_title' => $this->translate('Custom Marker'),
            ));

            CbpWidgetFormElements::tinyMce(array(
                'name'              => $this->getIdString('content'),
                'value'             => $instance['content'],
                'description_title' => $this->translate('Content'),
            ));
            ?>

            <script src="<?php echo CBP_BACKEND_URI . '/models/widgets/js/gmaps.js'; ?>"></script>
            <script type="text/javascript">
                                                                                                                                                                                                                                                                                
                var content = jQuery('#<?php echo $this->getIdString('content'); ?>').val() ? jQuery('#<?php echo $this->getIdString('content'); ?>').val() : '';
                var markerLat = '<?php echo $instance['marker_lat']; ?>';
                var markerLng = '<?php echo $instance['marker_lng']; ?>';
                                                                                                                                                                                                                                                                                            
                var map = new GMaps({
                    div: '#map',
                    lat: markerLat,
                    lng: markerLng,
                    zoom: parseInt('<?php echo $instance['zoom_level']; ?>'),
                    zoom_changed: function () {

                        jQuery('#<?php echo $this->getIdString('zoom_level'); ?>').val(this.zoom);
                    }
                });
                                                                                                                                                                                       
                if (markerLat && markerLng) {
                                                                                                                                    
                    var markerOptions = {
                        lat: markerLat,
                        lng: markerLng,
                        infoWindow: {
                            content : content
                        }
                    }
                                                                                                                                    
                <?php if ($instance['img_src'] && $image              = CbpWidgets::parseImageDetails($instance['img_src'])): ?>
                    var customMarker = new google.maps.MarkerImage();
                    customMarker.url = '<?php echo $image['selected_src']; ?>';
                    markerOptions.icon = customMarker;
                <?php endif; ?> 
                                                                                                                                                        
                    map.addMarker(markerOptions);       
                }
                                                                                                                                                                                                                                                                              
                GMaps.on('click', map.map, function(event) {
                    var lat = event.latLng.lat();
                    var lng = event.latLng.lng();

                    jQuery('#<?php echo $this->getIdString('marker_lat'); ?>').val(lat);
                    jQuery('#<?php echo $this->getIdString('marker_lng'); ?>').val(lng);
                                                                                                                                                                                                                                                                                    
                    tinyMCE.triggerSave();
                    var content = jQuery('#<?php echo $this->getIdString('content'); ?>').val();

                    map.removeMarkers();
                    map.addMarker({
                        lat: lat,
                        lng: lng,
                        infoWindow: {
                            content : content
                        }
                    });
                                                                                                                                                                                                                            
                });

            </script>
            <?php
        }

        public function widget($atts, $content)
        {
            extract(shortcode_atts(array(
                        'type'               => 'cbp_widget_map',
                        'css_class'          => '',
                        'custom_css_classes' => '',
                        'marker_lat'         => '48.85782582717226',
                        'marker_lng'         => '2.2945117950439453',
                        'zoom_level'         => '3',
                        'height'             => '400',
                        'img_src'            => '',
                        'padding'            => '',
                            ), $atts));

            $id                 = uniqid($this->getPrefix() . '-map-');
            $css_class          = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $padding            = CbpWidgets::getCssClass($padding);
            ?>    
            <div class="<?php echo $type; ?>_wrapper">
                <div id="<?php echo $id; ?>" class="<?php echo CbpWidgets::getDefaultWidgetCssClass(); ?> <?php echo $type; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $padding; ?>"style="height: <?php echo $height; ?>px"></div>
            </div>
            <?php if (self::$_isFirstRun): ?> 

                <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
                <script src="<?php echo CBP_BACKEND_URI . '/models/widgets/js/gmaps.js'; ?>"></script>

            <?php endif; ?>
            <?php self::$_isFirstRun = false; ?>
            <script type="text/javascript">
                                                                                                                                                                                                                                                                                
            <?php if ($content): ?>
                    var content = '<?php echo trim(preg_replace('/[\r\n\t ]+/', ' ', wpautop($content))); ?>';
            <?php else: ?>
                    var content = '';
            <?php endif; ?>
                                                                                                                                                                                                                                                                                
                var markerLat = '<?php echo $marker_lat; ?>';
                var markerLng = '<?php echo $marker_lng; ?>';

                var map = new GMaps({
                    div: '#<?php echo $id; ?>',
                    lat: markerLat,
                    lng: markerLng,
                    zoom: parseInt('<?php echo $zoom_level; ?>')
                });

                if (markerLat && markerLng) {
                                                                                                                                    
                    var markerOptions = {
                        lat: markerLat,
                        lng: markerLng,
                        infoWindow: {
                            content : content
                        }
                    }
                                                                                                                                    
                <?php if ($img_src && $image = CbpWidgets::parseImageDetails($img_src)): ?>
                    var customMarker = new google.maps.MarkerImage();
                    customMarker.url = '<?php echo $image['selected_src']; ?>';
                    markerOptions.icon = customMarker;
                <?php endif; ?> 
                                                                                                                                                        
                    map.addMarker(markerOptions);       
                }

            </script>
            <?php
        }

    }

    

    

    

    

    
endif;
