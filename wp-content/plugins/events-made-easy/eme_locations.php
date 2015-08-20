<?php
$feedback_message = "";

function eme_new_location() {
   $location = array(
   'location_name' => '',
   'location_address' => '',
   'location_town' => '',
   'location_latitude' => '',
   'location_longitude' => '',
   'location_description' => '',
   'location_category_ids' => '',
   'location_url' => '',
   'location_slug' => '',
   'location_image_url' => '',
   'location_external_ref' => '',
   'location_image_id' => 0,
   'location_attributes' => array(),
   'location_properties' => array()
   );

   $location['location_properties'] = eme_init_location_props($location['location_properties']);
   return $location;
}
 
function eme_init_location_props($props) {
   return $props;
}

function eme_locations_page() {
   $current_userid=get_current_user_id();
   if (isset($_GET['eme_admin_action']) && $_GET['eme_admin_action'] == "edit_location") { 
      $location_id = intval($_GET['location_id']);
      $location = eme_get_location($location_id);
      if (current_user_can( get_option('eme_cap_edit_locations')) ||
            (current_user_can( get_option('eme_cap_author_locations')) && ($location['location_author']==$current_userid))) {
         // edit location
         eme_locations_edit_layout($location);
      } else {
         $message = __('You have no right to edit this location!','eme');
         eme_locations_table_layout($message);
      }
   } elseif (isset($_GET['eme_admin_action']) && $_GET['eme_admin_action'] == "copy_location") { 
      $location_id = intval($_GET['location_id']);
      $location = eme_get_location($location_id);
      // make it look like a new location
      unset($location['location_id']);
      $location['location_name'].= __(" (Copy)","eme");

      if (current_user_can( get_option('eme_cap_add_locations'))) {
         eme_locations_edit_layout($location);
      } else {
         $message = __('You have no right to copy this location!','eme');
         eme_locations_table_layout($message);
      }
    } elseif (isset($_POST['eme_admin_action']) && $_POST['eme_admin_action'] == "add_location") { 
      if (current_user_can( get_option('eme_cap_add_locations'))) {
         $location = eme_new_location();
         eme_locations_edit_layout($location);
      } else {
         $message = __('You have no right to add a location!','eme');
         eme_locations_table_layout($message);
      }
   } elseif (isset($_POST['eme_admin_action']) && $_POST['eme_admin_action'] == "delete_location" && isset($_POST['locations'])) { 
      $locations = $_POST['locations'];
      foreach($locations as $location_id) {
         $location = eme_get_location(intval($location_id));
         if (current_user_can( get_option('eme_cap_edit_locations')) ||
               (current_user_can( get_option('eme_cap_author_locations')) && ($location['location_author']==$current_userid))) {
            eme_delete_location(intval($location_id));
         }
      }
      $message = __('Successfully deleted the selected locations.','eme');
      eme_locations_table_layout($message);
   } elseif (isset($_POST['eme_admin_action']) && ($_POST['eme_admin_action'] == "do_editlocation" || $_POST['eme_admin_action'] == "do_addlocation")) { 
      $action = $_POST['eme_admin_action'];
      if ($action == "do_editlocation")
         $orig_location=eme_get_location(intval($_POST['location_id']));

      if ($action == "do_addlocation" && !current_user_can( get_option('eme_cap_add_locations'))) {
         $message = __('You have no right to add a location!','eme');
         eme_locations_table_layout($message);
      } elseif ($action == "do_editlocation" && !(current_user_can( get_option('eme_cap_edit_locations')) ||
                  (current_user_can( get_option('eme_cap_author_locations')) && ($orig_location['location_author']==$current_userid)))) {
            $message = __('You have no right to edit this location!','eme');
            eme_locations_table_layout($message);
      } else {
         $location = eme_new_location();
         $location['location_name'] = trim(stripslashes($_POST['location_name']));
         $location['location_address'] = stripslashes($_POST['location_address']); 
         $location['location_town'] = stripslashes($_POST['location_town']); 
         $location['location_description'] = stripslashes($_POST['content']);
         $location['location_url'] = isset($_POST ['location_url']) ? eme_strip_tags ( $_POST ['location_url'] ) : '';
         $location['location_image_url'] = isset($_POST ['location_image_url']) ? eme_strip_tags ( $_POST ['location_image_url'] ) : '';
         $location['location_image_id'] = isset($_POST ['location_image_id']) ? intval ( $_POST ['location_image_id'] ) : 0;
         $location['location_slug'] = isset($_POST ['location_slug']) ? eme_permalink_convert(eme_strip_tags ( $_POST ['location_slug'] )) : eme_permalink_convert($location['location_name']);
         // we don't change the author
         //$location['location_author'] = $current_userid;
         if (isset ($_POST['location_category_ids'])) {
            // the category id's need to begin and end with a comma
            // this is needed so we can later search for a specific
            // cat using LIKE '%,$cat,%'
            $location ['location_category_ids']="";
            foreach ($_POST['location_category_ids'] as $cat) {
               if (is_numeric($cat)) {
                  if (empty($location ['location_category_ids'])) {
                     $location ['location_category_ids'] = "$cat";
                  } else {
                     $location ['location_category_ids'] .= ",$cat";
                  }
               }
            }
         } else {
            $location ['location_category_ids']="";
         }

         $location['location_latitude'] = $_POST['location_latitude'];
         $location['location_longitude'] = $_POST['location_longitude'];
         if (empty($location['location_latitude'])) {
            $location['location_latitude']  = 0;
            $location['location_longitude'] = 0;
         }

         $location_attributes = array();
         for($i=1 ; isset($_POST["mtm_{$i}_ref"]) && trim($_POST["mtm_{$i}_ref"])!='' ; $i++ ) {
            if (trim($_POST["mtm_{$i}_name"]) != '') {
               $location_attributes[$_POST["mtm_{$i}_ref"]] = stripslashes($_POST["mtm_{$i}_name"]);
            }
         }
         $location['location_attributes'] = serialize($location_attributes);

         $location_properties = array();
         $location_properties = eme_init_location_props($location_properties);
         foreach($_POST as $key=>$value) {
            if (preg_match('/eme_loc_prop_(.+)/', $key, $matches)) {
               $location_properties[$matches[1]] = stripslashes($value);
            }
         }
         $location['location_properties'] = serialize($location_properties);

         $validation_result = eme_validate_location($location);
         if ($validation_result == "OK") {
            if ($action == "do_addlocation") {
               $new_location = eme_insert_location($location);
               if ($new_location) {
                  $message = __('The location has been added.', 'eme'); 
               } else {
                  $message = __('There has been a problem adding the location.', 'eme'); 
               }      
            } elseif ($action == "do_editlocation") {      
               $location['location_id'] = intval($_POST['location_id']);
               if (eme_update_location($location)) {
                  $message = __('The location has been updated.', 'eme');
               } else {
                  $message = __('The location update failed.', 'eme');
               }
            }
            eme_locations_table_layout($message);
         } else {
            $message = $validation_result;
            eme_locations_edit_layout($location, $message);
         }
      }
   } else {
      // no action, just a locations list
      eme_locations_table_layout();
   }
}

