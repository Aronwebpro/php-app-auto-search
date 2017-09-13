<div id= "year-list maker-list-wrapper">
<ul>
<?php 
foreach ($makers_list as $maker) {
    echo '<li class="col-xs-12 col-sm-6 year-list-item" ><a href="/auto/search/'.preg_replace('/\s+/', '-', strtolower($maker->Make_Name)).'"><span class="maker-logo-img"><img src="/assets/images/makers_logo/'.preg_replace('/\s+/', '-', strtolower($maker->Make_Name)).'.jpg" alt="'.$maker->Make_Name.'"></span>'.$maker->Make_Name.'</a></li>';
} ?>
</ul>
</div>