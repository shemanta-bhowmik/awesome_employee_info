<?php 
/*
Plugin Name: Standard Employee List
Plugin URI: http://batch24.xyz/demo/plugins
Author: Sujan
Author URI: http://mtmsujan.com 
Version: 1.0 
Description: Standard and Easy to Use Employee List Plugin
*/

class Employee {
	public function __construct(){
		add_action('init', array($this, 'employee_default_init'));
		add_action('add_meta_boxes', array($this, 'employee_metabox_callback'));
		add_action('save_post', array($this, 'employee_metabox_save'));
		add_action('admin_enqueue_scripts', array($this, 'jquery_ui_tabs') );
		add_action('wp_enqueue_scripts', array($this, 'custom_styles_func') );
		add_shortcode( 'dynamic-employee-search', [ $this, 'dynamic_employee_search_func' ] );
		add_shortcode( 'employee-list-sc', [ $this, 'employee_list_sc_func' ] );
	}

	public function jquery_ui_tabs(){
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'employee_script', PLUGINS_URL('js/custom.js', __FILE__), array('jquery', 'jquery-ui-tabs') );
		wp_enqueue_style( 'employee-custom', PLUGINS_URL('css/custom.css', __FILE__ ));
	}

	public function custom_styles_func() {
		wp_enqueue_style('employee-custom', PLUGINS_URL('css/custom.css', __FILE__));
	}

	public function employee_default_init(){
		$labels = array(
			'name'               => _x( 'Employee', 'Employee Admin Menu Name', 'your-plugin-textdomain' ),
			'singular_name'      => _x( 'Employee', 'Employee Admin Menu singular name', 'your-plugin-textdomain' ),
			'menu_name'          => _x( 'Employee', 'admin menu', 'your-plugin-textdomain' ),
			'name_admin_bar'     => _x( 'Employee', 'add new on admin bar', 'your-plugin-textdomain' ),
			'add_new'            => _x( 'Add New', 'Employee', 'your-plugin-textdomain' ),
			'add_new_item'       => __( 'Add New Employee', 'your-plugin-textdomain' ),
			'new_item'           => __( 'New Employee', 'your-plugin-textdomain' ),
			'edit_item'          => __( 'Edit Employee', 'your-plugin-textdomain' ),
			'view_item'          => __( 'View Employee', 'your-plugin-textdomain' ),
			'all_items'          => __( 'All Employee', 'your-plugin-textdomain' ),
			'search_items'       => __( 'Search Employee', 'your-plugin-textdomain' ),
			'parent_item_colon'  => __( 'Parent Employee:', 'your-plugin-textdomain' ),
			'not_found'          => __( 'No Employee found.', 'your-plugin-textdomain' ),
			'not_found_in_trash' => __( 'No Employee found in Trash.', 'your-plugin-textdomain' )
		);

		$args = array(
			'labels'             => $labels,
	                'description'        => __( 'Employee list.', 'your-plugin-textdomain' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'employee' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon' 		 => 'dashicons-groups',
			'supports'           => array( 'title', 'editor', 'thumbnail' )
		);

		register_post_type( 'employee_list', $args );

		// employee types 

		$labels = array(
			'name'              => _x( 'Employee Types', 'taxonomy general name' ),
			'singular_name'     => _x( 'Employee Type', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Employee Types' ),
			'all_items'         => __( 'All Employee Types' ),
			'parent_item'       => __( 'Parent Employee Type' ),
			'parent_item_colon' => __( 'Parent Employee Type:' ),
			'edit_item'         => __( 'Edit Employee Type' ),
			'update_item'       => __( 'Update Employee Type' ),
			'add_new_item'      => __( 'Add New Employee Type' ),
			'new_item_name'     => __( 'New Employee Type Name' ),
			'menu_name'         => __( 'Employee Type' ),
		);

		$args = array(
			'hirearchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'type' ),
		);

		register_taxonomy( 'employee_type', array( 'employee_list' ), $args );


	}

	public function employee_metabox_callback(){
		// metabox for employees 
		
		add_meta_box('employee-info', 'Employee Information', array($this, 'employee_information'), 'employee_list', 'normal', 'high');
	}

	public function employee_information(){

		// personal info getting
		$father_val = get_post_meta( get_the_id(), 'ei_father', true );
		$mother_val = get_post_meta( get_the_id(), 'ei_mother', true );
		$gender_val = get_post_meta( get_the_id(), 'ei_gender', true );

		// official info getting
		$designation_val = get_post_meta( get_the_id(), 'ei_designation', true );
		
		// academic info getting
		$sscyear_val = get_post_meta( get_the_id(), 'ei_sscyear', true );
		$hscyear_val = get_post_meta( get_the_id(), 'ei_hscyear', true );
		$bscyear_val = get_post_meta( get_the_id(), 'ei_bscyear', true );

		// experience info getting
		$skills_val = get_post_meta( get_the_id(), 'ei_skills', true );

		?>

		<div id="tabs">
		  <ul>
		    <li><a href="#personal">Personal Information</a></li>
		    <li><a href="#official">Official Information</a></li>
		    <li><a href="#academic">Academic Information</a></li>
		    <li><a href="#experience">Experience</a></li>
		  </ul>

		 <!-- Personal Informations -->
		  <div id="personal">
		    <p><label for="father">Father's Name</label></p>
		    <p><input type="text" class="widefat" name="father" value="<?php echo $father_val; ?>" id="father"></p>
		    <p><label for="mother">Mother's Name</label></p>
		    <p><input type="text" class="widefat" name="mother" value="<?php echo $mother_val; ?>" id="mother"></p>

		    <p>
		    	<input type="radio" name="gender" value="male" <?php if ( $gender_val == 'male' ) { echo 'checked="checked"'; } ?> id="male">
		    	<label for="male"> Male</label> <br>
		    	<input type="radio" name="gender" value="female" <?php if ( $gender_val == 'female' ) { echo 'checked="checked"'; } ?> id="female">
		    	<label for="female">Female</label>
		    </p>
		  </div>

		  <!-- Official Information -->
		  <div id="official">
		    <p><label for="designation">Designation</label></p>
		    <p><input type="text" class="widefat" name="designation" value="<?php echo $designation_val; ?>" id="designation"></p>
		  </div>

		  <!-- Academic Informations -->
		  <div id="academic">
		    <p><label for="sscyear">SSC Year</label></p>
		    <p><input type="number" class="widefat" name="sscyear" value="<?php echo $sscyear_val; ?>" id="sscyear"></p>
			<p><label for="hscyear">HSC Year</label></p>
		    <p><input type="number" class="widefat" name="hscyear" value="<?php echo $hscyear_val; ?>" id="sscyear"></p>
			<p><label for="bscyear">BSC Year</label></p>
		    <p><input type="number" class="widefat" name="bscyear" value="<?php echo $bscyear_val; ?>" id="sscyear"></p>
		  </div>

		  <!-- Experience -->
		  <div id="experience">
		    <p><label for="skills">Skills:</label></p>
		    <p><input type="text" class="widefat" name="skills" value="<?php echo $skills_val; ?>" id="skills"></p>
		  </div>

		</div>
		<?php 
	}

	public function employee_metabox_save(){

		// personal info
		$father 	 = isset( $_REQUEST['father'] ) ? $_REQUEST['father'] : ' ';
		$mother 	 = isset( $_REQUEST['mother'] ) ? $_REQUEST['mother'] : ' ';
		$gender 	 = isset( $_REQUEST['gender'] ) ? $_REQUEST['gender'] : ' ';
		// official info
		$designation = isset( $_REQUEST['designation'] ) ? $_REQUEST['designation'] : ' ';
		// academic info
		$sscyear 	 = isset( $_REQUEST['sscyear'] ) ? $_REQUEST['sscyear'] : ' ';
		$hscyear 	 = isset( $_REQUEST['hscyear'] ) ? $_REQUEST['hscyear'] : ' ';
		$bscyear 	 = isset( $_REQUEST['bscyear'] ) ? $_REQUEST['bscyear'] : ' ';
		// experience info
		$skills 	 = isset( $_REQUEST['skills'] ) ? $_REQUEST['skills'] : ' ';

		// personal data send
		update_post_meta( get_the_id(), 'ei_father', $father );
		update_post_meta( get_the_id(), 'ei_mother', $mother );
		update_post_meta( get_the_id(), 'ei_gender', $gender );

		// official data send
		update_post_meta( get_the_id(), 'ei_designation', $designation );

		// academic data send
		update_post_meta( get_the_id(), 'ei_sscyear', $sscyear );
		update_post_meta( get_the_id(), 'ei_hscyear', $hscyear );
		update_post_meta( get_the_id(), 'ei_bscyear', $bscyear );

		// experience data send
		update_post_meta( get_the_id(), 'ei_skills', $skills );
	
	}


	/**
	 * Shortcode of Dynamic Employee Search Shortcode
	 * Shortcode: [dynamic-employee-search]
	 */

	public function dynamic_employee_search_func() {

		ob_start();
		
		global $post;
		$id  = $post->ID;
		$url = get_permalink( $id );
		
		?>

		<style>
			input,
			select {
				width: 100%;
				margin-bottom: 10px !important;
			}
		</style>

		<form action="<?php echo $url; ?>" method="GET">
			<input type="hidden" name="search" value="employeelist">
			<input type="text" placeholder="Name" name="employee-name">
			<select name="sscyear">
				<option value="">Select SSC Year</option>
				<?php 
					$num = 2000;
					while( $num < 2020 ) :
					$num++;
				?>
				<option value="<?php echo $num; ?>"><?php echo $num; ?></option>
				<?php
					endwhile;
				?>
			</select>
			<select name="skills">
				<option value="">Select Skills</option>
				<option value="wordpress">WordPress</option>
				<option value="megento">Megento</option>
				<option value="laravel">Laravel</option>
				<option value="rawphp">Raw PHP</option>
				<option value="javascript">Javascript</option>
			</select>
			<input type="submit" value="Search Now">
		</form>

		<?php return ob_get_clean();

	}

	public function employee_temp_change_filter_func() {

		add_filter( 'template_include', [ $this, 'employee_temp_filter_func' ] );

	}

	public function employee_temp_filter_func( $defaults ) {

		if ( isset( $_GET['search'] ) && $_GET['search'] == 'employeelist' ) {
			$defaults = __DIR__ . '/employee.php';
		}

		return $defaults;

	}

	public function employee_list_sc_func( $attr, $content ){
		
		ob_start();

		/**
		 * Getting global ID
		 * Converting post_id into url
		 */
		global $post;
		$id  = $post->ID;
		$url = get_permalink( $id );

		/**
		 * Creating shortcode
		 */
		$atts = shortcode_atts( [
			'count' => -1
		], $attr);

		extract( $atts );
		
		?>

			<style>
				input,
				select {
					width: 100%;
					margin-bottom: 10px !important;
				}
			</style>

			<h3>Filter Employees</h3>
			<form action="<?php echo $url; ?>" method="GET">
				<input type="hidden" name="search" value="employeelist">
				<input type="text" placeholder="Name" name="employee-name">
				<select name="sscyear">
					<option value="">Select SSC Year</option>
					<?php 
						$num = 2000;
						while( $num < 2020 ) :
						$num++;
					?>
					<option value="<?php echo $num; ?>"><?php echo $num; ?></option>
					<?php
						endwhile;
					?>
				</select>
				<select name="skills">
					<option value="">Select Skills</option>
					<option value="wordpress">WordPress</option>
					<option value="megento">Megento</option>
					<option value="laravel">Laravel</option>
					<option value="rawphp">Raw PHP</option>
					<option value="javascript">Javascript</option>
				</select>
				<input type="submit" value="Search Now">
			</form>

			<div class="employee-list">
				<?php 

					if( get_query_var( 'paged' ) ){
						$current_page = get_query_var( 'paged' );
					} else {
						$current_page = 1;
					}

					$employee = new WP_Query( [
						'post_type' => 'employee_list',
						'posts_per_page' => $count,
						'paged' => $current_page
					] );

					while( $employee->have_posts() ) : $employee->the_post();
				?>
				<article class="employee">
					<div class="employee-photo">
						<?php the_post_thumbnail(); ?>
					</div>
					<div class="employee-details">
						<h4>Name: <?php the_title(); ?></h4>
						<p><strong>Designation: </strong><?php echo get_post_meta( get_the_id(), 'ei_designation', true ); ?></p>
						<p><strong>About The Employee: </strong><?php the_content(); ?></p>
						<p><strong>Father's Name: </strong><?php echo get_post_meta( get_the_id(), 'ei_father', true ); ?></p>
						<p><strong>Mother's Name: </strong><?php echo get_post_meta( get_the_id(), 'ei_mother', true ); ?></p>
						<p><strong>Gender: </strong><?php echo get_post_meta( get_the_id(), 'ei_gender', true ); ?></p>
						<p><strong>SSC Year: </strong><?php echo get_post_meta( get_the_id(), 'ei_sscyear', true ); ?></p>
						<p><strong>Skills: </strong><?php echo get_post_meta( get_the_id(), 'ei_skills', true ); ?></p>
					</div>
					<hr>
				</article>

				<?php endwhile; ?>

				<div class="el-paginations">
				<?php 

					echo paginate_links( [
						'current' => $current_page,
						'total' => $employee->max_num_pages,
						'prev_text' => 'Previous Page',
						'next_text' => 'Next Page',
						'show_all' => true
					] );
					
				?>
				</div>
				
			</div>

		<?php return ob_get_clean();
	}

}

$employee = new Employee();
$employee -> employee_temp_change_filter_func();