$(document).ready(function(){
	jQuery.support.cors = true;
	var mytoken = getToken();
	var accesscode;
	
	
	
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
			cache: false,
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
					  arrows:true,
						prevArrow:"<img class='a-left control-c prev slick-prev' src='"+themeurl+"/modules/spotifyapi/assets/images/back.png'>",
						nextArrow:"<img class='a-right control-c next slick-next' src='"+themeurl+"/modules/spotifyapi/assets/images/next.png'>",
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

						}, 	{

							breakpoint: 600,
							settings: {
								slidesToShow: 1,
								//dots: true
							}

						}, {

							breakpoint: 300,
							settings: "unslick" // destroys slick

							}
						]
				});

				
				// Adjusting slick css
				$(".slick-prev, .slick-next").css({
					"height": "15px", 
					"width": "15px"
				});			
				$(".slick-next").css("right","-20px");
				$(".slick-prev").css("left", "-20px");
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
		  "crossOrigin": true,
		  "beforeSend": function (xhr) {
				xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
				xhr.setRequestHeader('Authorization', '*');
			},
          "url": spotifyagenturl,
          "method": "GET",
          "headers": {
              //"accept": "application/json",
              "Access-Control-Allow-Origin":"*"
          }
	}
	
    $.ajax(settings).done(function (response) {
		//console.log(response);
        getServerResult();
	});	
	
	//getOAuthToken();
	getSongsAll(mytoken);
	
	function getServerResult() 
	{
		return $.ajax({
			url: spotifyagenturl2,
			dataType: 'jsonp',
			async: true,
			cache: false,
			xhrFields: {
			  withCredentials: true
			},
			headers: {
					"accept": "text/plain",
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
	
	function getOAuthToken()
	{
		$.ajax({
		  url: spotifyagenturl21 + "?op=OAuth",
		  dataType: 'json',
		  method: "GET",
		})
		.then( function(oData) {
		  accesscode = oData;
		  runUpdate(accesscode);
		})
	}
	
	function getToken()
	{
		// Use your own token (this is just an example)
		var token;

		$.ajax({
		  url: spotifyagenturl21 + "?op=token",
		  dataType: 'json',
		  method: "GET",
		})
		.then( function(oData) {
		  getSongsAll(oData);
		})
	}
	
	function runUpdate(accesscode)
	{
		$.ajax({
		  url: spotifyagenturl + "?code=" + accesscode,
		  dataType: 'json',
		  method: "GET",
		})
		.then( function(oData) {
		  alert(oData);
		})
	}
	
	function getSongsAll(mytoken)
	{
		$.ajax({
		  url: spotifyagenturl21 + "?op=getList&token=" + mytoken,
		  dataType: 'json',
		  method: "GET",
		})
		.then( function(oData) {
		  console.log(oData);
		})
	}

});