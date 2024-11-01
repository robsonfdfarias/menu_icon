<?php

include_once(MICON_RFF_DIR_INC.'micon_rff_class_db.php');

class MIconAdminGET{
    private $db;
    function __construct(){
        $this->db = new MIconRffDB();
    }
    function verifyGet(){
        if(isset($_GET['id'])){
            $itemForId = $this->db->getItemForId($_GET['id'])[0];
            $jsonItemForId = '{
                "id":'.$itemForId->id.',
                "title": "'.$itemForId->title.'",
                "urlPage": "'.$itemForId->urlPage.'",
                "category":'.$itemForId->category.',
                "statusItem": "'.$itemForId->statusItem.'",
                "iconClass": "'.$itemForId->iconClass.'",
                "orderItems":'.$itemForId->orderItems.',
                "parentId":'.$itemForId->parentId.'
            }';
            echo '<div style="display:none;" id="contentMenuForId">'.$jsonItemForId.'</div>';
            $cat = $this->db->getCatById($itemForId->category);
            $jsonCat = '{
                "id": '.$cat->id.',
                "title": "'.$cat->title.'",
                "statusItem": "'.$cat->statusItem.'"
            }';
            echo '<div style="display:none;" id="contentCatForId">'.$jsonCat.'</div>';
            $jsonParentId='{"title":"Nenhum"}';
            if(!empty($itemForId->parentId)){
                $parentId = $this->db->getItemForId($itemForId->parentId)[0];
                $jsonParentId = '{
                    "id": '.$parentId->id.',
                    "title": "'.$parentId->title.'",
                    "urlPage": "'.$parentId->urlPage.'",
                    "category": '.$parentId->category.',
                    "statusItem": "'.$parentId->statusItem.'",
                    "iconClass": "'.$parentId->iconClass.'",
                    "orderItems": '.$parentId->orderItems.',
                    "parentId": '.$parentId->parentId.'
                }';
            }
            echo '<div style="display:none;" id="contentParentIdForId">'.$jsonParentId.'</div>';
            // echo 'ID selecionado: '.$_GET['id'];
        }
        if(isset($_GET['idCat'])){
            $cat = $this->db->getCatById($_GET['idCat']);
            $jsonCat = '{
                "id": '.$cat->id.',
                "title": "'.$cat->title.'",
                "statusItem": "'.$cat->statusItem.'"
            }';
            echo '<div style="display:none;" id="catForID">'.$jsonCat.'</div>';
        }
    }
}