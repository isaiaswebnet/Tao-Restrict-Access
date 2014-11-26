jQuery(document).ready(function($){
	$('.user_loggin .loggin').on('click', function(){
	    $('.return').html('Autenticando sua solicitação!');
		$.ajax({
		type      : 'post',
		url       : ajax_loggin_tao.loggin,
		data      : $(".user_loggin").serialize(),
		dataType  : 'html',
		success	  : function(return_loggin) {
			if(return_loggin == "auth"){
				location.href = ajax_loggin_tao.url;
			} else {
				$('.return').html(return_loggin);
			}
		}
		});
    });
});