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
    ?>
    <div id="miconRffForm" style="position: absolute; background-color:#fff; padding:30px; z-index:999; display:none; width:90%;height:90vh;">
            <form method="post" action="">
                <input type="hidden" name="gmp_action" value="add_menu_icon_rff">
                <p><label for="menu_icon_rff_title">Título:</label><input type="text" id="menu_icon_rff_title" name="menu_icon_rff_title" style="width:100%;" required></p>
                <p><label for="menu_icon_rff_url">URL:</label><input type="text" id="menu_icon_rff_url" name="menu_icon_rff_url" style="width:100%;" required></p>
                <p><label for="menu_icon_rff_parent">Escolha o Pai: (Se deixar em branco, ele será o pai)</label>
                <select id="menu_icon_rff_parent" name="menu_icon_rff_parent" style="width:100%;">
                    <option value="0">Nenhum</option>
                    <?php gmp_list_menu_icon_rff_options($db); ?>
                </select></p>
                <p>
                    <input type="submit" value="Adicionar">
                    <button onclick="cancelar()">Cancelar</button>
                </p>
            </form>
        </div>
    <div class="wrap">
        <h1>Gerenciar MenuIconRff</h1>
        <button onclick="newButton()">Inserir novo</button>
        <span id="ex"></span>
        <h2>Adicionar Novo MenuIconRff</h2>
        <div id="iconsRff" style="position: absolute; background-color:#fff; padding:10px; z-index:1000; display:none;">
            <?php
                $icones = new MIconsRffIcons();
                $icones->mostrar_dashicons();
            ?>
        </div>
        
        <?php
        $val = $db->getAllItemsArrayAdminPage();
        $val = $db->getAllItemsArray();
        ?>
        <script>
            function cancelar(){
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
