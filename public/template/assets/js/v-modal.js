$(document).ready(function() {
    $.wmBox = function(){
		$('body').prepend( '<div class="wmBox_overlay draggable-area">'+
								'<div class="wmBox_centerWrap">'+
									'<div class="wmBox_centerer">'+
										'<div class="wmBox_contentWrap" id="dragit">'+
											'<div class="wmBox_scaleWrap">');
												// '<div class="wmBox_closeBtn">'+  
												// 	'<p><em class="icon ni ni-cross"></em></p>'+
												// '</div>');
	};  

});

let vModal = function(link,size=null){
		var draggable = $('#dragit'); //element
		draggable.mouseover(function(e){
			$(this).css("cursor","move");	
		});  
		draggable.mousedown(function(e){
			var dr = $(this).addClass("drag").css("cursor","move");
			height = dr.outerHeight();
			width = dr.outerWidth();
			max_left = dr.parent().offset().left + dr.parent().width() - dr.width();
			max_top = dr.parent().offset().top + dr.parent().height() - dr.height();
			min_left = dr.parent().offset().left;
			min_top = dr.parent().offset().top;

			ypos = dr.offset().top + height - e.pageY,
			xpos = dr.offset().left + width - e.pageX;
			$(document.body).mousemove(function(e){
				var itop = e.pageY + ypos - height;
				var ileft = e.pageX + xpos - width;
				
				if(dr.hasClass("drag")){
					if(itop <= min_top ) { itop = min_top; }
					if(ileft <= min_left ) { ileft = min_left; }
					if(itop >= max_top ) { itop = max_top; }
					if(ileft >= max_left ) { ileft = max_left; }
					dr.offset({ top: itop,left: ileft});
				}
			}).mouseup(function(e){
					dr.removeClass("drag");
			});
		});

        $('.wmBox_overlay').fadeIn(750);
		var mySrc = link; 
		$('.wmBox_overlay .wmBox_scaleWrap').append('<iframe src="'+mySrc+'">');
		
		if(size != null){
			$(".wmBox_contentWrap").addClass(size); 
		}

		$('.wmBox_overlay iframe').click(function(e){
			e.stopPropagation();
		});
		
		$('.wmBox_overlay').click(function(e){
			e.preventDefault();
			var container = $("#dragit");
		    // if the target of the click isn't the container nor a descendant of the container
		    if (!container.is(e.target) && container.has(e.target).length === 0) 
		    {
		        $('.wmBox_overlay').fadeOut(750, function(){
					$(this).find('iframe').remove();
				});
		    }
			
		});
    }
    
