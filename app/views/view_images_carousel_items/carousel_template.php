<div id="vehicle-image-carousel" class="carousel slide" data-ride="carousel">
    <!--Indicators -->
    <ol class="carousel-indicators">
    <?php $this->generate_images_list($images, 'indicators')?>
    </ol>
    
    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
    <?php $this->generate_images_list($images, 'images')?>
    </div>
    
    <!-- Controls -->
    <a class="left carousel-control" href="#vehicle-image-carousel" role="button" data-slide="prev">
        <!--<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>-->
        <span id="arrow_left"><img src="<?php echo ROOT_URL; ?>/assets/images/arrow_left.png"></img></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#vehicle-image-carousel" role="button" data-slide="next">
        <!--<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>-->
        <span id="arrow_right"><img src="<?php echo ROOT_URL; ?>/assets/images/arrow_right.png"></img></span>
        <span class="sr-only">Next</span>
    </a>
</div>