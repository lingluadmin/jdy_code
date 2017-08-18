(function($){
	var numpic = $('#slides li').size()-1;
	var nownow = 0;
	var inout = 0;
	var TT = 0;
	var SPEED = 7000;
	var stopscroll = true;
	var btnPrev = $('#slides_prev');
	var btnNext = $('#slides_next');

	$('#slides li').eq(0).siblings('li').css({'display':'none'});


	var ulstart = '<ul id="pagination">',
		ulcontent = '',
		ulend = '</ul>';
	ADDLI();
	var pagination = $('#pagination li');
	var paginationwidth = $('#pagination').width();
	$('#pagination').css('margin-left',(-paginationwidth/2))
	
	pagination.eq(0).addClass('current')
		
	function ADDLI(){
		//var lilicount = numpic + 1;
		for(var i = 0; i <= numpic; i++){
			ulcontent += '<li>' + '<a href="#">' + (i+1) + '</a>' + '</li>';
		}
		
		$('#slides').after(ulstart + ulcontent + ulend);	
	}

	pagination.on('click',DOTCHANGE)
	
	function DOTCHANGE(){
		
		var changenow = $(this).index();
		
		$('#slides li').eq(nownow).css('z-index','800');
		$('#slides li').eq(changenow).css({'z-index':'900'}).show();
		pagination.eq(changenow).addClass('current').siblings('li').removeClass('current');
		$('#slides li').eq(nownow).fadeOut(400,function(){$('#slides li').eq(changenow).fadeIn(500);});
		nownow = changenow;
	}
	
	pagination.mouseenter(function(){
		inout = 1;
	})
	
	pagination.mouseleave(function(){
		inout = 0;
	})
	

	btnPrev.click(function() {

		nownow = nownow%(numpic+1);
		var NN = nownow%(numpic+1)-1;
		$('#slides li').eq(nownow).css('z-index','800');
		$('#slides li').eq(NN).stop(true,true).css({'z-index':'900'}).show();
		$('#slides li').eq(nownow).fadeOut(400,function(){$('#slides li').eq(NN).fadeIn(500);});
		pagination.eq(NN).addClass('current').siblings('li').removeClass('current');

		nownow-=1;


	});
	btnNext.click(function() {
		auto();

	});

	function GOGO(){
		
		auto();


		TT = setTimeout(GOGO, SPEED);
		
	}
	function auto(){
		var NN = nownow+1;

		if( inout == 1 ){
			//Do nothing
		} else {
			if(nownow < numpic){
				$('#slides li').eq(nownow).css('z-index','800');
				$('#slides li').eq(NN).css({'z-index':'900'}).show();
				pagination.eq(NN).addClass('current').siblings('li').removeClass('current');
				$('#slides li').eq(nownow).fadeOut(400,function(){$('#slides li').eq(NN).fadeIn(500);});
				nownow += 1;

			}else{
				NN = 0;
				$('#slides li').eq(nownow).css('z-index','800');
				$('#slides li').eq(NN).stop(true,true).css({'z-index':'900'}).show();
				$('#slides li').eq(nownow).fadeOut(400,function(){$('#slides li').eq(0).fadeIn(500);});
				pagination.eq(NN).addClass('current').siblings('li').removeClass('current');

				nownow=0;

			}
		}

	}
	if(stopscroll){
		$('#slides a').hover(function(){
			inout = 1;
		},function(){
			inout = 0;
		});
	}
	
	TT = setTimeout(GOGO, SPEED);



})(jQuery)