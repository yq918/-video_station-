/**
 * [登录注册JS--zxr]
 * @param  {[type]} event) {	              var email [description]
 * @return {[type]}        [description]
 */
 var send = 0; 
 var urlList = {
 	'get_form_key':'/user/getfromkey',
 	'post_from_user':'/user/signup',
 	'post_from_logout':'/user/logout',
 	'post_from_login' : '/user/login',
 	'get_commit_list' : '/comment/getcommentlist'
 }

 //注册
$(".subsignup").click(function(event) { 
	/* Act on the event */
    var email = $(".e-mail").val(),
        phone = $(".phone").val(),
        pwd   = $(".pwd").val(),
        formKey = $(".login_formKey").val(),
        nickname  = $(".nickname").val(); 
	    if(email == '' || phone== '' || pwd == '' || formKey == ''){
             alert('数据为空');
             return false;
	    }  
	    if(email.length< 5 || phone.length <5 || pwd.length<5){
	             alert('格式不正确');
	             return false;
		 }  
	    var params = {
	    	'email':email,
	    	'phone' :phone,
	    	'pwd':pwd,
	    	'formKey':formKey,
	    	'nickname' : nickname
	    }
    var data =  ajaxpost(urlList.post_from_user,params); 
    if(data.code == 'success'){
           alert('注册成功');
            window.location.reload(); 
    }else{
         alert(data.msg);
    } 
    
});


//注册,获取表单KEY
$(".sigin_up_button,.sigin_in_button").click(function(event) { 
   var ajaxdata = ajaxpost(urlList.get_form_key,{});
   if(ajaxdata.code == 'success' && typeof(ajaxdata.data.key) =='string') {
   	 $(".login_formKey").val(ajaxdata.data.key);
   } 
});


//退出按钮
$(".logout_button").click(function(){ 
    var ajaxret = ajaxpost(urlList.post_from_logout,{});
    if(ajaxret.code == 'success'){
            alert('我会在这里一直等你哟！');
            window.location.reload(); 
    }else{
         alert(ajaxret.msg);
    } 
});


//登录事件
$(".signin_login").click(function(event) {
	 var username = $(".username").val(),
	     passwd   = $(".passwd").val(),
	     token    = $(".login_formKey").val();
      if(username == '' || passwd == '' || token == ''){
        	alert('请填写帐号和密码');
            return false;
       }
       if(username.length <5 || passwd < 5){
       	  alert('请填写正确的帐号和密码');
            return false;
       } 
       var params = {
	    	'username':username,
	    	'passwd'  :passwd, 
	    	'token':token 
	    }
    var data =  ajaxpost(urlList.post_from_login,params);  
     if(data.code == 'success'){
            alert('欢迎,欢迎');
            window.location.reload(); 
    }else{
         alert(ajaxret.msg);
    }  

});





 /**
  * [getcommitList 获取评论列表]
  * @return {[type]} [description]
  */
function getcommitList()
{ 
	var  type = $(".hidtype").val();
	var  id   = $(".hidid").val();   
    var  url = urlList.get_commit_list+'/?id='+id+"&type="+type;
    $.get(url, function(data) {
    	var template_html = '';
    	var commitList = data.data;
    	$(commitList).each(function(index, el) {
    		template_html+= `<div class="media">
								<h5>${el.nickname}</h5> 
								<div class="media-body">
									<p>${el.comment}</p> 
									<span style="display:none">I've been waiting for you</span>
								</div>
							</div>`; 
    	});  
       $(".media-grids").append(template_html); 
       $(".commentAll").text(commitList.length); 
    }); 
}



function ajaxpost(url,params)
{ 
	var retData = null;
  if(send == 1){
	   alert('正在提交中,请稍后....');
	   return false;
   }  
   $.ajax({
     	url: url,
     	type: 'POST',
     	dataType: 'json',
     	async: false,
     	data: params,
     	beforeSend:function(){
     		send = 1;
     	}
   })
     .done(function(data) { 
     	send = 0;
     	retData =  data; 
     })
     .fail(function(data) {  
     	send = 0;
     	retData =  data;
     })
     .always(function(data) { 
     	send = 0;
     	retData =  data;
     });  
     return retData;
 };

 