var $attachmentsContainer;
var attachmentsLength;

(function($) {

	$(document).ready(function() {

		$attachmentsContainer = $('#attachments_metabox .attachments_container');

		$attachmentsContainer.find('.model').hide();

		attachmentsLength = $attachmentsContainer.find('.file-item').length;

		$('.add-new-file').live('click', function() {
			addAttachment();
			return false;
		});

		$('.remove-file').live('click', function() {
			var $item = $(this).parents('.file-item');
			removeAttachment($item);
			return false;
		})

	});

	function addAttachment() {

		var $item = $attachmentsContainer.find('.model').clone();

		$item.find('.file_url input').attr('name', 'video_attachments[' + attachmentsLength + '][url]');
		$item.find('.file_name input').attr('name', 'video_attachments[' + attachmentsLength + '][name]');
		$item.find('.file_description textarea').attr('name', 'video_attachments[' + attachmentsLength + '][description]');

		$item.removeClass('model').show().appendTo($attachmentsContainer);

		attachmentsLength++;

	}

	function removeAttachment($item) {
		$item.remove();
	}

})(jQuery);