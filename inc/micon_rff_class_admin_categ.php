<?php
include_once(MICON_RFF_DIR_INC.'micon_rff_class_db.php');
class MIconRffCateg{
    private $tableCat;
    private $db;
    private $classDB;
    function __construct(){
        global $wpdb;
        $this->tableCat = $wpdb->prefix.MICON_RFF_TABLE_CATEG;
        $this->db = $wpdb;
        $this->classDB = new MIconRffDB();
    }
    function openDivAdminCat(){
        echo '<div id="divGeralAdminCat" style="position:absolute;top:0;left:-20px;width:100%;height:100%;background-color:#f0f0f1;display:none;">
            <div onclick="closeDivAdminCateg()" title="Voltar para os itens de menu" style="position:absolute;right:35px;top:40px;padding:10px 15px;cursor:pointer;background-color:red;font-size:1.3rem;border-radius:3px;color:white;box-shadow:2px 2px 2px rgba(0,0,0,0.5);z-index:3;">X</div>
            <div style="width:calc(100% - 80px);height:calc(100% - 80px);margin:20px;background-color:#fff; padding:20px;position:relative;">';
            echo '<button onclick="newCategory()">Nova Categoria</button>';
            echo $this->openDivFormCateg();
            echo '<h2>Administrar categorias</h2>';
            echo $this->tableofItemsCateg();
        echo '    </div>
        </div>';
    }
    function tableofItemsCateg(){
        $all_categ = $this->classDB->getAllCategoryForSelectTag();
        echo '<div style="">
        <table border="1" cellspacing="0" id="tableAdminCategRff">
            <tr>
                <td>
                    ID
                </td>
                <td>
                    Título
                </td>
                <td>
                    Status
                </td>
                <td>
                    Ações
                </td>
            </tr>';
        foreach($all_categ as $categ){
            $params = $this->getUrlParams('idCat='.$categ["id"]);
            echo '<tr>
                <td>
                    '.$categ["id"].'
                </td>
                <td>
                    '.$categ["title"].'
                </td>
                <td>
                    '.$categ["statusItem"].'
                </td>
                <td>
                    <a href="?'.$params.'" style="text-decoration:none;padding:5px;"><span class="dashicons dashicons-edit-large" style="font-size: 20px;"></span></a>
                    <a href="?'.$params.'" style="text-decoration:none;padding:5px;"><span class="dashicons dashicons-trash" style="font-size: 20px;"></span></a>
                </td>
            </tr>';
        }
            echo '</table></div>';
    }
    function openDivFormCateg(){
        echo '<div id="divFormRffCatAdmin" style="position:absolute;background-color:white;border:1px solid #cdcdcd;top:0;left:0;width:calc(100% - 40px);height:calc(100% - 40px);padding:20px;z-index:4;display:none;">
            <form method="post" id="formRffCatAdmin">
                <input type="hidden" name="micon_rff_admin_cat_id" id="micon_rff_admin_cat_id">
                <p>
                    <label for="micon_rff_admin_cat_title">Insira o título:</label>
                    <input type="text" placeholder="Título" name="micon_rff_admin_cat_title" id="micon_rff_admin_cat_title" style="width:100%;" required>
                </p>
                <p>
                    <label for="micon_rff_admin_cat_status">Insira o título:</label>
                    <select name="micon_rff_admin_cat_status" id="micon_rff_admin_cat_status">
                        <option value="Ativo">Ativo</option>
                        <option value="Inativo">Inativo</option>
                    </select>
                </p>
                <p>
                    <button type="submit" id="btCadAdminCat" name="btCadAdminCat">Cadastrar</button>
                    <button type="submit" id="btCancelAdminCat" onclick="event.preventDefault(),closeDivFormAdminCat()">Cancelar</button>
                </p>
            </form>
        </div>';
    }
    //parâmetro a ser passado ('chave=valor')
    function getUrlParams($param){
        $res='';
        if(!empty($_GET)){
            $params = [];
            foreach($_GET as $key => $value){
                echo $key.'='.$value;
                $params[] = $key.'='.$value;
            }
            $res = implode('&', $params);
        }
        if($res==''){
            $res = $param;
        }else{
            $res .= '&'.$param;
        }
        return $res;
    }
}