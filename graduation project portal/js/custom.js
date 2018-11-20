/*global $*/

$("document").ready(function() {
    'use strict'; 

	var path = window.location.pathname;
  
	if(path == '/management_of_graduation_projects/supervisor/follow_up_weekly_work_of_group_edit.php'){
 if ( !$("select.form-control option.defult").is(":selected")) {

    $("#supInboxMessages").css({"border":"5px solid #0000FF"});
 }
 else{
 	
    $("#supInboxMessages").css({"border":"none"});
 }


	}

    /*send_your_weekly_project_work.php*/
    /*
var path = window.location.pathname;
  

	if(path == '/management_of_graduation_projects/std/send_your_weekly_project_work.php?action=add'){
 var l = document.getElementById('label'),
    c = document.getElementById('check_box_if_this_file_thesis');
   if(l&&c){
	l.onclick = ()=>{
  c.click()
} 
   }

}
 
 */  

/* join_into_another_grp.php*/
function viewMessage(id){
    alert(id);

} 
$(function() {
  $(".accordion .header").on("click", function() {
    $(this)
      .siblings()
      .toggleClass("hidden");
    $(this)
      .find("h3")
      .toggleClass("close open");
  });
  
 //$('.input-group.date').datepicker({format: "yyyy-mm-dd"}); 


});

$('#hd_name').val();
/**/
/*
$('#examiner_grp').on('change',function(){
	var selected_grp = $(this).val(); 
	$.ajax({
	url: "get_grp_member_for_this_grp.php",
	method: "POST",
	
	data: {selected_grp1:selected_grp},
	success:function(data){
		alert(data)
		//print submit using this page : set_submit_input_value_depend_on_examiner_and_grps_status.php
		$('#grp_member').html(data);
	},
		
error: function(data){
console.log('---------error');
console.log(data);
console.log(data.responseText);

$('#get_input_type_submit_depend_on_comm_disc_and_grp_status').text(data.responseText);
 

}
});

event.preventDefault();
});
*/
/**/
  var currentLocation = window.location.pathname;
 var currentPage = currentLocation.substring(currentLocation.lastIndexOf('/') + 1);
 
 $("a[href='"+currentPage+"'").parent().parent().addClass("active");
 
 $("#first_year").on("input", function() {
  var first_year=+$(this).val();
  /*var num = +$("#originalnum").val() + 1;
$("#originalnum").val(num);*/
if(first_year!=""){
   var inc_first_year=first_year+1; 
   $('#second_year').val(inc_first_year);
}
else{
    $('#second_year').val("");
}

 

});
$("#user_id").on("input", function() {
  var user_id=+$(this).val();
  /*var num = +$("#originalnum").val() + 1;
$("#originalnum").val(num);*/
if(user_id.length!=0 && user_id!=""){
     
   $('#user_pwd').val(user_id);
}
else{
    $('#user_pwd').val("");
}

 

});
$('#group_and_related_member_for_group_of_supervisor_msg').on('change',function(){
var loginSupervID = $('#loginSupervID').val();
var selected_grp
 = $('#group_and_related_member_for_group_of_supervisor_msg').val();
/**/
 $.ajax({
	url: "getMotherMsgToFixedGrpForSupInbox.php",
	method: "POST",
	data: {selected_grp:selected_grp,loginSupervID:loginSupervID},
	success:function(data){
		$('#supInboxMessages').html(data);
	},
		
error: function(data){
console.log('---------error');
console.log(data);
console.log(data.responseText);

$('#response').text(data.responseText);
 

}
});
/**/

});

 /*Start hide/appear pass*/
$('.eyes-cont i').on('click',function(){
  $(this).addClass('hide').siblings().removeClass('hide');
 
});

//appear and hide pass
$('#open').click(function(){

  $('#user_pwd').attr("type","text");
   $(this).hide();
   $('#close').show();
}) ;
$('#close').click(function(){

   $('#user_pwd').attr("type","password");
   $(this).hide();
   $('#open').show();
});
/*End hide/appear pass*/
 
 function preventNumberInput(e){
    var keyCode = (e.keyCode ? e.keyCode : e.which);
    if (keyCode > 47 && keyCode < 58 || keyCode > 95 && keyCode < 107 ){
        e.preventDefault();
    }
}
  $('#user_name').on('keypress',function(e) {
        preventNumberInput(e);
    });
 
 
  $("select.form-control").on("click", function() {
	  //input.btn.btn-success.text-center.submit
        if ( !$("select.form-control option.defult").is(":selected")) {
            $(".submit").prop("disabled", false);
			
            $(".submit").addClass("active-buttn");
        } else {
            $(".submit").prop("disabled", true);
            $(".submit").removeClass("active-buttn");
        }
    });
 
   $("select.form-control").on("select", function() {
        if ( !$("select.form-control option.defult").is(":selected")) {
            $(".submit").prop("disabled", false);
            $(".submit").addClass("active-buttn");
        } else {
            $(".submit").prop("disabled", true);
            $(".submit").removeClass("active-buttn");
        }
    });
 
	  
/*login*/
function preventNumberInput(e){
    var keyCode = (e.keyCode ? e.keyCode : e.which);
    if (keyCode > 47 && keyCode < 58 || keyCode > 95 && keyCode < 107 ){
        e.preventDefault();
    }
}
  $('#text_field').keypress(function(e) {
        preventNumberInput(e);
    });
     

//appear and hide pass
$('#open').click(function(){

  $('#usr_pwd').attr("type","text");
   $(this).hide(); 
  $('#close').removeClass('hide');
   $('#close').show();
}) ;
$('#close').click(function(){

   $('#usr_pwd').attr("type","password");
   $(this).hide();
   $('#close').addClass('hide');
   $('#open').show();
});
    //start a lnk active after click
    
     /*$(this).find('a').attr('name');*/
 $('ul#menu.nav.navbar-nav.navbar-right li,div.fixed-menu.is-visible ul#fixed_menu_1 li a').on('click',function() {
        $(this).addClass('active').parent().siblings().find('a').removeClass('active');
 
    });
  
$('ul#menu.nav.navbar-nav.navbar-right li,div.fixed-menu.is-visible ul#fixed_menu_1 li a').on('hover',function() {
        $(this).addClass('active').parent().siblings().find('a').removeClass('active');
    });

     $('.navbar-default .navbar-nav>li,div.fixed-menu.is-visible ul#fixed_menu_1 li a').on('click',function() {
        $(this).addClass('active').siblings().removeClass('active');

    });
  
    //end a lnk active after click
     
    // Hide Placeholder On Form Focus

    $('[placeholder]').focus(function () {

        $(this).attr('data-text', $(this).attr('placeholder'));

        $(this).attr('placeholder', '');

    }).blur(function () {

        $(this).attr('placeholder', $(this).attr('data-text'));

    }); 
 $("input").not('input[type="file"]').prop('required',true);
 var input = $("#file");

function something_happens() {
    input.replaceWith(input.val('').clone(true));
};
$('#optradio3').on('click',function(){
  something_happens();
});
/**/
$('#fileInput').on('change',function(){
if ($(this).val()=="") 
{

	  $('#optradio3').prop('disabled', true);
	//  $('#optradio2').prop('disabled', false);
	 // $('#optradio1').prop('disabled', false);

}
else
{
	  $('#optradio3').prop('disabled', true);
	  $('#optradio2').prop('disabled', false);
	  $('#optradio1').prop('disabled', false);

}
});
/**/
$('#optradio3').on('click',function(){

	$('#fileInput').prop('required',false); 
	$("#fileInput").hide();

});

$('#optradio2,#optradio1').on('click',function(){

	$("#fileInput").show();
	$('#fileInput').prop('required',true); 

});
/**/

  //$("#check_box_if_this_file_thesis").prop('required',false);
/*var check_if_this_file_thesis = document.getElementById('check_if_this_file_thesis');

if (check_if_this_file_thesis) {
	check_if_this_file_thesis.required=false;
}
*/
 //fixed menu

$('#grps_and_their_members').on('change',function(){ 
	
var grp_id=	$(this).val();
 if (grp_id!=0) {
 $.ajax({
	url: "res_depend_on_grp_weekly_file_status.php",
	method: "POST",
	data: {grp_id:grp_id},
	success:function(data){
		$('#res_depend_on_grp_weekly_file_status').html(data);
	},
		
error: function(data){
console.log('---------error');
console.log(data);
console.log(data.responseText);

$('#response').text(data.responseText);
 

}
});	
 }
 else{
 	$('#res_depend_on_grp_weekly_file_status').html('<div class="row"  style="margin-top: 12px;" ><div class="col-sm-4"></div><div class="col-sm-4 alert alert-danger"><strong>يرجى الاختيار</strong></div><div class="col-sm-4 "></div></div>');
 }


event.preventDefault();
});

 /*new 21/4*/
/* $(document).on('submit','#send_chat_msg',function(event) { */
  
 
	/* new */
	function refresh_msg_content(){
	 
		//every chat room pass it's variable in file
		//$(".chatMessages").load('chatlog.php');
		var supervisor_login_id = $('#supervisor_login_id').val();
	var msg_chat = $('#chat_msg').val();
	
	var selected_grp = $('#chatting_grp').val();
 
	 $('.chatMessages').val('');
		$.ajax({
	url: "get_msg_for_selected_grp.php",
	method: "POST", 
	data: {selected_grp1:selected_grp,supervisor_login_id1:supervisor_login_id },
	success:function(data){
	 $('.chatMessages').html(data);
	// $('.std_chatMessages').html(data);
	 var objDiv = document.getElementsByClassName("chatMessages")[0];
	 objDiv.scrollTop = objDiv.scrollHeight;
 	},
		
error: function(data){
console.log('---------error');
console.log(data);
console.log(data.responseText);

$('#ajax_res').text(data.responseText);
 

}
});
		}
	function refresh_std_msg_content(){
	 
	var std_login_id = $('#std_login_id').val();
	var msg_chat = $('#std_chat_msg').val();
	
	var selected_grp = $('#std_chatting_grp_id').val();
  
		$.ajax({
	url: "get_msg_for_selected_grp.php",
	method: "POST", 
	data: {selected_grp1:selected_grp,supervisor_login_id1:std_login_id },
	success:function(data){
	// $('.chatMessages').html(data);
	 $('.std_chatMessages').html(data);
	 
var objDiv = document.getElementsByClassName("std_chatMessages")[0];
objDiv.scrollTop =  objDiv.scrollHeight;

 	},
		
error: function(data){
console.log('---------error');
console.log(data);
console.log(data.responseText);

$('#ajax_res').text(data.responseText);
 

}
});
		}
	/* new */ 

	var path = window.location.pathname;
  
	if(path == '/management_of_graduation_projects/supervisor/chat_room.php'){
 
		/**/
		setInterval(refresh_msg_content,100);
		
		function refresh(){
	 
		//every chat room pass it's variable in file
		//$(".chatMessages").load('chatlog.php');
		var supervisor_login_id = $('#supervisor_login_id').val();
	var msg_chat = $('#chat_msg').val();
	
	var selected_grp = $('#chatting_grp').val();
 
	 $('#chat_msg').val('');
		$.ajax({
	url: "get_msg_for_selected_grp.php",
	method: "POST", 
	data: {selected_grp1:selected_grp,supervisor_login_id1:supervisor_login_id },
	success:function(data){
	 $('.chatMessages').html(data);
	// $('.std_chatMessages').html(data);
 
var objDiv = document.getElementsByClassName("chatMessages")[0];
objDiv.scrollTop =  objDiv.scrollHeight;
 
	},
		
error: function(data){
console.log('---------error');
console.log(data);
console.log(data.responseText);

$('#ajax_res').text(data.responseText);
 

}
});
		}
		//setInterval(refresh, 100);
	
		$('#chatting_grp').on('change',function(){
			refresh();
	 var selected_grp = $(this).val();
	 if(selected_grp==0){
		  $('.chatMessages,#chat_msg,#send_chat_msg,.chatContainer').css('display','none');
		  $('#ajax_res').html('<div class="alert alert-danger">يرجى الاختيار</div>');
		
		 $('.chatContainer').css('border','none');
	 }
	 else{
	$.ajax({
	url: "get_grp_name.php",
	method: "POST",
	data: {selected_grp1:selected_grp},
	success:function(data){
		  $('.chatContainer').css('border','5px solid #0000FF');
	  	
		$('.chatBottom,.chatMessages,.chatContainer').css('display','block');
		$('#chat_msg,#send_chat_msg').css('display','inline-block');
		
		
		 $('#ajax_res').html('');
 	  $('.chatHeader').css('display','block');
	  	 $('#hd_name').css('display','block');
		$('#hd_name').html(data);
		//setInterval(refresh, 100);
	},
		
error: function(data){
console.log('---------error');
console.log(data);
console.log(data.responseText);
$('#response').text(data.responseText);
 

}
});
 }
event.preventDefault();
 	
});
$('#chatForm').on('submit',function(e){
	   e.preventDefault(); // prevent default form submit
    // sending ajax request through jQuery
	var chatting_grp = $('#chatting_grp').val();
	var chat_msg = $('#chat_msg').val();
	var supervisor_login_id = $('#supervisor_login_id').val();
	$.ajax({
	url: "insert_usr_msg_about_fixed_grp.php",
	method: "POST",
	
	data: {chatting_grp1:chatting_grp,chat_msg1:chat_msg,supervisor_login_id1:supervisor_login_id },
	success:function(data){
		//$('#all_sup_in_this_semester1').html(data);
		refresh();
	},
		
error: function(data){
console.log('---------error');
console.log(data);
console.log(data.responseText);

$('#ajax_res').text(data.responseText);
 

}
});
	
	
	
	
	return false;
});	
	}
	else if(path == '/management_of_graduation_projects/std/chat_room.php')
	 {
		 setInterval(refresh_std_msg_content,100);
		 
		   
		 var std_chatting_grp_id = $('#std_chatting_grp_id').val(); 
		 if(std_chatting_grp_id!=0){
			 /* start std chat */
			refresh();
			function refresh(){
	 
		//every chat room pass it's variable in file
		//$(".chatMessages").load('chatlog.php');
		var std_login_id = $('#std_login_id').val();
	var msg_chat = $('#std_chat_msg').val();
	
	var selected_grp = $('#std_chatting_grp_id').val();
 
	 $('#std_chat_msg').val('');
		$.ajax({
	url: "get_msg_for_selected_grp.php",
	method: "POST", 
	data: {selected_grp1:selected_grp,supervisor_login_id1:std_login_id },
	success:function(data){
	// $('.chatMessages').html(data);
	 $('.std_chatMessages').html(data);
	 /*
	 var objDiv = document.getElementsByClassName("chatMessages");
objDiv.scrollTop = objDiv.scrollHeight;*/
	 var objDiv = document.getElementsByClassName("std_chatMessages")[0];
objDiv.scrollTop = objDiv.scrollHeight;
	 	  /*
		$('.chatBottom,.chatMessages,.chatContainer').css('display','block');
		$('#chat_msg,#send_chat_msg').css('display','inline-block');
		 
 	  $('.chatHeader').css('display','block') ;*/
	},
		
error: function(data){
console.log('---------error');
console.log(data);
console.log(data.responseText);

$('#ajax_res').text(data.responseText);
 

}
});
		}
		//setInterval(refresh, 100);
 
$('#std_chatForm').on('submit',function(e){
	   e.preventDefault(); // prevent default form submit
    // sending ajax request through jQuery
	var chatting_grp = $('#std_chatting_grp_id').val();
	var chat_msg = $('#std_chat_msg').val();
	var supervisor_login_id = $('#std_login_id').val();
	$.ajax({
	url: "insert_usr_msg_about_fixed_grp.php",
	method: "POST",
	
	data: {chatting_grp1:chatting_grp,chat_msg1:chat_msg,supervisor_login_id1:supervisor_login_id },
	success:function(data){
		//$('#all_sup_in_this_semester1').html(data);
		refresh();
	},
		
error: function(data){
console.log('---------error');
console.log(data);
console.log(data.responseText);

$('#ajax_res').text(data.responseText);
 

}
});
return false;
});	
			 /* end std chat */
		 }
	 }
	/* chat_room.php*/

function refresh_grp_msg(){
			var std_login_id = $('#std_login_id').val();
	var msg_chat = $('#std_chat_msg').val();
	
	var selected_grp = $('#std_chatting_grp_id').val();
 
	 $('#std_chat_msg').val('');
		$.ajax({
	url: "get_msg_for_selected_grp.php",
	method: "POST", 
	data: {selected_grp1:selected_grp,supervisor_login_id1:std_login_id },
	success:function(data){
	 $('.chatMessages').html(data);
	 $('.std_chatMessages').html(data);
	 
	 	  /*
		$('.chatBottom,.chatMessages,.chatContainer').css('display','block');
		$('#chat_msg,#send_chat_msg').css('display','inline-block');
		 
 	  $('.chatHeader').css('display','block') ;*/
	},
		
error: function(data){
console.log('---------error');
console.log(data);
console.log(data.responseText);

$('#ajax_res').text(data.responseText);
 

}
});
	
}
 
 /*end 21/4*/
/* new update on send_request_btn_grps_and_examinar */
$('#groups_for_specific_sup').on('change',function(){
	var groups_for_specific_sup = $(this).val();
	$.ajax({
	url: "get_specific_examiner_except_sup_of_selected_grp.php",
	method: "POST",
	
	data: {selected_grp_id:groups_for_specific_sup},
	success:function(data){
		$('#all_sup_in_this_semester1').html(data);
	},
		
error: function(data){
console.log('---------error');
console.log(data);
console.log(data.responseText);

$('#get_input_type_submit_depend_on_comm_disc_and_grp_status').text(data.responseText);
 

}
});
});
$('#all_sup_in_this_semester1').on('change',function(){
	var selected_examiner = $(this).val();
	var selected_grp = $('#groups_for_specific_sup').val();
	$.ajax({
	url: "set_submit_input_value_depend_on_examiner_and_grps_status.php",
	method: "POST",
	
	data: {selected_examiner1:selected_examiner,selected_grp1:selected_grp},
	success:function(data){
		//print submit using this page : set_submit_input_value_depend_on_examiner_and_grps_status.php
		$('#get_input_type_submit_depend_on_comm_disc_and_grp_status').html(data);
	},
		
error: function(data){
console.log('---------error');
console.log(data);
console.log(data.responseText);

$('#get_input_type_submit_depend_on_comm_disc_and_grp_status').text(data.responseText);
 

}
});
});
 
 /* 
 $('body').persianNum({
 
  numberType: 'english'

});
 */
/*
$('#user_id').focus(function (e) {
    var element = this;
    setTimeout(function () {
        element.selectionStart = element.value.length;
    }, 1);
});

 // Set caret to the end of an element
        $.fn.caretToEnd = function () {
            return this.queue(function (next) {
                $.caretTo(this, $(this).val().length);
                next();
            });
        };
    
	$("#user_id").attr('maxlength', '9');
        $("#user_id").caretToEnd(); // This is to set caret at end on page load

		  // Set caret to beginning of an element
        $.fn.caretToStart = function () {
            return this.caret(0);
        };
 $.caretTo = function (el, index) {
            if (el.createTextRange) {
                var range = el.createTextRange();
                range.move("character", index);
                range.select();
            } else if (el.selectionStart != null) {
                el.focus();
                el.setSelectionRange(index, index);
            }
        };*/
		
 /**/
$(document).on('submit','#request_btn_examinar_and_grps',function(event) {
	var selected_grp = $('#groups_for_specific_sup').val();
	var selected_examiner = $('#all_sup_in_this_semester1').val();
	
		
	$.ajax({
        url: 'change_status_btn_examinar_and_grps.php',
		data:{selected_grp1:selected_grp,selected_examiner1:selected_examiner},
		type: 'POST',
        success: function(data){ 
			   $('#form_request_status_result').html(data); 
		  },
		
error: function(data){
console.log('---------error');
console.log(data);
console.log(data.responseText); 
 $('#form_request_status_result').text(data.responseText); 
 }
    }); 
	
});
 
$(document).on('submit','#confirmationForm1',function(event) {
	/*start*/

	  var fd = new FormData();
    var file_data = $('#file_picker')[0].files; // for multiple files
	 
        fd.append("weekly_peoject_works_for_specific_grp", file_data[0]);
     
    var other_data = $('#confirmationForm1').serializeArray();
    $.each(other_data,function(key,input){
        fd.append(input.name,input.value);
    }); 
    $.ajax({
        url: 'respond_for_weekly_grp_file.php',
		data:fd,
        contentType: false,
        processData: false, 
		dataType : "JSON",
        type: 'POST',
        success: function(data){
         if(data.upload_file_status){
         	   $('#response').html(data.upload_file_status);
         }else if(data.errors){
         	   $('#response').html(data.errors);
         }
			 
        },
		
error: function(data){
console.log('---------error');
console.log(data);
console.log(data.responseText); 
 $('#response').text(data.responseText); 

}
    });
	  	 event.preventDefault();//->this will prevent page from upload again to appear error
});
	/*end new*/ 
/*
correct link
https://stackoverflow.com/questions/10899384/uploading-both-data-and-files-in-one-form-using-ajax
https://www.youtube.com/watch?v=Cafuu-ATJTY
file+form data
*/

$("i.fa.fa-gear").on('click',function(){
$(this).parent('div.fixed-menu').toggleClass('is-visible');
if($(this).parent('div.fixed-menu').hasClass('is-visible')){

 $(this).parent('div.fixed-menu').animate({
left:0

 },500);   
 $('body').animate({
paddingLeft:'240'

 },500); 

 /*
var url1      = window.location.href; 
var name1=url1.substring(  url.lastIndexOf( "/" ) + 1);
$('[name="+name1+"]').addClass("active");
*/


}
else{
   $(this).parent('div.fixed-menu').animate({
left:'-240'

 },500);   
   $('body').animate({
paddingLeft:0

 },500); 
}

});
/**/

/**/
/*

 var loc = window.location.pathname;

$('ul#fixed_menu_1.fixed-menu-item.from-left li').find('a').each(function() {
  $(this).toggleClass('active', $(this).attr('href') == loc);
});

*/
/* Start after click on list item link *//*tst*/

var li_width=  $('ul#fixed_menu_1.fixed-menu-item.from-left').width();
var li_height=  $('ul#fixed_menu_1.fixed-menu-item.from-left li').height();

$('.eyes-cont i').on('click',function(){
  $(this).addClass('hide').siblings().removeClass('hide');
 
});

 

/*click*/ 
 $('ul#fixed_menu_1.fixed-menu-item.from-left li').on('click',function(){

 $(this).addClass('active').siblings().removeClass('active');
 });

/*Start appear semesters for specific year*/
     
    /*End appear semesters for specific year*/

  });
      
