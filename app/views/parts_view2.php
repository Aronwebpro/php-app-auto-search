
<section id="get-model" class="search-section">
<!-------------------Box Header -------------------------->
	<div class="row searh-header"></div>
	<div class="model-info-title row">
		<div class="col-xs-2">
			<img style="width:100%;"src="<?php echo ROOT_URL; ?>assets/images/acura_2010_rdx.png"></img>
		</div>
		<div class="col-xs-10">
			<h2>Model:</h2>
			<span class="model-title"><?php echo $year; ?></span>
			<span class="model-title"><?php echo $maker; ?></span>
			<span class="model-title"><?php echo $model; ?></span>
		</div>
	</div>
<!-------------------Box Content -------------------------->
	<div class="vehicle-info-container" style="min-heigt:800px;">
		<div class="row">
			<div class="search-block block-right col-xs-12 col-sm-12 col-md-5">
				<div class="insert-info row">
					<div class="search-block-header col-xs-12">
						<h3>Model Image</h3>
					</div>
					<div class=" search-block-body col-xs-12">
						<img src="<?php echo ROOT_URL; ?>assets/images/2010-acura-rdx-1280-03.jpg"></img>
					</div>
				</div>
			</div>	
		</div>
		<div class="row">
			<div class="search-block col-xs-12 col-sm-12 col-md-5">
			    <div class="search-block-header row">
			        <div class="col-xs-12">
			            <h5>Vehicle Information:</h5>
			        </div>
			    </div>
			    <div class="search-block-body row">
			        <div class="col-xs-12">
			            <table>
			                <tr>
			                	<td><img style="width:25px;" src="assets/images/sedan.png"></img></td>
			                    <td>Type:</td>
			                    <td><?php echo $type; ?></td>
			                </tr>     			                <tr>
			                <td><img src="assets/images/engine.jpg"></img></td>
			                    <td>Engine:</td>
			                <td><?php echo $engine; ?></td>
			                </tr>
			                <tr>
			                	<td><img src="assets/images/seating.jpg"></img></td>
			                    <td>Doors:</td>
			                    <td><?php echo $doors; ?></td>
			                </tr>
			                <tr>
			                	<td><img src="assets/images/transmission.jpg"></img></td>
			                    <td>Transmission:</td>
			                    <td><?php echo $transmission; ?></td>
			                </tr>			                
			                <tr>
			                	<td><img src="assets/images/power.jpg"></img></td>
			                    <td>Power:</td>
			                    <td><?php echo $power; ?></td>
			                </tr>
                            <tr>
                            	<td><img src="assets/images/mpg.jpg"></img></td>
			                    <td>Gas Mileage:</td>
			                    <td><?php echo $gas_mileage; ?></td>
			                </tr>
			                 <tr>
                            	<td><img width="20" height="17" src="https://cdn3.iconfinder.com/data/icons/glypho-transport/64/speed-meter-outline-512.png"></img></td>
			                    <td>Acceleration:</td>
			                    <td><?php echo $acceleration; ?></td>
			                </tr>
			            </table>
			        </div>
			    </div>
			    <div class="search-block-footer row">
			    	
			    </div>
			</div>
		</div>	
		<div class="row">
			<div class="search-submit col-xs-12 ">
				<input id="model_submit" class="continue-button" type="submit" name="car_model" value="CONTINUE"/>
			</div>	
		</div>
	</div>

</section>

	
	
	

