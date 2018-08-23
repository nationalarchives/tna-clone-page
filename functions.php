<?php

function clone_status() {
	register_post_status( 'cloned', array(
		'label'                     => _x( 'Cloned', 'page' ),
		'public'                    => true,
		'exclude_from_search'       => true,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Cloned <span class="count">(%s)</span>', 'Cloned <span class="count">(%s)</span>' ),
	) );
}

function append_clone_status_list() {
	global $post;
	$complete = '';
	$label = '';
	if($post->post_status == 'cloned') {
		$complete = ' selected=\"selected\"';
		$label = 'Cloned';
	}
	echo js_clone_option( $complete, $label );
}

function js_clone_option( $complete, $label ) {

	$js = '
<script>
      jQuery(document).ready(function($){
           $("select#post_status").append("<option value=\"cloned\" %s>Clone</option>");
           $(".misc-pub-post-status #post-status-display").append("%s");
      });
</script>
';

	return sprintf( $js, $complete, $label );
}

function get_rendered_html( $page_url ) {

	if ( !class_exists('WP_Http') ) {
		include_once( ABSPATH . WPINC . '/class-http.php');
	}

	$request = new WP_Http;
	$result = $request->request( $page_url );
	$content = $result['body'];

	return $content;
}

function save_page_to_transient() {
	global $post;

	$page = get_transient( 'tna-clone-'.$post->ID );

	if ( ! $page ) {
		$permalink = get_permalink( $post->ID );

		$page = get_rendered_html( $permalink );

		set_transient( 'tna-clone-'.$post->ID, $page, 30 * DAY_IN_SECONDS );
	}
}

function delete_page_in_transient() {
	global $post;

	$page = get_transient( 'tna-clone-'.$post->ID );

	if( $page ) {
		delete_transient( 'tna-clone-'.$post->ID );
	}
}

function load_cloned_page() {
	global $post;

	$page = get_transient( 'tna-clone-'.$post->ID );

	if( $page && ! is_admin() ) {
		echo '<!-- Cloned page --> '.$page.' <!-- End cloned page --> ';
		die;
	}
}

function adds_save_clone_button() {
	 ?>
		<div id="draft-action">
			<div id="save-action">
				<input type="submit" name="save_clone_status" id="save-clone" value="Save Clone" class="button">
			</div>
			<div class="clear"></div>
		</div>
	<?php
}

function save_clone( $data, $postarr ) {
	if ( isset( $postarr['save_clone_status'] ) ) {

		if ( $postarr['save_clone_status'] == 'Save Clone' ) {
			$data['post_status'] = 'cloned';
		}
	}
	return $data;
}

