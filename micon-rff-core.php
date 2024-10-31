<?php

/*
* Núcleo do plugin
*/

//se chamar diretamente e não pelo wordpress, aborta
if(!defined('WPINC')){
    die();
 }

if(file_exists(plugin_dir_path(__FILE__).'inc/micon_rff_class_icons.php')){
    require_once(plugin_dir_path(__FILE__).'inc/micon_rff_class_icons.php');
}
if(file_exists(plugin_dir_path(__FILE__).'inc/micon_rff_class_db.php')){
    require_once(plugin_dir_path(__FILE__).'inc/micon_rff_class_db.php');
}
if(file_exists(plugin_dir_path(__FILE__).'inc/micon_rff_class_admin_categ.php')){
    require_once(plugin_dir_path(__FILE__).'inc/micon_rff_class_admin_categ.php');
}


add_action('admin_menu', 'micon_rff_admin_menu');
function micon_rff_admin_menu() {
    add_menu_page(
        'Menu Icon RFF', //Título da página
        'Menu Icon RFF', //Título do menu
        'manage_options', //nível de permissão
        'Menu-icon-rff', //Slug
        'micon_rff_admin_page', //Função chamada
        'dashicons-menu', //Ícone https://developer.wordpress.org/resource/dashicons/#admin-generic
        5 //Posição no menu
    );
}

