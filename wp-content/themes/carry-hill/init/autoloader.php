<?php

function cht_autoload($class_name)
{
    if (!class_exists($class_name, false)) {

        if (file_exists(CHT_BASE . $class_name . '.php')) {
            require_once CHT_BASE . $class_name . '.php';
        }

        else if (file_exists(CHT_THEME_ROOT . '/backend/controllers/' . $class_name . '.php')) {
            require_once CHT_THEME_ROOT . '/backend/controllers/' . $class_name . '.php';
        }

        else if (file_exists(CHT_THEME_ROOT . '/backend/models/' . $class_name . '.php')) {
            require_once CHT_THEME_ROOT . '/backend/models/' . $class_name . '.php';
        }

    }
}
spl_autoload_register('cht_autoload');
