   <script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
   <link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
   <script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
   <link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
   <link href="//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet" type="text/css" />
   <link href="catalog/view/theme/default/stylesheet/stylesheet.css" rel="stylesheet">
   <script src="catalog/view/javascript/common.js" type="text/javascript"></script>
  <link href="catalog/view/theme/default/stylesheet/register.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="catalog/view/javascript/AdminLTE.min.css">
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/skins/_all-skins.min.css">
  <body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <?php if ($logo) { ?>  
     <a href="<?php echo $home; ?>">
      <img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>"  height="40px" />
      </a>      
    <?php } else { ?>        
      <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>       
    <?php } ?>
  </div>
  <div class="register-box-body">
  <h3>Join as a Seller</h3>
   <?php if ($error_warning) { ?>
     <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> 
       <?php echo $error_warning; ?>
     </div>
    <?php } ?>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <fieldset id="account">
          <div class="form-group has-feedback required">
              <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
              <?php if ($error_firstname) { ?>
              <div class="text-danger"><?php echo $error_firstname; ?></div>
              <?php } ?>
          </div>
          <div class="form-group has-feedback required">
              <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" />
              <?php if ($error_lastname) { ?>
              <div class="text-danger"><?php echo $error_lastname; ?></div>
              <?php } ?>
          </div>
          <div class="form-group has-feedback required">
              <input type="email" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
              <?php if ($error_email) { ?>
              <div class="text-danger"><?php echo $error_email; ?></div>
              <?php } ?>
          </div>
          <div class="form-group has-feedback required">
              <input type="tel" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control"  />
              <?php if ($error_telephone) { ?>
              <div class="text-danger"><?php echo $error_telephone; ?></div>
              <?php } ?>
          </div>
		 <div class="form-group has-feedback required">
            <input type="text" name="username" value="<?php echo $username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" /> <?php if ($error_username) { ?>
              <div class="text-danger"><?php echo $error_username; ?></div>
              <?php } ?>
			   <span class="text-danger" id="availerror"></span>
          </div>
          <div class="form-group has-feedback required">
              <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
              <?php if ($error_password) { ?>
              <div class="text-danger"><?php echo $error_password; ?></div>
              <?php } ?>
          </div>  
		  </fieldset>
	    <div class="buttons">
        <?php if ($text_agree) { ?>
     <p style="text-align:center;"> <?php echo $text_agree; ?>
            <?php if ($agree) { ?>
            <input type="checkbox" name="agree" value="1" checked="checked" />
            <?php } else { ?>
            <input type="checkbox" name="agree" value="1" />
            <?php } ?>
            &nbsp;</p></div>
            <input type="submit" value="Register" class="background" />
        <?php } else { ?>
            <input type="submit" value="Register" class="background"  />
        <?php } ?>
      </form>
      <h2><?php echo $text_account_already;?></h2>
     	</div>
 </div>
</div>
<script type="text/javascript"><!--
// Sort the custom fields
$('#account .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#account .form-group').length) {
		$('#account .form-group').eq($(this).attr('data-sort')).before(this);
	} 
	if ($(this).attr('data-sort') > $('#account .form-group').length) {
		$('#account .form-group:last').after(this);
	}
	if ($(this).attr('data-sort') < -$('#account .form-group').length) {
		$('#account .form-group:first').before(this);
	}
});
$('#address .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#address .form-group').length) {
		$('#address .form-group').eq($(this).attr('data-sort')).before(this);
	} 
	if ($(this).attr('data-sort') > $('#address .form-group').length) {
		$('#address .form-group:last').after(this);
	}
	if ($(this).attr('data-sort') < -$('#address .form-group').length) {
		$('#address .form-group:first').before(this);
	}
});
$('input[name=\'customer_group_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=account/register/customfield&customer_group_id=' + this.value,
		dataType: 'json',
		success: function(json) {
			$('.custom-field').hide();
			$('.custom-field').removeClass('required');
			for (i = 0; i < json.length; i++) {
				custom_field = json[i];
				$('#custom-field' + custom_field['custom_field_id']).show();
				if (custom_field['required']) {
					$('#custom-field' + custom_field['custom_field_id']).addClass('required');
				}
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
$('input[name=\'customer_group_id\']:checked').trigger('change');
//--></script> 
<script type="text/javascript"><!--
$('button[id^=\'button-custom-field\']').on('click', function() {
	var node = this;
	$('#form-upload').remove();
	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');
	$('#form-upload input[name=\'file\']').trigger('click');
	$('#form-upload input[name=\'file\']').on('change', function() {
		$.ajax({
			url: 'index.php?route=tool/upload',
			type: 'post',
			dataType: 'json',
			data: new FormData($(this).parent()[0]),
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function() {
				$(node).button('loading');
			},
			complete: function() {
				$(node).button('reset');
			},
			success: function(json) {
				$('.text-danger').remove();
				if (json['error']) {
					$(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
				}
				if (json['success']) {
					alert(json['success']);
					$(node).parent().find('input').attr('value', json['file']);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
});
//--></script> 
<script type="text/javascript"><!--
   $('input#input-username').bind('keyup', function() {
	$.ajax({
		url: 'index.php?route=seller/register/availability',
		type: 'post',
		dataType: 'json',
		data: 'username=' + encodeURIComponent($('input[name=\'username\']').val()),
		success: function(data) {
			if (data['error']) {
				$('#availerror').html(data['error']);
			}
			if (data['success']) {
				$('#availerror').html('<div class="success">' + data['success'] + '</div>');
			}
		}
	});
	return false;
});
//--></script>
  <link href="catalog/view/javascript/summernote.css" rel="stylesheet">
<?php echo $footer; ?>