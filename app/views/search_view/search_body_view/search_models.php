<h2>Choose your model:</h2> 
<div id= "year-list maker-list-wrapper">
<ul>
    
<?php 
//print_r($exist_model);
if(empty($exist_model)) { echo "<p>There are no any models to show..</p>"; exit;}
foreach ($exist_model as $model) {
    echo '<li class="col-xs-12 col-sm-6 year-list-item" ><a href="'.ROOT_URL.'/auto/search/'.$maker.'.'.$year.'.'.preg_replace('/\s+/', '-', strtolower($model->model)).'">'.$model->model.'</a></li>';
}

?>
</ul>
</div>