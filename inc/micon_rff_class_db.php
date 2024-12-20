<?php

include_once(MICON_RFF_DIR_INC.'micon_rff_class_db_categ.php');

class MIconRffDB{
    private $dbCat;
    private $tableItens;
    private $db;
    function __construct(){
        global $wpdb;
        $this->tableItens = $wpdb->prefix.MICON_RFF_TABLE_ITEMS;
        $this->db = $wpdb;
        $this->dbCat = new MIconRffDbCat();
    }
    function getChild($item, $array){
        $tableItensMIconRff = $this->tableItens;
        // global $wpdb; //modifiquei o $wpdb->get_results por $this->db->get_results
        $re = $this->db->get_results("SELECT * FROM {$tableItensMIconRff} WHERE parentId={$item->id}");
        for($i=0;$i<count($re); $i++){
            // echo $re[$i]->title;
            // echo '<br><br>';
            $newAr = [];
            $newAr[$i] = $re[$i];
            $newAr[$i]->children = $this->getChild($re[$i], []);
            $array[$i] = $newAr;
        }
        return $array;
    }
    function generateArrayAllItems($where){
        // $all_items = $this->db->get_results("SELECT * FROM {$this->tableItens}", ARRAY_A);
        $all_items = $this->db->get_results("SELECT * FROM {$this->tableItens}$where");

        $ar = [];
        // $unique_items = [];
        $item_map = [];

        foreach ($all_items as $item) {
            // Pega apenas os itens pais
            if($item->parentId==null || $item->parentId==0){
                $item_map[$item->id] = $item;
                $item_map[$item->id]->children = $this->getChild($item, []); // pega os itens filhos
            }
        }
        // echo '<pre>'.print_r($item_map, true).'</pre>';
        return $item_map;
    }
    function printMenu($items, $class, $marc) {
        echo '<ul style="padding-left:10px;" class="'.$class.'">';
        $m = '';
        for($k=0;$k<$marc;$k++){
            $m.='-';
        }
        foreach ($items as $item) {
            // Imprime o título do item
            if(!empty($item->title)){
                echo '<li><div>'.$m.' <span class="'.$item->iconClass.'" style="font-size: 17px; padding-right: 5px;" title="'.$item->title
                .'"></span> <a href="?page=Menu-icon-rff&id='.$item->id.'">' . htmlspecialchars($item->title).'</a></div>';
                // Verifica se há filhos e chama a função recursivamente
                        $marc++;
                if (!empty($item->children)) {
                    // echo '<ol class="'.$class.$class.'1">';
                    for($r=0;$r<count($item->children); $r++){
                        // echo '<li>';
                        $this->printMenu($item->children[$r], $class.$class, $marc);
                        // echo '</li>';
                    }
                    // echo '</ol>';
                }
                echo '</li>';
            }
        }
        echo '</ul>';
    }
    function getAllItemsArrayAdminPage(){
        $item_map=$this->generateArrayAllItems(' ORDER BY category ASC');
        echo '<div style="display:flex;flex-wrap:wrap;justify-content:left; gap:10px;">';
        $timeSleep = 0;
        foreach($item_map as $item){
            if(!empty($item->title)){
                $timeSleep += 1;
                $cat = $this->dbCat->getCatById($item->category);
                echo '<div class="micon_rff_div_menu" style="--timesleep:'.$timeSleep.'s">';
                    echo '<span style="font-size:1.5rem;">'.htmlspecialchars($item->title).'</span>';
                    echo '<br>Categoria: <strong>'.$cat->title.'</strong><br>';
                    echo '<ul>';
                    echo '<li><div>- <span class="'.$item->iconClass.'" style="font-size: 17px; padding-right: 5px;" title="'.$item->title.'"></span><a href="?page=Menu-icon-rff&id='.$item->id.'">' . htmlspecialchars($item->title).'</a></div>';
                    if (!empty($item->children)) {
                        echo '<ul>';
                        for($r=0;$r<count($item->children); $r++){
                            echo '<li>';
                            $this->printMenu($item->children[$r], '', 2);
                            echo '</li>';
                        }
                        echo '</ul>';
                    }
                    echo '</li>';
                    echo '</li>';
                    echo '</ul>';
                echo '</div>';
            }
        }
        echo '</div>';
        // printMenu($item_map[1]->children[0]);
    }

    // function printMenu2($items, $class) {
    //     // echo '<li>';
    //     foreach ($items as $item) {
    //         // Imprime o título do item
    //         if(!empty($item->title)){
    //             echo '<li><span class="'.$item->iconClass.'" style="font-size: 20px; padding-right: 5px;"></span><a href="?page=Menu-icon-rff&id='.$item->id.'">' . htmlspecialchars($item->title).'</a>';
    //             // Verifica se há filhos e chama a função recursivamente
    //             if (!empty($item->children)) {
    //                 echo '<ul class="miconsRffUlLiAll '.$class.$class.'">';
    //                 for($r=0;$r<count($item->children); $r++){
    //                     $this->printMenu2($item->children[$r], $class.$class);
    //                 }
    //                 echo '</ul>';
    //             }
    //             echo '</li>';
    //         }
    //     }
    //     // echo '</li>';
    // }

