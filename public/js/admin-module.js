var adminModule = {};
console.log("Init this here");
(function(context) {
	context.template = false,
	context.init = function(){
		$('#save-module').click(function(){
            console.log("Click here");
			$('#admin-module-form').submit();
			return false;
			// todo: laravel based ajax validation similar to what's done on settings module
			// $.ajax({
			//	type: "POST",
			//	url: $('#admin-module-form').attr('action'),
			//	data: $('#admin-settings-form').serialize(),
			//	success: function(response) {
			//		if(response.status==='success') {
			//			context.showReport(response.data.report);
			//		} else if(response.status==='error') {
			//			$.pnotify({
			//				title: 'Error',
			//				type: 'info',
			//				text: response.message
			//			});
			//		}
			//	}
			// });
		});
		$('.image-cell').on({
            mouseenter: function(){
				context.galleryImgEnter($(this));
            },
            mouseleave: function(){
				context.galleryImgLeave($(this));
            }
        });
        $(document).on('click', '.menu-delete', function () {
            $('.menu-cell-wrap, .hover-menu p').fadeOut(100, function () {
                $('.confirm-delete').fadeIn(100);
                $('#del-cancel').click(function () {
                    $('.confirm-delete').fadeOut(100, function () {
                        $('.menu-cell-wrap, .hover-menu p').fadeIn(100);
                    });
                });
            });
            return false;
        });
        $(document).on('click', '#del-confirm', function () {
			var parent = $(this).parents('.image-cell');
			$.ajax({
				url: $('#admin-module-form').attr('action'),
				type: 'DELETE',
				data: {
					'column_name' : $(parent).data('column-name'),
					'id' : $(parent).data('entry-id'),
				},
				success: function(result) {
					$('#image-cell-image').fadeOut(300, function () {
						$(this).remove();
					});
				}
			});
			return false;
        });
        $(document).on('click', '#delete-module', function () {
        	$('#admin-module-form').append('<input type="hidden" name="_method" id="_method" value="DELETE" />').submit();
			return false;
        });
		$('#commit-module').click(function(){
			// Set the button to loading state (Twitter Bootstrap feature)
			$(this).button('loading');
			// Hide the cancel button
			$('#cancel-commit').hide();
			// Submit the form
			$('#admin-settings-form').submit();
			return false;
		});
		$('#cancel-commit').click(function(){
			context.cancelCommit();
			$('#remove_request').val(0);
			return false;
		});
	},
	context.galleryImgEnter = function($this){
        if($('.hover-menu', this).length) return;
        var $hoverTemplateInstance = $('.gallery-hover-template').clone();
        var imgCaption;
        if ($.hasData($this[0])){
            imgCaption = $this.find('img').attr('alt');
            if (imgCaption != "") $('p', $hoverTemplateInstance).html(imgCaption);
        }
        $this.append($hoverTemplateInstance.html());
        $(".hover-menu", $this).animate({
            opacity: 1
        }, 200,  function () {
            $(".hover-menu", $this).css('opacity',1); // IE FIX
            $('.eye-cell').on('click', function () {
                $('.lightbox-content img').attr('src', $('.user-image', $this).attr('src'))
                $('.lightbox-caption p').text(imgCaption);
                $('#galleryLightbox').lightbox();
            });
        });
    },
    context.galleryImgLeave = function($this){
		$(".hover-menu", $this).animate({
			opacity: 0
		}, 200, function () {
			$(".hover-menu", $this).remove();
		});
    },
	context.showReport = function(report){
		$('.reportContainer').html(report);
		$('#report').show();
		$('#form-controls').hide();
		$('#module-fields').hide();
		$('#module-settings').hide();
	},
	context.cancelCommit = function(){
		$('.reportContainer').empty();
		$('#report').hide();
		$('#form-controls').show();
		$('#module-fields').show();
		$('#module-settings').show();
	};
})(adminModule);

$().ready(function(){
	adminModule.init();
});
