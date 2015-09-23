jQuery(document).ready(function(){
	jQuery(".slides").cycle({
	speed: 600,
    manualSpeed: 100,
	slides: ".cycles-slide",
    
    carouselFluid: true,
    next:".cycle-next",
    prev:".cycle-prev"    
	});
});