function eme_locations_edit_layout($location, $message = "") {
   if (!isset($location['location_id']))
      $action="add";
   else
      $action="edit";
   ?>
   <div class="wrap">
      <div id="poststuff">
         <div id="icon-edit" class="icon32">
            <br />
         </div>
            
         <h2><?php if ($action=="add")
                     _e('Add location', 'eme');
                   else
                     _e('Edit location', 'eme');
             ?></h2>
             <?php admin_show_warnings(); ?>
         
         <?php if ($message != "") { ?>
            <div id="message" class="updated fade below-h2" style="background-color: rgb(255, 251, 204);">
               <p><?php  echo $message ?></p>
            </div>
         <?php } ?>
         <div id="ajax-response"></div>
   
         <form enctype="multipart/form-data" name="editloc" id="editloc" method="post" action="<?php echo admin_url("admin.php?page=eme-locations"); ?>" class="validate">
         <?php if ($action == "add") { ?>
         <input type="hidden" name="eme_admin_action" value="do_addlocation" />
         <?php } else { ?>
         <input type="hidden" name="eme_admin_action" value="do_editlocation" />
         <input type="hidden" name="location_id" value="<?php echo $location['location_id'] ?>" />
         <?php } ?>
         
         <!-- we need titlediv and title for qtranslate as ID -->
         <div id="titlediv" class="postbox">
            <h3>
               <?php _e('Location name', 'eme') ?>
            </h3>
            <div class="inside">
           <input name="location_name" id="title" type="text" required="required" value="<?php echo eme_sanitize_html($location['location_name']); ?>" size="40" />
           <?php if ($action=="edit") {
                    _e ('Permalink: ', 'eme' );
                    echo trailingslashit(home_url()).eme_permalink_convert(get_option ( 'eme_permalink_locations_prefix')).$location['location_id']."/";
                    $slug = $location['location_slug'] ? $location['location_slug'] : $location['location_name'];
                    $slug = untrailingslashit(eme_permalink_convert($slug));
           ?>
                    <input type="text" id="slug" name="location_slug" value="<?php echo $slug; ?>" /><?php echo user_trailingslashit(""); ?>
           <?php
                  }
           ?>
           </div>
         </div>
         <div class="postbox">
            <h3>
               <?php _e('Location address', 'eme') ?>
            </h3>
            <div class="inside">
            <input id="location_address" name="location_address" type="text" value="<?php echo eme_trans_sanitize_html($location['location_address']); ?>" size="40" />
            </div>
         </div>
 
         <div class="postbox">
            <h3>
               <?php _e('Location town', 'eme') ?>
            </h3>
            <div class="inside">
            <input name="location_town" id="location_town" type="text" value="<?php echo eme_trans_sanitize_html($location['location_town']); ?>" size="40" />
            </div>
         </div>
                        
         <div class="postbox">
            <h3>
               <?php _e('Location latitude/longitude', 'eme') ?>
            </h3>
            <div class="inside">
            <table><tr>
            <td><label for="location_latitude"><?php _e('Latitude', 'eme') ?></label></td>
            <td><input id="location_latitude" name="location_latitude" type="text" value="<?php echo eme_sanitize_html($location['location_latitude']); ?>" size="40" /></td>
            </tr>
            <tr>
            <td><label for="location_longitude"><?php _e('Longitude', 'eme') ?></label></td>
            <td><input id="location_longitude" name="location_longitude" type="text" value="<?php echo eme_sanitize_html($location['location_longitude']); ?>" size="40" /></td>
            </tr></table>
            </div>
         </div>

         <div class="postbox">
            <h3>
               <?php _e('Location image', 'eme') ?>
            </h3>
            <div class="inside">
             <?php if (isset($location['location_image_url']) && !empty($location['location_image_url'])) {
                       echo "<img id='eme_location_image_example' src='".$location['location_image_url']."' width='200' />";
                       echo "<input type='hidden' name='location_image_url' id='location_image_url' value='".$location['location_image_url']."' />";
                    } else {
                       echo "<img id='eme_location_image_example' src='' alt='' width='200' />";
                       echo "<input type='hidden' name='location_image_url' id='location_image_url' />";
                    }
                    if (isset($location['location_image_id']) && !empty($location['location_image_id'])) {
                       echo "<input type='hidden' name='location_image_id' id='location_image_id' value='".$location['location_image_id']."' />";
                    } else {
                       echo "<input type='hidden' name='location_image_id' id='location_image_id' />";
                    }
                    // based on code found at http://codestag.com/how-to-use-wordpress-3-5-media-uploader-in-theme-options/
              ?>

                <input type="button" name="location_image_button" id="location_image_button" value="<?php _e ( 'Set a featured image', 'eme' )?>" />
                <input type="button" id="eme_remove_old_image" name="eme_remove_old_image" value=" <?php _e ( 'Unset featured image', 'eme' )?>" />
            </div>
<script>
jQuery(document).ready(function($){

  $('#eme_remove_old_image').click(function(e) {
        $('#location_image_url').val('');
        $('#location_image_id').val('');
        $('#eme_location_image_example' ).attr("src",'');
  });
  $('#location_image_button').click(function(e) {
    e.preventDefault();

    var custom_uploader = wp.media({
        title: '<?php _e ( 'Select the image to be used as featured image', 'eme' )?>',
        button: {
            text: '<?php _e ( 'Set featured image', 'eme' )?>'
        },
        // Tell the modal to show only images.
        library: {
                type: 'image'
        },  
        multiple: false  // Set this to true to allow multiple files to be selected
    })
    .on('select', function() {
        var attachment = custom_uploader.state().get('selection').first().toJSON();
        $('#location_image_url').val(attachment.url);
        $('#location_image_id').val(attachment.id);
        $('#eme_location_image_example' ).attr("src",attachment.url);
    })
    .open();
  });
});
</script>
        </div>
 
        <?php if(get_option('eme_categories_enabled')) :?>
        <div class="postbox">
            <h3>
               <?php _e('Category', 'eme') ?>
            </h3>
            <div class="inside">
           <?php
           $categories = eme_get_categories();
           foreach ( $categories as $category) {
              if ($location['location_category_ids'] && in_array($category['category_id'],explode(",",$location['location_category_ids']))) {
                 $selected = "checked='checked'";
              } else {
                 $selected = "";
              }
           ?>
              <input type="checkbox" name="location_category_ids[]" value="<?php echo $category['category_id']; ?>" <?php echo $selected ?> /><?php echo $category['category_name']; ?><br />
           <?php
           }
           ?>
            </div>
        </div>
        <?php endif; ?>

         <?php 
            $gmap_is_active = get_option('eme_gmap_is_active');
            if ($gmap_is_active) :
          ?>   
         <div class="postbox"><?php 
               if (function_exists('qtrans_getLanguage') || function_exists('ppqtrans_getLanguage') || defined('ICL_LANGUAGE_CODE')) {
                  _e("Because qtranslate or a derivate is active, the title of the location might not update automatically in the balloon, so don't panic there.");
               }
              ?>
         </div>
         <div class="postbox" id="eme-admin-map-not-found"><p><?php _e('Map not found','eme') ?></p></div>
         <div class="postbox" id="eme-admin-location-map"></div>
         <br style="clear:both;" />
         <?php endif; ?>
         <div class="postbox" id="loc_description">
            <h3>
               <?php _e('Location description', 'eme') ?>
            </h3>
            <div class="inside">
               <div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea">
                  <!-- we need content for qtranslate as ID -->
                  <?php wp_editor($location['location_description'],"content"); ?>
               </div>
               <?php _e('A description of the Location. You may include any kind of info here.', 'eme') ?>
            </div>
         </div>
     	 <?php if (get_option('eme_attributes_enabled')) { ?>
         <div class="postbox">
            <h3>
               <?php _e ( 'Attributes', 'eme' ); ?>
            </h3>
               <?php eme_attributes_form($location); ?>
         </div>
       <?php } ?>
         <div class="postbox">
            <h3>
               <?php _e ( 'External link', 'eme' ); ?>
            </h3>
            <div class="inside">
            <input name="location_url" id="location_url" type="text" value="<?php echo eme_sanitize_html($location['location_url']); ?>" size="40" />
            <p><?php _e ( 'If this is filled in, the single location URL will point to this url instead of the standard location page.', 'eme' )?></p>
            </div>
         </div>
         <p class="submit"><input type="submit" class="button-primary" name="submit" value="<?php if ($action=="add") _e('Add location', 'eme'); else _e('Update location', 'eme'); ?>" /></p>
      </div>
      </form>
   </div>
   <?php
}

