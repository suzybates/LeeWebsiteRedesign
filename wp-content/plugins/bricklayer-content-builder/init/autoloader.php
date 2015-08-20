<?php

function cbp_autoload($class_name)
{
    if (!class_exists($class_name, false)) {

        if (file_exists(CBP_BASE . $class_name . '.php')) {
            require_once CBP_BASE . $class_name . '.php';
        }

        else if (file_exists(CBP_BACKEND_ROOT . '/controllers/' . $class_name . '.php')) {
            require_once CBP_BACKEND_ROOT . '/controllers/' . $class_name . '.php';
        }

        else if (file_exists(CBP_BACKEND_ROOT . '/models/' . $class_name . '.php')) {
            require_once CBP_BACKEND_ROOT . '/models/' . $class_name . '.php';
        }
        
        else if (file_exists(CBP_BACKEND_ROOT . '/models/widgets/' . $class_name . '.php')) {
            require_once CBP_BACKEND_ROOT . '/models/widgets/' . $class_name . '.php';
        }

    }
}
spl_autoload_register('cbp_autoload');