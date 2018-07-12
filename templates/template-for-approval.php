
<div class="row">
<?php 
		 global $post;	 
   		 $posts = $this->get_pending_contacts();
 
         foreach( $posts as $post ) : setup_postdata( $post );  ?>

   			<div class="col-sm-4">
   				<?php $id = GET_THE_ID(); ?>
   				<div class="single-contact" id="<?php echo $id; ?>">
   					  <div class="inner-top">
   					  	<h3><?php the_title(); ?></h3>
   					    <p><?php echo esc_html( get_post_meta($id, 'description', true) ) ?></p>
   					    <?php $cat = $this->get_post_categories($id); ?>	
   					    <?php echo ( $cat ) ?  '<span>' . $cat->name . '</span>': ''; ?>
   					  </div>  
   					  <div class="inner-bottom">
	   					  <div><span>Name </span> <?php echo  esc_html(  get_post_meta($id, 'name', true) ) ?></div>
	   					  <div><span>Email </span> <?php echo  esc_html( get_post_meta($id, 'email', true) ) ?></div>
	   					  <div><span>Phone </span> <?php echo  esc_html( get_post_meta($id, 'phone', true) ) ?></div>
	   					  <div><span>Website </span> <a href="<?php echo  esc_url( get_post_meta($id, 'website', true) ); ?>" target="_blank"><?php echo  esc_url( get_post_meta($id, 'website', true)) ; ?></a></div>
	   					  <div><span>Recommended By </span> <?php echo  esc_html( ( get_post_meta($id, 'person_recommended', true) ) ? get_post_meta($id, 'person_recommended', true) : 'N/A' ) ?></div>
	   					  <button class="btn btn-approve">Approve</button>
   					  </div>
   				</div>
   			

   			</div>

<?php   endforeach;
		wp_reset_postdata(); ?>
</div>		
<style>
	.single-contact {
	    margin-bottom: 25px;
	    padding: 20px 0;
	    border-radius: 3px;
	    box-shadow: 0 2px 15px #f3f0f0;
	}
	.single-contact .inner-top {
		padding: 10px 20px;
	}
	.single-contact .inner-bottom {
		padding: 0 20px;
	}
	.single-contact .inner-bottom > div {
	    padding: 8px 10px;
	    border-bottom: 1px solid #f5f4f4;
	}
	.single-contact .inner-top span {
		margin-left: 10px;
		display: inline-block;
		padding: 5px 10px;
		background: #0076c0;
		color: #fff;
	}

	.single-contact h3 {
		margin-bottom: 0;
		font-family: 'Geogrotesque-SemiBold', sans-serif;
    	font-size: 23px;
    	margin: 0 10px;
	}
	.single-contact p {
		margin: 0 10px;
	}
	.single-contact span {
		display: block;
		color: #abaaaa;
		font-size: 15px;
    	line-height: 1;
	}
	.single-contact button {
		height: auto;
		margin-top: 10px;
		margin-right: 5px;
		padding: 0.25em 1em;
		border-radius: 4px;
		background: #7ac143;
		color: #fff;
		font-family: 'Geogrotesque-Bold', sans-serif;
	}
	.single-contact button:hover {
		background: #002749;
	}
</style>
<script>
	
</script>