function eme_locations_table_layout($message = "") {
   $locations = eme_get_locations();

   ?>
      <div class="wrap nosubsub">
       <div id="poststuff">
         <div id="icon-edit" class="icon32">
            <br />
         </div>
         <h2><?php _e('Add a new location', 'eme') ?></h2>
         <?php admin_show_warnings(); ?>
         
         <?php if ($message != "") { ?>
            <div id="message" class="updated fade below-h2" style="background-color: rgb(255, 251, 204);">
               <p><?php echo $message ?></p>
            </div>
         <?php } ?>
         <div class="wrap">
         <form id="locations-filter" method="post" action="<?php echo admin_url("admin.php?page=eme-locations"); ?>">
            <input type="hidden" name="eme_admin_action" value="add_location" />
            <input type="submit" class="button-primary" name="submit" value="<?php _e('Add location', 'eme');?>" />
         </form>
         </div>

         <h2><?php _e('Locations', 'eme') ?></h2>
         <div id="col-container">
             <div class="col-wrap">
                <form id="locations-filter" method="post" action="<?php echo admin_url("admin.php?page=eme-locations"); ?>">
                  <input type="hidden" name="eme_admin_action" value="delete_location" />
                  <?php if (count($locations)>0) : ?>
                  <table class="widefat">
                     <thead>
                        <tr>
                           <th class="manage-column column-cb check-column" scope="col"><input type="checkbox" class="select-all" value="1" /></th>
                           <th><?php _e('ID', 'eme') ?></th>
                           <th><?php _e('Name', 'eme') ?></th>
                           <th><?php _e('Address', 'eme') ?></th>
                           <th><?php _e('Town', 'eme') ?></th>
                           <th></th>
                        </tr> 
                     </thead>
                     <tfoot>
                        <tr>
                           <th class="manage-column column-cb check-column" scope="col"><input type="checkbox" class="select-all" value="1" /></th>
                           <th><?php _e('ID', 'eme') ?></th>
                           <th><?php _e('Name', 'eme') ?></th>
                           <th><?php _e('Address', 'eme') ?></th>
                           <th><?php _e('Town', 'eme') ?></th>
                           <th><?php _e('Copy', 'eme') ?></th>
                        </tr>
                     </tfoot>
                     <tbody>
                        <?php foreach ($locations as $this_location) : ?>  
                        <tr>
                           <td><input type="checkbox" class ="row-selector" value="<?php echo $this_location['location_id']; ?>" name="locations[]" /></td>
                           <td><?php echo $this_location['location_id']; ?></td>
                           <td><a href="<?php echo admin_url("admin.php?page=eme-locations&amp;eme_admin_action=edit_location&amp;location_id=".$this_location['location_id']); ?>" title="<?php _e('Edit location','eme');?>"><?php echo eme_trans_sanitize_html($this_location['location_name']); ?></a></td>
                           <td><?php echo eme_trans_sanitize_html($this_location['location_address']); ?></td>
                           <td><?php echo eme_trans_sanitize_html($this_location['location_town']); ?></td>
                           <td><a href="<?php echo admin_url("admin.php?page=eme-locations&amp;eme_admin_action=copy_location&amp;location_id=".$this_location['location_id']); ?>" title="<?php _e('Duplicate this location','eme'); ?>"><img src='<?php echo EME_PLUGIN_URL."images/copy_16.png";?>'/></a></td>
                        </tr>
                        <?php endforeach; ?>
                     </tbody>

                  </table>

                  <div class="tablenav">
                     <div class="alignleft actions">
                     <input class="button-primary action" type="submit" name="doaction" value="Delete" onclick="return areyousure('<?php _e("Are you sure you want to delete these locations?","eme"); ?>');" />
                     <br class="clear"/> 
                     </div>
                     <br class="clear"/>
                  </div>
                  <?php else: ?>
                     <p><?php _e('No venues have been inserted yet!', 'eme') ?></p>
                  <?php endif; ?>
                  </form>
               </div>
         </div> <!-- end col-container -->
      </div> 
   </div>
   <?php
}

function eme_get_locations($eventful = false, $scope="all", $category = '', $offset = 0) { 
   global $wpdb;
   $locations_table = $wpdb->prefix.LOCATIONS_TBNAME; 
   $events_table = $wpdb->prefix.EVENTS_TBNAME;
   $locations = array();

   // for the query: we don't do "SELECT *" because the data returned from this function is also used in the function eme_global_map_json()
   // and some fields from the events table contain carriage returns, which can't be passed along
   // The function eme_global_map_json tries to remove these, but the data is not needed and better be safe than sorry
   $eventful = ($eventful==="true" || $eventful==="1") ? true : $eventful;
   $eventful = ($eventful==="false" || $eventful==="0") ? true : $eventful;
   if ($eventful) {
      $events = eme_get_events(0, $scope, "ASC", $offset, "", $category);
      if ($events) {
         foreach ($events as $event) {
            $location_id=$event['location_id'];
            if ($location_id) {
               $this_location = eme_get_location($location_id);
               // the key is based on the location name first and the location id (if different locations have the same name)
               // using this method we can then sort on the name
               if ($this_location['location_name']!='')
                  $locations[$this_location['location_name'].$location_id]=$this_location;
            }
         }
         // sort on the key (name/id pair)
         ksort($locations);
      }
   } else {
      $conditions = array ();
      if (get_option('eme_categories_enabled')) {
         if (is_numeric($category)) {
            if ($category>0)
               $conditions [] = " FIND_IN_SET($category,location_category_ids)";
         } elseif ($category == "none") {
            $conditions [] = "event_category_ids=''";
         } elseif ( preg_match('/,/', $category) ) {
            $category = explode(',', $category);
            $category_conditions = array();
            foreach ($category as $cat) {
               if (is_numeric($cat) && $cat>0) {
                  $category_conditions[] = " FIND_IN_SET($cat,location_category_ids)";
               } elseif ($cat == "none") {
                  $category_conditions[] = " location_category_ids=''";
               }
            }
            $conditions [] = "(".implode(' OR', $category_conditions).")";
         } elseif ( preg_match('/\+/', $category) ) {
            $category = explode('+', $category);
            $category_conditions = array();
            foreach ($category as $cat) {
               if (is_numeric($cat) && $cat>0)
                  $category_conditions[] = " FIND_IN_SET($cat,location_category_ids)";
            }
            $conditions [] = "(".implode(' AND ', $category_conditions).")";
         }
      }

      // extra conditions for authors: if we're in the admin itf, return only the locations for which you have the right to change anything
      $current_userid=get_current_user_id();
      if (is_admin() && !current_user_can( get_option('eme_cap_edit_locations')) && current_user_can( get_option('eme_cap_author_locations'))) {
         $conditions [] = "(location_author = $current_userid)";
      }

      $where = implode ( " AND ", $conditions );
      if ($where != "")
         $where = " AND " . $where;

      $sql = "SELECT * FROM $locations_table WHERE location_name != '' $where ORDER BY location_name";
      $locations = $wpdb->get_results($sql, ARRAY_A); 
      // don't forget the images (for the older locations that didn't use the wp gallery)
      foreach ($locations as $key=>$location) {
         if (empty($locations[$key]['location_image_id']) && empty($locations[$key]['location_image_url']))
            $locations[$key]['location_image_url'] = eme_image_url_for_location_id($location['location_id']);
         if (is_serialized($locations[$key]['location_attributes']))
            $locations[$key]['location_attributes'] = @unserialize($locations[$key]['location_attributes']);
         $locations[$key]['location_attributes'] = (!is_array($locations[$key]['location_attributes'])) ?  array() : $locations[$key]['location_attributes'] ;

         if (is_serialized($locations[$key]['location_properties']))
            $locations[$key]['location_properties'] = @unserialize($locations[$key]['location_properties']);
         $locations[$key]['location_properties'] = (!is_array($locations[$key]['location_properties'])) ?  array() : $locations[$key]['location_properties'] ;
         $locations[$key]['location_properties'] = eme_init_location_props($locations[$key]['location_properties']);

      }
   }
   if (has_filter('eme_location_list_filter')) $locations=apply_filters('eme_location_list_filter',$locations);
   return $locations;
}

