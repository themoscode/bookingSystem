
function lightbox(){
            var self=this;
            ///////////////////////////
            $(window).on('scroll', function(){
                   position();
              });
            $(window).on('resize', function(){
                   position();
              });
            
			position = function(){
                var stepy=30;
                if ($(window).height() <=320  ) {
                   // stepy=60;
                }
                if ($(window).height() >=800  ) {
                    //stepy=180;
                }
                if ($(window).height() >=1024  ) {
                   // stepy=260;
                }
                var posTop = $(window).scrollTop();                       
                $('#lightbox').css('margin-top', posTop + stepy + 'px'); 
                $('#lightbox_overlay').css('height', $(window).height()+ posTop + 'px');
            }
            ///////////////////////////
            
            
            closeit = function(){
               
                $('#lightbox').fadeTo(100, 0, function(){
                        $(this).remove();
                        $('#lightbox_overlay').fadeTo(250, 0, function(){
                                $(this).remove();
                        });
                }); 
            }
            
           
			this.open = function(html){
				$('body').append('<div id="lightbox_overlay"></div>')
						 .append('<div id="lightbox"></br>\n\
						 <div id = "lightbox_body" >'+html+'</div> </div>');
						 
				position();
				  
				$('#lightbox_overlay').fadeTo(500, 0.75, function(){
						$('#lightbox').fadeTo(250, 1);
				});
			
				$('#lightbox_overlay,#lightbox_btn_close').on('click', function(){
                   closeit(); 
				   myRoute.getRouteList();
				   myTimeTable.getTimeTableList();
				   myPassengerList.initResults();
				   //console.log('close');
               });	
			}
        }
    
   