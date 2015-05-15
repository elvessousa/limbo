<?php
/*-----------------------------------------------------------------------

	Post type Name: Limbo
    Make sure you know what you're doing before altering this code.

-----------------------------------------------------------------------*/
get_header();
wp_enqueue_style('ess-limbo', ESS_LIMBO_URL . '/css/ess-limbo-style.css');

if ( is_user_logged_in() ) {

    // ----------------------------------------------------
    // Archive page, show only for logged users
    // ----------------------------------------------------
    $id       = get_the_ID();
    $content  = get_the_content($id);
    $date     = '';
    $excerpt  = get_the_excerpt();
    $meta     = get_post_meta($id);
    $fields   = '<dt>'. __('Date', 'ess-limbo') .'</dt><dd>'. get_the_date() .'</dd>';

    // Custom fields
    foreach($meta as $key => $data){
        $label = str_replace("_", ' ', $key);
        $field = $data[0];

        if(is_array($data[0])) {
            $field = '<pre>'. $field . '</pre>';
        }
        $fields .= '<dt>'.$label.'</dt><dd>'.$field.'</dd>';
    }

    // Archive
    $output  = '<div class="limbo container">';
    $output .= '<div class="col-xs-12">';
    $output .= "<div class='date'>$date</div>";

    // Content
    if ($content) {
        $output .= '<h3 class="limbo-title">' . __('Content', 'ess-limbo') . '</h3>';
        $output .= do_shortcode((wpautop($content)));
        $output .= '<hr>';
    }

    // Excerpt
    if ($excerpt) {
        $output .= '<h3 class="limbo-title">' . __('Excerpt', 'ess-limbo') . '</h3> ';
        $output .= $excerpt;
        $output .= '<hr>';
    }

    // Custom fields
    $output .= '<h3 class="limbo-title">' . __('Custom fields', 'ess-limbo') . '</h3>';
    $output .= "<dl class='dl-horizontal'>$fields</dl>";

    $output .= '</div>';
    $output .= '</div>';

    echo $output;
} else {

    // ----------------------------------------------------
    // Archive page, for unlogged users
    // ----------------------------------------------------
    $output  = '<div class="limbo container">';
    $output .= '<div class="no-access">';
    $output .= '<h1 class="text-center">' . __('Sorry', 'ess-limbo') . '</h1>';
    $output .= '<h4 class="text-center">' . __('Access not allowed', 'ess-limbo') . '</h4>';
    $output .= '</div>';
    $output .= '</div>';
    echo $output;
}

get_footer();


