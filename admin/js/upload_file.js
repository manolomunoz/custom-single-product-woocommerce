(function($)

{
	jQuery(document).ready(function($){
 
 
		var custom_uploader;
	   
	   
		$('#cspw_upload_image_button').click(function(e) {
	   
		    e.preventDefault();
	   
		    //If the uploader object has already been created, reopen the dialog
		    if (custom_uploader) {
			  custom_uploader.open();
			  return;
		    }
	   
		    //Extend the wp.media object
		    custom_uploader = wp.media.frames.file_frame = wp.media({
			  title: 'Cambiar imagen',
			  button: {
				text: 'Cambiar imagen'
			  },
			  multiple: false
		    });
	   
		    //When a file is selected, grab the URL and set it as the text field's value
		    custom_uploader.on('select', function() {
			  attachment = custom_uploader.state().get('selection').first().toJSON();
			  $('#cspw_custom_logo_add_cart_button').attr('value', attachment.url);
		    });
	   
		    //Open the uploader dialog
		    custom_uploader.open();
	   
		});
		$('#cspw_upload_image_button_products').click(function(e) {
	   
			e.preventDefault();
	     
			//If the uploader object has already been created, reopen the dialog
			if (custom_uploader) {
			    custom_uploader.open();
			    return;
			}
	     
			//Extend the wp.media object
			custom_uploader = wp.media.frames.file_frame = wp.media({
			    title: 'Cambiar imagen',
			    button: {
				  text: 'Cambiar imagen'
			    },
			    multiple: false
			});
	     
			//When a file is selected, grab the URL and set it as the text field's value
			custom_uploader.on('select', function() {
			    attachment = custom_uploader.state().get('selection').first().toJSON();
			    $('#cspw_custom_products_logo_add_cart_button').attr('value', attachment.url);
			});
	     
			//Open the uploader dialog
			custom_uploader.open();
	     
		  });
	   
	   
	  });

})( jQuery );