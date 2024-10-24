<?php


// //registrar o tipo de dado GraphQL ------------------------------------------------------------------
// function gmp_register_graphql_menu() {
//     // Registrar o tipo MenuIconRffItem
//     register_graphql_object_type('MenuIconRffItem', [
//         'description' => 'Item de menuIconRff',
//         'fields' => [
//             'id' => [
//                 'type' => 'Int',
//                 'description' => 'ID do item do menuIconRff',
//             ],
//             'title' => [
//                 'type' => 'String',
//                 'description' => 'Título do item do menuIconRff',
//             ],
//             'url' => [
//                 'type' => 'String',
//                 'description' => 'URL do item do menuIconRff',
//             ],
//             'parentId' => [
//                 'type' => 'Int',
//                 'description' => 'ID do item pai',
//             ],
//             'children' => [
//                 'type' => ['list_of' => 'MenuIconRffItem'],
//                 'description' => 'Itens filhos',
//                 'resolve' => function($item) {
//                     $menuIconRffs = get_option('gmp_menu_icon_rff', []);
//                     $children = array_filter($menuIconRffs, function($menuIconRff) use ($item) {
//                         return $menuIconRff['parent'] == $item['id'];
//                     });
//                     return array_values($children);
//                 },
//             ],
//         ],
//     ]);

//     // Registrar a consulta para menuIconRff
//     register_graphql_field('RootQuery', 'menuIconRffs', [
//         'type' => ['list_of' => 'MenuIconRffItem'],
//         'description' => 'Retorna uma lista de menuIconRff',
//         'resolve' => function() {
//             return get_option('gmp_menu_icon_rff', []);
//         },
//     ]);
// }




// function gmp_register_graphql_connection_types() {
//     // Registrar a conexão de itens de menuIconRff
//     register_graphql_object_type('MenuIconRffItemToMenuIconRffItemConnection', [
//         'description' => 'Conexão de itens de menuIconRff',
//         'fields' => [
//             'edges' => [
//                 'type' => ['list_of' => 'MenuIconRffItemEdge'],
//                 'description' => 'A lista de bordas (edges) da conexão',
//             ],
//             'nodes' => [
//                 'type' => ['list_of' => 'MenuIconRffItem'],
//                 'description' => 'A lista de nós (nodes) da conexão',
//             ],
//             'pageInfo' => [
//                 'type' => 'PageInfo',
//                 'description' => 'Informações sobre a página de conexão',
//             ],
//         ],
//     ]);

//     // Registrar a borda de conexão de itens de menuIconRff
//     register_graphql_object_type('MenuIconRffItemEdge', [
//         'description' => 'Uma borda (edge) de uma conexão de itens de menuIconRff',
//         'fields' => [
//             'node' => [
//                 'type' => 'MenuIconRffItem',
//                 'description' => 'O item do menuIconRff',
//             ],
//         ],
//     ]);
// }

/////////////////////////////////////////////////////////

// Registrar o tipo de dado GraphQL ------------------------------------------------------------------
function gmp_register_graphql_menu() {
    global $wpdb; // Adiciona a referência ao objeto $wpdb

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
            'urlPage' => [
                'type' => 'String',
                'description' => 'Url usada para link o bt',
            ],
            'category' => [
                'type' => 'Int',
                'description' => 'categoria que pertence o item',
            ],
            'statusitem' => [
                'type' => 'String',
                'description' => 'Status do item',
            ],
            'iconClass' => [
                'type' => 'String',
                'description' => 'Ícone do menu',
            ],
            'orderItems' => [
                'type' => 'String',
                'description' => 'Ordem do item na listagem',
            ],
            'parentId' => [
                'type' => 'Int',
                'description' => 'ID do item pai',
            ],
            'children' => [
                'type' => ['list_of' => 'MenuIconRffItem'],
                'description' => 'Itens filhos',
                'resolve' => function($item) use ($wpdb) {
                    
                    // Consulta para obter os filhos da tabela micon_rff_items
                    $children = $wpdb->get_results(
                        $wpdb->prepare(
                            "SELECT * FROM {$wpdb->prefix}micon_rff_items WHERE parentId = %d",
                            $item['id']
                        ),
                        ARRAY_A // Retorna como array associativo
                    );
                    return $children;
                },
            ],
        ],
    ]);

    // Registrar a consulta para menuIconRff
    register_graphql_field('RootQuery', 'menuIconRffs', [
        'type' => ['list_of' => 'MenuIconRffItem'],
        'description' => 'Retorna uma lista de menuIconRff',
        'args' => [
            'category' => [
                'type' => 'Int',
                'description' => __('Category of the item', 'your-textdomain'),
            ],
        ],
        'resolve' => function($root, $args, $context, $info) use ($wpdb) {
            $tableItens = $wpdb->prefix.MICON_RFF_TABLE_ITEMS;
            $where_clauses = [];
            if(isset($args['category'])){
                $cID = sanitize_text_field($args['category']);
                $where_clauses[] = $wpdb->prepare('category = %d', $cID);
            }
            $where_sql = '';
            if(sizeof($where_clauses)>0){
                $where_sql = "WHERE ".implode(' AND ', $where_clauses);
            }

            $all_items = $wpdb->get_results("SELECT * FROM {$tableItens} {$where_sql}", ARRAY_A);

            // Array para armazenar os itens únicos
            $unique_items = [];
            $item_map = [];

            // Organizar os itens em um mapa para referência rápida
            foreach ($all_items as $item) {
                $item_map[$item['id']] = $item;
                $item_map[$item['id']]['children'] = []; // Inicializa a chave children
            }

            // Preencher os filhos
            foreach ($item_map as $item) {
                if ($item['parentId']) {
                    $item_map[$item['parentId']]['children'][] = $item;
                } else {
                    $unique_items[] = $item; // Adiciona apenas os itens de nível superior
                }
            }

            return $unique_items;
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
