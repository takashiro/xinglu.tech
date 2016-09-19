$(function(){
	$('#register-button').click(function(e){
		var password_input = $('#password');
		var password2_input = $('#password2');
		if(!password2_input.val()){
			var password = password_input.val();
			password_input.val('');
			password2_input.val(password);

			e.preventDefault();
			makeToast('请再次输入密码以确认。');
			password_input.focus();
		}else if(password_input.val() != password2_input.val()){
			password_input.val('');
			password2_input.val('');

			e.preventDefault();
			makeToast('您两次输入的密码不一致，请重新输入。');
			password_input.focus();
		}

		$('#login-form').attr('action', 'index.php?mod=user:register');
	});

	$('#login-button').click(function(){
		$('#login-form').attr('action', 'index.php?mod=user:login');
	});
});