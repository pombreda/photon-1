// FORM CONTROLS
$().ready(function() {
	$('.form-control-reset').click(function(){
		var $parent = $(this).parents('.form-control');
	    $('.form-control-reset', $parent).fadeOut(150, function () {
            $('.confirm-reset-wrapper', $parent).show();
        });
	});

	$('.form-control-cancel').click(function(){
		var $parent = $(this).parents('.form-control');
	    $('.confirm-reset-wrapper', $parent).fadeOut(150, function () {
		    $('.form-control-reset', $parent).show();
	    });
	});
});