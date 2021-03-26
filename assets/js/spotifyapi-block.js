$(document).ready(function(){

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
				var index = 0;
				var count = 0;
				$('.spotify-block-main').html(data);
				$('.spotifyholdermain').slick({
				  slidesToShow: 1,
				  slidesToScroll: 1,
				  autoplay: true,
				  autoplaySpeed: 4000,
				  //dots:true,
				  fade:true,
				  focusOnSelect:true,
				  pauseOnFocus:true,
				  pauseOnHover:true,
				  //vertical:true,
				  centerMode: true,
					responsive: [
					{

					  breakpoint: 1024,
					  settings: {
						slidesToShow: 1,
						infinite: true
					  }

					}, {

					  breakpoint: 600,
					  settings: {
						slidesToShow: 1,
						dots: true
					  }

					}, {

					  breakpoint: 300,
					  settings: "unslick" // destroys slick

					}
					]
				});		
			}
				//var sliderItemsTotal = $('.owl-item').length;
				//console.log(sliderItemsTotal);
				
				// Auto scroll
				//doSlider();
				
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
	

});