function eme_get_location($location_id=0) { 
   global $wpdb;

   if (!$location_id) {
      return eme_new_location();
   } else {
      $locations_table = $wpdb->prefix.LOCATIONS_TBNAME; 
      $sql = "SELECT * FROM $locations_table WHERE location_id ='$location_id'";
      $location = $wpdb->get_row($sql, ARRAY_A);
      if (!$location)
         return eme_new_location();

      // don't forget the images (for the older locations that didn't use the wp gallery)
      if (empty($location['location_image_id']) && empty($location['location_image_url']))
         $location['location_image_url'] = eme_image_url_for_location_id($location['location_id']);

      if (is_serialized($location['location_attributes']))
         $location['location_attributes'] = @unserialize($location['location_attributes']);
      $location['location_attributes'] = (!is_array($location['location_attributes'])) ?  array() : $location['location_attributes'] ;

      if (is_serialized($location['location_properties']))
         $location['location_properties'] = @unserialize($location['location_properties']);
      $location['location_properties'] = (!is_array($location['location_properties'])) ?  array() : $location['location_properties'] ;
      $location['location_properties'] = eme_init_location_props($location['location_properties']);

      if (has_filter('eme_location_filter')) $location=apply_filters('eme_location_filter',$location);

      return $location;
   }
}

function eme_get_town_location_ids($towns) {
   global $wpdb;
   $locations_table = $wpdb->prefix.LOCATIONS_TBNAME; 
   $location_ids = array();
   $conditions="";
   if ( is_array($towns) ) {
      $town_conditions = array();
      foreach ($towns as $town) {
         $town_conditions[] = " location_town = '$town'";
      }
      $conditions = "(".implode(' OR', $town_conditions).")";
   } elseif (!empty($towns)) {
      $conditions = " location_town = '$towns'";
   }
   if (!empty($conditions)) {
      $sql = "SELECT DISTINCT location_id FROM $locations_table WHERE ".$conditions;
      $location_ids = $wpdb->get_col($sql); 
   }
   return $location_ids;
}

function eme_image_url_for_location_id($location_id) {
   $image_basename= IMAGE_UPLOAD_DIR."/location-".$location_id;
   $image_baseurl= IMAGE_UPLOAD_URL."/location-".$location_id;
   $mime_types = array('gif','jpg','png');
   foreach($mime_types as $type) {
      $file_path = $image_basename.".".$type;
      $file_url = $image_baseurl.".".$type;
      if (file_exists($file_path)) {
         return $file_url;
      }
   }
   return '';
}

function eme_get_identical_location($location) { 
   global $wpdb;
   $locations_table = $wpdb->prefix.LOCATIONS_TBNAME; 
   //$sql = "SELECT * FROM $locations_table WHERE location_name ='".$location['location_name']."' AND location_address ='".$location['location_address']."' AND location_town ='".$location['location_town']."';";
  $prepared_sql=$wpdb->prepare("SELECT * FROM $locations_table WHERE location_name = %s AND location_address = %s AND location_town = %s", stripcslashes($location['location_name']), stripcslashes($location['location_address']), stripcslashes($location['location_town']) );
   $cached_location = $wpdb->get_row($prepared_sql, ARRAY_A);
   return $cached_location;
}

function eme_validate_location($location) {
   $location_required_fields = array("location_name" => __('The location name', 'eme'), "location_address" => __('The location address', 'eme'), "location_town" => __('The location town', 'eme'));
   $troubles = "";
   if (empty($location['location_name'])) {
      $troubles .= "<li>".$location_required_fields['location_name'].__(" is missing!", "eme")."</li>";
   }
   if (empty($location['location_longitude']) && empty($location['location_longitude'])) {
      if (empty($location['location_address'])) {
         $troubles .= "<li>".$location_required_fields['location_address'].__(" is missing!", "eme")."</li>";
      }
      if (empty($location['location_town'])) {
         $troubles .= "<li>".$location_required_fields['location_town'].__(" is missing!", "eme")."</li>";
      }
   }

   if (empty($troubles)) {
      return "OK";
   } else {
      $message = __('Ach, some problems here:', 'eme')."<ul>\n$troubles</ul>";
      return $message; 
   }
}

function eme_update_location($location) {
   global $wpdb;
   $table_name = $wpdb->prefix.LOCATIONS_TBNAME;

   $where=array('location_id' => $location['location_id']);

   // make sure we don't update the auto-increment id, which is the location id
   // it is ok to do so, but should not change anyway
   if (isset($location['location_id'])) {
      unset($location['location_id']);
   }

   if (!is_serialized($location['location_attributes']))
      $location['location_attributes'] = serialize($location['location_attributes']);
      
   if (!is_serialized($location['location_properties']))
      $location['location_properties'] = serialize($location['location_properties']);

   $location['location_modif_date']=current_time('mysql', false);
   $location['location_modif_date_gmt']=current_time('mysql', true);
   $wpdb->show_errors(true);
   if ($wpdb->update ( $table_name, $location, $where ) === false) {
      $wpdb->print_error();
      $wpdb->show_errors(false);
      return false;
   } else {
      $wpdb->show_errors(false);
      return true;
   }
}

function eme_insert_location($location,$force=0) {
   // the force parameter can be used to ignore capabilities for a user when inserting a location
   // the frontend submit plugin can use this
   global $wpdb;  
   $table_name = $wpdb->prefix.LOCATIONS_TBNAME; 

   // remove possible unwanted fields
   if (isset($location['location_id'])) {
      unset($location['location_id']);
   }

   // if GMap is off the hidden fields are empty, so I add a custom value to make the query work
   if (empty($location['location_longitude']))
      $location['location_longitude'] = 0;
   if (empty($location['location_latitude']))
      $location['location_latitude'] = 0;

   $location['location_creation_date']=current_time('mysql', false);
   $location['location_modif_date']=current_time('mysql', false);
   $location['location_creation_date_gmt']=current_time('mysql', true);
   $location['location_modif_date_gmt']=current_time('mysql', true);

   if (!is_serialized($location['location_attributes']))
      $location['location_attributes'] = serialize($location['location_attributes']);
      
   if (!is_serialized($location['location_properties']))
      $location['location_properties'] = serialize($location['location_properties']);
      
   if (current_user_can( get_option('eme_cap_add_locations')) || $force) {
      $wpdb->show_errors(true);
      if (!$wpdb->insert($table_name,$location)) {
         $wpdb->print_error();
         $wpdb->show_errors(false);
         return false;
      } else {
         $location_id = $wpdb->insert_id;
         $new_location = eme_get_location($location_id);
         $wpdb->show_errors(false);
         return $new_location;
      }
   } else {
      return false;
   }
}