function micon_rff_admin_page() {
    $adminCateg = new MIconRffCateg();
    $adminCateg->openDivAdminCat();
    $db = new MIconRffDB();
    $db->insertIconRff();
    $db->updateIconRff();
    $db->deleteIconRff();
    if(isset($_GET['id'])){
        $itemForId = $db->getItemForId($_GET['id'])[0];
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
        $cat = $db->getCatById($itemForId->category);
        $jsonCat = '{
            "id": '.$cat->id.',
            "title": "'.$cat->title.'",
            "statusItem": "'.$cat->statusItem.'"
        }';
        echo '<div style="display:none;" id="contentCatForId">'.$jsonCat.'</div>';
        $jsonParentId='{"title":"Nenhum"}';
        if(!empty($itemForId->parentId)){
            $parentId = $db->getItemForId($itemForId->parentId)[0];
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
    ?>
    <div id="iconsRff" style="position: absolute; background-color:#fff; padding:10px; z-index:1000; display:none;">
            <div id="btCloseRffIcons" onclick="closeDivIcons()">X</div><br><br><br><br>
            <?php
                $icones = new MIconsRffIcons();
                $icones->mostrar_dashicons();
            ?>
        </div>
        
    <div id="miconRffForm" style="position: absolute; background-color:#fff; padding:30px; z-index:999; display:none; width:90%;height:90vh;">
            <form method="post" action="" id="formRffIconsMenu">
                <span>
                    <label for="menu_icon_rff_orderItems">Ordem do item de menu:</label>
                    <input type="text" id="menu_icon_rff_orderItems" name="menu_icon_rff_orderItems" style="width:200px;" required>
                </span>
                <span><label for="menu_icon_rff_status">Selecione o status</label>
                    <select id="menu_icon_rff_status" name="menu_icon_rff_status" style="width:200px;">
                        <option value="Ativo">Ativo</option>
                        <option value="Inativo">Inativo</option>
                    </select>
                </span>
                <input type="hidden" name="menu_icon_rff_id" id="micons_rff_id">
                <p>
                    <label for="menu_icon_rff_title">Título:</label>
                    <input type="text" id="menu_icon_rff_title" name="menu_icon_rff_title" style="width:100%;" required>
                </p>
                <p>
                    <label for="menu_icon_rff_url">URL:</label>
                    <input type="text" id="menu_icon_rff_url" name="menu_icon_rff_url" style="width:100%;" required>
                </p>
                <p><label for="menu_icon_rff_parent">Escolha o Pai: (Se deixar Nenhum, ele não será filho)</label>
                    <select id="menu_icon_rff_parent" name="menu_icon_rff_parent" style="width:100%;">
                        <option value="0">Nenhum</option>
                        <?php gmp_list_menu_icon_rff_options($db); ?>
                    </select>
                </p>
                <p><label for="menu_icon_rff_cat">Selecione a categoria:</label>
                    <select id="menu_icon_rff_cat" name="menu_icon_rff_cat" style="width:100%;">
                        <?php getAllCatgsInOptions($db); ?>
                    </select>
                </p>
                <p>
                    <label for="fieldIconRff">Selecione o ícone</label>
                    <span style="position:relative;">
                        <span id="ex" style="position:absolute; left:10px; top:-3px; margin: auto 0px;"></span>
                        <input type="text" name="fieldIconRff" id="fieldIconRff" style="width: 50%; min-width:150px; padding-left:35px;" readonly required>
                    </span>
                    <button type="submit" onclick="event.preventDefault(), btSelectIconRff()">Selecionar</button>
                </p>
                    <!-- <input type="submit" name="insertMenuRff" id="insertMenuRff" value="Adicionar"> -->
                    <button type="submit" name="insertMenuRff" id="insertMenuRff">Cadastrar</button>
                    <button type="submit" name="updateMenuRff" id="updateMenuRff">Atualizar</button>
                    <button type="submit" name="openDelMenuRff" id="openDelMenuRff" style="background-color:red;">Deletar</button>
                    <div id="divDelItemRff" style="position:absolute;display:none;top:0;left:0;width:100%;height:100%;background-color:rgba(0,0,0,0.3);padding-top:30px;">
                        <div id="divDelItemRffInto" style="position:relative; max-width:400px; margin:auto auto; border: 1px solid #cdcdcd; padding:40px;background-color:white;">
                            <div style="font-size:2rem;line-height:2rem;font-weight:bold;">
                            Tem certeza que deseja excluir o item?
                            </div>
                            <button type="submit" name="deleteMenuRff" id="deleteMenuRff" style="background-color:red;">Deletar</button>
                            <button type="submit" name="abortDeleteMenuRff" id="abortDeleteMenuRff">Cancelar</button>
                        </div>
                    </div>
                    <button onclick="cancelar()">Cancelar</button>
                </p>
            </form>
        </div>
    <div class="wrap">
        <h1>Gerenciar MenuIconRff</h1>
        <button onclick="newButton()">Inserir novo</button>
        <button onclick="adminCateg()">Categorias</button>
        <h2>Adicionar Novo MenuIconRff</h2>
        
        <?php
            $db->getAllItemsArrayAdminPage();
            // $db->getAllItemsArray();
        ?>
        <script>
            function cancelar(){
                removeParamsUrl('id');
                document.getElementById('miconRffForm').style.display='none';
            }
            function newButton(){
                document.getElementById('miconRffForm').style.display='block';
                let btCreate = document.getElementById('insertMenuRff');
                btCreate.style.display = 'inline';
                let btUpdate = document.getElementById('updateMenuRff');
                btUpdate.style.display = 'none';
                let btDelete = document.getElementById('openDelMenuRff');
                btDelete.style.display = 'none';
            }
            function adminCateg(){
                addParamsUrl('adminCat', true);
                document.getElementById('divGeralAdminCat').style.display='block';
            }
        </script>
    </div>
    <?php
}

function gmp_list_menu_icon_rff_options($db) {
    $listMenu = $db->getAllItemsForSelectTag();
    foreach($listMenu as $menu){
        echo '<option value="' . esc_attr($menu['id']) . '">' . esc_html($menu['title']) . '</option>';
    }
}
function getAllCatgsInOptions($db) {
    $listCateg = $db->getAllCategoryForSelectTag();
    foreach($listCateg as $cat){
        echo '<option value="' . esc_attr($cat['id']) . '">' . esc_html($cat['title']) . '</option>';
    }
}
