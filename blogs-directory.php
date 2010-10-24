<?php
/*
Plugin Name: Blogs Directory
Plugin URI: http://premium.wpmudev.org/project/blogs-directory
Description: This plugin provides a paginated, fully search-able, avatar inclusive, automatic and rather good looking directory of all of the blogs on your WordPress Multisite or BuddyPress installation.
Author: Andrew Billits, Ulrich Sossou (Incsub)
Version: 1.0.9
Author URI:
*/

/*
Copyright 2007-2009 Incsub (http://incsub.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 2 - GPLv2) as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

//------------------------------------------------------------------------//
//---Config---------------------------------------------------------------//
//------------------------------------------------------------------------//

$blogs_directory_base = 'blogs'; //domain.tld/BASE/ Ex: domain.tld/user/

//------------------------------------------------------------------------//
//---Hook-----------------------------------------------------------------//
//------------------------------------------------------------------------//

if ($current_blog->domain . $current_blog->path == $current_site->domain . $current_site->path){
	add_filter('generate_rewrite_rules','blogs_directory_rewrite');
	$blogs_directory_wp_rewrite = new WP_Rewrite;
	$blogs_directory_wp_rewrite->flush_rules();
	add_filter('the_content', 'blogs_directory_output', 20);
	add_filter('the_title', 'blogs_directory_title_output', 99, 2);
	add_action('admin_footer', 'blogs_directory_page_setup');
}

add_action('wpmu_options', 'blogs_directory_site_admin_options');
add_action('update_wpmu_options', 'blogs_directory_site_admin_options_process');

//------------------------------------------------------------------------//
//---Functions------------------------------------------------------------//
//------------------------------------------------------------------------//

function blogs_directory_page_setup() {
	global $wpdb, $user_ID, $blogs_directory_base;
	if ( get_site_option('blogs_directory_page_setup') != 'complete' && is_site_admin() ) {
		$page_count = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->posts . " WHERE post_name = '" . $blogs_directory_base . "' AND post_type = 'page'");
		if ( $page_count < 1 ) {
			$wpdb->query( "INSERT INTO " . $wpdb->posts . " ( post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, guid, menu_order, post_type, post_mime_type, comment_count ) VALUES ( '" . $user_ID . "', '" . current_time( 'mysql' ) . "', '" . current_time( 'mysql' ) . "', '', '" . __('Blogs') . "', '', 'publish', 'closed', 'closed', '', '" . $blogs_directory_base . "', '', '', '" . current_time( 'mysql' ) . "', '" . current_time( 'mysql' ) . "', '', 0, '', 0, 'page', '', 0 )" );
		}
		update_site_option('blogs_directory_page_setup', 'complete');
	}
}

function blogs_directory_site_admin_options() {
	$blogs_directory_sort_by = get_site_option('blogs_directory_sort_by', 'alphabetically');
	$blogs_directory_per_page = get_site_option('blogs_directory_per_page', '10');
	$blogs_directory_background_color = get_site_option('blogs_directory_background_color', '#F2F2EA');
	$blogs_directory_alternate_background_color = get_site_option('blogs_directory_alternate_background_color', '#FFFFFF');
	$blogs_directory_border_color = get_site_option('blogs_directory_border_color', '#CFD0CB');
	?>
		<h3><?php _e('Blogs Directory') ?></h3>
		<table class="form-table">
            <tr valign="top">
                <th width="33%" scope="row"><?php _e('Sort By') ?></th>
                <td>
                    <select name="blogs_directory_sort_by" id="blogs_directory_sort_by">
                       <option value="alphabetically" <?php if ( $blogs_directory_sort_by == 'alphabetically' ) { echo 'selected="selected"'; } ?> ><?php _e('Blog Name (A-Z)'); ?></option>
                       <option value="latest" <?php if ( $blogs_directory_sort_by == 'latest' ) { echo 'selected="selected"'; } ?> ><?php _e('Newest'); ?></option>
                       <option value="last_updated" <?php if ( $blogs_directory_sort_by == 'last_updated' ) { echo 'selected="selected"'; } ?> ><?php _e('Last Updated'); ?></option>
                    </select>
                <br /><?php //_e('') ?></td>
            </tr>
            <tr valign="top">
                <th width="33%" scope="row"><?php _e('Listing Per Page') ?></th>
                <td>
				<select name="blogs_directory_per_page" id="blogs_directory_per_page">
				   <option value="5" <?php if ( $blogs_directory_per_page == '5' ) { echo 'selected="selected"'; } ?> ><?php _e('5'); ?></option>
				   <option value="10" <?php if ( $blogs_directory_per_page == '10' ) { echo 'selected="selected"'; } ?> ><?php _e('10'); ?></option>
				   <option value="15" <?php if ( $blogs_directory_per_page == '15' ) { echo 'selected="selected"'; } ?> ><?php _e('15'); ?></option>
				   <option value="20" <?php if ( $blogs_directory_per_page == '20' ) { echo 'selected="selected"'; } ?> ><?php _e('20'); ?></option>
				   <option value="25" <?php if ( $blogs_directory_per_page == '25' ) { echo 'selected="selected"'; } ?> ><?php _e('25'); ?></option>
				   <option value="30" <?php if ( $blogs_directory_per_page == '30' ) { echo 'selected="selected"'; } ?> ><?php _e('30'); ?></option>
				   <option value="35" <?php if ( $blogs_directory_per_page == '35' ) { echo 'selected="selected"'; } ?> ><?php _e('35'); ?></option>
				   <option value="40" <?php if ( $blogs_directory_per_page == '40' ) { echo 'selected="selected"'; } ?> ><?php _e('40'); ?></option>
				   <option value="45" <?php if ( $blogs_directory_per_page == '45' ) { echo 'selected="selected"'; } ?> ><?php _e('45'); ?></option>
				   <option value="50" <?php if ( $blogs_directory_per_page == '50' ) { echo 'selected="selected"'; } ?> ><?php _e('50'); ?></option>
				</select>
                <br /><?php //_e('') ?></td>
            </tr>
            <tr valign="top">
                <th width="33%" scope="row"><?php _e('Background Color') ?></th>
                <td><input name="blogs_directory_background_color" type="text" id="blogs_directory_background_color" value="<?php echo $blogs_directory_background_color; ?>" size="20" />
                <br /><?php _e('Default') ?>: #F2F2EA</td>
            </tr>
            <tr valign="top">
                <th width="33%" scope="row"><?php _e('Alternate Background Color') ?></th>
                <td><input name="blogs_directory_alternate_background_color" type="text" id="blogs_directory_alternate_background_color" value="<?php echo $blogs_directory_alternate_background_color; ?>" size="20" />
                <br /><?php _e('Default') ?>: #FFFFFF</td>
            </tr>
            <tr valign="top">
                <th width="33%" scope="row"><?php _e('Border Color') ?></th>
                <td><input name="blogs_directory_border_color" type="text" id="blogs_directory_border_color" value="<?php echo $blogs_directory_border_color; ?>" size="20" />
                <br /><?php _e('Default') ?>: #CFD0CB</td>
            </tr>
		</table>
	<?php
}

function blogs_directory_site_admin_options_process() {

	update_site_option( 'blogs_directory_sort_by' , $_POST['blogs_directory_sort_by']);
	update_site_option( 'blogs_directory_per_page' , $_POST['blogs_directory_per_page']);
	update_site_option( 'blogs_directory_background_color' , trim( $_POST['blogs_directory_background_color'] ));
	update_site_option( 'blogs_directory_alternate_background_color' , trim( $_POST['blogs_directory_alternate_background_color'] ));
	update_site_option( 'blogs_directory_border_color' , trim( $_POST['blogs_directory_border_color'] ));
}

function blogs_directory_rewrite($wp_rewrite){
	global $blogs_directory_base;
    $blogs_directory_rules = array(
        $blogs_directory_base . '/([^/]+)/([^/]+)/([^/]+)/([^/]+)/?$' => 'index.php?pagename=' . $blogs_directory_base,
        $blogs_directory_base . '/([^/]+)/([^/]+)/([^/]+)/?$' => 'index.php?pagename=' . $blogs_directory_base,
        $blogs_directory_base . '/([^/]+)/([^/]+)/?$' => 'index.php?pagename=' . $blogs_directory_base,
        $blogs_directory_base . '/([^/]+)/?$' => 'index.php?pagename=' . $blogs_directory_base
    );
    $wp_rewrite->rules = $blogs_directory_rules + $wp_rewrite->rules;
	return $wp_rewrite;
}

function blogs_directory_url_parse(){
	global $wpdb, $current_site, $blogs_directory_base;
	$blogs_directory_url = $_SERVER['REQUEST_URI'];
	if ( $current_site->path != '/' ) {
		$blogs_directory_url = str_replace('/' . $current_site->path . '/', '', $blogs_directory_url);
		$blogs_directory_url = str_replace($current_site->path . '/', '', $blogs_directory_url);
		$blogs_directory_url = str_replace($current_site->path, '', $blogs_directory_url);
	}
	$blogs_directory_url = ltrim($blogs_directory_url, "/");
	$blogs_directory_url = rtrim($blogs_directory_url, "/");
	$blogs_directory_url = ltrim($blogs_directory_url, $blogs_directory_base);
	$blogs_directory_url = ltrim($blogs_directory_url, "/");

	$blogs_directory_1 = $blogs_directory_2 = $blogs_directory_3 = $blogs_directory_4 = '';
	if( !empty( $blogs_directory_url ) ) {
		$blogs_directory_array = explode("/", $blogs_directory_url);
		for( $i = 1, $j = count( $blogs_directory_array ); $i <= $j ; $i++ ) {
			$blogs_directory_var = "blogs_directory_$i";
			${$blogs_directory_var} = $blogs_directory_array[$i-1];
		}
	}

	$page_type = '';
	$page_subtype = '';
	$page = '';
	$blog = '';
	$phrase = '';
	if ( empty( $blogs_directory_1 ) || is_numeric( $blogs_directory_1 ) ) {
		//landing
		$page_type = 'landing';
		$page = $blogs_directory_1;
		if ( empty( $page ) ) {
			$page = 1;
		}
	} else if ( $blogs_directory_1 == 'search' ) {
		//search
		$page_type = 'search';
		$phrase = isset( $_POST['phrase'] ) ? $_POST['phrase'] : '';
		if ( empty( $phrase ) ) {
			$phrase = $blogs_directory_2;
			$page = $blogs_directory_3;
			if ( empty( $page ) ) {
				$page = 1;
			}
		} else {
			$page = $blogs_directory_3;
			if ( empty( $page ) ) {
				$page = 1;
			}
		}
		$phrase = urldecode( $phrase );
	}

	$blogs_directory['page_type'] = $page_type;
	$blogs_directory['page'] = $page;
	$blogs_directory['phrase'] = $phrase;

	return $blogs_directory;
}

//------------------------------------------------------------------------//
//---Output Functions-----------------------------------------------------//
//------------------------------------------------------------------------//

function blogs_directory_title_output($title, $post_ID = '') {
	global $wpdb, $current_site, $post, $blogs_directory_base;

	if ( in_the_loop() && !empty( $post ) && $post->post_name == $blogs_directory_base && $post_ID == $post->ID) {
		$blogs_directory = blogs_directory_url_parse();
		if ( $blogs_directory['page_type'] == 'landing' ) {
			if ( $blogs_directory['page'] > 1 ) {
				$title = '<a href="http://' . $current_site->domain . $current_site->path . $blogs_directory_base . '/">' . $post->post_title . '</a> &raquo; ' . '<a href="http://' . $current_site->domain . $current_site->path . $blogs_directory_base . '/' . $blogs_directory['page'] . '/">' . $blogs_directory['page'] . '</a>';
			} else {
				$title = '<a href="http://' . $current_site->domain . $current_site->path . $blogs_directory_base . '/">' . $post->post_title . '</a>';
			}
		} else if ( $blogs_directory['page_type'] == 'search' ) {
			if ( $blogs_directory['page'] > 1 ) {
				$title = '<a href="http://' . $current_site->domain . $current_site->path . $blogs_directory_base . '/">' . $post->post_title . '</a> &raquo; <a href="http://' . $current_site->domain . $current_site->path . $blogs_directory_base . '/search/">' . __('Search') . '</a> &raquo; ' . '<a href="http://' . $current_site->domain . $current_site->path . $blogs_directory_base . '/search/' . urlencode($blogs_directory['phrase']) .  '/' . $blogs_directory['page'] . '/">' . $blogs_directory['page'] . '</a>';
			} else {
				$title = '<a href="http://' . $current_site->domain . $current_site->path . $blogs_directory_base . '/">' . $post->post_title . '</a> &raquo; <a href="http://' . $current_site->domain . $current_site->path . $blogs_directory_base . '/search/">' . __('Search') . '</a>';
			}
		}
	}
	return $title;
}

function blogs_directory_output($content) {
	global $wpdb, $current_site, $post, $blogs_directory_base;
	$bg_color = '';
	if ( $post->post_name == $blogs_directory_base ) {
		$blogs_directory_sort_by = get_site_option('blogs_directory_sort_by', 'alphabetically');
		$blogs_directory_per_page = get_site_option('blogs_directory_per_page', '10');
		$blogs_directory_background_color = get_site_option('blogs_directory_background_color', '#F2F2EA');
		$blogs_directory_alternate_background_color = get_site_option('blogs_directory_alternate_background_color', '#FFFFFF');
		$blogs_directory_border_color = get_site_option('blogs_directory_border_color', '#CFD0CB');
		$blogs_directory = blogs_directory_url_parse();
		if ( $blogs_directory['page_type'] == 'landing' ) {
			$search_form_content = blogs_directory_search_form_output('', $blogs_directory['phrase']);
			$navigation_content = blogs_directory_landing_navigation_output('', $blogs_directory_per_page, $blogs_directory['page']);
			$content .= $search_form_content;
			$content .= '<br />';
			$content .= $navigation_content;
			$content .= '<div style="float:left; width:100%">';
			$content .= '<table border="0" border="0" cellpadding="2px" cellspacing="2px" width="100%" bgcolor="">';
				$content .= '<tr>';
					$content .= '<td style="background-color:' . $blogs_directory_background_color . '; border-bottom-style:solid; border-bottom-color:' . $blogs_directory_border_color . '; border-bottom-width:1px; font-size:12px;" width="10%"> </td>';
					$content .= '<td style="background-color:' . $blogs_directory_background_color . '; border-bottom-style:solid; border-bottom-color:' . $blogs_directory_border_color . '; border-bottom-width:1px; font-size:12px;" width="90%"><center><strong>' .  __('Blogs') . '</strong></center></td>';
				$content .= '</tr>';
				//=================================//
				$avatar_default = get_option('avatar_default');
				$tic_toc = 'toc';
				//=================================//
				if ($blogs_directory['page'] == 1){
					$start = 0;
				} else {
					$math = $blogs_directory['page'] - 1;
					$math = $blogs_directory_per_page * $math;
					$start = $math;
				}

				$query = "SELECT * FROM " . $wpdb->base_prefix . "blogs WHERE spam != 1 AND deleted != 1 AND blog_id != 1";
				if ( $blogs_directory_sort_by == 'alphabetically' ) {
					if (VHOST == 'yes') {
						$query .= " ORDER BY domain ASC";
					} else {
						$query .= " ORDER BY path ASC";
					}
				} else if ( $blogs_directory_sort_by == 'latest' ) {
					$query .= " ORDER BY blog_id DESC";
				} else {
					$query .= " ORDER BY last_updated DESC";
				}
				$query .= " LIMIT " . intval( $start ) . ", " . intval( $blogs_directory_per_page );
				$blogs = $wpdb->get_results( $query, ARRAY_A );
				if ( count($blogs) > 0 ) {
					//=================================//
					foreach ($blogs as $blog){
						//=============================//
						$blog_title = get_blog_option( $blog['blog_id'], 'blogname', $blog['domain'] . $blog['path'] );
						if ($tic_toc == 'toc'){
							$tic_toc = 'tic';
						} else {
							$tic_toc = 'toc';
						}
						if ($tic_toc == 'tic'){
							$bg_color = $blogs_directory_alternate_background_color;
						} else {
							$bg_color = $blogs_directory_background_color;
						}
						//=============================//
						$content .= '<tr>';
							if ( function_exists('get_blog_avatar') ) {
								$content .= '<td style="background-color:' . $bg_color . '; padding-top:10px;" valign="top" width="10%"><center><a style="text-decoration:none;" href="http://' . $blog['domain'] . $blog['path'] . '">' . get_blog_avatar($blog['blog_id'], 32, $avatar_default) . '</a></center></td>';
							} else {
								$content .= '<td style="background-color:' . $bg_color . '; padding-top:10px;" valign="top" width="10%"></td>';
							}
							$content .= '<td style="background-color:' . $bg_color . ';" width="90%">';
							$content .= '<a style="text-decoration:none; font-size:1.5em; margin-left:20px;" href="http://' . $blog['domain'] . $blog['path'] . '">' . $blog_title . '</a><br />';
							$content .= '</td>';
						$content .= '</tr>';
					}
					//=================================//
				}
			$content .= '</table>';
			$content .= '</div>';
			$content .= $navigation_content;
		} else if ( $blogs_directory['page_type'] == 'search' ) {
			//=====================================//
			if ($blogs_directory['page'] == 1){
				$start = 0;
			} else {
				$math = $blogs_directory['page'] - 1;
				$math = $blogs_directory_per_page * $math;
				$start = $math;
			}
			if (VHOST == 'yes') {
				$query = "SELECT * FROM " . $wpdb->base_prefix . "blogs WHERE ( domain LIKE '%" . $blogs_directory['phrase'] . "%' ) AND spam != 1 AND deleted != 1 AND blog_id != 1";
			} else {
				$query = "SELECT * FROM " . $wpdb->base_prefix . "blogs WHERE ( path LIKE '%" . $blogs_directory['phrase'] . "%' ) AND spam != 1 AND deleted != 1 AND blog_id != 1";
			}
			$query .= " LIMIT " . intval( $start ) . ", " . intval( $blogs_directory_per_page );
			if ( !empty( $blogs_directory['phrase'] ) ) {
				$blogs = $wpdb->get_results( $query, ARRAY_A );
			}
			//=====================================//
			$search_form_content = blogs_directory_search_form_output('', $blogs_directory['phrase']);
			if ( !empty( $blogs ) ) {
				if ( count( $blogs ) < $blogs_directory_per_page ) {
					$next = 'no';
				} else {
					$next = 'yes';
				}
				$navigation_content = blogs_directory_search_navigation_output('', $blogs_directory_per_page, $blogs_directory['page'], $blogs_directory['phrase'], $next);
			}
			$content .= $search_form_content;
			$content .= '<br />';
			if ( !empty( $blogs ) ) {
				$content .= $navigation_content;
			}
			$content .= '<div style="float:left; width:100%">';
			$content .= '<table border="0" border="0" cellpadding="2px" cellspacing="2px" width="100%" bgcolor="">';
				$content .= '<tr>';
					$content .= '<td style="background-color:' . $blogs_directory_background_color . '; border-bottom-style:solid; border-bottom-color:' . $blogs_directory_border_color . '; border-bottom-width:1px; font-size:12px;" width="10%"> </td>';
					$content .= '<td style="background-color:' . $blogs_directory_background_color . '; border-bottom-style:solid; border-bottom-color:' . $blogs_directory_border_color . '; border-bottom-width:1px; font-size:12px;" width="90%"><center><strong>' .  __('Blogs') . '</strong></center></td>';
				$content .= '</tr>';
				//=================================//
				$avatar_default = get_option('avatar_default');
				$tic_toc = 'toc';
				//=================================//
				if ( !empty( $blogs ) ) {
					foreach ($blogs as $blog){
						//=============================//
						$blog_title = get_blog_option( $blog['blog_id'], 'blogname', $blog['domain'] . $blog['path'] );
						if ($tic_toc == 'toc'){
							$tic_toc = 'tic';
						} else {
							$tic_toc = 'toc';
						}
						if ($tic_toc == 'tic'){
							$bg_color = $blogs_directory_alternate_background_color;
						} else {
							$bg_color = $blogs_directory_background_color;
						}
						//=============================//
						$content .= '<tr>';
							if ( function_exists('get_blog_avatar') ) {
								$content .= '<td style="background-color:' . $bg_color . '; padding-top:10px;" valign="top" width="10%"><center><a style="text-decoration:none;" href="http://' . $blog['domain'] . $blog['path'] . '">' . get_blog_avatar($blog['blog_id'], 32, $avatar_default) . '</a></center></td>';
							} else {
								$content .= '<td style="background-color:' . $bg_color . '; padding-top:10px;" valign="top" width="10%"></td>';
							}
							$content .= '<td style="background-color:' . $bg_color . ';" width="90%">';
							$content .= '<a style="text-decoration:none; font-size:1.5em; margin-left:20px;" href="http://' . $blog['domain'] . $blog['path'] . '">' . $blog_title . '</a><br />';
							$content .= '</td>';
						$content .= '</tr>';
					}
				} else {
					$content .= '<tr>';
						$content .= '<td style="background-color:' . $bg_color . '; padding-top:10px;" valign="top" width="10%"></td>';
						$content .= '<td style="background-color:' . $bg_color . ';" width="90%">' . __('No results...') . '</td>';
					$content .= '</tr>';
				}
				//=================================//
			$content .= '</table>';
			$content .= '</div>';
			if ( !empty( $blogs ) ) {
				$content .= $navigation_content;
			}
		} else {
			$content = __('Invalid page.');
		}
	}
	return $content;
}

function blogs_directory_search_form_output($content, $phrase) {
	global $wpdb, $current_site, $blogs_directory_base;
	if ( !empty( $phrase ) ) {
		$content .= '<form action="' . $current_site->path . $blogs_directory_base . '/search/' . urlencode( $phrase ) . '/" method="post">';
	} else {
		$content .= '<form action="' . $current_site->path . $blogs_directory_base . '/search/" method="post">';
	}
		$content .= '<table border="0" border="0" cellpadding="2px" cellspacing="2px" width="100%" bgcolor="">';
		$content .= '<tr>';
		    $content .= '<td style="font-size:12px; text-align:left;" width="80%">';
				$content .= '<input name="phrase" style="width: 100%;" type="text" value="' . $phrase . '">';
			$content .= '</td>';
			$content .= '<td style="font-size:12px; text-align:right;" width="20%">';
				$content .= '<input name="Submit" value="' . __('Search') . '" type="submit">';
			$content .= '</td>';
		$content .= '</tr>';
		$content .= '</table>';
	$content .= '</form>';
	return $content;
}

function blogs_directory_search_navigation_output($content, $per_page, $page, $phrase, $next){
	global $wpdb, $current_site, $blogs_directory_base;
	if (VHOST == 'yes') {
		$blog_count = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->base_prefix . "blogs WHERE ( domain LIKE '%" . $phrase . "%' ) AND spam != 1 AND deleted != 1 AND blog_id != 1");
	} else {
		$blog_count = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->base_prefix . "blogs WHERE ( path LIKE '%" . $phrase . "%' ) AND spam != 1 AND deleted != 1 AND blog_id != 1");
	}
	$blog_count = $blog_count - 1;

	//generate page div
	//============================================================================//
	$total_pages = blogs_directory_roundup($blog_count / $per_page, 0);
	$content .= '<table border="0" border="0" cellpadding="2px" cellspacing="2px" width="100%" bgcolor="">';
	$content .= '<tr>';
	$showing_low = ($page * $per_page) - ($per_page - 1);
	if ($total_pages == $page){
		//last page...
		//$showing_high = $blog_count - (($total_pages - 1) * $per_page);
		$showing_high = $blog_count;
	} else {
		$showing_high = $page * $per_page;
	}

    $content .= '<td style="font-size:12px; text-align:left;" width="50%">';
	if ($blog_count > $per_page){
	//============================================================================//
		if ($page == '' || $page == '1'){
			//$content .= __('Previous');
		} else {
		$previous_page = $page - 1;
		$content .= '<a style="text-decoration:none;" href="http://' . $current_site->domain . $current_site->path . $blogs_directory_base . '/search/' . urlencode( $phrase ) . '/' . $previous_page . '/">&laquo; ' . __('Previous') . '</a>';
		}
	//============================================================================//
	}
	$content .= '</td>';
    $content .= '<td style="font-size:12px; text-align:right;" width="50%">';
	if ($blog_count > $per_page){
	//============================================================================//
		if ( $next != 'no' ) {
			if ($page == $total_pages){
				//$content .= __('Next');
			} else {
				if ($total_pages == 1){
					//$content .= __('Next');
				} else {
					$next_page = $page + 1;
				$content .= '<a style="text-decoration:none;" href="http://' . $current_site->domain . $current_site->path . $blogs_directory_base . '/search/' . urlencode( $phrase ) . '/' . $next_page . '/">' . __('Next') . ' &raquo;</a>';
				}
			}
		}
	//============================================================================//
	}
    $content .= '</td>';
	$content .= '</tr>';
    $content .= '</table>';
	return $content;
}

function blogs_directory_landing_navigation_output($content, $per_page, $page){
	global $wpdb, $current_site, $blogs_directory_base;
	$blog_count = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->base_prefix . "blogs WHERE spam != 1 AND deleted != 1 AND blog_id != 1");

	//generate page div
	//============================================================================//
	$total_pages = blogs_directory_roundup($blog_count / $per_page, 0);
	$content .= '<table border="0" border="0" cellpadding="2px" cellspacing="2px" width="100%" bgcolor="">';
	$content .= '<tr>';
	$showing_low = ($page * $per_page) - ($per_page - 1);
	if ($total_pages == $page){
		//last page...
		//$showing_high = $blog_count - (($total_pages - 1) * $per_page);
		$showing_high = $blog_count;
	} else {
		$showing_high = $page * $per_page;
	}

    $content .= '<td style="font-size:12px; text-align:left;" width="50%">';
	if ($blog_count > $per_page){
	//============================================================================//
		if ($page == '' || $page == '1'){
			//$content .= __('Previous');
		} else {
		$previous_page = $page - 1;
		$content .= '<a style="text-decoration:none;" href="http://' . $current_site->domain . $current_site->path . $blogs_directory_base . '/' . $previous_page . '/">&laquo; ' . __('Previous') . '</a>';
		}
	//============================================================================//
	}
	$content .= '</td>';
    $content .= '<td style="font-size:12px; text-align:right;" width="50%">';
	if ($blog_count > $per_page){
	//============================================================================//
		if ($page == $total_pages){
			//$content .= __('Next');
		} else {
			if ($total_pages == 1){
				//$content .= __('Next');
			} else {
				$next_page = $page + 1;
			$content .= '<a style="text-decoration:none;" href="http://' . $current_site->domain . $current_site->path . $blogs_directory_base . '/' . $next_page . '/">' . __('Next') . ' &raquo;</a>';
			}
		}
	//============================================================================//
	}
    $content .= '</td>';
	$content .= '</tr>';
    $content .= '</table>';
	return $content;
}

//------------------------------------------------------------------------//
//---Page Output Functions------------------------------------------------//
//------------------------------------------------------------------------//

//------------------------------------------------------------------------//
//---Support Functions----------------------------------------------------//
//------------------------------------------------------------------------//

function blogs_directory_roundup($value, $dp){
    return ceil($value*pow(10, $dp))/pow(10, $dp);
}

?>