function eme_delete_location($location_id) {
   global $wpdb;  

   $table_name = $wpdb->prefix.LOCATIONS_TBNAME;
   $sql = $wpdb->prepare("DELETE FROM $table_name where location_id=%d",$location_id);
   $wpdb->query( $sql );

   $events_table = $wpdb->prefix.EVENTS_TBNAME;
   $sql = $wpdb->prepare("UPDATE $events_table SET location_id=0 WHERE location_id = %d",$location_id);
   $wpdb->query($sql);

   $image_basename= IMAGE_UPLOAD_DIR."/location-".$location_id;
   eme_delete_image_files($image_basename);
}

function eme_location_has_events($location_id) {
   global $wpdb;  
   $events_table = $wpdb->prefix.EVENTS_TBNAME;
   if (!is_admin()) {
      if (is_user_logged_in()) {
         $condition = "AND event_status IN (".STATUS_PUBLIC.",".STATUS_PRIVATE.")";
      } else {
         $condition = "AND event_status=".STATUS_PUBLIC;
      }
   }

   $sql = "SELECT COUNT(event_id) FROM $events_table WHERE location_id = $location_id $condition";
   $affected_events = $wpdb->get_results($sql);
   return ($affected_events > 0);
}

function eme_global_map($atts) {
   global $eme_need_gmap_js;

   if (get_option('eme_gmap_is_active') == '1') {
      // the locations shortcode has been deteced, so we indicate
      // that we want the javascript in the footer as well
      $eme_need_gmap_js=1;
      extract(shortcode_atts(array(
                  'show_locations' => true,
                  'show_events' => false,
                  'eventful' => false,
                  'scope' => 'all',
                  'paging' => 0,
                  'category' => '',
                  'width' => 450,
                  'height' => 300,
                  'list_location' => 'after'
                  ), $atts));
      $eventful = ($eventful==="true" || $eventful==="1") ? true : $eventful;
      $show_events = ($show_events==="true" || $show_events==="1") ? true : $show_events;
      $show_locations = ($show_locations==="true" || $show_locations==="1") ? true : $show_locations;
      $eventful = ($eventful==="false" || $eventful==="0") ? false : $eventful;
      $show_events = ($show_events==="false" || $show_events==="0") ? false : $show_events;
      $show_locations = ($show_locations==="false" || $show_locations==="0") ? false : $show_locations;

      $events_page_link = eme_get_events_page(true, false);
      $prev_text = "";
      $next_text = "";
      $scope_offset=0;
      // for browsing: if paging=1 and only for this_week,this_month or today
      if ($eventful && $paging==1) {
         if (isset($_GET['eme_offset']))
            $scope_offset=$_GET['eme_offset'];
         $prev_offset=$scope_offset-1;
         $next_offset=$scope_offset+1;
         if ($scope=="this_week") {
            $start_of_week = get_option('start_of_week');
            $day_offset=date('w')-$start_of_week;
            if ($day_offset<0) $day_offset+=7;
            $limit_start=eme_date_calc("-$day_offset days +$scope_offset week");
            $limit_end=eme_date_calc("+6 days",$start_day);
            $scope = "$limit_start--$limit_end";
            $scope_text = eme_localised_date($limit_start)." -- ".eme_localised_date($limit_end);

            $prev_text = __('Previous week','eme');
            $next_text = __('Next week','eme');

         } elseif ($scope=="this_month") {
            $limit_start = eme_date_calc("first day of $scope_offset month");
            $limit_end   = eme_date_calc("last day of $scope_offset month");
            $scope = "$limit_start--$limit_end";
            $scope_text = eme_localised_date($limit_start,get_option('eme_show_period_monthly_dateformat'));
            $prev_text = __('Previous month','eme');
            $next_text = __('Next month','eme');

         } elseif ($scope=="this_year") {
            $year=date('Y')+$scope_offset;
            $limit_start = "$year-01-01";
            $limit_end   = "$year-12-31";
            $scope = "$limit_start--$limit_end";
            $scope = "$limit_start--$limit_end";
            $scope_text = eme_localised_date($limit_start,get_option('eme_show_period_yearly_dateformat'));
            $prev_text = __('Previous year','eme');
            $next_text = __('Next year','eme');

         } elseif ($scope=="today") {
            $scope = date('Y-m-d',strtotime("$scope_offset days"));
            $limit_start = $scope;
            $limit_end   = $scope;
            $scope_text = eme_localised_date($limit_start);
            $prev_text = __('Previous day','eme');
            $next_text = __('Next day','eme');

         } elseif ($scope=="tomorrow") {
            $scope_offset++;
            $scope = date('Y-m-d',strtotime("$scope_offset days"));
            $limit_start = $scope;
            $limit_end   = $scope;
            $scope_text = eme_localised_date($limit_start);
            $prev_text = __('Previous day','eme');
            $next_text = __('Next day','eme');
         }

         // to prevent going on indefinitely and thus allowing search bots to go on for ever,
         // we stop providing links if there are no more events left
         $older_events=eme_get_events ( 1, "--".$limit_start, "ASC", 0, $location['location_id'], $category);
         $newer_events=eme_get_events ( 1, "++".$limit_end, "ASC", 0, $location['location_id'], $category);
         if (count($older_events) == 0)
            $prev_text = "";
         if (count($newer_events) == 0)
            $next_text = "";
      }

      $id_base = preg_replace("/\D/","_",microtime(1));
      $id_base = rand()."_".$id_base;
      if (!preg_match('/\%$|px$/',$width)) $width=$width."px";
      if (!preg_match('/\%$|px$/',$height)) $height=$height."px";
      $result = "<div id='eme_global_map_$id_base' class='eme_global_map' style='width: $width; height: $height'>map</div>";
      // get the paging output ready
      if ($paging==1) {
         $pagination_top = "<div id='locations-pagination-top'> ";
         $this_page_url=$_SERVER['REQUEST_URI'];
         // remove the offset info
         $this_page_url= remove_query_arg('eme_offset',$this_page_url);
         if ($prev_text != "")
            $pagination_top.= "<a class='eme_nav_left' href='".add_query_arg(array('eme_offset'=>$prev_offset),$this_page_url)."'>&lt;&lt; $prev_text</a>";
         if ($next_text != "")
            $pagination_top.= "<a class='eme_nav_right' href='".add_query_arg(array('eme_offset'=>$next_offset),$this_page_url)."'>$next_text &gt;&gt;</a>";
         $pagination_top.= "<span class='eme_nav_center'>$scope_text</span>";
         $pagination_top.= "</div>";
         $pagination_bottom = str_replace("locations-pagination-top","locations-pagination-bottom",$pagination_top);
         $result = $pagination_top.$result.$pagination_bottom;
      }

      $eventful_string="eventful_".$id_base;
      $scope_string="scope_".$id_base;
      $category_string="category_".$id_base;
      $result .= "<script type='text/javascript'>
         <!--// 
      $eventful_string = '$eventful';
      $scope_string = '$scope';
      $category_string = '$category';
      events_page_link = '$events_page_link';
         //-->
         </script>";

      // we add the list if wanted (only for "before" or "after")
      $locations = eme_get_locations((bool)$eventful,$scope,$category,$scope_offset);
      $loc_list = "<div id='eme_div_locations_list_$id_base' class='eme_div_locations_list'><ol id='eme_locations_list_$id_base' class='eme_locations_list'>"; 
      $firstletter="A";
      foreach($locations as $location) {
         if ($show_locations) {
            $loc_list.="<li id='location-". $location['location_id']."_$id_base".
                           "' style='list-style-type: upper-alpha'><a>".
                           eme_trans_sanitize_html($location['location_name'])."</a></li>";
         }
         if ($show_events) {
            $events = eme_get_events(0,$scope,"ASC",$scope_offset,$location['location_id'], $category);
            $loc_list .= "<ol id='eme_events_list'>"; 
            foreach ($events as $event) {
               if ($show_locations)
                  $loc_list.="<li id='location-". $location['location_id']."_$id_base".
                           "' style='list-style-type: none'>- <a>".
                           eme_trans_sanitize_html($event['event_name'])."</a></li>";
               else
                  $loc_list.="<li id='location-". $location['location_id']."_$id_base".
                           "' style='list-style-type: none'>$firstletter. <a>".
                           eme_trans_sanitize_html($event['event_name'])."</a></li>";
            }
            $loc_list.= "</ol>"; 
         }
         // cool: we can increment strings in php, so we can mimic the CSS "style='list-style-type: upper-alpha'" thingie
         // usefull when we show events (more than one event per location)
         $firstletter++;
      }
      $loc_list .= "</ol></div>"; 
      if ($list_location=="before") {
         $result = $loc_list.$result;
      } elseif ($list_location=="after") {
         $result .= $loc_list;
      }
   } else {
      $result = "";
   }
   return $result;
}

