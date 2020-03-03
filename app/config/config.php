<?php

// URL base do site.
defined('BASE_URL') OR define('BASE_URL', '');

// URL base do storange
defined('BASE_STORANGE') OR define('BASE_STORANGE', '');

// Session | Caso deseje que a session seja iniciada em todas as páginas
// Apenas mude a constante para true.
defined("OPEN_SESSION") OR define('OPEN_SESSION', false);


$pluginsAutoLoad = [
    "bootstrap-grid" => [
        "js" => null,
        "css" => ["bootstrap-grid.min"]
    ],
//    "bootstrap" => [
//        "js" => ["js/bootstrap.min","js/popper.min"],
//        "css" => ["css/bootstrap.min"]
//    ],
    "jquery" => [
        "js" => ["jquery-3.4.1.min"],
        "css" => null
    ],
    "sweetalert" => [
        "js" => ["sweetalert2.all"],
        "css" => null,
    ],
    "owl-carousel" => [
        "js" => ["owl.carousel.min"],
        "css" => ["owl.carousel.min"]
    ],
    "mascara" => [
        "js" => ["mascara"],
        "css" => null,
    ],
    "dropify" => [
        "js" => ["js/dropify.min"],
        "css" => ["css/dropify.min"],
    ]
];

// Salva como constant
defined("PLGUINS_AUTOLOAD") OR define("PLGUINS_AUTOLOAD", serialize($pluginsAutoLoad));