<div id= "year-list year-list-wrapper">
<div class="row"><h2><?php echo ucwords(str_replace('-', ' ', $maker)) ?></h2></div> 
<div class="row">
 <ul>
<?php 
$year_reverse = array_reverse($all_years);
foreach ($year_reverse as $year) {
    echo '<li class="col-xs-12 col-sm-6 year-list-item" ><a href="/auto/search/'.$maker.'.'.$year->year_number.'">'.$year->year_number.'</a></li>';
} ?>
</ul>  
</div>
</div>