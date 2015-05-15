<?php
/*
Plugin Name: Le Limbo
Plugin URI: http://www.elvessousa.com.br
Description: Plugin for archived posts.
Version: 0.1
Author: Elves Sousa
Author Email: essousa@fcl.com.br
License:

	Copyright 2011 Elves Sousa (essousa@fcl.com.br)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

// ----------------------------------------------------
// Constants
// ----------------------------------------------------
define('ESS_LIMBO_URL', WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ) );
define('ESS_LIMBO_PATH', WP_PLUGIN_DIR."/".dirname( plugin_basename( __FILE__ ) ) );

// ----------------------------------------------------
// Enqueues [admin]
// ----------------------------------------------------
function ess_le_limbo_activate() {
    add_option('ess_limbo_options', '');
}
register_activation_hook( __FILE__, 'ess_le_limbo_activate' );

// ----------------------------------------------------
// Enqueues [admin]
// ----------------------------------------------------
function ess_le_limbo_admin_init() {
    // DHs
}
add_action('init', 'ess_le_limbo_admin_init');

// ----------------------------------------------------
// Enqueues
// ----------------------------------------------------
function ess_limbo_enqueues() {
    // ENQU
}
add_action('admin_enqueue_scripts', 'ess_limbo_enqueues');


// ----------------------------------------------------
// Translation
// ----------------------------------------------------
function ess_le_limbo_textdomain() {
    $languagesdir = dirname(plugin_basename(__FILE__)) . '/languages';
    load_plugin_textdomain('ess-limbo', false, $languagesdir);
}
add_action('init', 'ess_le_limbo_textdomain');


// ----------------------------------------------------
// Single template
// ----------------------------------------------------
function ess_limbo_custom_template($single) {
    global $post;
    if ($post->post_type == 'archive'){
        return ESS_LIMBO_PATH . '/templates/single-limbo.php';
    }
    return $single;
}
add_filter('single_template', 'ess_limbo_custom_template', 99);

// ----------------------------------------------------
// Substitute slashes
// ----------------------------------------------------
function ess_limbo_html_slashes($str) {
    $string = str_replace('"',"&quot;",$str);
    $string = str_replace("'","&#8217;",$str);
    return $string;
}

// ----------------------------------------------------
// Get sidebar
// ----------------------------------------------------
if(!function_exists('get_dynamic_sidebar')) {
    function get_dynamic_sidebar($index = 1) {
        $sidebar_contents = "";
        ob_start();
        dynamic_sidebar($index);
        $sidebar_contents = ob_get_clean();
        return $sidebar_contents;
    }
}

// ----------------------------------------------------
// Get layout options
// ----------------------------------------------------
function ess_limbo_options($option = null, $suboption = null) {

    $optionsjson  = stripslashes(get_option('ess_limbo_options'));
    $options      = json_decode($optionsjson, true);

    if ($option) {
        if ($suboption) {
            return $options[$option][$suboption];
        } else {
            return $options[$option];
        }
    } else {
        return $option;
    }
}

// ----------------------------------------------------
// Layout options page
// ----------------------------------------------------
function ess_register_limbo_options_page() {
    add_submenu_page(
        'edit.php?post_type=archive',
        __('Layout options','ess-customposts'),
        __('Layout options','ess-customposts'),
        'manage_options',
        'limbo-options', 'ess_limbo_options_page'
    );
}
//add_action('admin_menu', 'ess_register_limbo_options_page');


// ----------------------------------------------------
// Includes
// ----------------------------------------------------
require_once(ESS_LIMBO_PATH . '/limbo-type.php');
//require_once(ESS_LIMBO_PATH . '/admin/limbo-options.php');
?>
