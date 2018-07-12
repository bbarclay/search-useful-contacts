
<div class="grid contact-type-list">
<?php 
		 global $post;	 
  
  		 if( $posts ) :

         	foreach( $posts as $post ) : setup_postdata( $post );  ?>
         				<?php 

							the_content(); 
							$id = GET_THE_ID(); 
							$description 	=	esc_html(  get_post_meta($id, 'description', true) );
							$email			=	esc_html(  get_post_meta($id, 'email', true) );
							$phone          =	esc_html(  get_post_meta($id, 'phone', true) );
							$website 		=	esc_html(  get_post_meta($id, 'website', true) );
							$name           =   esc_html(  get_post_meta($id, 'name', true) );
							$company        =   esc_html(  get_post_meta($id, 'company_name', true) );

						?>
				   			<div class="column">
				   				<?php $id = GET_THE_ID(); ?>
				   				<div class="single-contact" id="<?php echo $id; ?>">
				   					  <div class="inner-top">
				   					  	<?php echo ( !empty($company) )  ? '<h3>' . $company . '</h3>': ''; ?>
				   					    <?php echo ( !empty($description) )  ? '<p>' . $description . '</p>': ''; ?>
				   					  </div>  
				   					  <div class="inner-bottom">
				   					      <?php if( !empty( $name ) ) : ?>
					   					  	<div><span>Name </span> <?php echo  $name; ?></div>
					   					  <?php endif; ?>	
					   					  <?php if( !empty( $email ) ) : ?>
					   					  	<div><span>Email </span> <?php echo  $email; ?></div>
					   					  <?php endif; ?>	
					   					  <?php if( !empty( $phone ) ) : ?>	
					   					  	<div><span>Phone </span> <?php echo  $phone; ?></div>
					   					  <?php endif; ?>
					   					  <?php if( !empty( $website ) ) : ?>	
					   					  <div><span>Website </span> <a href="<?php echo  $website ?>" target="_blank"><?php echo  $website; ?></a></div>
					   					  <?php endif; ?>
				   					  </div>
				   				</div>
				   			

				   			</div>

<?php   	endforeach;

		endif;
		wp_reset_postdata(); ?>
</div>	

<style>
	.single-contact {
	    margin-bottom: 25px;
	    padding: 0;
	    border: 1px solid #f1f0f0;
	    display: flex;
	    flex-wrap: wrap;
	    box-shadow: 0 2px 10px #f1eaea;
	}
	.single-contact:after {
		content: '';
	    clear: both;
	    display: block;
	} 
	.single-contact .inner-top {
		padding: 25px 20px;
		margin-bottom: 0;
		background: #002749;
		width: 200px;
		float: left;
		color: #fff;
	}
	.single-contact .inner-bottom {
	    padding: 10px 20px;
	    display: inline-block;
	    float: right;
	    width: calc(100% - 200px);
	}
	.single-contact .inner-bottom > div {
	    padding: 5px 0;
	    border-bottom: 1px solid #f9f9f9;
	}
	.single-contact .inner-bottom > div:last-child {
        border-bottom: 0;
	}
	.single-contact .inner-top span {
		display: inline-block;
		padding: 5px 10px;
		background: #0076c0;
		color: #fff;
	}
	.single-contact .inner-top p {
	    margin-bottom: 0;
	    line-height: 1.3;
	    font-size: 16px;
	}
	.single-contact h3 {
		margin-bottom: 0;
		font-family: 'Geogrotesque-SemiBold', sans-serif;
	    font-size: 21px;
	        color: #00bce4;
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
	.contact-type-list {
		display: flex;
		flex-wrap: wrap;
	}
	.contact-type-list .column {
		width: 50%;
		padding: 0 15px;
	}

	@media screen and ( max-width: 991px ) {
		.single-contact .inner-bottom {
			 width: 100%;
		}
		.single-contact .inner-top {
			width: 100%;
		}
	}
	@media screen and ( max-width: 600px ) {
		.contact-type-list .column {
			width: 100%;
		}
	}
</style>
