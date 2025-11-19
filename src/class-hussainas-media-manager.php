<?php
/**
 * Class Hussainas_Media_Manager
 *
 * Handles the registration of media taxonomies and integration
 * with the WordPress Media Library interface.
 *
 * @package Hussainas_Media_System
 * @version     1.0.0
 * @author      Hussain Ahmed Shrabon
 * @license     GPL-2.0-or-later
 * @link        https://github.com/iamhussaina
 * @textdomain  hussainas
 
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Hussainas_Media_Manager {

    /**
     * Taxonomy key constant.
     */
    const TAXONOMY_KEY = 'media_folder';

    /**
     * Initialize the manager.
     *
     * Registers necessary hooks for the media organization system.
     */
    public function init() {
        // Register the taxonomy.
        add_action( 'init', [ $this, 'register_media_taxonomy' ] );

        // Add dropdown filter to the media library list view.
        add_action( 'restrict_manage_posts', [ $this, 'add_taxonomy_filter' ] );

        // Add custom column header to media library.
        add_filter( 'manage_upload_columns', [ $this, 'add_column_header' ] );

        // Render custom column content.
        add_action( 'manage_media_custom_column', [ $this, 'render_column_content' ], 10, 2 );
        
        // Ensure taxonomy script support for attachment details.
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    /**
     * Registers the hierarchical taxonomy for attachments.
     * * This creates the "Folder" interface in the standard WP way.
     */
    public function register_media_taxonomy() {
        $labels = [
            'name'              => 'Media Folders',
            'singular_name'     => 'Media Folder',
            'search_items'      => 'Search Folders',
            'all_items'         => 'All Folders',
            'parent_item'       => 'Parent Folder',
            'parent_item_colon' => 'Parent Folder:',
            'edit_item'         => 'Edit Folder',
            'update_item'       => 'Update Folder',
            'add_new_item'      => 'Add New Folder',
            'new_item_name'     => 'New Folder Name',
            'menu_name'         => 'Folders',
        ];

        $args = [
            'hierarchical'      => true, // True creates a folder-like structure (parent/child).
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => [ 'slug' => 'media-folder' ],
            'update_count_callback' => '_update_post_term_count',
        ];

        register_taxonomy( self::TAXONOMY_KEY, [ 'attachment' ], $args );
    }

    /**
     * Adds a dropdown filter to the Media Library (List View).
     *
     * Allows users to filter images by specific folders.
     */
    public function add_taxonomy_filter() {
        $screen = get_current_screen();

        // Only render on the upload (media library) screen.
        if ( 'upload' !== $screen->id ) {
            return;
        }

        $selected = isset( $_GET[ self::TAXONOMY_KEY ] ) ? $_GET[ self::TAXONOMY_KEY ] : 0;

        wp_dropdown_categories( [
            'show_option_all' => 'All Folders',
            'taxonomy'        => self::TAXONOMY_KEY,
            'name'            => self::TAXONOMY_KEY,
            'orderby'         => 'name',
            'selected'        => $selected,
            'hierarchical'    => true,
            'value_field'     => 'slug',
            'show_count'      => true,
            'hide_empty'      => false,
        ] );
    }

    /**
     * Adds the 'Folder' column to the media list table headers.
     *
     * @param array $columns Existing columns.
     * @return array Modified columns.
     */
    public function add_column_header( $columns ) {
        $columns[ self::TAXONOMY_KEY ] = 'Folder';
        return $columns;
    }

    /**
     * Renders the content for the custom folder column.
     *
     * @param string $column_name The name of the column being rendered.
     * @param int    $post_id     The ID of the current attachment.
     */
    public function render_column_content( $column_name, $post_id ) {
        if ( self::TAXONOMY_KEY !== $column_name ) {
            return;
        }

        $terms = get_the_terms( $post_id, self::TAXONOMY_KEY );

        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            $output = [];
            foreach ( $terms as $term ) {
                $filter_link = esc_url( add_query_arg( [
                    'post_type'      => 'attachment',
                    self::TAXONOMY_KEY => $term->slug,
                ], admin_url( 'upload.php' ) ) );

                $output[] = sprintf(
                    '<a href="%s">%s</a>',
                    $filter_link,
                    esc_html( $term->name )
                );
            }
            echo implode( ', ', $output );
        } else {
            echo '<span aria-hidden="true">—</span>';
        }
    }

    /**
     * Enqueues specific styles or scripts if necessary.
     * * Currently used to fix minor UI alignment in the media modal if needed.
     */
    public function enqueue_assets() {
        // Placeholder for any CSS needed for the folder UI.
        // Keeping it standard WP UI for now to ensure lightweight integration.
    }
}
