
 function handlePincodeInput(type) {
        var pincode = $('input[name="' + type + '_pincode"]').val();
        
       $.ajax({
			//url: '/fetch-state-district',
		   	url: fetchStateDistrictUrl,
			type: 'GET',
			data: { pincode: pincode },
		  
			/*xhrFields: {
				withCredentials: true
			},*/
			
			success: function(response) {
				if (response.error) {
					$('.pinerror[data-type="' + type + '"]').text(response.error).show();
					$('input[name="' + type + '_state"]').val("");
					$('input[name="' + type + '_district"]').val("");
				} else {
					$('input[name="' + type + '_state"]').val(response.state);
					$('input[name="' + type + '_district"]').val(response.district);
					$('.pinerror[data-type="' + type + '"]').hide();
				}
			},
			error: function(xhr, status, error) {
				console.log(xhr.responseText);
				
			}
		});

    }

    