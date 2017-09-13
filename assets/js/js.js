$("document").ready(function() {


/******************* Maker, Year, Model lists manipulations *****************/    
    //Global variables
		var obj;	
		var models;
		var unique_models;
		var trim;
		var engines;
		models_array = [];
    
    //Generate Makers select list
    if(location.pathname == '/auto' || location.pathname == '/auto/') {
       	 $.ajax({
				type: 'GET',
				url:"/auto/makers",
				dataType: 'json',
				success: function (data) {
					obj = data;
					makers = [];
					
			//Collect makers names nad push to array from Json object
					for (var i = 0; i<obj.length; i++) {
						makers.push(obj[i].Make_Name);	
					}
					//Sort Array Asc
					makers.sort();
					//Append options on Model Select List
					makers.forEach(function(maker) {
						$('#maker_select').append("<option value='"+maker+"'>"+maker+"</option>");
					});
					
			 	},
				error: function (xhr, ajaxOptions, thrownError) {
    				   	alert(xhr.status);
        				alert(thrownError);
	        				
	    			}
				});
				
			//Generate Year select list
	        for(var year = 2017; year>=2000; year --) {
	            $('#year_select').append("<option value='"+year+"'>"+year+"</option>");
	        }	
       }
       

    
        
$("#maker_select").on('change', function() {
			var SelectedMaker = $('#maker_select option:selected').text();
			if ( SelectedMaker !== 'Select Maker') {
				removeMenuItem("#menu-make");
				removeMenuItem("#menu-year");
				removeMenuItem("#menu-model");
				removeMenuItem("#menu-trim");
				lockList('#model_select');
				unlockList('#year_select');
				resetYear();
				resetModel();
				resetEngine();
				resetTrim();
				lockList('#trim_select');
				$("#menu-link-block").append('<a href="auto/search/'+SelectedMaker.toLowerCase().replace(/\s/g, "-")+'" id="menu-make" class="header-vehicle-link"><span>'+SelectedMaker+'</span></a>');
				
			} else  {
				lockList('#year_select');
				resetYear();
				resetModel();
				lockList('#model_select');
				lockList('#engine_select');
				removeMenuItem("#menu-make");
				removeMenuItem("#menu-year");
				removeMenuItem("#menu-model");
				removeMenuItem("#menu-trim");
			}
		});
		
$("#year_select").on('change', function() {
			var SelectedMaker = $('#maker_select option:selected').text();
			var year = $('#year_select option:selected').text().toLowerCase();
			if(year !== 'select year') {
				removeMenuItem("#menu-year");
				removeMenuItem("#menu-model");
				removeMenuItem("#menu-trim");
				resetModel();
				resetEngine();
				resetTrim();
				lockList('#engine_select');
				lockList('#trim_select');
				var modelAnimation = $('#model-searching');
				var SelectedYear =	$('#year_select option:selected').text();
				var data = {
					'maker' : SelectedMaker,
					'year' : SelectedYear
				}
				modelAnimation.show();
				$.ajax({
					type: 'GET',
					url:"/auto/model",
					dataType: 'json',
					data: data,
					success: function (data) {
					if (data == false) {
								alert("Sorry we don't have models of this maker yet. Choice another maker");
								modelAnimation.hide();
								return
						}
						obj = data;
						models = [];
						//Collect model names nad push to array from Json object
						for (var i = 0; i<obj.length; i++) {
							models.push(obj[i].model);	
						}
						
						//Filter models array with unique values(get unique models)
						unique_models = models.filter( onlyUnique );
						
						//Sort Array Asc
						unique_models.sort();
					
						//Append options on Model Select List
						unique_models.forEach(function(model) {
							$('#model_select').append("<option value='"+model+"'>"+model+"</option>");
						});
						
						//Remove Disable attr from Models List
						$("#model_select").removeAttr('disabled');
						modelAnimation.hide();
					},
					error: function (xhr, ajaxOptions, thrownError) {
	    				   modelAnimation.hide();
	    				   	alert(xhr.status);
	        				alert(thrownError);
	        				
	    			}
				});
				$("#menu-link-block").append('<a href="auto/search/'+SelectedMaker.toLowerCase()+'.'+SelectedYear.toLowerCase()+'" id="menu-year" class="header-vehicle-link"><span>'+SelectedYear+'</span></a>');
			} else {
				removeMenuItem("#menu-year");
				removeMenuItem("#menu-model");
				removeMenuItem("#menu-trim");
				resetModel();
				resetEngine();
				resetTrim();
				$("#model_select").attr('disabled', true);
				$("#engine_select").attr('disabled', true);
				$("#trim_select").attr('disabled', true);
			}
			
		});
		
$("#model_select").on('change', function() { 
			var maker = $('#maker_select option:selected').text().toLowerCase();	
			var year = $('#year_select option:selected').text().toLowerCase();
			var model = $('#model_select option:selected').text().toLowerCase();
			
		//Check is model selected 
			if(model !== 'select model') {
				removeMenuItem("#menu-model");
				removeMenuItem("#menu-trim");
				resetEngine();
				resetTrim();
				lockList('#trim_select');
				var engineAnimation = $('#engine-searching');
				engineAnimation.show();
				//var selected_model = $('#model_select option:selected').text();
				var trim = [];
				var data = {
						'maker' : maker,
						'year'  : year,
						'model' : model,
					}
	
				$.ajax({
						type: 'GET',
						url: '/auto/edm_styles',	
						dataType: 'json',
						data: data,
						success: function (data) {
							if (data.error) {
								alert(data.error[0]);
								engineAnimation.hide();
								return
							}
							//Check is Ajax request not empty
							if(isEmpty(data) == false && data.styles !== undefined || data.styles !== null) {
								if (isEmpty(data) || data.styles.length == 0) {
										alert("Sorry we don't have data about this vehicle");
										engineAnimation.hide();
										return
								}
								
								engineAnimation.show();
								var styles = data.styles;
								
								engines = [];
								
								//Collect trim names and push to array from Json object
								for (var i = 0; i<styles.length; i++) {
									var engine;
									var model = {};
									if (getValueInPren(styles[i].name) == false) {
										engine = "No Information about Engine";
									} else {
										engine = getValueInPren(styles[i].name);
										
									}
									
									//Set models array with properties /id/engine/trim
									model.engine = engine;
									model.id = styles[i].id;
									model.trim = styles[i].trim;
									model.name = styles[i].name;
									
									//Push values to engines arrray and to models array
									engines.push(engine);
									models_array.push(model);

								}//End For loop

								//-------------------------------------------------------------------------------------------------	
								if (engines[0] == "No Information about Engine") {
									 $('#engine_select').html("<option value='"+engines[0]+"'>"+engines[0]+"</option>");
									
									 engineChange(true);
									 
									 //Remove Disable attr from Models List
									$("#trim_select").removeAttr('disabled');
									$("#engine_select").attr('disabled', true);
									
								} else {
								//-------------------------------------------------------------------------------------------------		
									//Filter models array with unique values(get unique models)
									unique_engines = engines.filter(onlyUnique);
									//Sort Array Asc
									unique_engines.sort();
									//Append options on Model Select List
									unique_engines.forEach(function(engine_model) {
										$('#engine_select').append("<option value='"+engine_model+"'>"+engine_model+"</option>");
									});
									
									//Remove Disable attr from Models List
									setTimeout(function(){
										engineAnimation.hide();
										$("#engine_select").removeAttr('disabled');
									}, 100);
									
								}//end if
		
								engineAnimation.hide();
								
							} else {
								alert("We don't have data about this vehicle");
							}//end -- Check is Ajax request not empty;

						}, //end Success
						error: function (xhr, ajaxOptions, thrownError) {
								engineAnimation.hide();
							   	alert(xhr.status);
			    				alert(thrownError);
						}
				});//end Ajax request;
				
				//Generate Model link in Top Menu
				var maker_url = maker.replace(/ /g,"_");
				$("#menu-link-block").append('<a href="auto/search/'+maker_url+'.'+year+'.'+model+'" id="menu-model" class="header-vehicle-link"><span>'+model+'</span></a>');
				
		} else {
				removeMenuItem("#menu-model");
				removeMenuItem("#menu-trim");
				resetEngine();
				resetTrim();
				lockList('#trim_select');
				$("#engine_select").attr('disabled', true);
				$("#trim_select").attr('disabled', true);
		}//end //Check is model selected 
			
		});
		
$("#engine_select").on('change', function() { 
	engineChange();
});
	

	

/******************* Continue button VIN decoding *****************/
$('#vin_submit').click(function() {
		var vin = $('#vin_input').val();
		getEdmVin(vin);
	});
	
/******************* Continue button 2 select fields *****************/
$('#model_submit').click(function() {
	var maker = $('#maker_select option:selected').text().toLowerCase();	
	var year = $('#year_select option:selected').text().toLowerCase();
	var model = $('#model_select option:selected').text().toLowerCase();
	var engine = $('#engine_select option:selected').text().toLowerCase();
	var trim = $('#trim_select option:selected').val();
	
	if(maker == 'select maker' || year == 'select year' || model == 'select model' || engine == 'select engine' || trim == 'select trim' ) {
		alert('Please Select all fields');
		return;
	}
	getEdmEmodel(trim, maker, year, model);

});


/******************* Modal Script *****************/
//Click on Next button
$('#part_continue').on('click', function () {
	var status = $('#part_continue').attr('data-status');
	switch (status) {
		case 'step1': 
			var partGroup = '#'+$('#part-group-select option:selected').val();
			$(partGroup).addClass('visible');
			$('#tab-step2 a').trigger('click');
			$('#tab-step2').removeClass('mouse-disable');
			$('#part_back').css('display', 'inline-block');
			$('#part_continue').attr('data-status', 'step2');
			
			
			break;
		case 'step2':
			$('#part_model_close').trigger('click');
			$('#part_continue').attr('data-status', 'step1');
			var maker = $('#modal-maker').text().toLowerCase();	
			var year = $('#modal-year').text().toLowerCase();
			var model = $('#modal-model').text().toLowerCase();
			var engine = $('#modal-engine').text().toLowerCase();
			var partGroup = $('#part-group-select option:selected').val();
        	var partSubgroup = $('#step2 option:selected').val();

			
			var url = "http://www.rockauto.com/en/catalog/"+changeWhitespace(maker)+","+year+","+changeWhitespace(model);
			window.open(url, '_blank');
	}
});

//Click on Back button
$('#part_back').on('click', function () {
	$('#tab-step1 a').trigger('click');
	$('#part_back').css('display', 'none');
	
});

//Click on Tabs
$('#tab-step1').on('click', function () {
	$('#part_back').css('display', 'none');
	$('#part_continue').attr('data-status', 'step1');
});
$('#tab-step2').on('click', function () {
	$('#part_back').css('display', 'inline-block');
	$('#part_continue').attr('data-status', 'step2');
});

$("#part-group-select").on('change', function() {
	$('#tab-step2').addClass('mouse-disable');
	$('#step2 .visible').removeClass('visible');
	
});
$('#part_model_close').on('click', function () {
	close_modal('parts-modal');
	$('#tab-step1 a').trigger('click');
	$('#tab-step2').addClass('mouse-disable');
	$('#step2 .visible').removeClass('visible');
	$("#part-group-select").val($("#part-group-select option:first").val());
});

/******************* Find Parts Button *****************/
$('#find-parts-button').click(function() {
	open_modal('parts-modal');
});


});//End of Document Ready

