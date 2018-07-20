<?php 

$result = 'firstname';
if ( is_wp_error( $result ) ) {
    $error_string = $result->get_error_message();
    echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
}

$msg_id = isset($_GET['msg']) ? $_GET['msg'] : 0;

$msg_id = substr($msg_id, 0, 1 );

?>

<div class="mbb-form">
      <?php 

            // Message is pass in the function
            if ( array_key_exists($msg_id, $message)) {
                echo '<span class="label label-default">' . $message[$msg_id] . '</span>';
            }

      ?>
      <form method="post" action="<?php echo esc_url( admin_url('admin-ajax.php') ) ?>" enctype="multipart/form-data">
            <input type="hidden" value="suc_add_contact" name="action" />
            <?php wp_nonce_field('suc_action','suc_field') ?>
            <div class="row">
                 <div class="col-sm-6">
                        <div class="form-group">
                              <label>First Name</label>
                              <input type="text" name="firstname" value="" class="widefat" required/>
                        </div> 
                 </div> 
                 <div class="col-sm-6">
                        <div class="form-group">
                              <label>Last Name</label>
                              <input type="text" name="lastname" value="" class="widefat" required/>
                        </div>                      
                 </div>
            </div>     
            
            <div class="row">
                 <div class="col-sm-6">
                        <div class="form-group">
                              <label>Phone</label>
                              <input type="text" name="phone" value="" class="widefat" required/>
                        </div>  
                 </div> 
                 <div class="col-sm-6">
                        <div class="form-group">
                              <label>Email</label>
                              <input type="email" name="email" value="" class="widefat" required/>
                        </div>                      
                 </div>
            </div> 

            
            <div class="form-group">
                  <label>Description</label>
                  <input type="text" name="description" value="" class="widefat" />
            </div>  


            <div class="row">
                 <div class="col-sm-6">
                        <div class="form-group">
                              <label>Company</label>
                              <input type="text" name="company_name" value="" class="widefat" required/>
                        </div>  
                 </div> 
                 <div class="col-sm-6">
                        <div class="form-group">
                              <label>Website</label>
                              <input type="text" name="website" value="" class="widefat" required/>
                        </div>                    
                 </div>
            </div>   

            <div class="row">
                 <div class="col-sm-6">
                        <div class="form-select">
                              <label>Category</label>
                              <?php 

                                    $categories = $this->get_category(); 

                                    if( $categories ) :

                                          echo '<select name="post_category" required>';
                                          echo  '<option value="">Select</option>';
                                          foreach( $categories  as $category ) {
                                                echo '<option value="'. $category->term_id .'" >' . $category->name . '</option>';
                                          }

                                          echo '</select>';

                                    endif;

                              ?>
                        </div> 
                 </div> 
                 <div class="col-sm-6">
                        <div class="form-group">
                              <label>Person Recommending</label>
                              <input type="text" name="person_recommended" value="" class="widefat" required/>
                        </div>                  
                 </div>
            </div>   
 

            <input type="submit" value="Submit" />

      </form>
</div>
