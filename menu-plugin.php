<?php
/*
Plugin Name: Menu Ícon RFF
Plugin URI: http://exemplo.com
Description: Plugin para adicionar um menu com 3 níveis.
Version: 2.0
Author: Robson Ferreira de Farias
Email: robsonfdfarias@gmail.com
Author URI: http://infocomrobson.com.br
License: GPL2
*/


define('MICON_RFF_DIR_INC', dirname(__FILE__).'/inc/');
define('MICON_RFF_URL_CSS', plugins_url('css/', __FILE__));
define('MICON_RFF_URL_JS', plugins_url('js/', __FILE__));
define('MICON_RFF_TABLE_CATEG', 'micon_rff_categ');
define('MICON_RFF_TABLE_ITEMS', 'micon_rff_items');

if(file_exists(plugin_dir_path(__FILE__).'inc/micon_rff_class_icons.php')){
    require_once(plugin_dir_path(__FILE__).'inc/micon_rff_class_icons.php');
}
if(file_exists(plugin_dir_path(__FILE__).'inc/micon_rff_hooks.php')){
    require_once(plugin_dir_path(__FILE__).'inc/micon_rff_hooks.php');
    register_activation_hook(__FILE__, 'micon_rff_install');
    register_deactivation_hook(__FILE__, 'micon_rff_uninstall');
}
if(file_exists(plugin_dir_path(__FILE__).'inc/micon_rff_graphql.php')){
    require_once(plugin_dir_path(__FILE__).'inc/micon_rff_graphql.php');
    add_action('graphql_register_types', 'gmp_register_graphql_menu');
    add_action('graphql_register_types', 'gmp_register_graphql_connection_types');
}


if(file_exists(plugin_dir_path(__FILE__).'micon-rff-core.php')){
    require_once(plugin_dir_path(__FILE__).'micon-rff-core.php');
}

 // Adiciona o CSS e JS
 function micon_rff_adicionar_scripts() {
    wp_enqueue_style('micon-rff-modal-css', plugin_dir_url(__FILE__) . 'css/micon_rff_core.css');
    wp_enqueue_script('micon-rff-modal-js', plugin_dir_url(__FILE__) . 'js/micon_rff_functions.js', array('jquery'), null, true);
}
  
  add_action('admin_enqueue_scripts', 'micon_rff_adicionar_scripts');



//adicionar estilos personalizados
function gmp_enqueue_styles() {
    wp_enqueue_style('gmp-style', plugins_url('style.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'gmp_enqueue_styles');
