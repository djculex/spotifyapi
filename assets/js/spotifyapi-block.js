$(document).ready(function(){
	
	/*alert(spotifyagenturl);
	*/
/*	
	$.ajax({
		type: 'GET',
        url: spotifyagenturl,
        dataType : 'jsonp',
		jsonpCallback: 'callback',
		xhrFields: {
			  withCredentials: true
			},
		crossDomain: true,
		contentType: "application/json; charset=utf-8;",
		async: false,
		success: function (response) {
			console.log('callback success: ', response);
			getServerResult();
		},
		error: function (xhr, status, error) {
			console.log(status + '; ' + error);
		}
    });
	


	function callFunction(data){
	 //$('.spotifyholder').innerHTML = data;
	}
*/

$.ajax({
        url: spotifyagenturl2,
        dataType: 'html',
		async: true,
		xhrFields: {
		  withCredentials: true
		},
		headers: {
				"accept": "application/json",
				"Access-Control-Allow-Origin":"*",
				"Access-Control-Allow-Headers": "*"
        },
		crossDomain: true,
		beforeSend: function (xhr) {
			xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
			xhr.setRequestHeader('Authorization', '*');
		},
        success: function (data) {
			$('.spotify-block-main').html(data);
			
			// Auto scroll
			//doSlider();
			
        }

});



	var settings = {
          'cache': false,
          'dataType': "jsonp",
          "async": true,
          "crossDomain": true,
          "url": spotifyagenturl,
          "method": "GET",
          headers : {'Access-Control-Allow-Origin':'accounts.spotify.com'},
	}
	
    $.ajax(settings).done(function (response) {
		//console.log(response);
        //getServerResult();
	});	

	function getServerResult() 
	{
		return $.ajax({
			url: spotifyagenturl2,
			dataType: 'jsonp',
			async: true,
			xhrFields: {
			  withCredentials: true
			},
			headers: {
					"accept": "application/json",
					"Access-Control-Allow-Origin":"*",
					"Access-Control-Allow-Headers": "*"
			},
			crossDomain: true,
			beforeSend: function (xhr) {
				xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
				xhr.setRequestHeader('Authorization', '*');
			},
			success: function (data) {
				console.log(data);
				//Data from the server in the in the variable "data"
				//In the form of an array
			}

		});	
	}
	
	//Scroller recently played
	function doSlider()
	{
		var offset = 0;
		var count = $(".spotifyholdermain  > * > *").length;
		//console.log(count);
		window.setInterval(
		  function() {
			offset = (offset - 75) % (count * 75); // 104px div height (incl margin)
			//offset = $(".spotifyholdermain > * >*").height()
			$(".spotifyholdermain > *").css({
			  "transform": "translateY(" + offset + "px)",
			});
		  }, 2000);
	}

});