function eme_single_location_map_shortcode($atts){
   extract ( shortcode_atts ( array ('id'=>'','width' => 0, 'height' => 0), $atts ) );
   $location=eme_get_location($id);
   $map_div = eme_single_location_map($location, $width, $height);
   return $map_div;
}

function eme_display_single_location($location_id,$template_id=0) {
   $location = eme_get_location( intval($location_id) );
   if ($template_id) {
      $single_location_format= eme_get_template_format($template_id);
   } else {
      $single_location_format = get_option('eme_single_location_format');
   }
   $page_body = eme_replace_locations_placeholders ($single_location_format, $location);
   return $page_body;
}

function eme_get_location_shortcode($atts) {
   extract ( shortcode_atts ( array ('id'=>'','template_id'=>0), $atts ) );
   return eme_display_single_location($id,$template_id);
}

function eme_get_locations_shortcode($atts) {
   global $wpdb, $jquery_override_lang;
   extract(shortcode_atts(array(
      'eventful'  => false,
      'category'  => '',
      'scope'     => 'all',
      'scope'     => 'all',
      'offset'    => 0,
      'template_id' => 0,
      'template_id_header' => 0,
      'template_id_footer' => 0
   ), $atts));
   $eventful = ($eventful==="true" || $eventful==="1") ? true : $eventful;
   $eventful = ($eventful==="false" || $eventful==="0") ? false : $eventful;

   $locations = eme_get_locations((bool)$eventful, $scope, $category, $offset);

   // format is not a locations shortcode, so we need to set the value to "" here, to avoid php warnings
   $format="";
   $eme_format_header="";
   $eme_format_footer="";

   if ($template_id) {
      $format = eme_get_template_format($template_id);
   }
   if ($template_id_header) {
      $format_header = eme_get_template_format($template_id_header);
      $eme_format_header=eme_replace_locations_placeholders($format_header);
   }
   if ($template_id_footer) {
      $format_footer = eme_get_template_format($template_id_footer);
      $eme_format_footer=eme_replace_locations_placeholders($format_footer);
   }
   if (empty($format)) {
      $format = get_option('eme_location_list_format_item' );
      $format = ( $format != '' ) ? $format : "<li class=\"location-#_LOCATIONID\">#_LOCATIONNAME</li>";
      if (empty($eme_format_header)) {
	      $eme_format_header = eme_replace_locations_placeholders(get_option('eme_location_list_format_header' ));
	      $eme_format_header = ( $eme_format_header != '' ) ? $eme_format_header : DEFAULT_LOCATION_LIST_HEADER_FORMAT;
      }
      if (empty($eme_format_footer)) {
	      $eme_format_footer = eme_replace_locations_placeholders(get_option('eme_location_list_format_footer' ));
	      $eme_format_footer = ( $eme_format_footer != '' ) ? $eme_format_footer : DEFAULT_LOCATION_LIST_FOOTER_FORMAT;
      }
   }

   $output = "";
   foreach ($locations as $location) {
      $output .= eme_replace_locations_placeholders($format,$location);
   }
   $output = $eme_format_header . $output . $eme_format_footer;
   $output .= <<<EOD
      <script type="text/javascript">
      //<![CDATA[
      jQuery(document).ready(function() {
         jQuery('#eme_locations.calendar li').each(function(){
               jQuery(this).click(function(){
                  location_id = jQuery(this).attr('class').replace('location-','');
                  jQuery('.location_chosen').text(location_id);
                  prev_month_link = jQuery('.prev-month:first');
                  tableDiv = jQuery(prev_month_link).closest('table').parent();
                  (jQuery(prev_month_link).hasClass('full-link')) ?
                     fullcalendar = 1 : fullcalendar = 0;
                  (jQuery(prev_month_link).hasClass('long_events')) ?
                     long_events = 1 : long_events = 0;
                  loadCalendar(tableDiv, fullcalendar, long_events);
               } );
         } );
      });   
      //]]> 

      </script>
EOD;
   return $output;
}

