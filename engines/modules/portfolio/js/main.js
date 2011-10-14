function portfolioAddComment ()
{
	var form = document.getElementById('dle-comments-form');

	if (dle_wysiwyg == "yes") {
		document.getElementById('comments').value = $('#comments').html();
		var editor_mode = 'wysiwyg';
	} else { var editor_mode = ''; }

	if (form.comments.value == '' || form.name.value == '')
	{
		DLEalert ( dle_req_field, dle_info );
		return false;
	}

	if ( form.sec_code ) {

	   var sec_code = form.sec_code.value;

    } else { var sec_code = ''; }

	if ( form.recaptcha_response_field ) {
	   var recaptcha_response_field= Recaptcha.get_response();
	   var recaptcha_challenge_field= Recaptcha.get_challenge();
    } else {
	   var recaptcha_response_field= '';
	   var recaptcha_challenge_field= '';
	}

	if ( form.allow_subscribe ) {

		if ( form.allow_subscribe.checked == true ) {

		   var allow_subscribe= "1";

		} else {

		   var allow_subscribe= "0";

		}

    } else { var allow_subscribe= "0"; }

	ShowLoading('');

	$.post(dle_root + "engine/modules/portfolio/ajax/addcomments.php", { post_id: form.post_id.value, comments: form.comments.value, name: form.name.value, mail: form.mail.value, editor_mode: editor_mode, skin: dle_skin, sec_code: sec_code, recaptcha_response_field: recaptcha_response_field, recaptcha_challenge_field: recaptcha_challenge_field, allow_subscribe: allow_subscribe }, function(data){

		if ( form.sec_code ) {
           form.sec_code.value = '';
           reload();
	    }

		HideLoading('');

		RunAjaxJS('dle-ajax-comments', data);

		if (data != 'error' && document.getElementById('blind-animation')) {

			$("html"+( ! $.browser.opera ? ",body" : "")).animate({scrollTop: $("#dle-ajax-comments").position().top - 70}, 1100);

			setTimeout(function() { $('#blind-animation').show('blind',{},1500)}, 1100);
		}

	});

	/*
	var form = document.getElementById('dle-comments-form');
    var dle_comments_ajax = new dle_ajax();

	if (dle_wysiwyg == "yes") {
		document.getElementById('comments').value = tinyMCE.get('comments').getContent();
		dle_comments_ajax.setVar("editor_mode", 'wysiwyg');
	}

	if (form.comments.value == '' || form.name.value == '')
	{
		alert ( dle_req_field );
		return false;
	}

	dle_comments_ajax.onShow ('');
	var varsString = "post_id=" + form.post_id.value;
	dle_comments_ajax.setVar("comments", dle_comments_ajax.encodeVAR(form.comments.value));
	dle_comments_ajax.setVar("name", dle_comments_ajax.encodeVAR(form.name.value));
	dle_comments_ajax.setVar("mail", dle_comments_ajax.encodeVAR(form.mail.value));
	dle_comments_ajax.setVar("skin", dle_skin);

	if ( form.sec_code ) {

	   dle_comments_ajax.setVar("sec_code", form.sec_code.value);

    }

	if ( form.allow_subscribe ) {

		if ( form.allow_subscribe.checked == true ) {

		   dle_comments_ajax.setVar("allow_subscribe", "1");

		} else {

		   dle_comments_ajax.setVar("allow_subscribe", "0");

		}
    }

	dle_comments_ajax.requestFile = dle_root + "engine/modules/portfolio/ajax/addcomments.php";
	dle_comments_ajax.method = 'POST';
	dle_comments_ajax.execute = true;
	dle_comments_ajax.element = 'dle-ajax-comments';
	dle_comments_ajax.sendAJAX(varsString);
	*/
}

function PortfolioMenuCommBuild( m_id, area )
{
	var menu=new Array();

	menu[0]='<a onclick="portfolio_ajax_comm_edit(\'' + m_id + '\', \'' + area + '\'); return false;" href="#">' + menu_short + '</a>';
	//menu[1]='<a href="' + dle_root + '?do=portfolio&act=comments&sub_act=comm_edit&id=' + m_id + '&area=' + area + '">' + menu_full + '</a>';

	return menu;
};

