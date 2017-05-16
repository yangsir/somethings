$(function() {
	var wWidth = $(window).width();
	var dWidth = $('.down').width();
	$('.down').css('left',(wWidth-dWidth)/2)
	$('body').click(function(){
		$('.down').hide();
	});
	$('.pull').click(function(e){
		e.stopPropagation();
		if($('.down').is(':hidden')){
			$(this).find('.down').show();
		}else{
			$(this).find('.down').hide();
		}
	});
	
	var wWidth = $('body').width();
	$('.item').width(wWidth)
	var $pointer = $('.pointer span');
	var flipsnap = Flipsnap('.flipsnap', {
		distance: wWidth
	});
	
	flipsnap.element.addEventListener('fspointmove', function() {
		$pointer.filter('.current').removeClass('current');
		$pointer.eq(flipsnap.currentPoint).addClass('current');
	}, false);
	//$('.cdiv dl:even').css('border-right','#ddd 1px solid')
	var pWidth = $('.pointer').width();
	$('.pointer').css('left',(320-pWidth)/2);
	
	
});
