<?php

define('CHT_URI', get_template_directory_uri());
define('CHT_BACKEND_ROOT', get_template_directory() . '/backend');
define('CHT_THEME_ROOT', get_template_directory());
define('CHT_BASE', CHT_THEME_ROOT . '/base/');
define('CHT_BACKEND_URI', CHT_URI . '/backend' );
define('CHT_POST_META_TABS_DIR', CHT_BACKEND_ROOT . '/views/post-meta/post-tabs' );
define('CHT_PAGE_META_TABS_DIR', CHT_BACKEND_ROOT . '/views/page-meta/page-tabs' );
define('CHT_VIEWS_DIR', CHT_BACKEND_ROOT . '/views/' );
define('CHT_THEME_OPTIONS_TABS_DIR', CHT_VIEWS_DIR . 'theme-options/tabs' );
define('CHT_FRONT_PUBLIC_URI', CHT_URI . '/public/' );
define('CHT_BACKEND_PUBLIC_URI', CHT_BACKEND_URI . '/public/' );
define('CHT_ADMIN_CAPABILITY', 'manage_options');
define('CHT_VIEW_FILE_TYPE', '.phtml');
define('CHT_APP_NAME', 'Carry Hill');
define('CHT_APP_PREFIX', 'cht_');
define('CHT_APP_TEXT_DOMAIN', 'cht_default');