function portfolio_whenCompletedCommentsEdit(){

	var post_main_obj = document.getElementById( 'comm-id-' + comm_id );
	var post_box_top  = _get_obj_toppos( post_main_obj );

			if ( post_box_top )
			{
				scroll( 0, post_box_top - 70 );
			}

};

function portfolio_ajax_comm_edit( c_id, area )
{
	if ( ! c_cache[ c_id ] || c_cache[ c_id ] == '' )
	{
		c_cache[ c_id ] = $('#comm-id-'+c_id).html();
	}

	ShowLoading('');

	$.get(dle_root + "engine/modules/portfolio/ajax/editcomments.php", { id: c_id, area: area, action: "edit" }, function(data){

		HideLoading('');

		RunAjaxJS('comm-id-'+c_id, data);

		setTimeout(function() {
           $("html:not(:animated)"+( ! $.browser.opera ? ",body:not(:animated)" : "")).animate({scrollTop: $("#comm-id-" + c_id).position().top - 70}, 700);
        }, 100);

	});
	return false;

	/*

	if ( ! c_cache[ c_id ] || c_cache[ c_id ] == '' )
	{
		c_cache[ c_id ] = document.getElementById( 'comm-id-'+c_id ).innerHTML;
	}

	var ajax = new dle_ajax();
	comm_id = c_id;
	ajax.onShow ('');
	var varsString = "";
	ajax.setVar("id", c_id);
	ajax.setVar("area", area);
	ajax.setVar("action", "edit");
	ajax.requestFile = dle_root + "engine/modules/portfolio/ajax/editcomments.php";
	ajax.method = 'GET';
	ajax.element = 'comm-id-'+c_id;
	ajax.execute = true;
	ajax.onCompletion = portfolio_whenCompletedCommentsEdit;
	ajax.sendAJAX(varsString);
	return false;
	*/
};

function portfolio_ajax_cancel_comm_edit( c_id )
{
	if ( n_cache[ c_id ] != "" )
	{
		document.getElementById( 'comm-id-'+c_id ).innerHTML = c_cache[ c_id ];
	}

	return false;
};

function portfolio_whenCompletedSaveComments()
{
	c_cache[ comm_edit_id ] = '';
}


function portfolio_ajax_save_comm_edit( c_id, area )
{
    var comm_txt = '';
	comm_edit_id = c_id;

	if (dle_wysiwyg == "yes") {

		comm_txt = $('#dleeditcomments'+c_id).html();

	} else {

		comm_txt = $('#dleeditcomments'+c_id).val();

	}

	ShowLoading('');

	$.post(dle_root + "engine/modules/portfolio/ajax/editcomments.php", { id: c_id, comm_txt: comm_txt, area: area, action: "save" }, function(data){

		HideLoading('');
		c_cache[ comm_edit_id ] = '';
		$("#comm-id-"+c_id).html(data);

	});
	return false;

	/*var ajax = new dle_ajax();
	var comm_txt = '';

	comm_edit_id = c_id;
	ajax.onShow ('');

	if (dle_wysiwyg == "yes") {

		comm_txt = ajax.encodeVAR( tinyMCE.get('dleeditcomments'+c_id).getContent() );

	} else {

		comm_txt = ajax.encodeVAR( document.getElementById('dleeditcomments'+c_id).value );

	}

	var varsString = "comm_txt=" + comm_txt;

	ajax.setVar("id", c_id);
	ajax.setVar("area", area);
	ajax.setVar("action", "save");
	ajax.requestFile = dle_root + "engine/modules/portfolio/ajax/editcomments.php";
	ajax.method = 'POST';
	ajax.element = 'comm-id-'+c_id;
	ajax.onCompletion = portfolio_whenCompletedSaveComments;
	ajax.sendAJAX(varsString);

	return false;   */
};