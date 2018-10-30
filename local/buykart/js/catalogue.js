$(document).on('ready', function(){

	// Auto submit filter forms when selection changes 
	$('.filter-bar select').on('change', function(){
		$('.filter-bar').submit();
	});

	// Update variable product information
	$('.product-form .product-tier').on('change', function(){
		var id = $(this).val();
		var parent = $(this).parents('.product-item');

		// Update price
		var newPrice = $('.product-price', parent).attr('data-tier-' + id);
		$('.product-price .amount', parent).text(newPrice);

		// Update course duration
		var newDuration = $('.product-duration', parent).attr('data-tier-' + id);
		$('.product-duration', parent).text(newDuration);
	});
	

	$('#list').click(function(event){
		event.preventDefault();
		$('#productslist .item').removeClass('startpage col-lg-4 grid-group-item');
		$('#productslist .item').addClass('col-lg-12 list-group-item');
		$('#products .card').addClass('list-view-card');
		$('#products .img').addClass('img-product');
		$('#products .card-body').addClass('card-body-list');
		/*$('#products .image').removeClass('image1');
		$('#products .card-body').removeClass('card-body1');*/
	});
    	$('#grid').click(function(event){
		event.preventDefault();
		$('#productslist .item').removeClass('startpage col-lg-12 list-group-item');
		$('#productslist .item').addClass('col-lg-4 grid-group-item');
		$('#products .card').removeClass('list-view-card');
		$('#products .img').removeClass('img-product');
		$('#products .card-body').removeClass('card-body-list');
		/*$('#products .image').removeClass('image1');
		$('#products .card-body').removeClass('card-body1');*/
	});

});
