<?php
if (!class_exists('CbpEditor')) :

    /**
     * @author Parmenides <krivinarius@gmail.com>
     */
    class CbpEditor
    {
        public static $mce_locale;
//	private static $mce_settings = array();
//	private static $qt_settings = array();
        private static $plugins = array();
        private static $ext_plugins;
        private static $first_init;
        private static $mce_settings = null;
        private static $qt_settings  = null;
        private static $baseurl;

        public static function init()
        {
            $ajax = new CbpAjax();
            $ajax->setAjaxCallback(array(__CLASS__, 'cbpEditorHtmlAjax'));
            $ajax->run();
        }
        /*
         * AJAX Call Used to Generate the WP Editor
         */

        public static function cbpEditorHtmlAjax()
        {

            // do array merge here with custom settings
            /*
              array(
              'wpautop' => true,
              'media_buttons' => true,
              'textarea_name' => $editor_id,
              'textarea_rows' => 20,
              'tabindex' => '',
              'tabfocus_elements' => ':prev,:next',
              'editor_css' => '',
              'editor_class' => '',
              'teeny' => false,
              'dfw' => false,
              'tinymce' => true,
              'quicktags' => true
              );
             */
            $content = stripslashes($_POST['content']);
            wp_editor($content, $_POST['id'], array(
                'textarea_name' => $_POST['textarea_name']
            ));
            self::editor_settings($_POST['id'], array());
            $mce_init = self::get_mce_init($_POST['id']);
            $qt_init  = self::get_qt_init($_POST['id']);
            ?>
            <script type="text/javascript">
                tinyMCEPreInit.mceInit = jQuery.extend( tinyMCEPreInit.mceInit, <?php echo $mce_init ?>);
                tinyMCEPreInit.qtInit = jQuery.extend( tinyMCEPreInit.qtInit, <?php echo $qt_init ?>);
            </script>
            <?php
            die();
        }

        public static function renderEditor($content, $editor_id, $settings = array())
        {

            $set = wp_parse_args($settings, array(
                'wpautop'           => true, // use wpautop?
                'media_buttons'     => true, // show insert/upload button(s)
                'textarea_name'     => $editor_id, // set the textarea name to something different, square brackets [] can be used here
                'textarea_rows'     => 20,
                'tabindex'          => '',
                'tabfocus_elements' => ':prev,:next', // the previous and next element ID to move the focus to when pressing the Tab key in TinyMCE
                'editor_css'        => '', // intended for extra styles for both visual and Text editors buttons, needs to include the <style> tags, can use "scoped".
                'editor_class'      => '', // add extra class(es) to the editor textarea
                'teeny'             => false, // output the minimal editor config used in Press This
                'dfw'               => false, // replace the default fullscreen with DFW (needs specific DOM elements and css)
                'tinymce'           => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
                'quicktags'         => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
                    ));

//            require_once 'CbpEditorManger.php';
            // had to make unique id to prevent tinymce
            // from reusing the same one, because it only use it once
            $uid = uniqid('_temp_');
            $editor_id = $editor_id . $uid;
            $content = stripslashes($content);
            wp_editor($content, $editor_id, $set);
//            CbpEditorManger::editor($content, $editor_id, $set);
            self::editor_settings($editor_id, $set);
//            CbpEditorManger::editor_settings($editor_id, $set);
//            CbpEditorManger::editor_js();

            $mce_init = self::get_mce_init($editor_id);
            $qt_init  = self::get_qt_init($editor_id);
            ?>
            <script type="text/javascript">
                tinyMCEPreInit.mceInit = jQuery.extend( tinyMCEPreInit.mceInit, <?php echo $mce_init ?>);
                tinyMCEPreInit.qtInit = jQuery.extend( tinyMCEPreInit.qtInit, <?php echo $qt_init ?>);

                tinyMCE.init(tinyMCEPreInit.mceInit['<?php echo $editor_id; ?>']);

                try {
                    quicktags(tinyMCEPreInit.qtInit['<?php echo $editor_id; ?>']);
                } catch (e) {}
                  
                if ( typeof jQuery !== 'undefined' ) {
				jQuery('.wp-editor-wrap').on( 'click.wp-editor', function() {
					if ( this.id ) {
                                            window.wpActiveEditor = this.id.slice( 3, -5 );
                                    }
                            });
                    }
            </script>
            <?php
        }

        public static function getJsInit($editor_id, $settings = array())
        {

            $set = wp_parse_args($settings, array(
                'wpautop'           => true, // use wpautop?
                'media_buttons'     => true, // show insert/upload button(s)
                'textarea_name'     => $editor_id, // set the textarea name to something different, square brackets [] can be used here
                'textarea_rows'     => 20,
                'tabindex'          => '',
                'tabfocus_elements' => ':prev,:next', // the previous and next element ID to move the focus to when pressing the Tab key in TinyMCE
                'editor_css'        => '', // intended for extra styles for both visual and Text editors buttons, needs to include the <style> tags, can use "scoped".
                'editor_class'      => '', // add extra class(es) to the editor textarea
                'teeny'             => false, // output the minimal editor config used in Press This
                'dfw'               => false, // replace the default fullscreen with DFW (needs specific DOM elements and css)
                'tinymce'           => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
                'quicktags'         => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
                    ));


            self::editor_settings($editor_id, $set);

            $mce_init = self::get_mce_init($editor_id);
            $qt_init  = self::get_qt_init($editor_id);
            ?>
            
              tinyMCEPreInit.mceInit = jQuery.extend( tinyMCEPreInit.mceInit, <?php echo $mce_init ?>);
              tinyMCEPreInit.qtInit = jQuery.extend( tinyMCEPreInit.qtInit, <?php echo $qt_init ?>);

              tinyMCE.init(tinyMCEPreInit.mceInit['<?php echo $editor_id; ?>']);
              try {
              quicktags(tinyMCEPreInit.qtInit['<?php echo $editor_id; ?>']);
              } catch (e) {}
            

            <?php
        }

        public static function quicktags_settings($qtInit, $editor_id)
        {
            self::$qt_settings = $qtInit;
            return $qtInit;
        }

        public static function tiny_mce_before_init($mceInit, $editor_id)
        {
            self::$mce_settings = $mceInit;
            return $mceInit;
        }
        /*
         * Code coppied from _WP_Editors class (modified a little)
         */

        private static function get_qt_init($editor_id)
        {
            $qtInit = '';
            if (!empty(self::$qt_settings)) {
                foreach (self::$qt_settings as $editor_id => $init) {
                    $options = self::_parse_init($init);
                    $qtInit .= "'$editor_id':{$options},";
                }
                $qtInit  = '{' . trim($qtInit, ',') . '}';
            } else {
                $qtInit = '{}';
            }
            return $qtInit;
        }

        private static function get_mce_init($editor_id)
        {
            $mceInit = '';
            if (!empty(self::$mce_settings)) {
                foreach (self::$mce_settings as $editor_id => $init) {
                    $options = self::_parse_init($init);
                    $mceInit .= "'$editor_id':{$options},";
                }
                $mceInit = '{' . trim($mceInit, ',') . '}';
            } else {
                $mceInit = '{}';
            }
            return $mceInit;
        }

        private static function _parse_init($init)
        {
            $options = '';

            foreach ($init as $k => $v) {
                if (is_bool($v)) {
                    $val = $v ? 'true' : 'false';
                    $options .= $k . ':' . $val . ',';
                    continue;
                } elseif (!empty($v) && is_string($v) && ( ('{' == $v{0} && '}' == $v{strlen($v) - 1}) || ('[' == $v{0} && ']' == $v{strlen($v) - 1}) || preg_match('/^\(?function ?\(/', $v) )) {
                    $options .= $k . ':' . $v . ',';
                    continue;
                }
                $options .= $k . ':"' . $v . '",';
            }

            return '{' . trim($options, ' ,') . '}';
        }

        public static function editor_settings($editor_id, $set)
        {
            $first_run = false;

//		if ( empty(self::$first_init) ) {
//			if ( is_admin() ) {
//				add_action( 'admin_print_footer_scripts', array( __CLASS__, 'editor_js'), 50 );
//				add_action( 'admin_footer', array( __CLASS__, 'enqueue_scripts'), 1 );
//			} else {
//				add_action( 'wp_print_footer_scripts', array( __CLASS__, 'editor_js'), 50 );
//				add_action( 'wp_footer', array( __CLASS__, 'enqueue_scripts'), 1 );
//			}
//		}

		if ((bool) $set['quicktags']) {

			$qtInit = array(
				'id' => $editor_id,
				'buttons' => ''
			);

			if ( is_array($set['quicktags']) )
				$qtInit = array_merge($qtInit, $set['quicktags']);

			if ( empty($qtInit['buttons']) )
				$qtInit['buttons'] = 'strong,em,link,block,del,ins,img,ul,ol,li,code,more,close';

			if ( $set['dfw'] )
				$qtInit['buttons'] .= ',fullscreen';

			/**
			 * Filter the Quicktags settings.
			 *
			 * @since 3.3.0
			 *
			 * @param array  $qtInit    Quicktags settings.
			 * @param string $editor_id The unique editor ID, e.g. 'content'.
			 */
			$qtInit = apply_filters( 'quicktags_settings', $qtInit, $editor_id );

			self::$qt_settings[$editor_id] = $qtInit;

//			self::$qt_buttons = array_merge( self::$qt_buttons, explode(',', $qtInit['buttons']) );
		}

		if ((bool) $set['tinymce']) {

			if ( empty( self::$first_init ) ) {
				self::$baseurl = includes_url( 'js/tinymce' );
				$mce_locale = get_locale();

				if ( empty( $mce_locale ) || 'en' == substr( $mce_locale, 0, 2 ) ) {
					$mce_locale = 'en';
				}

				self::$mce_locale = $mce_locale;

				/** This filter is documented in wp-admin/includes/media.php */
				$no_captions = (bool) apply_filters( 'disable_captions', '' );
				$first_run = true;
				$ext_plugins = '';

				if ( $set['teeny'] ) {

					/**
					 * Filter the list of teenyMCE plugins.
					 *
					 * @since 2.7.0
					 *
					 * @param array  $plugins   An array of teenyMCE plugins.
					 * @param string $editor_id Unique editor identifier, e.g. 'content'.
					 */
					self::$plugins = $plugins = apply_filters( 'teeny_mce_plugins', array( 'fullscreen', 'image', 'wordpress', 'wpeditimage', 'wplink' ), $editor_id );
				} else {

					/**
					 * Filter the list of TinyMCE external plugins.
					 *
					 * The filter takes an associative array of external plugins for
					 * TinyMCE in the form 'plugin_name' => 'url'.
					 *
					 * The url should be absolute, and should include the js filename
					 * to be loaded. For example:
					 * 'myplugin' => 'http://mysite.com/wp-content/plugins/myfolder/mce_plugin.js'.
					 *
					 * If the external plugin adds a button, it should be added with
					 * one of the 'mce_buttons' filters.
					 *
					 * @since 2.5.0
					 *
					 * @param array $external_plugins An array of external TinyMCE plugins.
					 */
					$mce_external_plugins = apply_filters( 'mce_external_plugins', array() );

					$plugins = array(
						'charmap',
						'hr',
						'media',
						'paste',
						'tabfocus',
						'textcolor',
						'fullscreen',
						'wordpress',
						'wpeditimage',
						'wpgallery',
						'wplink',
						'wpdialogs',
						'wpview',
					);

//					if ( ! self::$has_medialib ) {
//						$plugins[] = 'image';
//					}

					/**
					 * Filter the list of default TinyMCE plugins.
					 *
					 * The filter specifies which of the default plugins included
					 * in WordPress should be added to the TinyMCE instance.
					 *
					 * @since 3.3.0
					 *
					 * @param array $plugins An array of default TinyMCE plugins.
					 */
					$plugins = array_unique( apply_filters( 'tiny_mce_plugins', $plugins ) );

					if ( ( $key = array_search( 'spellchecker', $plugins ) ) !== false ) {
						// Remove 'spellchecker' from the internal plugins if added with 'tiny_mce_plugins' filter to prevent errors.
						// It can be added with 'mce_external_plugins'.
						unset( $plugins[$key] );
					}

					if ( ! empty( $mce_external_plugins ) ) {

						/**
						 * Filter the translations loaded for external TinyMCE 3.x plugins.
						 *
						 * The filter takes an associative array ('plugin_name' => 'path')
						 * where 'path' is the include path to the file.
						 *
						 * The language file should follow the same format as wp_mce_translation(),
						 * and should define a variable ($strings) that holds all translated strings.
						 *
						 * @since 2.5.0
						 *
						 * @param array $translations Translations for external TinyMCE plugins.
						 */
						$mce_external_languages = apply_filters( 'mce_external_languages', array() );

						$loaded_langs = array();
						$strings = '';

						if ( ! empty( $mce_external_languages ) ) {
							foreach ( $mce_external_languages as $name => $path ) {
								if ( @is_file( $path ) && @is_readable( $path ) ) {
									include_once( $path );
									$ext_plugins .= $strings . "\n";
									$loaded_langs[] = $name;
								}
							}
						}

						foreach ( $mce_external_plugins as $name => $url ) {
							if ( in_array( $name, $plugins, true ) ) {
								unset( $mce_external_plugins[ $name ] );
								continue;
							}

							$url = set_url_scheme( $url );
							$mce_external_plugins[ $name ] = $url;
							$plugurl = dirname( $url );

							if ( in_array( $name, $loaded_langs ) ) {
								$ext_plugins .= 'tinyMCEPreInit.load_ext("' . $plugurl . '", "' . $mce_locale . '");' . "\n";
							}
						}
					}
				}

				if ( $set['dfw'] )
					$plugins[] = 'wpfullscreen';

				self::$plugins = $plugins;
				self::$ext_plugins = $ext_plugins;

				self::$first_init = array(
					'theme' => 'modern',
					'skin' => 'lightgray',
					'language' => self::$mce_locale,
					'resize' => 'vertical',
					'formats' => "{
						alignleft: [
							{selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: {textAlign:'left'}},
							{selector: 'img,table,dl.wp-caption', classes: 'alignleft'}
						],
						aligncenter: [
							{selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: {textAlign:'center'}},
							{selector: 'img,table,dl.wp-caption', classes: 'aligncenter'}
						],
						alignright: [
							{selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: {textAlign:'right'}},
							{selector: 'img,table,dl.wp-caption', classes: 'alignright'}
						],
						strikethrough: {inline: 'del'}
					}",
					'relative_urls' => false,
					'remove_script_host' => false,
					'convert_urls' => false,
					'browser_spellcheck' => true,
					'fix_list_elements' => true,
					'entities' => '38,amp,60,lt,62,gt',
					'entity_encoding' => 'raw',
					'menubar' => false,
					'keep_styles' => false,
					'paste_remove_styles' => true,

					// Limit the preview styles in the menu/toolbar
					'preview_styles' => 'font-family font-size font-weight font-style text-decoration text-transform',

					'wpeditimage_disable_captions' => $no_captions,
					'wpeditimage_html5_captions' => current_theme_supports( 'html5', 'caption' ),
					'plugins' => implode( ',', $plugins ),
				);

				if ( ! empty( $mce_external_plugins ) ) {
					self::$first_init['external_plugins'] = json_encode( $mce_external_plugins );
				}

				$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
				$version = 'ver=' . $GLOBALS['wp_version'];
				$dashicons = includes_url( "css/dashicons$suffix.css?$version" );
				$mediaelement = includes_url( "js/mediaelement/mediaelementplayer.min.css?$version" );
				$wpmediaelement = includes_url( "js/mediaelement/wp-mediaelement.css?$version" );

				// WordPress default stylesheet and dashicons
				$mce_css = array(
					$dashicons,
					$mediaelement,
					$wpmediaelement,
					self::$baseurl . '/skins/wordpress/wp-content.css?' . $version
				);

				// load editor_style.css if the current theme supports it
				if ( ! empty( $GLOBALS['editor_styles'] ) && is_array( $GLOBALS['editor_styles'] ) ) {
					$editor_styles = $GLOBALS['editor_styles'];

					$editor_styles = array_unique( array_filter( $editor_styles ) );
					$style_uri = get_stylesheet_directory_uri();
					$style_dir = get_stylesheet_directory();

					// Support externally referenced styles (like, say, fonts).
					foreach ( $editor_styles as $key => $file ) {
						if ( preg_match( '~^(https?:)?//~', $file ) ) {
							$mce_css[] = esc_url_raw( $file );
							unset( $editor_styles[ $key ] );
						}
					}

					// Look in a parent theme first, that way child theme CSS overrides.
					if ( is_child_theme() ) {
						$template_uri = get_template_directory_uri();
						$template_dir = get_template_directory();

						foreach ( $editor_styles as $key => $file ) {
							if ( $file && file_exists( "$template_dir/$file" ) )
								$mce_css[] = "$template_uri/$file";
						}
					}

					foreach ( $editor_styles as $file ) {
						if ( $file && file_exists( "$style_dir/$file" ) )
							$mce_css[] = "$style_uri/$file";
					}
				}

				/**
				 * Filter the comma-delimited list of stylesheets to load in TinyMCE.
				 *
				 * @since 2.1.0
				 *
				 * @param array $stylesheets Comma-delimited list of stylesheets.
				 */
				$mce_css = trim( apply_filters( 'mce_css', implode( ',', $mce_css ) ), ' ,' );

				if ( ! empty($mce_css) )
					self::$first_init['content_css'] = $mce_css;
			}

			if ( $set['teeny'] ) {

				/**
				 * Filter the list of teenyMCE buttons (Text tab).
				 *
				 * @since 2.7.0
				 *
				 * @param array  $buttons   An array of teenyMCE buttons.
				 * @param string $editor_id Unique editor identifier, e.g. 'content'.
				 */
				$mce_buttons = apply_filters( 'teeny_mce_buttons', array('bold', 'italic', 'underline', 'blockquote', 'strikethrough', 'bullist', 'numlist', 'alignleft', 'aligncenter', 'alignright', 'undo', 'redo', 'link', 'unlink', 'fullscreen'), $editor_id );
				$mce_buttons_2 = $mce_buttons_3 = $mce_buttons_4 = array();
			} else {

				/**
				 * Filter the first-row list of TinyMCE buttons (Visual tab).
				 *
				 * @since 2.0.0
				 *
				 * @param array  $buttons   First-row list of buttons.
				 * @param string $editor_id Unique editor identifier, e.g. 'content'.
				 */
				$mce_buttons = apply_filters( 'mce_buttons', array('bold', 'italic', 'strikethrough', 'bullist', 'numlist', 'blockquote', 'hr', 'alignleft', 'aligncenter', 'alignright', 'link', 'unlink', 'wp_more', 'spellchecker', 'fullscreen', 'wp_adv' ), $editor_id );

				/**
				 * Filter the second-row list of TinyMCE buttons (Visual tab).
				 *
				 * @since 2.0.0
				 *
				 * @param array  $buttons   Second-row list of buttons.
				 * @param string $editor_id Unique editor identifier, e.g. 'content'.
				 */
				$mce_buttons_2 = apply_filters( 'mce_buttons_2', array( 'formatselect', 'underline', 'alignjustify', 'forecolor', 'pastetext', 'removeformat', 'charmap', 'outdent', 'indent', 'undo', 'redo', 'wp_help' ), $editor_id );

				/**
				 * Filter the third-row list of TinyMCE buttons (Visual tab).
				 *
				 * @since 2.0.0
				 *
				 * @param array  $buttons   Third-row list of buttons.
				 * @param string $editor_id Unique editor identifier, e.g. 'content'.
				 */
				$mce_buttons_3 = apply_filters( 'mce_buttons_3', array(), $editor_id );

				/**
				 * Filter the fourth-row list of TinyMCE buttons (Visual tab).
				 *
				 * @since 2.5.0
				 *
				 * @param array  $buttons   Fourth-row list of buttons.
				 * @param string $editor_id Unique editor identifier, e.g. 'content'.
				 */
				$mce_buttons_4 = apply_filters( 'mce_buttons_4', array(), $editor_id );
			}

			$body_class = $editor_id;

			if ( $post = get_post() ) {
				$body_class .= ' post-type-' . sanitize_html_class( $post->post_type ) . ' post-status-' . sanitize_html_class( $post->post_status );
				if ( post_type_supports( $post->post_type, 'post-formats' ) ) {
					$post_format = get_post_format( $post );
					if ( $post_format && ! is_wp_error( $post_format ) )
						$body_class .= ' post-format-' . sanitize_html_class( $post_format );
					else
						$body_class .= ' post-format-standard';
				}
			}

			if ( !empty($set['tinymce']['body_class']) ) {
				$body_class .= ' ' . $set['tinymce']['body_class'];
				unset($set['tinymce']['body_class']);
			}

			if ( $set['dfw'] ) {
				// replace the first 'fullscreen' with 'wp_fullscreen'
				if ( ($key = array_search('fullscreen', $mce_buttons)) !== false )
					$mce_buttons[$key] = 'wp_fullscreen';
				elseif ( ($key = array_search('fullscreen', $mce_buttons_2)) !== false )
					$mce_buttons_2[$key] = 'wp_fullscreen';
				elseif ( ($key = array_search('fullscreen', $mce_buttons_3)) !== false )
					$mce_buttons_3[$key] = 'wp_fullscreen';
				elseif ( ($key = array_search('fullscreen', $mce_buttons_4)) !== false )
					$mce_buttons_4[$key] = 'wp_fullscreen';
			}

			$mceInit = array (
				'selector' => "#$editor_id",
				'wpautop' => (bool) $set['wpautop'],
				'indent' => ! $set['wpautop'],
				'toolbar1' => implode($mce_buttons, ','),
				'toolbar2' => implode($mce_buttons_2, ','),
				'toolbar3' => implode($mce_buttons_3, ','),
				'toolbar4' => implode($mce_buttons_4, ','),
				'tabfocus_elements' => $set['tabfocus_elements'],
				'body_class' => $body_class
			);

			if ( $first_run )
				$mceInit = array_merge( self::$first_init, $mceInit );

			if ( is_array( $set['tinymce'] ) )
				$mceInit = array_merge( $mceInit, $set['tinymce'] );

			/*
			 * For people who really REALLY know what they're doing with TinyMCE
			 * You can modify $mceInit to add, remove, change elements of the config
			 * before tinyMCE.init. Setting "valid_elements", "invalid_elements"
			 * and "extended_valid_elements" can be done through this filter. Best
			 * is to use the default cleanup by not specifying valid_elements,
			 * as TinyMCE contains full set of XHTML 1.0.
			 */
			if ( $set['teeny'] ) {

				/**
				 * Filter the teenyMCE config before init.
				 *
				 * @since 2.7.0
				 *
				 * @param array  $mceInit   An array with teenyMCE config.
				 * @param string $editor_id Unique editor identifier, e.g. 'content'.
				 */
				$mceInit = apply_filters( 'teeny_mce_before_init', $mceInit, $editor_id );
			} else {

				/**
				 * Filter the TinyMCE config before init.
				 *
				 * @since 2.5.0
				 *
				 * @param array  $mceInit   An array with TinyMCE config.
				 * @param string $editor_id Unique editor identifier, e.g. 'content'.
				 */
				$mceInit = apply_filters( 'tiny_mce_before_init', $mceInit, $editor_id );
			}

			if ( empty( $mceInit['toolbar3'] ) && ! empty( $mceInit['toolbar4'] ) ) {
				$mceInit['toolbar3'] = $mceInit['toolbar4'];
				$mceInit['toolbar4'] = '';
			}

			self::$mce_settings[$editor_id] = $mceInit;
		} // end if self::$this_tinymce
        }
    }

    
    
endif;