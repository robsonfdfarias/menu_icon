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
    $db = new MIconRffDB();
    $db->insertIconRff();
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
                <input type="hidden" name="micons_rff_id" id="micons_rff_id">
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
                    <button type="submit" name="insertMenuRff">Cadastrar</button>
                    <button onclick="cancelar()">Cancelar</button>
                </p>
            </form>
        </div>
    <div class="wrap">
        <h1>Gerenciar MenuIconRff</h1>
        <button onclick="newButton()">Inserir novo</button>
        <h2>Adicionar Novo MenuIconRff</h2>
        
        <?php
        $val = $db->getAllItemsArrayAdminPage();
        $val = $db->getAllItemsArray();
        ?>
        <script>
            function cancelar(){
                removeParamsUrl('id');
                document.getElementById('miconRffForm').style.display='none';
            }
            function newButton(){
                document.getElementById('miconRffForm').style.display='block';
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
