<?php

if (!function_exists('CbpEditorManger')) {
    
    require_once 'CbpWpEditor.php';
    
    class CbpEditorManger extends CbpWpEditor
    {

        // commented out from 1087 to 1101, 1122
        // renamed js tinyMCEPreInit to cbptinyMCEPreInit
	public static function editor_js() {
		global $tinymce_version, $concatenate_scripts, $compress_scripts;

		/**
		 * Filter "tiny_mce_version" is deprecated
		 *
		 * The tiny_mce_version filter is not needed since external plugins are loaded directly by TinyMCE.
		 * These plugins can be refreshed by appending query string to the URL passed to "mce_external_plugins" filter.
		 * If the plugin has a popup dialog, a query string can be added to the button action that opens it (in the plugin's code).
		 */
		$version = 'ver=' . $tinymce_version;
		$tmce_on = !empty(self::$mce_settings);

		if ( ! isset($concatenate_scripts) )
			script_concat_settings();

		$compressed = $compress_scripts && $concatenate_scripts && isset($_SERVER['HTTP_ACCEPT_ENCODING'])
			&& false !== stripos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip');

		$mceInit = $qtInit = '';
		if ( $tmce_on ) {
			foreach ( self::$mce_settings as $editor_id => $init ) {
				$options = self::_parse_init( $init );
				$mceInit .= "'$editor_id':{$options},";
			}
			$mceInit = '{' . trim($mceInit, ',') . '}';
		} else {
			$mceInit = '{}';
		}

		if ( !empty(self::$qt_settings) ) {
			foreach ( self::$qt_settings as $editor_id => $init ) {
				$options = self::_parse_init( $init );
				$qtInit .= "'$editor_id':{$options},";
			}
			$qtInit = '{' . trim($qtInit, ',') . '}';
		} else {
			$qtInit = '{}';
		}

		$ref = array(
			'plugins' => implode( ',', self::$plugins ),
			'theme' => 'modern',
			'language' => self::$mce_locale
		);

		$suffix = ( defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ) ? '' : '.min';

		/**
		 * Fires immediately before the TinyMCE settings are printed.
		 *
		 * @since 3.2.0
		 *
		 * @param array $mce_settings TinyMCE settings array.
		 */
		do_action( 'before_wp_tiny_mce', self::$mce_settings );
		?>

		<script type="text/javascript">
		cbptinyMCEPreInit = {
			baseURL: "<?php echo self::$baseurl; ?>",
			suffix: "<?php echo $suffix; ?>",
			<?php

			if ( self::$drag_drop_upload ) {
				echo 'dragDropUpload: true,';
			}

			?>
			mceInit: <?php echo $mceInit; ?>,
			qtInit: <?php echo $qtInit; ?>,
			ref: <?php echo self::_parse_init( $ref ); ?>,
			load_ext: function(url,lang){var sl=tinymce.ScriptLoader;sl.markDone(url+'/langs/'+lang+'.js');sl.markDone(url+'/langs/'+lang+'_dlg.js');}
		};
		</script>
		<?php

		$baseurl = self::$baseurl;
		// Load tinymce.js when running from /src, else load wp-tinymce.js.gz (production) or tinymce.min.js (SCRIPT_DEBUG)
		$mce_suffix = false !== strpos( $GLOBALS['wp_version'], '-src' ) ? '' : '.min';

//		if ( $tmce_on ) {
//			if ( $compressed ) {
//				echo "<script type='text/javascript' src='{$baseurl}/wp-tinymce.php?c=1&amp;$version'></script>\n";
//			} else {
//				echo "<script type='text/javascript' src='{$baseurl}/tinymce{$mce_suffix}.js?$version'></script>\n";
//				echo "<script type='text/javascript' src='{$baseurl}/plugins/compat3x/plugin{$suffix}.js?$version'></script>\n";
//			}
//
//			echo "<script type='text/javascript'>\n" . self::wp_mce_translation() . "</script>\n";
//
//			if ( self::$ext_plugins ) {
//				// Load the old-format English strings to prevent unsightly labels in old style popups
//				echo "<script type='text/javascript' src='{$baseurl}/langs/wp-langs-en.js?$version'></script>\n";
//			}
//		}

		/**
		 * Fires after tinymce.js is loaded, but before any TinyMCE editor
		 * instances are created.
		 *
		 * @since 3.9.0
		 *
		 * @param array $mce_settings TinyMCE settings array.
		 */
		do_action( 'wp_tiny_mce_init', self::$mce_settings );

		?>
		<script type="text/javascript">
		<?php

		if ( self::$ext_plugins )
			echo self::$ext_plugins . "\n";

		if ( ! is_admin() )
//			echo 'var ajaxurl = "' . admin_url( 'admin-ajax.php', 'relative' ) . '";';

		?>

		( function() {
                    
                    
                tinyMCEPreInit.mceInit = jQuery.extend( tinyMCEPreInit.mceInit, <?php echo $mceInit; ?>);
                tinyMCEPreInit.qtInit = jQuery.extend( tinyMCEPreInit.qtInit, <?php echo $qtInit; ?>);

                tinyMCE.init(tinyMCEPreInit.mceInit['<?php echo $editor_id; ?>']);

                try {
                    quicktags(tinyMCEPreInit.qtInit['<?php echo $editor_id; ?>']);
                } catch (e) {}
                    
                    
			var init, edId, qtId, firstInit, wrapper;
//
			if ( typeof tinymce !== 'undefined' ) {
//				for ( edId in cbptinyMCEPreInit.mceInit ) {
//					if ( firstInit ) {
//						init = cbptinyMCEPreInit.mceInit[edId] = tinymce.extend( {}, firstInit, cbptinyMCEPreInit.mceInit[edId] );
//					} else {
//						init = firstInit = cbptinyMCEPreInit.mceInit[edId];
//					}
//
					wrapper = tinymce.DOM.select( '#wp-' + edId + '-wrap' )[0];
//
					if ( ( tinymce.DOM.hasClass( wrapper, 'tmce-active' ) || ! cbptinyMCEPreInit.qtInit.hasOwnProperty( edId ) ) ) {
//
						try {
//							tinymce.init( init );
//
							if ( ! window.wpActiveEditor ) {
								window.wpActiveEditor = edId;
							}
						} catch(e){}
					}
//				}
			}
//
//			if ( typeof quicktags !== 'undefined' ) {
//				for ( qtId in tinyMCEPreInit.qtInit ) {
//					try {
//						quicktags( cbptinyMCEPreInit.qtInit[qtId] );
//
//						if ( ! window.wpActiveEditor ) {
//							window.wpActiveEditor = qtId;
//						}
//					} catch(e){};
//				}
//			}
//
			if ( typeof jQuery !== 'undefined' ) {
				jQuery('.wp-editor-wrap').on( 'click.wp-editor', function() {
					if ( this.id ) {
						window.wpActiveEditor = this.id.slice( 3, -5 );
					}
				});
			} else {
//				for ( qtId in cbptinyMCEPreInit.qtInit ) {
//					document.getElementById( 'wp-' + qtId + '-wrap' ).onclick = function() {
//						window.wpActiveEditor = this.id.slice( 3, -5 );
//					}
//				}
			}
		}());
		</script>
		<?php

		if ( in_array( 'wplink', self::$plugins, true ) || in_array( 'link', self::$qt_buttons, true ) )
			self::wp_link_dialog();

		if ( in_array( 'wpfullscreen', self::$plugins, true ) || in_array( 'fullscreen', self::$qt_buttons, true ) )
			self::wp_fullscreen_html();

		/**
		 * Fires after any core TinyMCE editor instances are created.
		 *
		 * @since 3.2.0
		 *
		 * @param array $mce_settings TinyMCE settings array.
		 */
		do_action( 'after_wp_tiny_mce', self::$mce_settings );
	}
    }

}
