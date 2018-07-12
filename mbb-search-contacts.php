<?php


/**
*  Plugin Name: Business Blueprint Search Contacts
*  Plugin URI: businessblueprint.com.au
*  Description: Search Useful Contacts
*
*  Version: 1.0.0
*  Author: Business Blueprint
*  Text Domain: useful-contacts
*/
if( ! defined('ABSPATH') ) {
	exit();
}


define('SUC_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

class SearchUsefulContacts {


   public function run() {

        add_action('wp_enqueue_scripts', array( $this, 'register_scripts' ) );
        add_action( 'init', array( $this, 'register_post_type' ) );
        add_action( 'init', array( $this, 'register_taxonomy' ) );
        add_action( 'wp_ajax_query_contact', array( $this, 'query_contact' ));
        add_action( 'wp_ajax_nopriv_query_contact', array( $this, 'query_contact' ));
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes'));
        add_action( 'save_post', array( $this, 'save_contacts_meta'), 10, 2 );
        add_shortcode( 'suc_form', array($this, 'shortcode_form') );

        // Add contact on Frontend
        add_action( 'wp_ajax_suc_add_contact', array( $this, 'suc_add_contact' ));
        add_action( 'wp_ajax_nopriv_suc_add_contact', array( $this, 'suc_add_contact' ));

        // Approve Contact
        add_action( 'wp_ajax_approve_contact', array( $this, 'approve_contact' ));
        add_action( 'wp_ajax_nopriv_approve_contact', array( $this, 'approve_contact' ));

   }

   public function load_dependencies() {

      require_once SUC_PLUGIN_PATH . 'public/class-useful-contacts-public.php';
   }

   /**
    * Registers the event post type.
    */
   function register_post_type() {

      $labels2 = array(
         'name'               => __( 'Contact Type' ),
         'singular_name'      => __( 'Contact Type' ),
         'add_new'            => __( 'Add New Type' ),
         'add_new_item'       => __( 'Add New Type' ),
         'edit_item'          => __( 'Edit Contact Type' ),
         'new_item'           => __( 'Add New Contact Type' ),
         'view_item'          => __( 'View Contact Types' ),
         'search_items'       => __( 'Search Contact Type' ),
         'not_found'          => __( 'No Contact Type found' ),
         'not_found_in_trash' => __( 'No Contact Type found in trash' )
      );
      $supports2 = array(
         'title',
         'editor',
         'thumbnail',
         'revisions',
      );
      $args2 = array(
         'labels'               => $labels2,
         'supports'             => $supports2,
         'public'               => true,
         'capability_type'      => 'post',
         'rewrite'              => array( 'slug' => 'mbb-contact-type' ),
         'has_archive'          => true,
         'menu_position'        => 30,
         'menu_icon'            => 'dashicons-book-alt'
   
      );
      

      $labels = array(
         'name'               => __( 'Contact List' ),
         'singular_name'      => __( 'Contact' ),
         'add_new'            => __( 'Add New Contact' ),
         'add_new_item'       => __( 'Add New Contact' ),
         'edit_item'          => __( 'Edit Useful Contact' ),
         'new_item'           => __( 'Add New Useful Contact' ),
         'view_item'          => __( 'View Useful Contacts' ),
         'search_items'       => __( 'Search Useful Contact' ),
         'not_found'          => __( 'No contact found' ),
         'not_found_in_trash' => __( 'No contact found in trash' )
      );
      $supports = array(
         'title',
         'editor',
         'thumbnail',
         'revisions',
      );
      $args = array(
         'labels'               => $labels,
         'supports'             => $supports,
         'public'               => true,
         'capability_type'      => 'post',
         'rewrite'              => array( 'slug' => 'useful_contacts' ),
         'has_archive'          => true,
         'menu_position'        => 30,
         'menu_icon'            => 'dashicons-book-alt',
         'register_meta_box_cb' => array($this,'add_meta_boxes'),
         'supports'             => array( 'title' ),
         'taxonomies'           => array( 'category','useful_contacts_cat'),
      );
      register_post_type( 'useful_contact_types', $args2 );
      register_post_type( 'useful_contacts', $args );

   }


   public function add_meta_boxes() {

      add_meta_box( 
         'metabox_display',
         __('Contact\'s Info','useful-contacts'),
         array( $this, 'metabox_display'),
         'useful_contacts',
         'normal',
         'default'
      );

   }


  public function register_taxonomy() {  
      register_taxonomy(  
          'useful_contacts_cat', 
          'useful_contacts',       
          array(  
              'hierarchical' => false,  
              'label' => 'Contact Type', 
              'public' => false,  
              'query_var' => false,
              'rewrite' => array(
                  'slug' => 'contact_categories', 
                  'with_front' => true 
              )
          )  
      ); 
      register_taxonomy_for_object_type( 'category', 'useful_contacts' ); 
  }  

   public function metabox_display() {

      global $post;

      wp_nonce_field( basename( __FILE__ ),'contacts_nonce');

      
      $company_name = get_post_meta( $post->ID, 'company_name', true );
      $description  = get_post_meta( $post->ID, 'description', true );
      $name         = get_post_meta( $post->ID, 'name', true );
      $phone        = get_post_meta( $post->ID, 'phone', true );
      $email        = get_post_meta( $post->ID, 'email', true );
      $website      = get_post_meta( $post->ID, 'website', true );


      require_once SUC_PLUGIN_PATH . '/admin/inc/class-post-metadata.php';
   }


   /**
    * Save the metabox data
    */
   function save_contacts_meta( $post_id, $post ) {
  
      if ( ! current_user_can( 'edit_post', $post_id ) ) {
         return $post_id;
      }

      if ( ! isset( $_POST['name'] ) || ! wp_verify_nonce( $_POST['contacts_nonce'], basename(__FILE__) ) ) {
         return $post_id;
      }

      $contacts_meta['company_name'] = sanitize_text_field( $_POST['company_name'] );
      $contacts_meta['description']  = sanitize_text_field( $_POST['description'] );
      $contacts_meta['name']         = sanitize_text_field( $_POST['name'] );
      $contacts_meta['phone']        = sanitize_text_field( $_POST['phone'] );
      $contacts_meta['email']        = sanitize_text_field( $_POST['email'] );
      $contacts_meta['website']      = sanitize_text_field( $_POST['website'] );


      $this->post_meta_value( $contacts_meta, $post_id );
   }


   //Frontend ============================
    


   /**
   * Display form using shortcode [suc_form]
   *
   * @return HTML form
   */
   public function shortcode_form() {

      ob_start();

      $message = $this->get_message();

      require_once SUC_PLUGIN_PATH . '/templates/template-form.php';

      $content = ob_get_clean();

      return $content;

   }


   public function suc_add_contact() {
            
           // Avoid CSRF
           if( ! check_ajax_referer('suc_security_action', 'suc_security_field') )
           {
               wp_send_json_error( array('error' => 'security') ); 
           }

           if( ! current_user_can('subscriber') && ! current_user_can('administrator') ) 
           {
                  wp_send_json_error( array('error' => 'unathorized user') ); 
           }

           //get user id
           $user_id = get_current_user_id();

           //Clean all fields before posting
           $firstname          = sanitize_text_field( $_POST['firstname'] );
           $lastname           = sanitize_text_field( $_POST['lastname'] );
           $email              = sanitize_text_field( $_POST['email'] );
           $description        = sanitize_text_field( $_POST['description'] ); 
           $company            = wp_strip_all_tags( $_POST['company_name'] ); 
           $website            = sanitize_text_field( $_POST['website'] ); 
           $phone              = sanitize_text_field( $_POST['phone'] ); 
           $category           = sanitize_text_field( $_POST['post_category'] ); 
           $person_recommended = sanitize_text_field( $_POST['person_recommended'] ); 
           $fullname           = $firstname . ' ' . $lastname;
                    

           if( $this->is_authorized_user() == true ) {

               $status = 'publish';

           } else {

              $status = 'draft';

           }

           $post_array = array(
                           'post_title'   => $company,
                           'post_content' => '',
                           'post_status'  => $status,
                           'post_type'    => 'useful_contacts',
                           'post_author'  => $user_id
                        );
            
         //insert post          
          $post =  wp_insert_post( $post_array );

         wp_set_post_terms($post, (array)$category, 'category', true); 

         //set meta value 
         $contacts_meta['company_name'] = $company;
         $contacts_meta['description'] = $description;
         $contacts_meta['name'] = $fullname;
         $contacts_meta['phone'] = $phone;
         $contacts_meta['email'] = $email;
         $contacts_meta['website'] = $website;
         $contacts_meta['person_recommended'] = $person_recommended;

         //post meta value
         $this->post_meta_value( $contacts_meta, $post );

         //set terms of the post
         $terms = wp_set_post_terms( $post, array( $category ) );

          if( $terms ) {

             if( wp_get_referer() ) 
             {
                  //send email to admin 
                  $this->send_email( $person_recommended, $contacts_meta );

                  $message_code = 1;
                  wp_safe_redirect( wp_get_referer() . '?msg=' . $message_code );

             }
             else 
             {
               wp_redirect( home_url() );
             }
          } 
          else 
          {
            $message_code = 2;
            wp_safe_redirect( wp_get_referer() . '?msg=' . $message_code );
          }

          die();  

   }

   public function send_email($person, $data = array() ) {

      $url = site_url() . '/contact-to-be-added';

      $to = 'july@businessblueprint.com';
      $subject = 'New contact is added by ' . $person;

      ob_start();

        $GLOBALS["use_html_content_type"] = TRUE;

        include( 'templates/email-for-approval.php' );

        $message = ob_get_contents();

      ob_end_clean();

      $headers = array('Content-Type: text/html; charset=UTF-8');
     
      wp_mail( $to, $subject, $message, $headers );
   }


   // Handles Ajax Action Hook
   public function query_contact() {

   		$search = sanitize_text_field( $_POST['search'] );	

   		$args = array(

   					'post_type' => 'useful_contacts',
   					'post_status' => 'publish',
   					's' => $search,
   					'posts_per_page' => 1 	
   		);

          
   		$query =  new WP_Query( $args ); 

   		if( $query->have_posts() ) :

   			while($query->have_posts()) : $query->the_post();
   		?>

		        <article class="search-result">
			          <div class="icon"></div><div class="search-result__contact">
			            <div class="search-result__details">
			                <h1 class="search-result__title">
			                     <a href="<?php echo get_the_permalink(); ?>" target="_blank"><?php the_title(); ?></a></h1>
			               <?php the_excerpt(); ?>     
			            <footer class="search-result__footer">
			              <div class="grid">
			                 <div class="grid__column  grid__column--12">
			                   <a href="<?php echo get_the_permalink(); ?>" class="search-result__more" target="_blank">View Contacts</a>
			                </div>
			              </div>
			            </footer>
			          </div>
			        </div>
		        </article>
	   		<?php
   			endwhile;

   		endif;

   		wp_reset_postdata();

   		die();

   }

   public function approve_contact() {

        $id = (int)$_POST['id'];
        
   
        if( ! wp_verify_nonce( $_POST['security'], 'suc_security' ) ) 
            wp_send_json_error(array('status' => 'error'));
        
        if( ! isset( $id ) ) 
          wp_send_json_error(array('status' => 'ID is not found!'));
        
        if( ! current_user_can('subscriber') &&  ! current_user_can('administrator') && $this->is_authorized_user() )
             wp_send_json_error(array('status' => 'You need to login before you can approve'));


        $post = array(
            'ID' => $id,
            'post_status' => 'publish'
        );   

        $is_posted = wp_update_post( $post );   

        if( $is_posted ) 
        {

            wp_send_json_success(array('status' => true, 'posted' => 1));
        }
        else
        {
            wp_send_json_error(array('status' => 'Error submitting post'));
        }
        
        die();
   }


   //Register scripts
   public function register_scripts() {
         wp_enqueue_script('useful-contacts', plugins_url('js/result.js', __FILE__), array(), null, true  );

         wp_localize_script( 
               'useful-contacts', 
               'searchusefulcontacts_ajax', 
               array( 
                  'ajax_url' => admin_url('admin-ajax.php'),
                  'security' => wp_nonce_field('suc_security', 'suc_security_field')

               ) );

   }


   // Helpers

   public function get_category( ) {

         $post_categories =  get_terms( array('taxonomy' => 'category') );

         return $post_categories;
   }

   /**
   * Show message
   *
   *  @return STRING
   */
   public function get_message() {
          $messages = array(
             '1' => 'Contact has been successfully submitted!',
             '2' => 'Something went wrong! Please contact support'
         );

          return $messages;
   }

   /**
   * Check if user is authorize to post
   *
   *  @return boolean
   */
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

   /**
   * Upload Post meta 
   */
   public function post_meta_value($contacts_meta = '', $post_id = '') {

      if(!$contacts_meta)
            return "";

      if(!$post_id)
            return "";   

      foreach ( $contacts_meta as $key => $value ) :

         if ( 'revision' === $post->post_type ) {
            return;
         }
         if ( get_post_meta( $post_id, $key, false ) ) {
           
            update_post_meta( $post_id, $key, $value );
         } else {
     
            add_post_meta( $post_id, $key, $value);
         }
         if ( ! $value ) {
          
            delete_post_meta( $post_id, $key );
         }
      endforeach;
   }

}


function run_suc() {
	$search = new SearchUsefulContacts();
	$search->run();
  $search->load_dependencies();

  $contact = new UsefulContactsPublic();
}
run_suc();
