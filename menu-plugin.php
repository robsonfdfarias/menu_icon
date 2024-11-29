<?php
/*
Plugin Name: Menu Ícon RFF
Plugin URI: https://github.com/robsonfdfarias/menu_icon
Description: Plugin para adicionar um menu com 3 níveis.
Version: 3.1
Author: Robson Ferreira de Farias
Email: robsonfdfarias@gmail.com
Author URI: http://infocomrobson.com.br/site
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
if(file_exists(plugin_dir_path(__FILE__).'inc/micon_rff_shortcode.php')){
    require_once(plugin_dir_path(__FILE__).'inc/micon_rff_shortcode.php');
}


if(file_exists(plugin_dir_path(__FILE__).'micon-rff-core.php')){
    require_once(plugin_dir_path(__FILE__).'micon-rff-core.php');
}

 // Adiciona o CSS e JS
 function micon_rff_adicionar_scripts_admin() {
    wp_enqueue_style('micon-rff-admin-css', plugin_dir_url(__FILE__) . 'css/micon_rff_admin.css');
    wp_enqueue_script('micon-rff-modal-js', plugin_dir_url(__FILE__) . 'js/micon_rff_functions.js', array('jquery'), null, true);
}

// Adiciona o CSS e JS
function micon_rff_adicionar_scripts_core() {
   wp_enqueue_style('micon-rff-modal-core-css', plugin_dir_url(__FILE__) . 'css/micon_rff_core.css');
   wp_enqueue_script('micon-rff-modal-core-js', plugin_dir_url(__FILE__) . 'js/micon_rff_functions.js', array('jquery'), null, true);
}
  
  add_action('admin_enqueue_scripts', 'micon_rff_adicionar_scripts_admin');
  add_action('wp_enqueue_scripts', 'micon_rff_adicionar_scripts_core');



//adicionar estilos personalizados
function gmp_enqueue_styles() {
    wp_enqueue_style('gmp-style', plugins_url('style.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'gmp_enqueue_styles');
