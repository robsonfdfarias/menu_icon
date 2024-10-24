<?php


//registrar o tipo de dado GraphQL ------------------------------------------------------------------
function gmp_register_graphql_menu() {
    // Registrar o tipo MenuIconRffItem
    register_graphql_object_type('MenuIconRffItem', [
        'description' => 'Item de menuIconRff',
        'fields' => [
            'id' => [
                'type' => 'Int',
                'description' => 'ID do item do menuIconRff',
            ],
            'title' => [
                'type' => 'String',
                'description' => 'Título do item do menuIconRff',
            ],
            'url' => [
                'type' => 'String',
                'description' => 'URL do item do menuIconRff',
            ],
            'parentId' => [
                'type' => 'Int',
                'description' => 'ID do item pai',
            ],
            'children' => [
                'type' => ['list_of' => 'MenuIconRffItem'],
                'description' => 'Itens filhos',
                'resolve' => function($item) {
                    $menuIconRffs = get_option('gmp_menu_icon_rff', []);
                    $children = array_filter($menuIconRffs, function($menuIconRff) use ($item) {
                        return $menuIconRff['parent'] == $item['id'];
                    });
                    return array_values($children);
                },
            ],
        ],
    ]);

    // Registrar a consulta para menuIconRff
    register_graphql_field('RootQuery', 'menuIconRffs', [
        'type' => ['list_of' => 'MenuIconRffItem'],
        'description' => 'Retorna uma lista de menuIconRff',
        'resolve' => function() {
            return get_option('gmp_menu_icon_rff', []);
        },
    ]);
}




function gmp_register_graphql_connection_types() {
    // Registrar a conexão de itens de menuIconRff
    register_graphql_object_type('MenuIconRffItemToMenuIconRffItemConnection', [
        'description' => 'Conexão de itens de menuIconRff',
        'fields' => [
            'edges' => [
                'type' => ['list_of' => 'MenuIconRffItemEdge'],
                'description' => 'A lista de bordas (edges) da conexão',
            ],
            'nodes' => [
                'type' => ['list_of' => 'MenuIconRffItem'],
                'description' => 'A lista de nós (nodes) da conexão',
            ],
            'pageInfo' => [
                'type' => 'PageInfo',
                'description' => 'Informações sobre a página de conexão',
            ],
        ],
    ]);

    // Registrar a borda de conexão de itens de menuIconRff
    register_graphql_object_type('MenuIconRffItemEdge', [
        'description' => 'Uma borda (edge) de uma conexão de itens de menuIconRff',
        'fields' => [
            'node' => [
                'type' => 'MenuIconRffItem',
                'description' => 'O item do menuIconRff',
            ],
        ],
    ]);
}