    // function getAllItemsArray(){
    //     $item_map=$this->generateArrayAllItems('');
    //     echo '<ul class="miconsRffUlLiAll n">';
    //     $this->printMenu2($item_map, 'n');
    //     echo '</ul>';
    // }


    function printMenu2($items, $class) {
        // echo '<li>';
        $cl = '';
        for($i=0;$i<$class;$i++){
            $cl .= 'n';
        }
        $val = '';
        foreach ($items as $item) {
            // Imprime o título do item
            if(!empty($item->title)){
                $url = $this->verifyIfPagOrURL($item->urlPage);
                $val .= '<li><span class="'.$item->iconClass.'" style="font-size: 20px; padding-right: 5px;"></span><a href="'.$url.'">' . htmlspecialchars($item->title).'</a>';
                // Verifica se há filhos e chama a função recursivamente
                if (!empty($item->children)) {
                    // $val .= '<ul class="miconsRffUlLiAll '.$class.$class.'">';
                    $val .= '<ul class="'.$cl.' miconsRffUlLiAll">';
                    for($r=0;$r<count($item->children); $r++){
                        $val .= $this->printMenu2($item->children[$r], $class+1);
                    }
                    $val .= '</ul>';
                }
                $val .= '</li>';
            }
        }
        return $val;
        // echo '</li>';
    }

    function verifyIfPagOrURL($id){
        $url = $id;
        if(ctype_digit($id)){
            $page = get_post($id);
            if($page){
                $url = get_permalink($id);
            }
        }
        return $url;
    }

    function getAllItemsArray($idCat){
        $val = '';
        $item_map=$this->generateArrayAllItems(' WHERE category='.$idCat);
        $val .= '<ul class="n miconsRffUlLiAll">';
        // $val .= $this->printMenu2($item_map, 'n');
        $val .= $this->printMenu2($item_map, 2);
        $val .= '</ul>';
        return $val;
    }
    function getAllItemsArray2(){
        return 'Olá.......';
    }





    function printMenuOption($items, $marc) {
        $m = '';
        for($i=0;$i<$marc;$i++){
            $m .= '-';
        }
        $val = '';
        foreach ($items as $item) {
            if(!empty($item->title)){
                $val .= '<option value="'.$item->id.'">'.$m.$item->title.'</option>';
                // Verifica se há filhos e chama a função recursivamente
                if (!empty($item->children)) {
                    for($r=0;$r<count($item->children); $r++){
                        $val .= $this->printMenuOption($item->children[$r], $marc+1);
                    }
                }
            }
        }
        return $val;
    }

    function getAllItemsForSelectTag(){
        $all_items = $this->generateArrayAllItems(' ORDER BY category ASC');
        $options = '';
        foreach($all_items as $item){
            $options .= '<option value="'.$item->id.'" style="background-color:black;color:white;">'.$item->title.'</option>';
            if (!empty($item->children)) {
                for($r=0;$r<count($item->children); $r++){
                    $options .= $this->printMenuOption($item->children[$r], 1);
                }
            }
        }
        return $options;
    }

    function getItemForId($id){
        $item = $this->db->get_results("SELECT * FROM {$this->tableItens} WHERE id={$id}");
        return $item;
    }

    function insertIconRff(){
        if(isset($_POST['insertMenuRff'])){
            if(isset($_POST['menu_icon_rff_title']) && 
            isset($_POST['menu_icon_rff_url']) && 
            isset($_POST['menu_icon_rff_parent']) && 
            isset($_POST['menu_icon_rff_orderItems']) && 
            isset($_POST['menu_icon_rff_status']) && 
            isset($_POST['menu_icon_rff_cat']) && 
            isset($_POST['fieldIconRff'])){
                //aplica o sanitize_text_field
                $miconrffTitle = sanitize_text_field($_POST['menu_icon_rff_title']);
                $miconrffUrl = sanitize_text_field($_POST['menu_icon_rff_url']);
                $miconrffParent = sanitize_text_field($_POST['menu_icon_rff_parent']);
                $miconrffIcon = sanitize_text_field($_POST['fieldIconRff']);
                $miconrffOrderItems = sanitize_text_field($_POST['menu_icon_rff_orderItems']);
                $miconrffStatus = sanitize_text_field($_POST['menu_icon_rff_status']);
                $miconrffCat = sanitize_text_field($_POST['menu_icon_rff_cat']);
                $result = $this->db->insert(
                    $this->tableItens,
                    array(
                        'title' => $miconrffTitle,
                        'urlPage' => $miconrffUrl,
                        'category' => $miconrffCat,
                        'statusItem' => $miconrffStatus,
                        'iconClass' => $miconrffIcon,
                        'orderItems' => $miconrffOrderItems,
                        'parentId' => $miconrffParent,
                    )
                );
                if($result<=0 || $result==false){
                    echo '<div class="notice notice-failure is-dismissible"><p>Não foi possível inserir o item de menu. Erro: '.$this->db->last_error.'</p></div>';
                }else{
                    echo '<div class="notice notice-success is-dismissible"><p>Item de menu <strong>inserido</strong> com sucesso!</p></div>';
                }
            }
        }else{
            // echo 'NEUTRO....---'.$n;
        }
    }