function eme_replace_locations_placeholders($format, $location="", $target="html", $do_shortcode=1, $lang='') {

   // first we do the custom attributes, since these can contain other placeholders
   preg_match_all("/#(ESC|URL)?_ATT\{.+?\}(\{.+?\})?/", $format, $results);
   foreach($results[0] as $resultKey => $result) {
      $need_escape = 0;
      $need_urlencode = 0;
      $orig_result = $result;
      if (strstr($result,'#ESC')) {
         $result = str_replace("#ESC","#",$result);
         $need_escape=1;
      } elseif (strstr($result,'#URL')) {
         $result = str_replace("#URL","#",$result);
         $need_urlencode=1;
      }
      $replacement = "";
      //Strip string of placeholder and just leave the reference
      $attRef = substr( substr($result, 0, strpos($result, '}')), 6 );
      if (isset($location['location_attributes'][$attRef])) {
         $replacement = $location['location_attributes'][$attRef];
      }
      if( trim($replacement) == ''
            && isset($results[2][$resultKey])
            && $results[2][$resultKey] != '' ) {
         //Check to see if we have a second set of braces;
         $replacement = substr( $results[2][$resultKey], 1, strlen(trim($results[2][$resultKey]))-2 );
      }

      if ($need_escape)
         $replacement = eme_sanitize_request(eme_sanitize_html(preg_replace('/\n|\r/','',$replacement)));
      if ($need_urlencode)
         $replacement = rawurlencode($replacement);
      $format = str_replace($orig_result, $replacement ,$format );
   }

   preg_match_all("/#(ESC|URL)?@?_?[A-Za-z0-9_]+/", $format, $placeholders);
   // make sure we set the largest matched placeholders first, otherwise if you found e.g.
   // #_LOCATION, part of #_LOCATIONPAGEURL would get replaced as well ...
   usort($placeholders[0],'sort_stringlenth');

   foreach($placeholders[0] as $result) {
      $need_escape = 0;
      $need_urlencode = 0;
      $orig_result = $result;
      $found=1;
      if (strstr($result,'#ESC')) {
         $result = str_replace("#ESC","#",$result);
         $need_escape=1;
      } elseif (strstr($result,'#URL')) {
         $result = str_replace("#URL","#",$result);
         $need_urlencode=1;
      }
      $replacement = "";

      // echo "RESULT: $result <br>";
      // matches alla fields placeholder
      if (preg_match('/#_MAP/', $result)) {
         $replacement = eme_single_location_map($location);
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement);
         } elseif ($target == "rss")  {
            $replacement = apply_filters('eme_general_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif (preg_match('/#_PASTEVENTS/', $result)) {
         $replacement = eme_events_in_location_list($location, "past");
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement);
         } elseif ($target == "rss")  {
            $replacement = apply_filters('eme_general_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif (preg_match('/#_NEXTEVENTS/', $result)) {
         $replacement = eme_events_in_location_list($location);
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement);
         } elseif ($target == "rss")  {
            $replacement = apply_filters('eme_general_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif (preg_match('/#_ALLEVENTS/', $result)) {
         $replacement = eme_events_in_location_list($location, "all");
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement);
         } elseif ($target == "rss")  {
            $replacement = apply_filters('eme_general_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif (preg_match('/#_(ADDRESS|TOWN)/', $result)) {
         $field = "location_".ltrim(strtolower($result), "#_");
         if (isset($location[$field]))
            $replacement = $location[$field];
         $replacement = eme_trans_sanitize_html($replacement,$lang);
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement);
         } elseif ($target == "rss")  {
            $replacement = apply_filters('eme_general_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif (preg_match('/#_(NAME|LOCATIONNAME|LOCATION)$/', $result)) {
         $field = "location_name";
         if (isset($location[$field]))
            $replacement = $location[$field];
         $replacement = eme_trans_sanitize_html($replacement,$lang);
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement);
         } elseif ($target == "rss")  {
            $replacement = apply_filters('eme_general_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif (preg_match('/#_LOCATIONID/', $result)) {
         $field = "location_id";
         $replacement = $location[$field];
         $replacement = eme_trans_sanitize_html($replacement,$lang);
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement);
         } elseif ($target == "rss")  {
            $replacement = apply_filters('eme_general_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif (preg_match('/#_(IMAGE|LOCATIONIMAGE)$/', $result)) {
         if (!empty($location['location_image_id']))
            $location['location_image_url'] = wp_get_attachment_url($location['location_image_id']);
         if(!empty($location['location_image_url'])) {
            $replacement = "<img src='".$location['location_image_url']."' alt='".eme_trans_sanitize_html($location['location_name'],$lang)."'/>";
            if ($target == "html") {
               $replacement = apply_filters('eme_general', $replacement);
            } elseif ($target == "rss")  {
               $replacement = apply_filters('eme_general_rss', $replacement);
            } else {
               $replacement = apply_filters('eme_text', $replacement);
            }
         }

      } elseif (preg_match('/#_(IMAGEURL|LOCATIONIMAGEURL)/', $result)) {
         if (!empty($location['location_image_id']))
            $location['location_image_url'] = wp_get_attachment_url($location['location_image_id']);
         if ($location['location_image_url'] != '') {
            $replacement = $location['location_image_url'];
            if ($target == "html") {
               $replacement = apply_filters('eme_general', $replacement);
            } elseif ($target == "rss")  {
               $replacement = apply_filters('eme_general_rss', $replacement);
            } else {
               $replacement = apply_filters('eme_text', $replacement);
            }
         }

      } elseif (preg_match('/#_LOCATIONIMAGETHUMB$/', $result)) {
         if (!empty($location['location_image_id'])) {
            $thumb_array = image_downsize( $location['location_image_id'], get_option('eme_thumbnail_size') );
            $thumb_url = $thumb_array[0];
            $thumb_width = $thumb_array[1];
            $thumb_height = $thumb_array[2];
            $replacement = "<img width='$thumb_width' height='$thumb_height' src='".$thumb_url."' alt='".eme_trans_sanitize_html($location['location_name'],$lang)."'/>";
            if ($target == "html") {
               $replacement = apply_filters('eme_general', $replacement);
            } elseif ($target == "rss")  {
               $replacement = apply_filters('eme_general_rss', $replacement);
            } else {
               $replacement = apply_filters('eme_text', $replacement);
            }
         }

      } elseif (preg_match('/#_LOCATIONIMAGETHUMBURL/', $result)) {
         if (!empty($location['location_image_id'])) {
            $thumb_array = image_downsize( $location['location_image_id'], get_option('eme_thumbnail_size') );
            $thumb_url = $thumb_array[0];
            $replacement = $thumb_url;
            if ($target == "html") {
               $replacement = apply_filters('eme_general', $replacement);
            } elseif ($target == "rss")  {
               $replacement = apply_filters('eme_general_rss', $replacement);
            } else {
               $replacement = apply_filters('eme_text', $replacement);
            }
         }

      } elseif (preg_match('/#_LOCATIONPAGEURL/', $result)) {
         $replacement = eme_location_url($location,$lang);
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement);
         } elseif ($target == "rss")  {
            $replacement = apply_filters('eme_general_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif (preg_match('/#_LATITUDE/', $result)) {
         $field = "location_latitude";
         $replacement = $location[$field];
         $replacement = eme_trans_sanitize_html($replacement,$lang);
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement);
         } elseif ($target == "rss")  {
            $replacement = apply_filters('eme_general_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif (preg_match('/#_LONGITUDE/', $result)) {
         $field = "location_longitude";
         $replacement = $location[$field];
         $replacement = eme_trans_sanitize_html($replacement,$lang);
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement);
         } elseif ($target == "rss")  {
            $replacement = apply_filters('eme_general_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif (preg_match('/#_DIRECTIONS/', $result)) {
         $replacement = eme_add_directions_form($location);
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement);
         } elseif ($target == "rss")  {
            $replacement = apply_filters('eme_general_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif (preg_match('/#_CATEGORIES|#_LOCATIONCATEGORIES/', $result) && get_option('eme_categories_enabled')) {
         $categories = eme_get_location_categories($location['location_id']);
         $replacement = eme_trans_sanitize_html(join(", ",$categories),$lang);
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement);
         } elseif ($target == "rss")  {
            $replacement = apply_filters('eme_general_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif (preg_match('/#_EDITLOCATIONLINK/', $result)) {
         if (current_user_can( get_option('eme_cap_edit_locations')) ||
            (current_user_can( get_option('eme_cap_author_locations')) && ($location['location_author']==$current_userid))) {
            $replacement = "<a href=' ".admin_url("admin.php?page=eme-locations&amp;eme_admin_action=edit_location&amp;location_id=".$location['location_id'])."'>".__('Edit')."</a>";
         }

      } elseif (preg_match('/#_EDITLOCATIONURL/', $result)) {
         if (current_user_can( get_option('eme_cap_edit_locations')) ||
            (current_user_can( get_option('eme_cap_author_locations')) && ($location['location_author']==$current_userid))) {
            $replacement = admin_url("admin.php?page=eme-locations&amp;eme_admin_action=edit_location&amp;location_id=".$location['location_id']);
         }

      } elseif (preg_match('/#_IS_SINGLE_LOC/', $result)) {
         if (eme_is_single_location_page())
            $replacement = 1;
         else
            $replacement = 0;

      } elseif (preg_match('/#_IS_LOGGED_IN/', $result)) {
         if (is_user_logged_in())
            $replacement = 1;
         else
            $replacement = 0;

      } elseif (preg_match('/#_IS_ADMIN_PAGE/', $result)) {
         if (is_admin())
            $replacement = 1;
         else
            $replacement = 0;

      } else {
         $found = 0;
      }

      if ($found) {
         if ($need_escape)
            $replacement = eme_sanitize_request(eme_sanitize_html(preg_replace('/\n|\r/','',$replacement)));
         if ($need_urlencode)
            $replacement = rawurlencode($replacement);
         $format = str_replace($orig_result, $replacement ,$format );
      }
   }

   # we handle DESCRIPTION the last, so no placeholder replacement happens accidentaly in the text of #_DESCRIPTION
   if (preg_match('/#_DESCRIPTION|#_LOCATIONDETAILS/', $format, $placeholders)) {
      $result=$placeholders[0];
      $need_escape = 0;
      $need_urlencode = 0;
      $orig_result = $result;
      if (strstr($result,'#ESC')) {
         $result = str_replace("#ESC","#",$result);
         $need_escape=1;
      } elseif (strstr($result,'#URL')) {
         $result = str_replace("#URL","#",$result);
         $need_urlencode=1;
      }
      $replacement = "";

      $field = "location_description";
      if (isset($location[$field]))
         $replacement = $location[$field];

      // no real sanitizing needed, but possible translation
      // this is the same as for an event in fact
      $replacement = eme_translate($replacement);
      if ($target == "html")
         $replacement = apply_filters('eme_notes', $replacement);
      elseif ($target == "rss") {
         $replacement = apply_filters('eme_notes_rss', $replacement);
         $replacement = apply_filters('the_content_rss', $replacement);
      } else {
         $replacement = apply_filters('eme_text', $replacement);
      }

      if ($need_escape)
         $replacement = eme_sanitize_request(eme_sanitize_html(preg_replace('/\n|\r/','',$replacement)));
      if ($need_urlencode)
         $replacement = rawurlencode($replacement);
      $format = str_replace($orig_result, $replacement ,$format );
   }

   // now, replace any language tags found
   $format = eme_translate($format,$lang);

   // and now replace any shortcodes, if wanted
   if ($do_shortcode)
      return do_shortcode($format);   
   else
      return $format;   
}

