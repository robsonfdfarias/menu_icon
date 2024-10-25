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
    <div class="wrap">
        <h1>Gerenciar MenuIconRff</h1>
        <span id="ex"></span>
        <form method="post" action="options.php">
            <?php
            settings_fields('gmp_menu_icon_rff_options');
            do_settings_sections('graphql-menu');
            submit_button();
            ?>
        </form>
        <h2>Adicionar Novo MenuIconRff</h2>
        <form method="post" action="">
            <input type="hidden" name="gmp_action" value="add_menu_icon_rff">
            <p><label for="menu_icon_rff_title">Título:</label><input type="text" id="menu_icon_rff_title" name="menu_icon_rff_title" required></p>
            <p><label for="menu_icon_rff_url">URL:</label><input type="text" id="menu_icon_rff_url" name="menu_icon_rff_url" required></p>
            <p><label for="menu_icon_rff_parent">MenuIconRff Pai:</label>
            <select id="menu_icon_rff_parent" name="menu_icon_rff_parent">
                <option value="0">Nenhum</option>
                <?php gmp_list_menu_icon_rff_options(); ?>
            </select></p>
            <p><input type="submit" value="Adicionar MenuIconRff"></p>
        </form>
        <?php
        $val = $db->getAllItemsArray();
            $icones = new MIconsRffIcons();
            $icones->mostrar_dashicons();
        ?>
    </div>
    <?php
}

function gmp_list_menu_icon_rff_options() {
    $menuIconRffs = get_option('gmp_menu_icon_rff', []);
    foreach ($menuIconRffs as $menuIconRff) {
        echo '<option value="' . esc_attr($menuIconRff['id']) . '">' . esc_html($menuIconRff['title']) . '</option>';
    }
}

function gmp_handle_menu_icon_rff_submission() {
    if (isset($_POST['gmp_action']) && $_POST['gmp_action'] === 'add_menu_icon_rff') {
        $title = sanitize_text_field($_POST['menu_icon_rff_title']);
        $url = esc_url_raw($_POST['menu_icon_rff_url']);
        $parent = intval($_POST['menu_icon_rff_parent']);

        $menuIconRffs = get_option('gmp_menu_icon_rff', []);
        $id = count($menuIconRffs) + 1;

        $menuIconRffs[] = [
            'id' => $id,
            'title' => $title,
            'url' => $url,
            'parent' => $parent,
            'children' => []
        ];

        foreach ($menuIconRffs as &$menuIconRff) {
            if ($menuIconRff['parent'] == $id) {
                $menuIconRff['children'][] = [
                    'id' => $id,
                    'title' => $title,
                    'url' => $url,
                    'parent' => $parent,
                    'children' => []
                ];
            }
        }

        update_option('gmp_menu_icon_rff', $menuIconRffs);
        wp_redirect(add_query_arg('page', 'graphql-menu', admin_url('admin.php')));
        exit;
    }
}
add_action('admin_init', 'gmp_handle_menu_icon_rff_submission');

