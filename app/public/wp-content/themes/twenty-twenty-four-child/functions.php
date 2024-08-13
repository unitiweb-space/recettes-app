<?php

//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
///////////////////// Création du CPT Recette ////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////

function nb_cpt_recette()
{


    /* Recette */
    $labels = array(
        'name'                => _x('Recettes', 'Post Type General Name', 'textdomain'),
        'singular_name'       => _x('Recette', 'Post Type Singular Name', 'textdomain'),
        'menu_name'           => __('Recettes', 'textdomain'),
        'name_admin_bar'      => __('Recettes', 'textdomain'),
        'parent_item_colon'   => __('Parent Item:', 'textdomain'),
        'all_items'           => __('Toutes les recettes', 'textdomain'),
        'add_new_item'        => __('Add New Item', 'textdomain'),
        'add_new'             => __('Ajouter une recette', 'textdomain'),
        'new_item'            => __('New Item', 'textdomain'),
        'edit_item'           => __('Edit Item', 'textdomain'),
        'update_item'         => __('Modifier la recette', 'textdomain'),
        'view_item'           => __('Voir la recette', 'textdomain'),
        'search_items'        => __('Rechercher une recette', 'textdomain'),
        'not_found'           => __('Recette non trouvée', 'textdomain'),
        'not_found_in_trash'  => __('Recette non trouvée dans la corbeille', 'textdomain'),
    );
    $rewrite = array(
        'slug'                => _x('recette', 'recette', 'textdomain'),
        'with_front'          => true,
        'pages'               => true,
        'feeds'               => false,
    );
    $args = array(
        'label'               => __('recette', 'textdomain'),
        'description'         => __('Recettes', 'textdomain'),
        'labels'              => $labels,
        // 'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        'supports'            => array('title', 'thumbnail', 'editor'),
        'taxonomies'          => array('recette_type'),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-admin-home',
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => true,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'query_var'           => 'recette',
        'rewrite'             => $rewrite,
        'capability_type'     => 'post',
    );
    register_post_type('recette', $args);
}

add_action('init', 'nb_cpt_recette', 10);

add_action('after_setup_theme', function () {
    \Carbon_Fields\Carbon_Fields::boot();
});


//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
/////////// Création des paramètres Carbon Fields ////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////


use \Carbon_Fields\Container;
use \Carbon_Fields\Field;

add_action('carbon_fields_register_fields', function () {

    // // Crée une nouvelle entrée dans le menu de l'admin WordPress
    // Container::make('theme_options', 'Option du theme')
    //    ->add_tab('Pied de page', [
    //         Field::make('text', 'facebook_url', 'URL Facebook'),
    //         Field::make('text', 'twitter_url', 'URL Twitter'),
    //         Field::make('text', 'youtube_url', 'URL Youtube'),
    //    ])
    //    ->add_tab('Apparence', [
    //        Field::make('color', 'bg_color', 'Couleur de fond'),
    //    ]);

    // Ajoute des champs à un type de contenu particulier
    Container::make('post_meta', 'Options de la recette')
        ->where('post_type', '=', 'recette')

        ->add_fields([
            // Field::make("select", "recette_category", "Type de recette")
            //     ->set_default_value('rent')
            //      ->set_options([
            //          'salade' => 'Salade',
            //          'entree' => 'Entrée',
            //          'plat' => 'Plat',
            //          'dessert' => 'Dessert'
            //      ]),


            // Un champs complex permet de créer un répéteur pour les ingrédients
            Field::make('complex', 'recette_ingredient', 'Liste des ingrédients')
                ->add_fields([
                    Field::make('text', 'ingredient', 'Ingrédient'),
                    Field::make('text', 'quantity', 'Quantité'),
                    //  Field::make('checkbox', 'available', 'Disponible ?')
                ])
                ->set_layout('tabbed-vertical')
                //  ->set_header_template('<%- name %> <%- available ? "✅" : "❌" %>')
                ->set_header_template('<%- quantity %> <%- ingredient %>'),

            Field::make("text", "temps_cuisson", "Temps de cuisson")
                ->set_attribute('type', 'string'),

            // Un champs complex permet de créer un répéteur pour les étapes
            Field::make('complex', 'recette_etapes', 'Étapes de la recette')
                ->add_fields([
                    Field::make('text', 'description', 'Description étape'),
                    //  Field::make('checkbox', 'available', 'Disponible ?')
                ])
                ->set_layout('tabbed-vertical')
                //  ->set_header_template('<%- name %> <%- available ? "✅" : "❌" %>')
                ->set_header_template('<%- description %>'),

        ]);

    // Container::make('post_meta', 'Information sur la recette')
    // ->where('post_type', '=', 'recette ')
    // ->add_fields([
    //     Field::make("select", "recette_category", "Achat ou location ?")
    //     ->set_default_value('rent')
    //     ->set_options([
    //         'rent' => 'Location',
    //         'buy' => 'Achat'
    //     ])
    //     ]);
});

// Limite l'extrait à 40 mots
add_filter('excerpt_length', function () {
    return 25;
});
