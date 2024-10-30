<?php
global $wpdb;
$tableCatMIconRff = $wpdb->prefix.MICON_RFF_TABLE_CATEG;
$tableItensMIconRff = $wpdb->prefix.MICON_RFF_TABLE_ITEMS;
class MIconRffDB{
    private $tableCat;
    private $tableItens;
    private $db;
    function __construct(){
        global $tableCatMIconRff;
        global $tableItensMIconRff;
        global $wpdb;
        $this->tableCat = $tableCatMIconRff;
        $this->tableItens = $tableItensMIconRff;
        $this->db = $wpdb;
    }
    function getFather($item, $array){
        global $tableItensMIconRff;
        global $wpdb;
        $re = $wpdb->get_results("SELECT * FROM {$tableItensMIconRff} WHERE parentId={$item->id}");
        for($i=0;$i<count($re); $i++){
            // echo $re[$i]->title;
            // echo '<br><br>';
            $newAr = [];
            $newAr[$i] = $re[$i];
            $newAr[$i]->children = $this->getFather($re[$i], []);
            $array[$i] = $newAr;
        }
        return $array;
    }
    function generateArrayAllItems(){
        // $all_items = $this->db->get_results("SELECT * FROM {$this->tableItens}", ARRAY_A);
        $all_items = $this->db->get_results("SELECT * FROM {$this->tableItens}");

        $ar = [];
        $unique_items = [];
        $item_map = [];

        foreach ($all_items as $item) {
            // Pega apenas os itens pais
            if($item->parentId==null || $item->parentId==0){
                $item_map[$item->id] = $item;
                $item_map[$item->id]->children = $this->getFather($item, []); // pega os itens filhos
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
                echo '<li>'.$m.' <span class="'.$item->iconClass.'" style="font-size: 17px; padding-right: 5px;" title="'.$item->title.'"></span> <a href="?page=Menu-icon-rff&id='.$item->id.'">' . htmlspecialchars($item->title).'</a>';
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
        $item_map=$this->generateArrayAllItems();
        echo '<div style="display:flex;">';
        foreach($item_map as $item){
            if(!empty($item->title)){
                echo '<div style="width:fit-content; background-color:#fff; padding:20px; border:1px solid #cdcdcd; margin-right:10px;">';
                    echo '<span style="font-size:1.5rem;">'.htmlspecialchars($item->title).'</span>';
                    echo '<ul>';
                        echo '<li>- <span class="'.$item->iconClass.'" style="font-size: 17px; padding-right: 5px;" title="'.$item->title.'"></span><a href="?page=Menu-icon-rff&id='.$item->id.'">' . htmlspecialchars($item->title).'</a>';
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

    function printMenu2($items, $class) {
        // echo '<li>';
        foreach ($items as $item) {
            // Imprime o título do item
            if(!empty($item->title)){
                echo '<li><span class="'.$item->iconClass.'" style="font-size: 20px; padding-right: 5px;"></span><a href="?page=Menu-icon-rff&id='.$item->id.'">' . htmlspecialchars($item->title).'</a>';
                // Verifica se há filhos e chama a função recursivamente
                if (!empty($item->children)) {
                    echo '<ul class="miconsRffUlLiAll '.$class.$class.'">';
                    for($r=0;$r<count($item->children); $r++){
                        $this->printMenu2($item->children[$r], $class.$class);
                    }
                    echo '</ul>';
                }
                echo '</li>';
            }
        }
        // echo '</li>';
    }

    function getAllItemsArray(){
        $item_map=$this->generateArrayAllItems();
        echo '<ul class="miconsRffUlLiAll n">';
        $this->printMenu2($item_map, 'n');
        echo '</ul>';
    }

    function getAllItemsForSelectTag(){
        $all_items = $this->db->get_results("SELECT * FROM {$this->tableItens}", ARRAY_A);
        return $all_items;
    }

    function getAllCategoryForSelectTag(){
        $all_categ = $this->db->get_results("SELECT * FROM {$this->tableCat}", ARRAY_A);
        return $all_categ;
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
                $id=$_POST['menu_icon_rff_id'];
                $miconrffTitle = sanitize_text_field($_POST['menu_icon_rff_title']);
                $miconrffUrl = sanitize_text_field($_POST['menu_icon_rff_url']);
                $miconrffParent = sanitize_text_field($_POST['menu_icon_rff_parent']);
                $miconrffIcon = sanitize_text_field($_POST['fieldIconRff']);
                $miconrffOrderItems = sanitize_text_field($_POST['menu_icon_rff_orderItems']);
                $miconrffStatus = sanitize_text_field($_POST['menu_icon_rff_status']);
                $miconrffCat = sanitize_text_field($_POST['menu_icon_rff_cat']);
                $this->db->update(
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
                    echo '<div class="notice notice-success is-dismissible"><p>Item de menu <strong>atualizada</strong> com sucesso!</p></div>';
                }
            }
        }else{
            // echo 'NEUTRO....---'.$n;
        }
    }
}