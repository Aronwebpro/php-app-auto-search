<div class="col-xs-12 edm-review">
	<div class="row"> 
		<h3 class="inline-block">-<?php echo $review_title; ?></h3>
		<p><span><?php  echo $user_rating; ?> </span> <img src="/assets/images/svg/review_stars/review_stars<?php echo $user_rating; ?>.svg" alt="User Rating"></p>
		<p class="inline-block"></p>
	</div>
	<div class="row review-widget-author">
		<p><span class="bold">Author:</span> <?php  echo $review_author; ?></p>
		<p><span class="bold">Date:</span> <?php  echo $review_date; ?></p>
	</div>
	<div id="edm-review-text-block-<?php echo $review_id_number; ?>" class="row review-text-wrapper" data-text="closed">
		<p class="review-text"><?php  echo $review_text; ?></p>
	</div>
	<div class="row">
		<a href="javascript:void(0)"><p id="read-full-review-<?php echo $review_id_number; ?>" class ="read-full-review-button button-down">Read More...</p></a>
	</div>
</div>
						
			
			
						