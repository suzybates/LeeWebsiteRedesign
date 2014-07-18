<?php
/**
 * @package WordPress
 * @subpackage Carry Hill
 */
?>
<form class="search-form double-pad-top double-pad-bottom" action="<?php  echo esc_url( home_url( '/' ) ) ?>" method="get">
    <input class="pull-left" type="text" name="s" id="s" value="<?php the_search_query(); ?>" />
    <button class="pull-left" type="submit"><i class="fa fa-search"></i></button>
</form>
