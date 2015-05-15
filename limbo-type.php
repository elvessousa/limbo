<?php
/*-----------------------------------------------------------------------

	Type name: Limbo
    Make sure you know what you're doing before altering this code.

    - Limbo fields
    - Arquive posts
    - Arquive this [Link]
    - Columns labels [admin]
    - Columns [admin]

------------------------------------------------------------------------*/
if ( !function_exists('ess_limbo_type') ) {

    add_action('init', 'ess_limbo_type');
    function ess_limbo_type() {
        $limbo_labels = array(
            'name'                => _x('Archives', 'ess-le-limbo'),
            'singular_name'       => _x('Archive', 'ess-le-limbo'),
            'menu_name'           => __('Archives', 'ess-le-limbo'),
            'add_new'             => __('Add archive', 'ess-le-limbo'),
            'add_new_item'        => __('Add new archive', 'ess-le-limbo'),
            'edit'                => __('Edit', 'ess-le-limbo'),
            'edit_item'           => __('Edit archive', 'ess-le-limbo'),
            'new_item'            => __('New limbo', 'ess-le-limbo'),
            'view'                => __('View archives', 'ess-le-limbo'),
            'view_item'           => __('View archives', 'ess-le-limbo'),
            'search_items'        => __('Search archive', 'ess-le-limbo'),
            'not_found'           => __('No archives found', 'ess-le-limbo'),
            'not_found_in_trash'  => __('No archives found in trash', 'ess-le-limbo'),
            'parent'              => __('Parent archives', 'ess-le-limbo'),
        );

        $limbo_args = array(
            'labels'              => $limbo_labels,
            'singular_label'      => __('limbo', 'ess-le-limbo'),
            'public'              => true,
            'show_ui'             => true,
            'capability_type'     => 'page',
            'hierarchical'        => false,
            'rewrite'             => true,
            'exclude_from_search' => true,
            'supports'            => array('title', 'editor', 'excerpt', 'author', 'thumbnail',
                                           'comments', 'trackbacks', 'revisions', 'custom-fields',
                                           'page-attributes', 'post-formats')
        );
        register_post_type('archive', $limbo_args);
    }

    // ----------------------------------------------------
    // Archive posts
    // ----------------------------------------------------
    function ess_limbo_archive_post(){
        global $wpdb;
        if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'ess_limbo_archive_post' == $_REQUEST['action'] ) ) ) {
            wp_die('No post to duplicate has been supplied!');
        }

        $post_id  = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);
        $post     = get_post( $post_id );

        $current_user     = wp_get_current_user();
        $new_post_author  = $current_user->ID;

        if (isset( $post ) && $post != null) {
            $args = array(
                'comment_status' => $post->comment_status,
                'ping_status'    => $post->ping_status,
                'post_author'    => $new_post_author,
                'post_content'   => $post->post_content,
                'post_excerpt'   => $post->post_excerpt,
                'post_name'      => $post->post_name,
                'post_parent'    => $post->post_parent,
                'post_password'  => $post->post_password,
                'post_status'    => 'published',
                'post_title'     => $post->post_title,
                'post_type'      => 'archive',
                'to_ping'        => $post->to_ping,
                'menu_order'     => $post->menu_order
            );

            $new_post_id = wp_insert_post( $args );

            $taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
            foreach ($taxonomies as $taxonomy) {
                $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
                wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
            }

            $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
            if (count($post_meta_infos)!=0) {
                $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
                foreach ($post_meta_infos as $meta_info) {
                    $meta_key        = $meta_info->meta_key;
                    $meta_value      = addslashes($meta_info->meta_value);
                    $sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
                }
                $sql_query .= implode(" UNION ALL ", $sql_query_sel);
                $wpdb->query($sql_query);
            }

            wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
            exit;
        } else {
            wp_die('Post creation failed, could not find original post: ' . $post_id);
        }
    }
    add_action( 'admin_action_ess_limbo_archive_post', 'ess_limbo_archive_post' );

    // ----------------------------------------------------
    // Arquive this [Link]
    // ----------------------------------------------------
    function ess_limbo_archive_post_link( $actions, $post ) {
        if (current_user_can('edit_posts') && 'archive' !== get_post_type()) {
            $label = __('Archive this', 'ess-limbo');
            $actions['archive'] = '<a href="admin.php?action=ess_limbo_archive_post&amp;post=' . $post->ID . '" title="Duplicate this item" rel="permalink">' . $label . '</a>';
        }
        return $actions;
    }

    add_filter( 'post_row_actions', 'ess_limbo_archive_post_link', 10, 2 );
    add_filter( 'page_row_actions', 'ess_limbo_archive_post_link', 10, 2 );

    // ----------------------------------------------------
    // Columns labels [admin]
    // ----------------------------------------------------
    add_filter("manage_edit-archive_columns", "ess_limbo_edit_columns");
    add_action("manage_archive_posts_custom_column",  "ess_limbo_columns_display");
    function ess_limbo_edit_columns($limbo_columns){
        $limbo_columns = array(
            "cb"        => "<input type=\"checkbox\" />",
            "icon"      => "",
            "title"     => __('Title', 'ess-le-limbo'),
//          "shortcode" => __('Shortcode', 'ess-le-limbo'),
            "author"    => __('Archived by', 'ess-le-limbo'),
            "date"      => __('Archived date', 'ess-le-limbo')
        );
        return $limbo_columns;
    }

    // ----------------------------------------------------
    // Columns [admin]
    // ----------------------------------------------------
    function ess_limbo_columns_display($limbo_columns){
        switch ($limbo_columns) {
            case "icon":
            the_post_thumbnail( array(80,60), array('class' => 'attachment-80x60' ) );
            break;
            case "shortcode":
            echo '<pre>[ess-limbo-page id="'. get_the_ID() .'"]</pre>';
            break;
        }
    }
}
