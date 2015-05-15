<?php
/*-----------------------------------------------------------------------

	LAYOUT OPTIONS
    Prints and saves
    Make sure you know what you're doing before altering this code.

    - Enqueues
    - Save to database

------------------------------------------------------------------------*/

// ----------------------------------------------------
// Enqueues
// ----------------------------------------------------
function ess_layout_options_page() {

    // Angular
    wp_enqueue_script('angularjs', ESS_LAYOUT_URL . '/bin/angular.min.js',  array(), null );
    wp_enqueue_script('angularjs-sanitize', ESS_LAYOUT_URL . '/bin/angular-sanitize.min.js', array(), null  );
    wp_enqueue_script('angularjs-sortable', ESS_LAYOUT_URL . '/bin/sortable.js', array(), null  );

    // Labels
    $labels = array(
        'site_url'  => get_home_url(),
    );

    // Scripts
    wp_enqueue_style('layout-options', ESS_LAYOUT_URL . '/css/ess-layout-options.css');
    wp_register_script('layout-options', ESS_LAYOUT_URL . '/js/ess-layout-options.js', array(), true);
    wp_localize_script('layout-options', 'ess_layout', $labels);
    wp_enqueue_script('layout-options');

    // Options page
    ess_layout_options_save();
    require_once( ESS_LAYOUT_PATH . '/admin/layout-options-fields.php');

}

// ----------------------------------------------------
// Save to database
// ----------------------------------------------------
function ess_layout_options_save() {
    $message = '';
    if ( 'save' == $_REQUEST['action'] ) {
        $message  = '<p class="ess-message">';
        $message .= __('Layout options saved!', 'ess-layout');
        $meesage .= '</p>';
        echo $message;
        $layout_options = sanitize_text_field($_REQUEST['ess_layout_options']);
        if (!get_option('ess_layout_options')) {
            add_option('ess_layout_options', $layout_options);
            update_option('ess_layout_options', $layout_options);
        } else {
            update_option('ess_layout_options', $layout_options);
        }
    }
}
