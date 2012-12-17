<?php
    $layout_width = intval ( filter_input(INPUT_GET, 'layout_width', FILTER_SANITIZE_NUMBER_INT) );
    if ( $layout_width > 0 ) { /* Has legitimate width */ } else { $layout_width = 940; } // Default Width
    header("Content-type: text/css; charset: UTF-8");
?>
@media only screen and (min-width: 768px) {
}


