var domain = document.domain;
var a_url 	  = "//"+domain+"/";
var cur_url   = "http://"+domain+"/";
var s_cur_url = "http://"+domain+"/";

$.ajaxSetup ({  
    cache: false  
});

function linkPage(page){
	window.location.href = cur_url+page;
}

function linkSPage(page){
	window.location.href = s_cur_url+page;
}

function linkAPage(page){
	window.location.href = a_url+page;
}

function submitAJAX(el){				
	var fData 	= $('#'+el.id).serialize();		//entire form
	var url		= $('#ajax_url').val();			//url to post to
	var pre		= $('#pre_process').val();		//pre processing function (if any)
	var post	= $('#post_process').val();		//post processing function (if any)
		    	
	if(pre){
		var preprocess = window[pre]();
		if(!preprocess.status){
			handleError(preprocess.error);
			return false;
		}
	}

	$.post(s_cur_url+url, fData, function(data) {
		console.log("Data Loaded: " + data);
		data = JSON.parse(data);
		
		//check if we need to post process
		if(post){

		}

		//standard post processing functions
		if(data.status == "success"){			
			if(data.action == "redirect"){
				linkAPage(data.url);
			}
		}else if(data.status == "error"){
			handleError(data.msg);
		}
	});
	
	return false;
}

function handleError(e){
	alert(e);
}