$(function () {
         $("select.form-control option.text-center").click(function () {
             var selText = $(this).text();
             $("select.form-control").html(selText);
         });
  $("select.form-control option.text-center").hover(function () {
             var selText = $(this).text();
             $("select.form-control").html(selText);
         });

});

 /* follow_up_weekly_work_of_grp_for_supervisor.php */
 $(document).on('submit','#send_msg_to_selected_group_from_sup',function(event) {
	var sender1 = $('#loginStdID').val();
	var motherMsg1 = $('#messageID').val();
	var selected_grp = $('#selected_grp').val();
		
	$.ajax({
        url: 'replyOnMotherMsgAndViewRelatedFromSup.php',
		data:{sender:sender1,motherMsg:motherMsg1},
		type: 'POST',
        success: function(data){ 
        	//alert('data'+data);
        	 window.open("replyOnMotherMsgAndViewRelatedFromSup.php?msg_id="+motherMsg1+"&"+"sender="+sender1+"&"+"selected_group1="+selected_grp);
			  // $('#form_request_status_result1').html(data); 
		  },
		
error: function(data){
console.log('---------error');
console.log(data);
console.log(data.responseText); 
 $('#form_request_status_result').text(data.responseText); 
 }
    }); 
	 event.preventDefault();
});
/* start progress bar */

function progressHandler(event) {
  _("loaded_n_total").innerHTML = "Uploaded " + event.loaded + " bytes of " + event.total;
  var percent = (event.loaded / event.total) * 100;
  _("progressBar").value = Math.round(percent);
  _("status").innerHTML = Math.round(percent) + "% uploaded... please wait";
}

function completeHandler(event) {
  _("status").innerHTML = event.target.responseText;
  _("progressBar").value = 0;
}

function errorHandler(event) {
  _("status").innerHTML = "Upload Failed";
}

function abortHandler(event) {
  _("status").innerHTML = "Upload Aborted";
}
/* end progress bar */
/*
$("#user_fname,#user_lname,#user_grp,#idea_name,#txt_msg").on("input", function(){
this.value = this.value.replace(/[^ء-ي\s]/g, '');
});
 */