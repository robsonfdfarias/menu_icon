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
    $table_categ = $wpdb->prefix.DOWNLOAD_RFF_TABLE_CATEG;
    $sqlCateg = "CREATE TABLE IF NOT EXISTS $table_categ (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title varchar(200) NOT NULL,
        statusItem varchar(20),
        PRIMARY KEY (id)
    ) $charset_collate;";
    dbDelta($sqlCateg);
    $table_item = $wpdb->prefix.DOWNLOAD_RFF_TABLE_ITEMS;
    $sqlItem = "CREATE TABLE IF NOT EXISTS $table_item (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title varchar(200) NOT NULL,
        content TEXT NOT NULL,
        urlPage varchar(200) NOT NULL,
        urlDoc varchar(200) NOT NULL,
        startDate varchar(20) NOT NULL,
        endDate varchar(20) NOT NULL,
        category mediumint(9) NOT NULL,
        clicks varchar(200) NOT NULL,
        tags TEXT NOT NULL,
        statusItem varchar(20),
        dateUp varchar(20),
        orderItems mediumint(9),
        PRIMARY KEY (id)
    ) $charset_collate;";
    dbDelta($sqlItem);
 }

 function micon_rff_uninstall(){
    global $wpdb;
    $table_categ = $wpdb->prefix.DOWNLOAD_RFF_TABLE_CATEG;
    $sqlCateg = "DROP TABLE IF EXISTS $table_categ;";
    $wpdb->query($sqlCateg);
    $table_item = $wpdb->prefix.DOWNLOAD_RFF_TABLE_ITEMS;
    $sqlItem = "DROP TABLE IF EXISTS $table_item;";
    $wpdb->query($sqlItem);
 }