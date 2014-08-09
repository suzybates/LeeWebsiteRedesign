<?php

/**
 *
 * @author Parmenides <krivinarius@gmail.com>
 */
class CbpWidgetFormElements
{

    public static function text($options)
    {
        $attribs = isset($options['attribs']) && is_array($options['attribs']) ? self::setAttribs($options['attribs']) : '';
        ?>
        <div class="row cbp_form_element_wrapper">
            <div class="half padded border-top">
                <input type="text" id="<?php echo $options['name']; ?>" name="<?php echo $options['name']; ?>" value="<?php echo $options['value']; ?>"  <?php echo $attribs; ?>/>
            </div>
            <div class="half padded border-top">
                <?php if (isset($options['description_title'])): ?>
                    <label for="<?php echo $options['name']; ?>"><?php echo $options['description_title']; ?></label>
                <?php endif; ?>
                <?php if (isset($options['description_body'])): ?>
                    <p>
                        <?php echo $options['description_body']; ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    public static function textarea($options)
    {
        $attribs = isset($options['attribs']) && is_array($options['attribs']) ? self::setAttribs($options['attribs']) : '';
        ?>
        <div class="row cbp_form_element_wrapper">
            <div class="half padded border-top">
                <textarea id="<?php echo $options['name']; ?>" name="<?php echo $options['name']; ?>" <?php echo $attribs; ?>><?php echo CbpWidgets::strip(stripslashes($options['value'])); ?></textarea>
            </div>
            <div class="half padded border-top">
                <?php if (isset($options['description_title'])): ?>
                    <label for="<?php echo $options['name']; ?>"><?php echo $options['description_title']; ?></label>
                <?php endif; ?>
                <?php if (isset($options['description_body'])): ?>
                    <p>
                        <?php echo $options['description_body']; ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    public static function select($options)
    {
        $attribs = isset($options['attribs']) && is_array($options['attribs']) ? self::setAttribs($options['attribs']) : '';
        ?>
        <div class="row cbp_form_element_wrapper">
            <div class="half padded border-top">
                <select name="<?php echo $options['name']; ?>" id="<?php echo $options['name']; ?>" <?php echo $attribs; ?>>
                    <?php foreach ($options['options'] as $val => $displayName): ?>
                        <option <?php echo CbpUtils::maybeSelected($val == $options['value']); ?> value="<?php echo $val; ?>"><?php echo $displayName; ?></option>
                    <?php endforeach; ?>
                </select>            
            </div>
            <div class="half padded border-top">
                <?php if (isset($options['description_title'])): ?>
                    <label for="<?php echo $options['name']; ?>"><?php echo $options['description_title']; ?></label>
                <?php endif; ?>
                <?php if (isset($options['description_body'])): ?>
                    <p>
                        <?php echo $options['description_body']; ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    public static function selectPost($options)
    {
    
        $attribs     = isset($options['attribs']) && is_array($options['attribs']) ? self::setAttribs($options['attribs']) : '';
        $posts_array = get_posts(array(
       		'post_type'      => 'newsletter_item',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC'
                ));
        ?>
        <div class="row cbp_form_element_wrapper">
            <div class="half padded border-top">
                <?php global $post; ?>
                <select name="<?php echo $options['name'] ?>" id="<?php echo $options['name'] ?>" <?php echo $attribs; ?>>

                    <?php foreach ($posts_array as $post): ?>
                        <option <?php echo CbpUtils::maybeSelected($post->ID == $options['value']); ?> value="<?php echo $post->ID; ?>" title="<?php echo get_the_title(); ?>"><?php echo get_the_title(); ?></option>

                    <?php endforeach; ?>

                </select>
            </div>
            <div class="half padded border-top">
                <?php if (isset($options['description_title'])): ?>
                    <label for="<?php echo $options['name']; ?>"><?php echo $options['description_title']; ?></label>
                <?php endif; ?>
                <?php if (isset($options['description_body'])): ?>
                    <p>
                        <?php echo $options['description_body']; ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
    public static function selectNewsletterPost($options)
    {
    
        $attribs     = isset($options['attribs']) && is_array($options['attribs']) ? self::setAttribs($options['attribs']) : '';
        $posts_array = get_posts(array(
       		'post_type'      => 'newsletter_item',
            'posts_per_page' => -1,
            'orderby'        => 'post_date',
            'order'          => 'ASC'
                ));
        ?>
        <div class="row cbp_form_element_wrapper">
            <div class="half padded border-top">
                <?php global $post; ?>
                <select name="<?php echo $options['name'] ?>" id="<?php echo $options['name'] ?>" <?php echo $attribs; ?>>

                    <?php foreach ($posts_array as $post): ?>
                        <option <?php echo CbpUtils::maybeSelected($post->ID == $options['value']); ?> value="<?php echo $post->ID; ?>" title="<?php echo get_the_title(); ?>"><?php echo get_the_date(); ?>-<?php echo get_the_title(); ?></option>

                    <?php endforeach; ?>

                </select>
            </div>
            <div class="half padded border-top">
                <?php if (isset($options['description_title'])): ?>
                    <label for="<?php echo $options['name']; ?>"><?php echo $options['description_title']; ?></label>
                <?php endif; ?>
                <?php if (isset($options['description_body'])): ?>
                    <p>
                        <?php echo $options['description_body']; ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    public static function selectPage($options)
    {
        $attribs = isset($options['attribs']) && is_array($options['attribs']) ? self::setAttribs($options['attribs']) : '';
        ?>
        <div class="row cbp_form_element_wrapper">
            <div class="half padded border-top">
                <select name="<?php echo $options['name'] ?>" id="<?php echo $options['name'] ?>" <?php echo $attribs; ?>>
                    <?php $pages   = get_pages(); ?>
                    <?php foreach ($pages as $page): ?>
                        <option <?php echo CbpUtils::maybeSelected($page->ID == $options['value']); ?> value="<?php echo $page->ID; ?>" title="<?php echo $page->post_title; ?>"><?php echo $page->post_title; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="half padded border-top">
                <?php if (isset($options['description_title'])): ?>
                    <label for="<?php echo $options['name']; ?>"><?php echo $options['description_title']; ?></label>
                <?php endif; ?>
                <?php if (isset($options['description_body'])): ?>
                    <p>
                        <?php echo $options['description_body']; ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    public static function selectCategories($options)
    {
        $attribs = isset($options['attribs']) && is_array($options['attribs']) ? self::setAttribs($options['attribs']) : '';
        ?>
        <div class="row cbp_form_element_wrapper">
            <div class="half padded border-top">
                <?php $cats    = get_categories(); ?>
                <select name="<?php echo $options['name']; ?>" multiple="multiple" id="<?php echo $options['name']; ?>" <?php echo $attribs; ?>>
                    <?php foreach ($cats as $category): ?>
                        <option <?php echo CbpUtils::maybeSelected(in_array($category->cat_ID, $options['value'])); ?> value="<?php echo $category->cat_ID; ?>" ><?php echo $category->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="half padded border-top">
                <?php if (isset($options['description_title'])): ?>
                    <label for="<?php echo $options['name']; ?>"><?php echo $options['description_title']; ?></label>
                <?php endif; ?>
                <?php if (isset($options['description_body'])): ?>
                    <p>
                        <?php echo $options['description_body']; ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
    
    public static function selectTaxonomy($options)
    {
        $args = isset($options['args']) ? $options['args'] : array();
        $taxonomies        = get_taxonomies($args, 'objects');
        $attribs = isset($options['attribs']) && is_array($options['attribs']) ? self::setAttribs($options['attribs']) : '';
        ?>
        <div class="row cbp_form_element_wrapper">
            <div class="half padded border-top">
                <?php $cats    = get_categories(); ?>
                <select name="<?php echo $options['name']; ?>" id="<?php echo $options['name']; ?>" <?php echo $attribs; ?>>
                    <?php foreach ($taxonomies as $taxonomy): ?>
                        <option <?php echo CbpUtils::maybeSelected($taxonomy->name == $options['value']); ?> value="<?php echo $taxonomy->name; ?>" ><?php echo $taxonomy->labels->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="half padded border-top">
                <?php if (isset($options['description_title'])): ?>
                    <label for="<?php echo $options['name']; ?>"><?php echo $options['description_title']; ?></label>
                <?php endif; ?>
                <?php if (isset($options['description_body'])): ?>
                    <p>
                        <?php echo $options['description_body']; ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    public static function selectEventCategory($options)
    {
        $args = isset($options['args']) ? $options['args'] : array();
       
        $attribs = isset($options['attribs']) && is_array($options['attribs']) ? self::setAttribs($options['attribs']) : '';
        ?>
        <div class="row cbp_form_element_wrapper">
            <div class="half padded border-top">
                <?php $cats    = eme_get_categories(); ?>
                <select name="<?php echo $options['name']; ?>" multiple="multiple" id="<?php echo $options['name']; ?>" <?php echo $attribs; ?>>
                    <?php foreach ($cats as $category): ?>
                        <option <?php echo CbpUtils::maybeSelected($category['category_id'] == $options['value']); ?> value="<?php echo $category['category_id']; ?>" ><?php echo $category['category_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="half padded border-top">
                <?php if (isset($options['description_title'])): ?>
                    <label for="<?php echo $options['name']; ?>"><?php echo $options['description_title']; ?></label>
                <?php endif; ?>
                <?php if (isset($options['description_body'])): ?>
                    <p>
                        <?php echo $options['description_body']; ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
    
    public static function selectTemplate($options)
    {
        $attribs = isset($options['attribs']) && is_array($options['attribs']) ? self::setAttribs($options['attribs']) : '';
        $templates = get_posts(array('post_type' => 'templates', 'posts_per_page' => -1,'orderby' => 'title','order' => 'ASC'));
        ?>

        <div class="row cbp_form_element_wrapper">
            <div class="half padded border-top">
                <?php $cats    = get_categories(); ?>
                <select name="<?php echo $options['name']; ?>" id="<?php echo $options['name']; ?>" <?php echo $attribs; ?>>
                    <?php foreach ($templates as $template): ?>
                        <option <?php echo CbpUtils::maybeSelected($template->ID == $options['value']); ?> value="<?php echo $template->ID; ?>" ><?php echo $template->post_title; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="half padded border-top">
                <?php if (isset($options['description_title'])): ?>
                    <label for="<?php echo $options['name']; ?>"><?php echo $options['description_title']; ?></label>
                <?php endif; ?>
                <?php if (isset($options['description_body'])): ?>
                    <p>
                        <?php echo $options['description_body']; ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    public static function tinyMce($options)
    {
        $attribs = isset($options['attribs']) && is_array($options['attribs']) ? self::setAttribs($options['attribs']) : '';
        ?>
        <div class="row padded border-top cbp_form_element_wrapper">
            <?php if (isset($options['description_title'])): ?>
                <label for="<?php echo $options['name']; ?>"><?php echo $options['description_title']; ?></label>
                <br />
            <?php endif; ?>
            <?php if (isset($options['description_body'])): ?>
                <p>
                    <?php echo $options['description_body']; ?>
                </p>
            <?php endif; ?>
            <?php CbpEditor::renderEditor(CbpWidgets::cleanAttributes($options['value']), $options['name']); ?>

        </div>
        <?php
    }

    public static function colorPicker($options)
    {
        $attribs = isset($options['attribs']) && is_array($options['attribs']) ? self::setAttribs($options['attribs']) : '';

        $alpha = isset($options['alpha']) && $options['alpha'] ? $options['alpha'] : false;
        $value = isset($options['value']) && $options['value'] ? $options['value'] : '#ffffff';
        ?>
        <div class="row cbp_form_element_wrapper">
            <div class="half padded border-top">
                <input type="text" name="<?php echo $options['name']; ?>" id="<?php echo $options['name']; ?>" <?php echo $attribs; ?>/>
            </div>
            <div class="half padded border-top">
                <?php if (isset($options['description_title'])): ?>
                    <label for="<?php echo $options['name']; ?>"><?php echo $options['description_title']; ?></label>
                <?php endif; ?>
                <?php if (isset($options['description_body'])): ?>
                    <p>
                        <?php echo $options['description_body']; ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>


        <script type="text/javascript">
            //<![CDATA[
            jQuery(document).ready(function($)
            {     
                                                                                                                                                                                                                                                                                                                                                                                                
                $('#<?php echo $options['name']; ?>').spectrum('destroy').spectrum({
                    preferredFormat: "<?php echo $alpha ? 'rgb' : 'hex'; ?>",
                    color: "<?php echo $value; ?>",    
                    showPalette: true,
                    showInput: true,
                    showAlpha: <?php echo $alpha ? 'true' : 'false'; ?>,
                    showInitial: true,
                    palette: [
        <?php if (isset($options['defaults']) && $options['defaults']) echo json_encode($options['defaults']) . ','; ?>
                        //                        cbp_content_builder.colorPresets['default'],
                        //                        cbp_content_builder.colorPresets.user
                    ]
                });

                $("#<?php echo $options['name']; ?>").spectrum("set", "<?php echo $value; ?>");

            });
            //]]>   
        </script>

        <?php
    }

    public static function mediaUploadOld($options)
    {
        // generate unique id to enable multiplee instances
        $id      = uniqid(CbpWidgets::getPrefix() . '-uploader-');
        $attribs = isset($options['attribs']) && is_array($options['attribs']) ? self::setAttribs($options['attribs']) : '';
        ?>
        <div class="cbp_form_element_wrapper">
            <div class="row">
                <div class="half padded border-top">
                    <input id="<?php echo $options['name']; ?>" name="<?php echo $options['name']; ?>" class="upload-field"  type="text" value="<?php echo $options['value']; ?>"  <?php echo $attribs; ?>/>
                </div>
                <div class="half padded border-top">
                    <button class="button upload-button"><?php echo CbpTranslate::translateString('Browse'); ?></button>
                </div>
            </div>
            <div class="row">
                <div class="half padded">
                    <div id="<?php echo $id . '-img-container-element'; ?>" class="image-container"><?php if ($options['value']): ?><img src="<?php echo $options['value']; ?>" alt="cbp-widget-image-image" /><?php endif; ?></div>
                </div>
                <div class="half padded">
                    <?php if (isset($options['description_title'])): ?>
                        <label for="<?php echo $options['name']; ?>"><?php echo $options['description_title']; ?></label>
                    <?php endif; ?>
                    <?php if (isset($options['description_body'])): ?>
                        <p>
                            <?php echo $options['description_body']; ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            (function($){
                cbp_content_builder.data.execScript = function () {

                    var $formfield = null;
                    var $imgContainer, attachment;

                    $('.upload-button').on('click', function(e) {
                        e.preventDefault();

                        var $parent = $(this).parent();

                        $formfield    = $('#<?php echo $options['name']; ?>');
                        $imgContainer = $('#<?php echo $id . '-img-container-element'; ?>');

                        // New 3.5 media uploader
                        if(wp && wp.media) {
                                                                                                
                                                                                                
                            // Media Library params
                            var frame = wp.media({
                                title : 'Pick an imag',
                                multiple : false,
                                library : { type : 'image'},
                                button : { text : 'Insert' }
                            });
                                                                                                
                                                                                                
                            // Runs on select
                            frame.on('select',function() {
                                                                                                                                
                                // Get the attachment details
                                attachment = frame.state().get('selection').first().toJSON();
                                                                                                                                
                                // Set image URL
                                $formfield.val( attachment['url'] );
                                $imgContainer.html('<img src="'+attachment['url']+'">');
                                                                                                
                            });
                                                                                                
                            // Open ML
                            frame.open();
                                                                                                
                        } else {
                                                                                                                                        
                            tb_show('', 'media-upload.php?type=image&TB_iframe=true');
                            window.original_send_to_editor = window.send_to_editor;
                            window.send_to_editor = function(html) {

                                var imgurl;

                                if ($formfield) {
                                    imgurl = $('img',html).attr('src');
                                    $formfield.val(imgurl);
                                    $imgContainer.html(html);
                                    tb_remove();
                                }
                                else {
                                    window.original_send_to_editor(html);
                                }
                                $formfield = null;
                            }
                        }

                    });

                };
                cbp_content_builder.data.execScript();
            })(jQuery);
        </script>
        <?php
    }

    public static function mediaUpload($options)
    {
        // generate unique id to enable multiple instances
        $id      = uniqid(CbpWidgets::getPrefix() . '-uploader-');
        $attribs = isset($options['attribs']) && is_array($options['attribs']) ? self::setAttribs($options['attribs']) : '';
        ?>
        <div class="cbp_form_element_wrapper">
            <div class="row">
                <div class="half padded border-top">
                    <input id="<?php echo $options['name']; ?>-hidden" name="<?php echo $options['name']; ?>" class="upload-field-hidden"  type="text" value="<?php echo $options['value']; ?>"/>
                    <input id="<?php echo $options['name']; ?>" class="upload-field"  type="text"  <?php echo $attribs; ?>/>
                </div>
                <div class="half padded border-top">
                    <button id="<?php echo $id . '-upload-button'; ?>" class="button upload-button"><?php echo CbpTranslate::translateString('Browse'); ?></button>
                    <button id="<?php echo $id . '-remove-button'; ?>" class="button remove-button"><?php echo CbpTranslate::translateString('Remove'); ?></button>
                </div>
            </div>
            <div class="row">
                <div class="half padded">
                    <div id="<?php echo $id . '-img-container-element'; ?>" class="image-container"></div>
                </div>
                <div class="half padded">
                    <?php if (isset($options['description_title'])): ?>
                        <label for="<?php echo $options['name']; ?>"><?php echo $options['description_title']; ?></label>
                    <?php endif; ?>
                    <?php if (isset($options['description_body'])): ?>
                        <p>
                            <?php echo $options['description_body']; ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row">
                <div class="half padded">
                    <select id="<?php echo $options['name']; ?>-thumb-dimensions"></select>
                </div>
                <div class="half padded">
                    <label><?php echo CbpTranslate::translateString('Select Thumb Dimensions'); ?></label>
                </div>

            </div>
        </div>
        <script type="text/javascript">
            (function($){
                cbp_content_builder.data.execScript = function () {

                    var $imgContainer, attachment;
                    var $uploadButton          = $('#<?php echo $id . '-upload-button'; ?>');
                    var $removeButton          = $('#<?php echo $id . '-remove-button'; ?>');
                    var $thumbDimensionsSelect = $('#<?php echo $options['name']; ?>-thumb-dimensions');
                    var $formfield             = $('#<?php echo $options['name']; ?>');
                    var $formfieldHidden       = $('#<?php echo $options['name']; ?>-hidden');
                    var $imgContainer          = $('#<?php echo $id . '-img-container-element'; ?>');

                    (function init() {

                        var parts              = $formfieldHidden.val().split('|');
                        var attachmentId       = parts[0];
                        var fullUrl            = parts[1];
                        var thumbUrl           = parts[2];
                        var thumbRegisteredKey = parts[3] || false;
        
                        // prior to v1.2
                        var isLegacy     = thumbRegisteredKey ? false : true;
                        
                                                
                        if (attachmentId) {

                            $.ajax({
                                url: ajaxurl,
                                type: 'POST',
                                data: {
                                    action: 'cbpGetAttachmentImg',
                                    id: attachmentId
                                },
                                dataType: 'json',
                                beforeSend: function() {
                                    $('body').append('<div id="cbp-form-loader"></div>')
                                }
                            }).done(function(response) {
                                $('#cbp-form-loader').remove();
                                if (response) {
                                            
                                    attachment = response;
                                    if (thumbUrl) {
                                        setValues(attachment, thumbUrl, thumbRegisteredKey);
                                    }
                                    if (fullUrl) {
                                        $formfield.val(fullUrl);
                                    }
                                }
                            }).fail(function() {
                                alert( "error" );
                            });
                                            
                        }
                                                
                    })();
                                            
                    $thumbDimensionsSelect.on('change', function () {
                        var selectVal = $(this).val();
                        var parts     = selectVal.split('|');
                        
                        // check if is prior v1.2
                        var imgUrl = parts[0] || false;
                        var key    = parts[1] || false;
                        var isLegacy = !key;
                        
                        if (isLegacy) {
                            setValues(attachment, selectVal);
                        } else {
                            // as of v1.2
                            setValues(attachment, imgUrl, key);
                        }
                    });
                    
                    $removeButton.on('click', function(e) {
                        e.preventDefault();
                        
                        if (confirm('This action will remove the image!')) {
                            reset();
                        }
                    });

                    $uploadButton.on('click', function(e) {
                        e.preventDefault();

//                        var $parent = $(this).parent();

                        // New 3.5 media uploader
                        if(wp && wp.media) {
                                                                                                
                            // Media Library params
                            var frame = wp.media({
                                title : 'Pick an image',
                                multiple : false,
                                library : { type : 'image'},
                                button : { text : 'Insert' }
                            });
                                                                                                
                            // Runs on select
                            frame.on('select',function() {
                                                                                                                                
                                // Get the attachment details
                                attachment = frame.state().get('selection').first().toJSON();
                                                                                             
                                setValues(attachment, attachment.url, 'full');
                                                                                             
                                // Set image URL
                                $formfield.val(attachment.url);
                                //                                $imgContainer.html('<img src="'+attachment['url']+'">');
                                                                                                
                            });
                                                                                                
                            // Open ML
                            frame.open();
                                                                                                
//                        } else {
//                                                                                                                                        
//                            tb_show('', 'media-upload.php?type=image&TB_iframe=true');
//                            window.original_send_to_editor = window.send_to_editor;
//                            window.send_to_editor = function(html) {
//
//                                var imgurl;
//
//                                if ($formfield) {
//                                    imgurl = $('img',html).attr('src');
//                                    $formfield.val(imgurl);
//                                    $imgContainer.html(html);
//                                    tb_remove();
//                                }
//                                else {
//                                    window.original_send_to_editor(html);
//                                }
//                                $formfield = null;
//                            }
                        } // endif

                    }); // end on click
                          
                    function reset() {
                        $formfieldHidden.val('');
                        $formfield.val('');
                        $imgContainer.html('');
                        $thumbDimensionsSelect.html('');
                    }
                          
                    function setValues(attachment, imgUrl, key) {
                                                
                        var key = key || false;
                        
                        if (attachment && attachment.id) { // is attachment object
                            $thumbDimensionsSelect.html(getOptionsHtml(attachment.sizes, imgUrl, key)); // select it if it matches "full""
                            var preSelectedimgUrl = $thumbDimensionsSelect.val();
                            
                            var parts     = preSelectedimgUrl.split('|');
                            // check if is prior v1.2
                            var imgUrl = parts[0] || false;
                            var key    = parts[1] || false;
                            var isLegacy = !key;
                            
                            var key = isLegacy ? '|' + key : '';
                            var concatData = attachment.id + '|' + attachment.url + '|' + preSelectedimgUrl + key;
                                                
                            $formfieldHidden.val(concatData);
                            
                            if (isLegacy) {
                                $imgContainer.html('<img src="' + preSelectedimgUrl + '">');
                            } else {
                                $imgContainer.html('<img src="' + imgUrl + '">');
                            }
                        } else if (typeof attachment === 'string') { // is string id
                                                    
                        }
                                                
                    }
                                            
                    function getOptionsHtml(attachmentSizesObj, imgUrl, registeredKey) {
                        var markup = '';
                        var imgUrl = imgUrl || '';
                        var name = '';
                        
                        $.each(attachmentSizesObj, function (key, obj) {
                            name = key + ' - ' + obj.width + 'x' + obj.height;
                            
                            if (registeredKey) {
                                markup += optionMarkup(name, obj.url + '|' + key, key === registeredKey);
                            // is prior v1.2
                            } else {
                                markup += optionMarkup(name, obj.url, obj.url === imgUrl);
                            }
                        });
                        return markup;
                    }
                    function optionMarkup(name, value, isSelected) {
                        var selected = isSelected ? 'selected="selected"' : '';
                        return '<option value="' + value + '" ' + selected + '>' + name + '</option>'
                    }

                };
                cbp_content_builder.data.execScript();
            })(jQuery);
        </script>
        <?php
    }

    public static function iconSelect($options)
    {
        $attribs = isset($options['attribs']) && is_array($options['attribs']) ? self::setAttribs($options['attribs']) : '';
        $pattern = '/\.(fa-(?:\w+(?:-)?)+):before\s+{\s*content:\s*"(.+)";\s+}/';
        $subject = file_get_contents(CBP_BACKEND_ROOT . '/public/css/font-awesome.css');

        preg_match_all($pattern, $subject, $matches, PREG_SET_ORDER);

        $icons = array();

        foreach ($matches as $match) {
            $icons[$match[1]] = $match[2];
        }

//        $icons = var_export($icons, TRUE);
//        $icons = stripslashes($icons);
//        print_r($icons);
        ?>
        <div class="cbp_form_element_wrapper">

            <div class="row">
                <div class="half padded border-top">
                    <div class="row padded">
                        <i id="<?php echo $options['name']; ?>-display-icon" class="fa <?php echo $options['value']; ?> fa-3x"></i>
                    </div>
                    <input type="text" name="<?php echo $options['name']; ?>" id="<?php echo $options['name']; ?>" value="<?php echo $options['value']; ?>"/>

                </div>
                <div class="half padded border-top">
                    <?php if (isset($options['description_title'])): ?>
                        <label for="<?php echo $options['name']; ?>"><?php echo $options['description_title']; ?></label>
                    <?php endif; ?>
                    <?php if (isset($options['description_body'])): ?>
                        <p>
                            <?php echo $options['description_body']; ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row padded">
                <ul class="cbp-font-awesome-icon-select">

                    <?php foreach ($icons as $key => $value): ?>
                        <?php $selectedClass = $key == $options['value'] ? ' class="cbp-icon-selected"' : ''; ?>
                        <li<?php echo $selectedClass; ?> data-name="<?php echo $key; ?>">
                            <i class="fa <?php echo $key; ?> fa-2x"></i>
                        </li>    
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <script>
            (function($){
                                                                                                                                                                                                                                
                $('.cbp-font-awesome-icon-select li').on('click', function () {
                                                                                                                                                                                                                            
                    var $this              = $(this);
                    var name               = $this.data('name');
                    var $selectedIconInput = $('#<?php echo $options['name']; ?>');
                    var $displayIcon       = $('#<?php echo $options['name']; ?>-display-icon');
                                                                                                                                                                                                                                    
                    $this.addClass('cbp-icon-selected').siblings().removeClass('cbp-icon-selected');
                    $selectedIconInput.val(name); 
                    $displayIcon.attr('class', '').addClass('fa ' + name + ' fa-3x');
                                                                                                                                                                                                                                    
                });
                                                                                                                                                                                                                                
            })(jQuery);
        </script>
        <?php
    }

    public static function selectRegiseredImageSizes($options)
    {
        $attribs = isset($options['attribs']) && is_array($options['attribs']) ? self::setAttribs($options['attribs']) : '';
        ?>

        <div class="row cbp_form_element_wrapper">
            <div class="half padded border-top">
                <?php global $_wp_additional_image_sizes; ?>
                <select name="<?php echo $options['name']; ?>" id="<?php echo $options['name']; ?>" <?php echo $attribs; ?>>
                    <option <?php echo CbpUtils::maybeSelected('thumbnail' == $options['value']); ?> value="thumbnail"><?php echo CbpTranslate::translateString('thumbnail') ?></option>
                    <?php if ($_wp_additional_image_sizes): ?> 
                        <?php foreach ($_wp_additional_image_sizes as $imageSizeName => $image_size): ?>
                            <option <?php echo CbpUtils::maybeSelected($imageSizeName == $options['value']); ?> value="<?php echo $imageSizeName; ?>" ><?php echo $imageSizeName . ': ' . $image_size['width'] . 'px, ' . $image_size['height'] . 'px'; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <option <?php echo CbpUtils::maybeSelected('full-width' == $options['value']); ?> value="full-width"><?php echo CbpTranslate::translateString('full width') ?></option>
                </select>
            </div>
            <div class="half padded border-top">
                <?php if (isset($options['description_title'])): ?>
                    <label for="<?php echo $options['name']; ?>"><?php echo $options['description_title']; ?></label>
                <?php endif; ?>
                <?php if (isset($options['description_body'])): ?>
                    <p>
                        <?php echo $options['description_body']; ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <?php
    }

    public static function subwidgetItems($options)
    {
        ?>
        <?php if (isset($options['use_tags']) && $options['use_tags']): ?>
            <textarea style="display:none;" type="text" id="<?php echo CbpWidgets::getPrefix(); ?>_tags" name="<?php echo CbpWidgets::getPrefix(); ?>_tags"><?php echo isset($options['tags']) ? CbpWidgets::strip(stripslashes($options['tags'])) : ''; ?></textarea>
        <?php endif; ?>
        <textarea name="<?php echo CbpWidgets::getPrefix(); ?>_content" id="<?php echo CbpWidgets::getPrefix(); ?>-items-content"><?php echo CbpWidgets::strip(stripslashes($options['value'])); ?></textarea>
        <div class="item-builder-container border-top">
            <div id="create-item"></div>
            <div id="items-container"></div>
            <form id="item-form"></form>
            <form id="save-all-form"></form>
        </div>

        <!--Item Builder-->
        <script type="text/template" id="item_template">
            <span class="item-delete"><i class="fa fa-trash-o"></i></span>
            <span class="item-edit"><i class="fa fa-edit fa-2x"></i></span>
            <span class="item-title"><#= model.name #></span>
            <# if (model.img_src) { #>
            <p class="item-image"><img src="<#= model.img_src.split('|')[2] #>" /></p>
            <# } #>
        </script>
        <!--**************************************************-->
        <script type="text/template" id="create_item_template">
            <div class="row cbp_form_element_wrapper">
                <div class="half padded border-top">
                    <input type="text" id="new-item-name" data-type="<?php echo isset($options['type']) ? $options['type'] : ''; ?>"/>
                </div>
                <div class="half padded border-top">
                    <button id="add-item" class="button"><?php echo CbpTranslate::translateString('Add New Item') ?></button> 
                </div>
            </div>
        </script>
        <!--**************************************************-->
        <script>
            var cbp_content_builder = cbp_content_builder || {};
            cbp_content_builder.data = cbp_content_builder.data || {};
            cbp_content_builder.data.temp = cbp_content_builder.data.temp || {};
            cbp_content_builder.data.temp.widget_element_items = cbp_content_builder.data.temp.widget_element_items || {};
            cbp_content_builder.data.temp.widget_element_items.subwidget_id = '<?php echo isset($options['subwidget_id']) ? $options['subwidget_id'] : ''; ?>';
            cbp_content_builder.data.temp.widget_element_items.enable_tags = '<?php echo isset($options['use_tags']) ? $options['use_tags'] : ''; ?>';
        </script>
        <!--**************************************************-->
        <script type="text/template" id="save_all_form_template">
            <input id="save-all" type="submit" class="button button-primary button-large" value="Save All Items" <# if (!cbp_content_builder.data.temp.widget_element_items.use_save_all_item ) { #> style="display:none;"<# } #>>
            <p <# if (!cbp_content_builder.data.temp.widget_element_items.use_save_all_item ) { #> style="display:none;"<# } #>><?php echo CbpTranslate::translateString('If you have created new item or have made any changes to any of the items you must press "Save All" to save them and then press "Save" to save all settings for this widget!'); ?></p>
        </script>
        <script src="<?php echo CBP_BACKEND_URI . '/models/widgets/js/item-builder.min.js'; ?>"></script>
        <?php
    }

    public static function setAttrib($name, $value)
    {
        $out  = '';
        $name = (string) $name;
        if ('_' == $name[0]) {
            throw new Exception(sprintf('Invalid attribute "%s"; must not contain a leading underscore', $name));
        }


        if ($value) {
            $out .= $name . '="' . $value . '"';
        }
        return $out;
    }

    public static function setAttribs(array $attribs)
    {
        $out = '';
        foreach ($attribs as $key => $value) {
            $out .= self::setAttrib($key, $value) . ' ';
        }

        return $out;
    }
}
