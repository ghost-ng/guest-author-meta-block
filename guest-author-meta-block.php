<?php
/**
 * Plugin Name: Guest Author Meta Block
 * Description: A plugin that adds a "Guest Author(s)" meta box to the post editing sidebar and a Gutenberg block to display it.
 * Version: .75
 * Author: <a href="https://github.com/ghost-ng/" target="_blank">ghost-ng</a>
 * Author URI: https://github.com/ghost-ng/
 */

// Prevent direct access to the file.
if (!defined('ABSPATH')) {
    exit;
}

// Register the custom post meta for guest author.
function gam_register_guest_author_meta() {
    register_post_meta('post', '_guest_author_name', array(
        'type' => 'string',
        'description' => 'Guest Author Name',
        'single' => true,
        'show_in_rest' => true, // Ensure it's available in the REST API for Gutenberg.
        'auth_callback' => function() {
            return current_user_can('edit_posts');
        }
    ));
}
add_action('init', 'gam_register_guest_author_meta');

// Add the meta box to the post editing sidebar.
function gam_add_guest_author_meta_box() {
    add_meta_box(
        'guest_author_meta_box',          // Unique ID
        'Guest Author(s)',                // Box title
        'gam_guest_author_meta_box_html', // Content callback
        'post',                           // Post type
        'side',                           // Context (right sidebar)
        'high'                            // Priority
    );
}
add_action('add_meta_boxes', 'gam_add_guest_author_meta_box');

// Render the HTML for the meta box.
function gam_guest_author_meta_box_html($post) {
    // Retrieve the value from the post meta.
    $guest_authors = get_post_meta($post->ID, '_guest_author_name', true);
    ?>
    <label for="guest_author_field">Guest Author(s)</label>
    <input type="text" id="guest_author_field" name="guest_author_field" value="<?php echo esc_attr($guest_authors); ?>" placeholder="Enter guest author name(s)" style="width:100%;">
    <?php
}

// Save the meta box data when the post is saved.
function gam_save_guest_author_meta_box($post_id) {
    // Verify autosave is not being used.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Verify user has permission to edit post.
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save the guest author field.
    if (isset($_POST['guest_author_field'])) {
        update_post_meta($post_id, '_guest_author_name', sanitize_text_field($_POST['guest_author_field']));
    }
}
add_action('save_post', 'gam_save_guest_author_meta_box');

// Enqueue the Gutenberg block's JavaScript for the block editor.
function gam_enqueue_block_editor_assets() {
    wp_enqueue_script(
        'gam-guest-author-block',
        plugins_url('guest-author-meta-block.js', __FILE__),
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-data'),
        filemtime(plugin_dir_path(__FILE__) . 'guest-author-meta-block.js') // Ensure the file is refreshed when modified.
    );
}
add_action('enqueue_block_editor_assets', 'gam_enqueue_block_editor_assets');

// Render the guest author name dynamically for each post on the frontend.
function render_guest_author_block( $attributes, $content, $block ) {
    // Fetch the postId from the block's context
    $post_id = isset( $block->context['postId'] ) ? $block->context['postId'] : get_the_ID();

    // Fetch guest author from post meta
    $guest_author = get_post_meta( $post_id, '_guest_author_name', true );
    if ( ! $guest_author ) {
        $guest_author = 'Anonymous';  // Fallback if no guest author found
    }

    // Handle block attributes for formatting
    $font_size = isset( $attributes['fontSize'] ) ? $attributes['fontSize'] : 16;
    $font_weight = isset( $attributes['isBold'] ) && $attributes['isBold'] ? 'bold' : 'normal';
    $font_style = isset( $attributes['isItalic'] ) && $attributes['isItalic'] ? 'italic' : 'normal';
    $text_decoration = isset( $attributes['isUnderline'] ) && $attributes['isUnderline'] ? 'underline' : 'none';
    $is_hidden = isset( $attributes['isHidden'] ) && $attributes['isHidden'];

    // If hidden, return a placeholder
    if ( $is_hidden ) {
        return '<div><hidden placeholder></div>';
    }

    // Style for the output (similar to editor-side)
    $style = sprintf(
        'font-size: %dpx; font-weight: %s; font-style: %s; text-decoration: %s;',
        esc_attr( $font_size ),
        esc_attr( $font_weight ),
        esc_attr( $font_style ),
        esc_attr( $text_decoration )
    );

    // Render the block content with dynamic data and styles
    return sprintf(
        '<div class="wp-block-guest-author" style="%s">By: %s</div>',
        esc_attr( $style ),
        esc_html( $guest_author )
    );
}

// Register the dynamic block with the rendering callback
register_block_type( 'gam/guest-author-display', array(
    'render_callback' => 'render_guest_author_block',
    'attributes'      => array(
        'fontSize'   => array(
            'type'    => 'number',
            'default' => 16,
        ),
        'isBold'     => array(
            'type'    => 'boolean',
            'default' => false,
        ),
        'isItalic'   => array(
            'type'    => 'boolean',
            'default' => false,
        ),
        'isUnderline'=> array(
            'type'    => 'boolean',
            'default' => false,
        ),
        'isHidden'   => array(
            'type'    => 'boolean',
            'default' => false,
        ),
    ),
    'provides_context' => array(
        'postId' => 'postId',
    ),
) );