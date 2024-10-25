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
    function getAllItemsArray(){
        // $all_items = $this->db->get_results("SELECT * FROM {$this->tableItens}", ARRAY_A);
        $all_items = $this->db->get_results("SELECT * FROM {$this->tableItens}");

        $ar = [];
        function getFather($item, $array){
            global $tableItensMIconRff;
            global $wpdb;
            $re = $wpdb->get_results("SELECT * FROM {$tableItensMIconRff} WHERE parentId={$item->id}");
            for($i=0;$i<count($re); $i++){
                // echo $re[$i]->title;
                // echo '<br><br>';
                $newAr = [];
                $newAr[$i] = $re[$i];
                $newAr[$i]->children = getFather($re[$i], []);
                $array[$i] = $newAr;
            }
            return $array;
        }
        $unique_items = [];
        $item_map = [];

        foreach ($all_items as $item) {
            if($item->parentId==null){
                $item_map[$item->id] = $item;
                $item_map[$item->id]->children = getFather($item, []);
            }
        }

        function printMenu($items) {
            echo '<ul style="padding-left:10px;">';
            foreach ($items as $item) {
                // Imprime o título do item
                if(!empty($item->title)){
                    echo '<li>-<a href="?page=Menu-icon-rff&id='.$item->id.'">' . htmlspecialchars($item->title).'</a>';
                    // Verifica se há filhos e chama a função recursivamente
                    if (!empty($item->children)) {
                        for($r=0;$r<count($item->children); $r++){
                            printMenu($item->children[$r]);
                        }
                    }
                    echo '</li>';
                }
            }
            echo '</ul>';
        }
        printMenu($item_map);
    }
}