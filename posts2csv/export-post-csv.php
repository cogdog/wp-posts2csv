<?php
/*
Plugin Name: Export Post Data as CSV
Plugin URI: https://github.com/cogdog/wp-posts2csv
Description: Provides CSV download of basic post data, filtered by category, including identification of Feed Wordpress syndicated posts, character, word,  link count, and list of links
Version: 0.1
License: GPLv2
Author: Alan Levine
Author URI: http://cogdog.info
*/

defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );

if ( ! class_exists( 'Posts2csv' ) ) {
	class Posts2csv {
	
		public function __construct()
		{
			// add admin menu item
			add_action( 'admin_menu',  array( &$this, 'export_post_data_add_page' ) );
			
			// handle form action
			add_action( 'admin_post_export_post_do_export',  array( &$this, 'export_post_generate_csv' ) );
		}
		
		
		public function export_post_data_add_page() {
			// add to a new menu item Dashboard under Tools
			add_submenu_page( 'tools.php',  'Export Posts Data To CSV', 'Post CSV Export', 'manage_options', 'posts2csv', array( &$this, 'export_post_data_form' ) );
		}
		
		public function export_post_data_form() {
			// displays form for the tool
		
		?>
			<div class="wrap">
			<form method="post" id="export-post-data" action="admin-post.php" target="_blank">
			<input type="hidden" name="action" value="export_post_do_export" />
			
			<?php wp_nonce_field( 'posts2csv' ); ?>
			
				<h2>Export Post Data Options</h2>
				<?php
				 if ( isset( $_POST['cat'] )) {
				?>
   				<div id='message' class='updated fade'><p><strong>Posts successfully exported as CSV!</strong></p></div>
				<?php
  				}
				?>

				<p>Generate a CSV download data for all posts, or just within a category, including title, author, date, source (local or syndicated if Feed Wordpress is used), character, word, and link count, and all URLs for links including in the post.</p>
	
				<p><label>Choose Category:<br />
				<?php wp_dropdown_categories( 'show_option_all=All%20Posts&show_count=1&hierarchical=1' )?>
				</label></p>

	
				<p class="submit">
				<input type="submit" name="submit" class="button-primary" value="Export Blog Data" />
				</p>
			</form>
			</div>
			<?php
		} 


		public function getUrls( $string ) {
			// find all URLs inside href tags
			// There are only 2 billions diferent regex strings out there. 
			preg_match_all('/<a href="(.*?)"/s', $string, $matches);

			// return array of matches
			return ( $matches[1] );

		}

		public function outputCSV( $data ) {
			// write to csv
			// --- h/t http://stackoverflow.com/a/6493794/2418186
			$output = fopen("php://output", "w");
	
			//add BOM to fix UTF-8 in Excel so it openes w/o complaints
			// --- h/t http://www.skoumal.net/cs/node/24
			fputs($output, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) )); 

			foreach ($data as $row) {
				fputcsv($output, $row); 
			}
	
			fclose($output);
		}

		
		public function export_post_generate_csv() {
			// do the stuff to generate the CSV data
		
			// get off of my lawn!
			if ( !current_user_can( 'manage_options' ) )

			{
			  wp_die( 'You are not allowed to be on this page.' );
			}
			
			// Check el nonce 
			check_admin_referer( 'posts2csv' );

			// arguments for get posts call
			$args = array(
				'posts_per_page'   => -1,
				'orderby'          => 'date',
				'order'            => 'DESC',
				'post_type'        => 'post',
				'post_status'      => 'publish',
			);


			// category id comes from form (name=cat)
			$cat_id = $_POST['cat'];

			if ( $cat_id > 0 ) {
				// filter by a category

				// add parameter to argument array
				$args['category'] = $cat_id;

				// create apporpriate file name
				$file_name = sanitize_title( get_bloginfo( 'name') . ' blog data ' . get_the_category_by_ID( $cat_id )  . ' category');

				// for feedback
				$pretty_title = get_bloginfo( 'name') . ' blog data for ' . get_the_category_by_ID( $cat_id )  . ' category';

			} else {
				// grab all dem posts, all categories

				// create apporpriate file name
				$file_name = sanitize_title( get_bloginfo( 'name' ) . ' blog data all posts' );

				// just in case   for feedback
				$pretty_title = get_bloginfo( 'name' ) . ' blog data for all posts';	
			}

			// get them posts!
			$got_posts = get_posts( $args ); 

			// danger will robinson
			if ( count ($got_posts) == 0 ) die ('Uh oh, no posts found for ' . $pretty_title);

			// make labels for headers (first row)
			$headers = ['ID' , 'Source',  'Post Title', 'URL',  'Publish Date', 'Author Name', 'Author User Name', 'Blog Name', 'Character Count', 'Word Count', 'Link Count', 'Links'];

			// add headers
			$blog_data[] = $headers;

			foreach ( $got_posts as $post ) {
				// make like The Loop
				setup_postdata( $post );

				// default meaning it comes from this blog
				$blog_source = 'local';

				// get link, remote if syndicated via Feed WordPress, otherwise use permalink
				$permalink = get_post_meta( $post->ID, 'syndication_permalink', true ) ;

				if ( $permalink ) {
					// switch flag if we are syndicated stuff
					$blog_source = 'syndicated';
				} else {
					//local post use permalink
					$permalink =  get_permalink($post->ID);
				}

				// get source site, if syndicated
				$blog_name = get_post_meta( $post->ID, 'syndication_source', true ) ;

				if ( $blog_name ) {
					// switch flag if we are syndicated stuff (maybe we slipped through above?)
					$blog_source = 'syndicated';
				} else {
					//local post user blog name
					$blog_name =  get_bloginfo( 'name' );
				}

				// data for each row
				$row = [
					strval($post->ID),
					$blog_source,
					get_the_title($post->ID),
					$permalink,
					get_the_time('M d, Y h:i:sa', $post->ID ),
					get_the_author_meta( 'first_name' ) . ' ' . get_the_author_meta( 'last_name' ),
					get_the_author_meta( 'user_login' ),
					$blog_name,
					strval( strlen( strip_tags( get_the_content() ) ) ),
					strval( str_word_count( strip_tags( get_the_content() ) ) ),
					strval( substr_count( get_the_content(), "</a>") ),
					implode( ",", $this->getUrls( get_the_content() ) )
					];
				$blog_data[] = $row;
			}

			// header this
			header("Content-Type: text/csv");
			header("Content-Disposition: attachment; filename=$file_name.csv");
			// Disable caching
			header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
			header("Pragma: no-cache"); // HTTP 1.0
			header("Expires: 0"); // Proxies

			// here comes the data
			$this->outputCSV( $blog_data );			
		}
		
		

	}
	
	new Posts2csv;
 }








?>