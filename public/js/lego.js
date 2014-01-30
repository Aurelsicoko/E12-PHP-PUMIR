$('.index a').on('click',function(e){
	e.preventDefault();
	$.ajax({
		url:$(this).attr('href')
	})
	.success(function(data){
		$('.users').html(data);
	});
});
$('.users').on('click','a',function(e){
	e.preventDefault();
	$.ajax({
		url:$(this).attr('href')
	})
	.success(function(data){
		$('section + section').html(data);
	});
});
$('input[name="name"]').on('keyup',function(){
	var $form=$(this).parent('form');
	$.ajax({
		url:$form.attr('action'),
		method:$form.attr('method'),
		data:$form.serialize()
	})
	.success(function(data){
		$('.users').html(data);
	})
});












