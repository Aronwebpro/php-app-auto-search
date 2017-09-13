
<div id="page-model-detail">
<section class="section-wrapper">
<!-------------------Header -------------------------->
	<div id="wrapper-header">
		
		<!--Header Top line-->
		<div class="row">
			<div class="searh-header">
				<!--<a href="">	<span><img src="/assets/images/svg/menu_bar.svg"></img></span></a>-->
				<a href="/auto"><span><img src="/assets/images/svg/home_button.svg"></img></span></a>
				<a href="/auto/search/" class="header-vehicle-link header-vehicle-link-first"><span>Makers</span></a>
				<a href="<?php echo '/auto/search/'.$maker ?>" class="header-vehicle-link"><span><?php echo $maker; ?></span></a>
				<a href="<?php echo '/auto/search/'.$maker.'.'.$year ?>" class="header-vehicle-link"><span><?php echo $year; ?></span></a>
				<a href="<?php echo '/auto/search/'.$maker.'.'.$year.'.'.$model ?>" class="header-vehicle-link"><span ><?php echo $model; ?></span></a>
				<span class="header-vehicle-link remove-after"><?php echo $trim; ?></span>
			</div>
		</div><!--End Row -->
		
		<!--Vehicle title wrapper-->
		<div class="row">
			<div class="col-xs-5 col-sm-2">
				<div class="model-title-image">
					<img src="<?php echo $image_title; ?>"></img>
				</div>
			</div>
			<div class="col-xs-7 col-sm-5">
				<h4 class="model-title">
					<span class="model-title"><?php echo $year; ?></span>
					<span class="model-title"><?php echo $maker; ?></span>
					<span class="model-title"><?php echo $model; ?></span>
				</h4>
			</div> 
			<div class="col-xs-12 col-sm-5 ">
				<div class="cta-buttons fl_r">
					<div class="find-parts-wrapper">
							<img src="/assets/images/order_parts.png">
							<button id="find-parts-button" class="button vehicle-cta-button" href="" type="button">Find Parts</button>
						</div>
				</div>
			</div>
		</div><!--End Row -->
		
		<!--Title wrapper-->
		<div class="model-gallery-header">
			<div class="row">
				<div class="co-xs-12">
					<h2>Vehicle Information</h2>
				</div>	
			</div>
		</div>	
	</div><!--En Box Header -->
<!-------------------Content -------------------------->
	<div id="wrapper-body" class="vehicle-info-container">
		<div class="row">
			<!------------- Model Image widget ----------------->
			<div id="model-image" class="widget-wrapper col-xs-11 col-sm-11 col-md-5">
				
				<div class="widget-header row">
					<h4>Model Image</h4>
				</div>
				<div class="widget-body row">
					<img id="widget-model_image-image" src="<?php echo $image_model; ?>" />
				</div>
				<div class="widget-footer row">
		    
		    	</div>
			</div><!--End Image block -->

	   		<!------------- Model Information box ----------------->
			<div id="model-info" class="widget-wrapper col-xs-11 col-sm-11 col-md-5">
			    <div class="widget-header row">
			        <div class="col-xs-12">
			            <h4>Specifications:</h4>
			        </div>
			    </div>
			    <div class="widget-body row">
			        <div class="col-xs-12">
			            <table>
			            	<tr>
			                	<td> <!--<img class="vehicle-spec-icon" src="/assets/images/icons/car_type.png"></img>--> </td>
			                    <td>Make:</td>
			                    <td><?php echo $maker; ?></td>
			                </tr>
			            	<tr>
			                	<td><!--<img class="vehicle-spec-icon" src="/assets/images/icons/car_type.png"></img>--></td>
			                    <td>Model:</td>
			                    <td><?php echo $model; ?></td>
			                </tr>
			                <tr>
			                	<td><img class="vehicle-spec-icon" src="/assets/images/icons/car_type.png"></img></td>
			                    <td>Type:</td>
			                    <td><?php echo $type; ?></td>
			                </tr>
			                <tr>
			                <td><img class="vehicle-spec-icon" src="/assets/images/icons/engine2.png"></img></td>
			                    <td>Engine:</td>
			                <td><?php echo $engine; ?></td>
			                </tr>
			                <tr>
			                <td><img class="vehicle-spec-icon" src="/assets/images/icons/trim.png"></img></td>
			                    <td>Trim:</td>
			                <td><?php echo $trim; ?></td>
			                </tr>
			                <tr>
			                	<td><img class="vehicle-spec-icon" src="/assets/images/icons/doores.png"></img></td>
			                    <td>Doors:</td>
			                    <td><?php echo $doors; ?></td>
			                </tr>
			                <tr>
			                	<td><img class="vehicle-spec-icon" src="/assets/images/icons/transmission.png"></img></td>
			                    <td>Transmission:</td>
			                    <td><?php echo $transmission; ?></td>
			                </tr>			                
			            </table>
			        </div>
			    </div>
			    <div class="widget-footer row">
			    </div>
			</div><!--End Information Box -->
		
		</div><!--End Row -->	
	</div><!--End Block Content-->
</section><!--End Page Section -->


<!------------- Model Gallery ---------------------->
<section id="model-gallery" class="search-section container section-wrapper" <?php if($images == '') { echo 'style="display:none"'; } ?>>
	<div class="model-gallery-header">
		<div class="row">
			<div class="co-xs-12">
				<h2>Model Gallery</h2>
			</div>	
		</div>
	</div>
	<div class="vehicle-info-container">
		<div class="row">
			<div class="widget-wrapper1">
				<div class="col-xs-12">
					<?php if($images !== '') { $this->image_carousel($images); } ?>
				</div>
			</div>
		</div>
	</div>
	
	
</section><!--End Gallery Section -->	

<!------------- Model Reviews box ----------------->
<section id="model-reviews" class="section-wrapper">
    <div class="model-gallery-header">
		<div class="row">
			<div class="co-xs-12">
				<h2>Reviews</h2>
			</div>	
		</div>
	</div>
			<!--Rating wrapper-->
		<div class="row">
			<div class="ratings-widget col-xs-12">
				<h3>Average Rating:</h3>
				<span class="rating-star"><?php if( $rating_number !== 0) { ?> <img src="/assets/images/svg/rating_stars/star<?php  echo $rating_number; ?>.svg"></img><?php } else { ?> No Rating <?php } ?> </span>
			</div>
		</div>
    <div class="vehicle-info-container">
		<div id="model-reviews" class="widget-wrapper">
				<div class="reviews-widget row">
					<div class="col-xs-12">
						<div class="widget-header row">
						<h4><span class="bold">Edmunds.com</span> visitor vehicle reviews:</h4>
					</div>
					<div class="widget-body row">
						
					<?php if($reviews !== '') { $this->generate_reviews($reviews); } else { ?> <h2>There is not Emdmunds review about this vehicle</h2><?php } ?>
					</div>
					<div class="widget-footer row reviews-disclaimer">
			    		<p><span class="bold">Edmunds.com</span> Visitor Vehicle Ratings and Reviews are the property of Edmunds.com, and may not be reproduced or distributed without the consent of Edmunds.com. Edmunds® is a trademark of Edmunds.com, Inc. Edmunds.com, Inc. is not affiliated with this website or app.</p>
			    	</div>
					</div>
				</div>
			</div>
    </div>
</section> 	
</div><!--End Div page-model-detail -->
<!-- Find Parts Modal-->
<?php $this->generate_find_parts_modal($maker, $year, $model, $engine, $trim, $image_title); ?>
 
 