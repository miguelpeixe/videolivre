jQuery(document).ready(function() {
	var fileInput;

	jQuery('.upload_file_button').live('click', function() {
		fileInput = jQuery(this).prev('input');
		post_id = jQuery('#post_ID').val();
		tb_show('', 'media-upload.php?post_id='+post_id+'&type=file&TB_iframe=true');
		return false;
	});

	// user inserts file into post. only run custom if user started process using the above process
	// window.send_to_editor(html) is how wp would normally handle the received data

	window.original_send_to_editor = window.send_to_editor;
	window.send_to_editor = function(html){

		if (fileInput) {
			fileurl = jQuery(html).attr('href');

			fileInput.val(fileurl);

			tb_remove();

		} else {
			window.original_send_to_editor(html);
		}
	};
	
});