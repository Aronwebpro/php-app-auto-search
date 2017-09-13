<h1>Playground</h1>
   <style type="text/css">
   	.open {
   		display:block;
   	}
   	
   </style>
<button class="modal-button" data-target="parts-modal">Open Modal</button> 
    
<div id="parts-modal" class="modal-block" role="dialog">
	<div class="modal-inner">
		<div class="modal-inner-content">
			<div class="modal-header">
				<button type="button" class="close-button modal-close-button" data-target="parts-modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title">Find Your Auto Parts</h3>
			</div><!--End modal-header-->
			<div class="modal-header">
				<div class="row">
					<div class="col-xs-6">
						<h4>Your selected vehicle:</h4>
						<p>Make: <span id="modal-maker" class="bold" ><?php echo $maker; ?></span></p>
						<p>Year: <span id="modal-year" class="bold"><?php echo $year; ?></span> </p>
						<p>Model: <span id="modal-model" class="bold"><?php echo $model; ?></span></p>
						<p>Engine: <span id="modal-engine" class="bold"><?php echo $engine; ?></span></p>
						<p>Trim: <span id="modal-trim" class="bold"><?php echo $trim; ?></span></p>
					</div>
					<div class="col-xs-6">
						<img src="<?php echo $image_title; ?>"></img>
					</div>
				</div><!--End Row-->	
			</div><!--End Modal-header-->
			<div class="modal-body">


</div><!--End Modal-Body-->
			<div class="modal-footer">
				<button type="button" class="button modal-close-button" data-target="parts-modal" aria-label="Close" ><span aria-hidden="true">Close</span></button>
			</div><!-- End Modal Footer-->
		</div>
	</div>
</div>
    
    
<?php getJavascript_local('test.js'); ?>
    
    
