<?php



class UsefulContactsPublic
{

    /**
    * Display form using shortcode [suc_form]
    *
    * @return Cartegoies
    */
    public function shortcode_contact_category( $atts ) {


	      

  	      $atts = shortcode_atts(
	            array(
	                  'category' => ''
	              ), 
	            $atts, 
	            'suc_category');

	      $category = $atts['category'];
		

		ob_start();

	    $posts = $this->get_publish_contacts( $category );

	    require_once SUC_PLUGIN_PATH . '/templates/template-contacts-category.php';

		$output_string = ob_get_contents();
		if (ob_get_contents()) ob_end_clean();
		return $output_string;

    }





	 /**
	 * Callback function for shortcode to display
	 * for approval contacts
	 */ 
	 public function show_pending_contacts()
	 {

	 	ob_start();

        require_once SUC_PLUGIN_PATH . 'templates/template-for-approval.php';

		$output_string = ob_get_contents();
		if (ob_get_contents()) ob_end_clean();
		return $output_string;

	 }


	 /**
	 * Get Pending Contacts
	 * 
	 * @return array loop
	 */ 
	 public function get_pending_contacts() {

	 	$args = array(
	 				'posts_per_page' => -1,
	 				'orderby' => 'date',
	 				'order' => 'DESC',
	 				'post_type' => 'useful_contacts',
	 				'post_status' => 'draft'
	 		);	


	 	$post_array = get_posts( $args );

	 	return $post_array;

	 }


	 /**
	 * Get Pending Contacts
	 * 
	 * @return array loop
	 */ 
	 public function get_publish_contacts($category = '') {

	 	if( !$category )
	 		  return false;

	 	$args = array(
	 				'posts_per_page' => -1,
	 				'orderby' => 'date',
	 				'order' => 'DESC',
	 				'post_type' => 'useful_contacts',
	 				'post_status' => 'publish',
	 				'category_name' => $category,
	 	);	

	 	$post_array = get_posts( $args );

	 	return $post_array;

	 }

	 /**
	 * Get Categories by Id
	 * 
	 * @return array categories
	 *
	 */
	 public function get_post_categories($id = '') {

      if(!$id)
          return false;

      $categories = wp_get_post_terms( $id, 'category' );

      foreach($categories as $category) {
          $cat = get_category( $category );
      }

      if( isset($cat) ) {
    	  return $cat;
  	  }

  	  return false;

    }

    public function is_authorized_user() {

      $users = array();


      $users = [
          'july@businessblueprint.com', 
          'josh@businessblueprint.com', 
          'emma@businessblueprint.com', 
          'dale@businessblueprint.com'];

      $current_user =  get_user_by('id',  get_current_user_id());

      $current_user->user_email;


      if( in_array( $current_user->user_email, $users) )   {
      
         return true;
      
      } else {

        return false;

      } 

   }
   
}