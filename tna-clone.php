<?php
/**
 * Plugin Name: TNA Clone Page
 * Plugin URI: https://github.com/nationalarchives/tna-clone-page
 * Description: Clones page for editing - The National Archives Wordpress plugin.
 * Version: 1.5.2
 * Author: The National Archives
 * Author URI: https://github.com/nationalarchives
 * License: GPL2
 */

// define( 'DONOTCACHEPAGE', true );

/* Included functions */
include 'functions.php';


add_action( 'init', 'clone_status' );
add_action( 'admin_footer-post.php', 'append_clone_status_list' );
add_action( 'cloned_page',  'save_page_to_transient', 10, 1 );
add_action( 'publish_page',  'delete_page_in_transient', 10, 1 );
add_action( 'template_redirect', 'load_cloned_page' );
add_action( 'post_submitbox_misc_actions', 'adds_save_clone_button' );

add_filter( 'wp_insert_post_data' , 'save_clone' , '99', 2 );
