<?php

/*
* Núcleo do plugin
*/

 //se chamar diretamente e não pelo wordpress, aborta
 if(!defined('WPINC')){
    die();
 }


 function micon_rff_install(){
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $charset_collate = $wpdb->get_charset_collate();
    $table_categ = $wpdb->prefix.MICON_RFF_TABLE_CATEG;
    $sqlCateg = "CREATE TABLE IF NOT EXISTS $table_categ (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title varchar(200) NOT NULL,
        statusItem varchar(20),
        PRIMARY KEY (id)
    ) $charset_collate;";
    dbDelta($sqlCateg);
    $table_item = $wpdb->prefix.MICON_RFF_TABLE_ITEMS;
    $sqlItem = "CREATE TABLE IF NOT EXISTS $table_item (
        id mediumint(18) NOT NULL AUTO_INCREMENT,
        title varchar(240) NOT NULL,
        urlPage varchar(240) NOT NULL,
        category mediumint(9) NOT NULL,
        statusItem varchar(15),
        iconClass varchar(100) NOT NULL,
        orderItems mediumint(4),
        parentId mediumint(18) DEFAULT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (category) REFERENCES $table_categ(id) ON DELETE CASCADE
    ) $charset_collate;";
    dbDelta($sqlItem);
 }

 function micon_rff_uninstall(){
    global $wpdb;
    $table_categ = $wpdb->prefix.MICON_RFF_TABLE_CATEG;
    $sqlCateg = "DROP TABLE IF EXISTS $table_categ;";
    $resutCat = $wpdb->query($sqlCateg);
    $table_item = $wpdb->prefix.MICON_RFF_TABLE_ITEMS;
    $sqlItem = "DROP TABLE IF EXISTS $table_item;";
    $resultIt = $wpdb->query($sqlItem);
    if($resultCat!==false){
        //excluído com sucesso
    }
    if($resultIt!==false){
        //excluído com sucesso
    }
 }