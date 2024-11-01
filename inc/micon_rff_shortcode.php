<?php

/**
 * Shortcode
 */

 if(!defined('WPINC')){
    die();
 }


if(file_exists(MICON_RFF_DIR_INC.'micon_rff_class_db.php')){
    include_once(MICON_RFF_DIR_INC.'micon_rff_class_db.php');
}

$mIconRffDb = new MIconRffDB();

function micon_rff_model1($atts){
    global $mIconRffDb;
    $atts = shortcode_atts(array(
        'idcat'=>1
    ), $atts);
    $val = $mIconRffDb->getAllItemsArray();
    $html = '<div style="position:absolute;width:100%;left:0;top:15px;font-size:0.8rem;">'.$val.'</div>';
    return $html;
}

function micon_rff_register_shortcodes(){
    add_shortcode('micon_rff_1', 'micon_rff_model1');
}
add_action('init', 'micon_rff_register_shortcodes');