function eme_add_directions_form($location) {
   $locale_code = substr ( get_locale (), 0, 2 );
   $res = "";
   if (isset($location['location_address']) && isset($location['location_town'])) {
      $res .= '<form action="//maps.google.com/maps" method="get" target="_blank" style="text-align:left;">';
      $res .= '<div id="eme_direction_form"><label for="saddr">'.__('Your Street Address','eme').'</label><br />';
      $res .= '<input type="text" name="saddr" id="saddr" value="" />';
      $res .= '<input type="hidden" name="daddr" value="'.$location['location_address'].', '.$location['location_town'].'" />';
      $res .= '<input type="hidden" name="hl" value="'.$locale_code.'" /></div>';
      $res .= '<input type="submit" value="'.__('Get Directions','eme').'" />';
      $res .= '</form>';
   }

   # some people might want to change the form to their liking
   if (has_filter('eme_directions_form_filter')) $res=apply_filters('eme_directions_form_filter',$res);

   return $res;
}

function eme_single_location_map($location,$width=0,$height=0) {
   global $eme_need_gmap_js;

   $gmap_is_active = get_option('eme_gmap_is_active'); 
   $map_text = addslashes(eme_replace_locations_placeholders(get_option('eme_location_baloon_format'), $location));
   # no newlines allowed, otherwise no map is shown
   $map_text = preg_replace("/\r\n?|\n\r?/","<br />",$map_text);
   // if gmap is not active: we don't show the map
   // if the location name is empty: we don't show the map. But that can never happen since it's checked when creating the location
   if ($gmap_is_active) {
      $eme_need_gmap_js=1;
      //$id_base = $location['location_id'];
      // we can't create a unique <div>-id based on location id alone, because you can have multiple maps on the sampe page for
      // different events but they can go to the same location...
      // So we also use the event_id (if present) and the microtime for this, and replace all non digits by underscore (otherwise the generated javascript will error)
      $id_base = preg_replace("/\D/","_",microtime(1));
      // the next is only possible when called from within an event (events-manager.php)
      if (isset($location['event_id'])) {
         $id_base = $location['event_id']."_".$id_base;
      } else {
         $id_base = rand()."_".$id_base;
      }
      $id="eme-location-map_".$id_base;
      $latitude_string="latitude_".$id_base;
      $longitude_string="longitude_".$id_base;
      $map_text_string="map_text_".$id_base;
      $zoom_factor_string="zoom_factor_".$id_base;
      $maptype_string="maptype_".$id_base;
      $enable_zooming_string="enable_zooming_".$id_base;
      $enable_zooming=get_option('eme_gmap_zooming') ? 'true' : 'false';
      $zoom_factor=get_option('eme_indiv_zoom_factor');
      $maptype=get_option('eme_indiv_maptype');
      if ($zoom_factor >14) $zoom_factor=14;
      #$latitude_string="latitude";
      #$longitude_string="longitude";
         //$map_div = "<div id='$id' style=' background: green; width: 400px; height: 300px'></div>" ;
      if (!empty($width) && !empty($height)) {
	 if (!preg_match('/\%$|px$/',$width)) $width=$width."px";
	 if (!preg_match('/\%$|px$/',$height)) $height=$height."px";
         $map_div = "<div id='$id' style='width: $width; height: $height' ></div>" ;
      } else {
         $map_div = "<div id='$id' class='eme-location-map'></div>" ;
      }
      $map_div .= "<script type='text/javascript'>
      <!--// 
      $latitude_string = parseFloat('".$location['location_latitude']."');
      $longitude_string = parseFloat('".$location['location_longitude']."');
      $map_text_string = '$map_text';
      $enable_zooming_string = '$enable_zooming';
      $zoom_factor_string = $zoom_factor;
      $maptype_string = '$maptype';
      //-->
      </script>";
      // $map_div .= "<script src='".EME_PLUGIN_URL."eme_single_location_map.js' type='text/javascript'></script>";
   } else {
      $map_div = "";
   }
   return $map_div;
}

function eme_events_in_location_list($location, $scope = "") {
   $eme_event_list_number_events=get_option('eme_event_list_number_items' );
   $events = eme_get_events($eme_event_list_number_events,$scope,"","",$location['location_id']);
   $list = "";
   if (count($events) > 0) {
      foreach($events as $event)
         $list .= eme_replace_placeholders(get_option('eme_location_event_list_item_format'), $event);
   } else {
      $list = get_option('eme_location_no_events_message');
   }
   return $list;
}

function eme_locations_search_ajax() {
   if(isset($_GET['id']) && $_GET['id'] != "") {
      $item = eme_get_location($_GET['id']);
      $record = array();
      $record['id']      = $item['location_id'];
      $record['name']    = eme_trans_sanitize_html($item['location_name']); 
      $record['address'] = eme_trans_sanitize_html($item['location_address']);
      $record['town']    = eme_trans_sanitize_html($item['location_town']); 
      $record['latitude']    = eme_trans_sanitize_html($item['location_latitude']); 
      $record['longitude']    = eme_trans_sanitize_html($item['location_longitude']); 
      echo json_encode($record);

   } else {

      $locations = eme_get_locations();
      $return = array();

      if (!isset($_GET["q"])) {
         echo json_encode($return);
         return;
      }

      foreach($locations as $item) {
         $record = array();
         $record['id']      = $item['location_id'];
         $record['name']    = eme_trans_sanitize_html($item['location_name']); 
         $record['address'] = eme_trans_sanitize_html($item['location_address']);
         $record['town']    = eme_trans_sanitize_html($item['location_town']); 
         $record['latitude']    = eme_trans_sanitize_html($item['location_latitude']); 
         $record['longitude']    = eme_trans_sanitize_html($item['location_longitude']); 
         $return[]  = $record;
      }

      $q = strtolower($_GET["q"]);
      if (!$q) return;

      $result=array();
      foreach($return as $row) {
         if (strpos(strtolower($row['name']), $q) !== false)
            $result[]=$row;
      }
      echo json_encode($result);
   }
}

?>