    function updateIconRff(){
        if(isset($_POST['updateMenuRff'])){
            if(isset($_POST['menu_icon_rff_id']) && isset($_POST['menu_icon_rff_title']) && 
            isset($_POST['menu_icon_rff_url']) && 
            isset($_POST['menu_icon_rff_parent']) && 
            isset($_POST['menu_icon_rff_orderItems']) && 
            isset($_POST['menu_icon_rff_status']) && 
            isset($_POST['menu_icon_rff_cat']) && 
            isset($_POST['fieldIconRff'])){
                //aplica o sanitize_text_field
                $id = sanitize_text_field($_POST['menu_icon_rff_id']);
                $miconrffTitle = sanitize_text_field($_POST['menu_icon_rff_title']);
                $miconrffUrl = sanitize_text_field($_POST['menu_icon_rff_url']);
                $miconrffParent = sanitize_text_field($_POST['menu_icon_rff_parent']);
                $miconrffIcon = sanitize_text_field($_POST['fieldIconRff']);
                $miconrffOrderItems = sanitize_text_field($_POST['menu_icon_rff_orderItems']);
                $miconrffStatus = sanitize_text_field($_POST['menu_icon_rff_status']);
                $miconrffCat = sanitize_text_field($_POST['menu_icon_rff_cat']);
                $result = $this->db->update(
                    $this->tableItens,
                    array(
                        'title' => $miconrffTitle,
                        'urlPage' => $miconrffUrl,
                        'category' => $miconrffCat,
                        'statusItem' => $miconrffStatus,
                        'iconClass' => $miconrffIcon,
                        'orderItems' => $miconrffOrderItems,
                        'parentId' => $miconrffParent,
                    ),
                    array('id'=>$id),
                    array('%s'),
                    array('%d'),
                );
                if($result<=0 || $result==false){
                    echo '<div class="notice notice-failure is-dismissible"><p>Não foi possível editar o item de menu. Erro: '.$this->db->last_error.'</p></div>';
                }else{
                    echo '<div class="notice notice-success is-dismissible"><p>Item de menu <strong>atualizado</strong> com sucesso!</p></div>';
                }
            }
        }else{
            // echo 'NEUTRO update....---';
        }
    }

    function deleteIconRff(){
        if(isset($_POST['deleteMenuRff'])){
            if(isset($_POST['menu_icon_rff_id'])){
                //aplica o sanitize_text_field
                $id = sanitize_text_field($_POST['menu_icon_rff_id']);
                $result = $this->db->delete(
                    $this->tableItens,
                    array('id'=>$id),
                    array('%d'),
                );
                if($result<=0 || $result==false){
                    echo '<div class="notice notice-failure is-dismissible"><p>Não foi possível excluir o item de menu. Erro: '.$this->db->last_error.'</p></div>';
                }else{
                    echo '<div class="notice notice-success is-dismissible"><p>Item de menu <strong>excluído</strong> com sucesso!</p></div>';
                }
            }
        }else{
            // echo 'NEUTRO excluir....---';
        }
    }

    function verifyActionForm(){
        $this->insertIconRff();
        $this->updateIconRff();
        $this->deleteIconRff();
    }

    function render_teste() {
        $args = array(
            'post_type' => array('pmjs_pagina', 'page', 'outros'), // Define o tipo de post como 'page'
            'post_status' => 'publish', // Apenas páginas publicadas
            'posts_per_page' => -1, // Sem limite de resultados
        );
        
        $query = new WP_Query($args);
        $pages = $query->posts; // Retorna as páginas como um array de objetos WP_Post
        ?>
            <!-- <div id="micon-rff-pagina-field" class="localizacao-container" style="display:none;">
            <label for="micon-rff-pagina-input"> -->
            <?php //esc_html_e('Localização', 'pmjs-menus'); ?>
            <!-- </label> -->
            <select id="micon-rff-pagina-input" name="micon-rff-pagina-input" style="display:none;">
        <?php
            echo '<option value="0" disabled selected>Selecione uma opção</option>';
            foreach ($pages as $page) {
                echo '<option value="' . esc_attr($page->ID) . '">' . esc_html($page->post_title) . '</option>';
            }
        ?>
            </select>
            <!-- </div> -->
        <?php
    }


/**
 * Aqui começa as categorias-----------------------------------------------
 */

    function getAllCategoryForSelectTag(){
        return $this->dbCat->getAllCategoryArray();
    }

    function getCatById($id){
        return $this->dbCat->getCatById($id);
    }

}