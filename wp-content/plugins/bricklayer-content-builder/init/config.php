<?php
require_once 'globals.php';
require_once 'autoloader.php';
require_once 'helpers.php';
require_once 'CbpBootstrap.php';
require_once(CBP_BACKEND_ROOT . '/models/CbpWidgets.php' );
require_once(CBP_BACKEND_ROOT . '/models/CbpWidget.php' );

$bootstrap = new CbpBootstrap(); 

CbpEditor::init();
CbpWidgetFormElements::init();
