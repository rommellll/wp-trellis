$(document).ready(function() {
	// Cross Sell Slides Settings
    $('.cross-sell-slides').owlCarousel({
        loop:false,
        margin:10,
        nav:true,
        navText: ['<i class="las la-angle-left" style="font-size:18px;"></i>','<i class="las la-angle-right" style="font-size: 18px;"></i>'],
        dots: false,
        responsive:{
            0:{
                items:3
            },
            600:{
                items:4
            },
            1000:{
                items:5
            }
        }
    });
    
    $('#filter-trigger-mobile, #filter-trigger-desktop .wopb-filter-title-section').click(function(){
    console.log('trigger');
    	$('#filter-trigger-desktop').toggleClass('active');
    });
});