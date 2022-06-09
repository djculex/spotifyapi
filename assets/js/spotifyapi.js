/*!     spotifyapi.js for Xoops SpotifyApi
 *
 *      (c) 2022 Michael Albertsen
 *	    Website: https://culex.dk
 *		profile: https://github.com/djculex
 *	    Docs: https://github.com/djculex/spotifyapi#readme
 *	    Repo: https://github.com/djculex/spotifyapi
 *		Issues: https://github.com/djculex/spotifyapi/issues
 */

$(document).ready(function(){
	jQuery.support.cors = true;
	var spotifyapicharttype = CheckCheckbox();
	$('#startingDate').prepend($('<option>',
	// Append black text and value -1 to dropdown
	 {
		value: -1,
		text : ''
	}));
	
	selectID('#startingDate'); // Choose first visible option in end date
	
	$('#charttype').change(function(){
		spotifyapicharttype = CheckCheckbox();
	});
	
	$("#startingDate").change(function () {
		// when start date option is changed
		
		var selectedStart = $(this).children("option:selected").text();	// get value of selected start date
		startchange(selectedStart);
	});
	
	$('#endDate').change(function () {
			// On selected end date redirect to url with parameters
			window.location = spotifyarcpath + "?year=" + $("#startingDate option:selected").text() + "&week=" + $("#endDate option:selected").text() + "&type=" + spotifyapicharttype;
		}); // end change
	

	
	/*
	 * Function to iterate selectors not hidden and select first as selected
	 * @param id the id to search for
	 * @return false
	*/
	function selectID (id) {
		$(id + ' option').each(function () {
			// iterate only visible options choosing first as selected
			if ($(this).css('display') != 'none') {
				$(this).prop("selected", true);
				return false;
			}
		});
	} // end
	
	/*
	 *
	 *
	 */
	function startchange(year)
    {
        $.ajax({
            type: 'post',
            data: {'year': year},
            url: 'year.php',
            dataType: 'json',
            success: function(res){
				var $select = $('#endDate'); 
				$select.find('option').remove();  
				$.each(res,function(key, value) 
				{
					$select.append('<option value=' + key + '>' + value + '</option>');
				});
				$('#endDate').prepend('<option value="-1" selected="selected"></option>');
            },
            error: function(res){
            }
        });
    }
	
	// function to get chart type based on checked/unchecked check box
	function CheckCheckbox()
	{
		var ret = '';
		if( $('#charttype').is(':checked') ){
			ret = 'accumulated';
		}
		else{
			ret = 'classic';
		}
		return ret;
	}
	
}); // end document ready