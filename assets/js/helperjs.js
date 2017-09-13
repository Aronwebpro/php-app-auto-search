/******************* Helper functions*****************/


/******************* Text Expand *****************/
function expandReview(button_id, block_id, height) {
	$(button_id).click(function() {
		if($(block_id).attr('data-text') == 'closed' ) {
			$(block_id).addClass('review-text-wrapper-open');
			$(block_id).css("height", height);
			$(block_id).removeClass('review-text-wrapper');
			$(block_id).attr('data-text', 'open');
			$(button_id).text('Read Less...');
			$(button_id).removeClass('');
			$(button_id).addClass('button-up');
			
		} else {
			$(block_id).addClass('review-text-wrapper');
			$(block_id).removeClass('review-text-wrapper-open');
			$(block_id).attr('data-text', 'closed');
			$(block_id).css('height', '50px');
			$(button_id).text('Read More...');
			$(button_id).removeClass('button-up');
			$(button_id).addClass('button-down');
			
			
		}
	});
}		
		
(function add_expand_button() {
	var h = $('.review-text');
	 if(h !== null ) {
	 	 for(var i = 0; i<h.length; i++){
			var	height = h[i].clientHeight;
 			var block_id = '#edm-review-text-block-'+i
	 		var button_id = '#read-full-review-'+i;
 			if(height < 60 ) {
				$(button_id).css('display', 'none');
			} else {
				$(button_id).css('display', 'block');
			}
			expandReview(button_id, block_id, height);	
	 	 }
	 }
})();


/******************* Functions to help handle select fields *****************/
//Reset Select fields
function resetYear() {
	$("#year_select :selected").val('Select Year');
	$("#year_select").val($("#year_select option:first").val());
}
function resetModel() {
	$("#model_select :selected").val('Select Model');
	$("#model_select").val($("#model_select option:first").val());
	var modelsList = $("#model_select option");
	if (modelsList.length > 1) {
		for (var i=1; i<modelsList.length; i++ ) {
			modelsList[i].remove();
		}
	}
}
function resetEngine() {
	$("#engine_select :selected").val('Select Engine and Trim');
	$("#engine_select").val($("#engine_select option:first").val());
	var enginesList = $("#engine_select option");
	if (enginesList.length > 1) {
		for (var i=1; i<enginesList.length; i++ ) {
			enginesList[i].remove();
		}
	}
}
function resetTrim() {
	$("#trim_select :selected").val('Select Trim');
	$("#trim_select").val($("#trim_select option:first").val());
	var trimList = $("#trim_select option");
	if (trimList.length > 1) {
		for (var i=1; i<trimList.length; i++ ) {
			trimList[i].remove();
		}
	}
}
//Lock/unlock Select fields
function lockList(list_id) {
	$(list_id).attr('disabled', true);
}
function unlockList(list_id) {
	$(list_id).removeAttr('disabled');
}
//Loading animation
function pageLoader(action) {
	var loaderHTML = '<div id="loader" class="loder-background"><img id="loader-image" src="assets/images/gif/pageloader.gif"></img></div>';
	if (action == 'load') {
		$('body').append(loaderHTML);
	} else {
		$('#loader').remove();
	}
}

//Get values in Brackets
function getValueInPren(string) {
	var regExp = /\(([^)]+)\)/;
	var matches = regExp.exec(string);
	
	if(matches !== null ){
		return matches[1];
	} else {
		return false;
	}
	
}
//Change Whitespace to plus sign
function changeWhitespace (string) {
	var replaced = string.split(' ').join('+');
	return replaced;
}
		
/******************* General Functions *****************/		
//Remove Model menu item
function removeMenuItem(id) {
	if($(id) !== null) {
			$(id).remove();
		}
}


//Check for emptu JSON obj
function isEmpty(obj) {
    for(var prop in obj) {
        if(obj.hasOwnProperty(prop))
            return false;
    }

    return JSON.stringify(obj) === JSON.stringify({});
}

//Open-Close Modal
function open_modal(id) {
	var x = document.getElementById(id);
	x.style.display= 'block';
}
function close_modal(id) {
	var x = document.getElementById(id);
	x.style.display= 'none';
}

//Get Unique values 
function onlyUnique(value, index, self) { 
    return self.indexOf(value) === index;
}


/******************* Call Ajax request *****************/	
//By selected model
function  getEdmEmodel(id, maker, year, model) {
	pageLoader('load');
	var data = {
				'styleId': id,
				'maker': maker,
				'year' : year,
				'model': model
				}
	$.ajax({
			type: 'GET',
			url: '/auto/emodel',
			dataType: 'text',
			data: data,
			success: function (data) {
					$('body').html(data);
					pageLoader('stop');
					
			},
			error: function (xhr, ajaxOptions, thrownError) {
					pageLoader('stop');
				   	alert(xhr.status);
    				alert(thrownError);
			}
	});
}
//Vin
function  getEdmVin(vin) {
	pageLoader('load');
	var data = {
					'vin': vin
				}
	$.ajax({
			type: 'GET',
			url: '/auto/vin',
			dataType: 'text',
			data: data,
			success: function (data) {
			
			$("body").html(data);
			pageLoader('stop');
			},
			error: function (xhr, ajaxOptions, thrownError) {
				   	pageLoader('stop');
				   	alert(xhr.status);
    				alert(thrownError);
			}
	});
}

//Generate Engine list
function engineChange(noInfo = false) {
	resetTrim(); 
	var trimAnimation = $('#trim-searching');
	var selectedEngine = $('#engine_select option:selected').text();
	//Check is selected engine
	if (selectedEngine !== 'Select Engine and Transmission') {
		trimAnimation.show();
		if (noInfo == true) {
			for(var i = 0; i<models_array.length; i++) {
				$('#trim_select').append("<option value='"+models_array[i].id+"'>"+models_array[i].name+"</option>");
			}//end for;
		} else {
			for(var i = 0; i<models_array.length; i++) {
				if (models_array[i].engine == selectedEngine ) {
					$('#trim_select').append("<option value='"+models_array[i].id+"'>"+models_array[i].name.replace(/ *\([^)]*\) */g, "")+"</option>");
				}//end if;
			}//end for;
		}
		//Remove Disable attr from Models List
		setTimeout(function(){
			trimAnimation.hide();
			$("#trim_select").removeAttr('disabled');
		}, 100);
	} else {
		resetTrim();
		$("#trim_select").attr('disabled', true);
	}//end if //Check is selected engine;
}

//Testing
function  getActionById(id, action) {
	pageLoader('load');
	var data = {
				'styleId': id	
				}
	$.ajax({
			type: 'GET',
			url: '/auto/'+action,
			dataType: 'text',
			data: data,
			success: function (data) {
				$("body").html(data);
				pageLoader('stop');
			},
			error: function (xhr, ajaxOptions, thrownError) {
					pageLoader('stop');
				   	alert(xhr.status);
    				alert(thrownError);
			}
	});
}
