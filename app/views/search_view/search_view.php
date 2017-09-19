<div id="page-vin-detail">
<section class="section-wrapper">
<!-------------------Header -------------------------->
<div id="wrapper-header">
	
	<!--Header Top menu-->
	<div class="row">
		<div class="searh-header">
			<a href="/auto"><span><img src="<?php echo ROOT_URL; ?>/assets/images/svg/home_button.svg"></img></span></a>
			<a href="/auto/search/" class="header-vehicle-link header-vehicle-link-first"><span>Makers</span></a>
			<a style="<?php if (!isset($maker)) { echo "display:none;";}?>" href="<?php echo ROOT_URL.'/auto/search/'.$maker ?>" class="header-vehicle-link"><span><?php echo ucwords(str_replace('-', ' ', $maker)); ?></span></a>
			<a style="<?php if (!isset($year)) { echo "display:none;";}?>" href="<?php echo ROOT_URL.'/auto/search/'.$maker.'.'.$year ?>" class="header-vehicle-link"><span><?php echo $year; ?></span></a>
			<a style="<?php if (!isset($model)) { echo "display:none;";}?>"href="<?php echo ROOT_URL.'/auto/search/'.$maker.'.'.$year.'.'.$model ?>" class="header-vehicle-link"><span ><?php echo strtoupper($model); ?></span></a>
			<span class="header-vehicle-link remove-after"><?php echo $trim; ?></span>
		</div>
	</div><!--End Row -->
</div><!--End Header -->

<!-------------------Content -------------------------->
	<div id="wrapper-body" class="vehicle-info-container">
		<div class="row">
			
			
            <?php if(isset($search_body_view)) { include(LIB_PATH."/views/".$search_body_view); } ?>
	   	
		
		</div><!--End Row -->	
	</div><!--End Block Content-->
</section><!--End Page Section -->


</section><!--End Model Review Section -->
</div>