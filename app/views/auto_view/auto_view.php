<section id="get-model" class="section-wrapper">
		<div class="row">
			<div id ="menu-link-block" class="searh-header">
				<a href="<?php echo ROOT_URL; ?>/auto"><span><img src="<?php echo ROOT_URL; ?>/assets/images/svg/home_button.svg"></img></span></a>
				<a href="<?php echo ROOT_URL; ?>/auto/search/" class="header-vehicle-link header-vehicle-link-first"><span>Makers</span></a>
			</div>
		</div><!--End Row --> 
		<?php if($errors) { error_message($errors); } ?>
	<div class="row">
		<div class="search-title col-xs-12">
			<h3>START HERE</h3>
		</div>
	</div>
	<div class="row middle-title">
		<div class="col-xs-12">
			<div class="center-title">
				<h3>Select your Car Model</h3>
			</div>
		</div>
	</div>
	<div id="search-info">
		<div class="row">
			<div class="search-block block-left col-xs-12 col-md-4">
				<div class="insert-info row ">
					<div class="col-xs-12 vin-row">
						<label for="vin">Please Enter VIN</label>
						<input id="vin_input" type="text" name="vin"/>
					</div>
				</div>
				
					<div id="vin-submit-block"  class="search-submit">
						<input id="vin_submit" class="button continue-button" type="submit" name="car_model" value="CONTINUE"/>
					</div>
			
				
			</div>
			<div id = "or-block" class="col-xs-12 col-md-2">
				<div class="or-row">--OR--</div>	
			</div>
			<div class="search-block block-right col-xs-12 col-md-5">
				<div class="insert-info row">
					<div class="select_list">
						<span class="before-select">1</span>
						<select name="maker" id="maker_select">
							<option value="">Select Maker</option>
						</select>
					</div>
					
					<div class="select_list">
						<span class="before-select">2</span>
						<select name="year" id="year_select" disabled>
							<option value="select">Select Year</option>
						</select>
					</div>
					<div class="select_list">
						<span class="before-select">3</span>
						<select name="model" id="model_select" disabled>
							<option value="select">Select Model</option>
						</select>
						<span id="model-searching"><img src="<?php echo ROOT_URL; ?>/assets/images/bx_loader.gif"></span>
					</div>
					<div class="select_list">
						<span class="before-select">4</span>
						<select name="model" id="engine_select" disabled>
							<option value="select">Select Engine and Transmission</option>
						</select>
						<span id="engine-searching"><img src="<?php echo ROOT_URL; ?>/assets/images/bx_loader.gif"></span>
					</div>
					<div class="select_list">
						<span class="before-select">5</span>
						<select name="trim" id="trim_select" disabled>
							<option value="select">Select Trim</option>
						</select>
						<span id="trim-searching"><img src="<?php echo ROOT_URL; ?>/assets/images/bx_loader.gif"></span>
					</div>
				</div>	
				<div class="row">
					<div class="search-submit">
						<input id="model_submit" class="button continue-button" type="submit" name="car_model" value="CONTINUE"/>
					</div>	
				</div>
			</div>
		</div>
	</div>
</section>

	
	
	
