<?php

class MIconRffDbCat{
    private $db;
    private $tableCat;
    function __construct(){
        global $wpdb;
        $this->db = $wpdb;
        $this->tableCat = $wpdb->prefix.MICON_RFF_TABLE_CATEG;
    }
    function getAllCategoryArray(){
        $all_categ = $this->db->get_results("SELECT * FROM {$this->tableCat}", ARRAY_A);
        return $all_categ;
    }
    function getCatById($id){
        $id = sanitize_text_field($id);
        $cat = $this->db->get_results("SELECT * FROM {$this->tableCat} WHERE id={$id}");
        return $cat[0];
    }
    function insertCat($title, $status){
        $title = sanitize_text_field($title);
        $status = sanitize_text_field($status);
        $res = $this->db->insert(
            $this->tableCat,
            array(
                'title' => $title,
                'statusItem' => $status,
            )
        );
        if($res<=0 || $res==false){
            echo '<div class="notice notice-failure is-dismissible" style="top:-50px;"><p>Não foi possível inserir a categoria. Erro: '.$this->db->last_error.'</p></div>';
        }else{
            echo '<div class="notice notice-success is-dismissible" style="top:-50px;"><p>Categoria <strong>inserida</strong> com sucesso!</p></div>';
        }
    }
    function updateCat($id, $title, $status){
        $id = sanitize_text_field($id);
        $title = sanitize_text_field($title);
        $status = sanitize_text_field($status);
        $res = $this->db->update(
            $this->tableCat,
            array(
                'title' => $title,
                'statusItem' => $status,
            ),
            array('id'=>$id),
            array('%s'),
            array('%d'),
        );
        if($res<=0 || $res==false){
            echo '<div class="notice notice-failure is-dismissible"><p>Não foi possível atualizar a categoria. Erro: '.$this->db->last_error.'</p></div>';
        }else{
            echo '<div class="notice notice-success is-dismissible"><p>Categoria <strong>atualizar</strong> com sucesso!</p></div>';
        }
    }
    function deleteCat($id){
        $id = sanitize_text_field($id);
        $res = $this->db->delete(
            $this->tableCat,
            array('id'=>$id),
            array('%d'),
        );
        if($res<=0 || $res==false){
            echo '<div class="notice notice-failure is-dismissible"><p>Não foi possível deletar a categoria. Erro: '.$this->db->last_error.'</p></div>';
        }else{
            echo '<div class="notice notice-success is-dismissible"><p>Categoria <strong>deletada</strong> com sucesso!</p></div>';
        }
    }
}