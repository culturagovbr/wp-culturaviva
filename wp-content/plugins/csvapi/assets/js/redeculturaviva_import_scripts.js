/**
 * 
 */

jQuery(document).ready(function()
{
	jQuery("#importcsv").click(function(event)
	{
		event.preventDefault();
		
		var form = new FormData(jQuery('#importcsv-form')[0]);
		form.append('action', 'ImportarCsv');
		
        jQuery.ajax(
        {
            type: 'POST',
            url: redeculturaviva_import_scripts_object.ajax_url,
            data: form,
            processData: false,
            contentType: false,
            success: function(response)
            {
            	jQuery('#result').append(response);
            },
        });
	});
	
	/*jQuery('#media-importcsv-filename').change( function(event) {
		datafiles = new FormData();
		datafiles.append('media-importcsv-filename', event.target.files[0]);
	});*/
	
});