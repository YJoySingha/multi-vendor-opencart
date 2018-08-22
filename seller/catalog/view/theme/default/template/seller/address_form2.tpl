<?php echo $header; ?>
<?php echo $column_left; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
      <div class="pull-left">
      <ul class="breadcrumb">
          <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
  </section>
   <!-- Main content -->
   <section class="content">
   <div class="row">
    <div class="col-xs-12">
     <div class="box">
       <div class="box-header">
        <h2><?php echo $heading_title; ?><small> - how to receive your earnings</small></h2>
       </div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
		  <fieldset>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-password"></label>
            <div class="col-sm-10">
              <?php if ($paypalorcheque == 1) { ?>
            <input type="radio" name="paypalorcheque" value="1" checked="checked" />
            Paypal
            <input type="radio" name="paypalorcheque" value="0" />
            Cheque
	    <input type="radio" name="paypalorcheque" value="2" />
            Bank Transfer
            <?php }elseif ($paypalorcheque == 0) { ?>
            <input type="radio" name="paypalorcheque" value="1" />
            Paypal
            <input type="radio" name="paypalorcheque" value="0" checked="checked" />
            Cheque
	    <input type="radio" name="paypalorcheque" value="2" />
            Bank Transfer
            <?php } else { ?>
            <input type="radio" name="paypalorcheque" value="1" />
            Paypal
            <input type="radio" name="paypalorcheque" value="0" />
            Cheque
	    <input type="radio" name="paypalorcheque" value="2" checked="checked" />
            Bank Transfer
            <?php } ?>
            </div>
          </div>
		   <div class="form-group required" id="paypal-tr">
            <label class="col-sm-2 control-label" for="input-paypal"><?php echo $entry_paypalemail; ?></label>
            <div class="col-sm-4">
              <input type="text" name="paypal_email" value="<?php echo $paypal_email; ?>" placeholder="<?php echo $entry_paypalemail; ?>" 
			  id="input-paypal" class="form-control" />
              <?php if ($error_paypalemail) { ?>
              <div class="text-danger"><?php echo $error_paypalemail; ?></div>
              <?php } ?>
            </div>
          </div>
		   <div class="form-group required" id="bankname-tr">
            <label class="col-sm-2 control-label" for="input-bank"><?php echo $entry_bankname; ?></label>
            <div class="col-sm-10">
              <input type="text" name="bank_name" value="<?php echo $bank_name; ?>" placeholder="<?php echo $entry_bankname; ?>" 
			  id="input-bank" class="form-control" />
               <?php if ($error_bankname) { ?>
              <div class="text-danger"><?php echo $error_bankname; ?></div>
              <?php } ?>
            </div>
          </div>
		   <div class="form-group required" id="accountnumber-tr">
            <label class="col-sm-2 control-label" for="input-accountnumber"><?php echo $entry_accountnumber; ?></label>
            <div class="col-sm-10">
              <input type="text" name="account_number" value="<?php echo $account_number; ?>" placeholder="<?php echo $entry_accountnumber; ?>" 
			  id="input-accountnumber" class="form-control" />
               <?php if ($error_accountnumber) { ?>
              <div class="text-danger"><?php echo $error_accountnumber; ?></div>
              <?php } ?>
            </div>
          </div>
		   <div class="form-group required" id="accountname-tr">
            <label class="col-sm-2 control-label" for="input-accountname"><?php echo $entry_accountname; ?></label>
            <div class="col-sm-10">
              <input type="text" name="account_name" value="<?php echo $account_name; ?>" placeholder="<?php echo $entry_accountname; ?>" 
			  id="input-accountname" class="form-control" />
               <?php if ($error_accountname) { ?>
              <div class="text-danger"><?php echo $error_accountname; ?></div>
              <?php } ?>
            </div>
          </div>
		  <div class="form-group required" id="branch-tr">
            <label class="col-sm-2 control-label" for="input-branch"><?php echo $entry_branch; ?></label>
            <div class="col-sm-10">
              <input type="text" name="branch" value="<?php echo $branch; ?>" placeholder="<?php echo $entry_branch; ?>" 
			  id="input-branch" class="form-control" />
               <?php if ($error_branch) { ?>
              <div class="text-danger"><?php echo $error_branch; ?></div>
              <?php } ?>
            </div>
          </div>
		   <div class="form-group required" id="ifsccode-tr">
            <label class="col-sm-2 control-label" for="input-ifsccode"><?php echo $entry_ifsccode; ?></label>
            <div class="col-sm-10">
              <input type="text" name="ifsccode" value="<?php echo $ifsccode; ?>" placeholder="<?php echo $entry_ifsccode; ?>" 
			  id="input-ifsccode" class="form-control" />
               <?php if ($error_ifsccode) { ?>
              <div class="text-danger"><?php echo $error_ifsccode; ?></div>
              <?php } ?>
            </div>
          </div>
		   <div class="form-group required" id="cheque-tr">
            <label class="col-sm-2 control-label" for="input-cheque"><?php echo $entry_cheque; ?></label>
            <div class="col-sm-10">
              <input type="text" name="cheque" value="<?php echo $cheque; ?>" placeholder="<?php echo $entry_cheque; ?>" 
			  id="input-cheque" class="form-control" />
               <?php if ($error_cheque) { ?>
              <div class="text-danger"><?php echo $error_cheque; ?></div>
              <?php } ?>
            </div>
          </div>
        </fieldset>
        <input type="hidden" name="address_id" value="<?php echo $address_id;?>"/>
        <div class="buttons clearfix">
          <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
          <div class="pull-right">
            <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
          </div>
        </div>
      </form>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div> 
<script type="text/javascript"><!--
$('select[name=\'country_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=account/account/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('input[name=\'postcode\']').parent().parent().addClass('required');
			} else {
				$('input[name=\'postcode\']').parent().parent().removeClass('required');
			}
			html = '<option value=""><?php echo $text_select; ?></option>';
			if (json['zone']) {
				for (i = 0; i < json['zone'].length; i++) {
					html += '<option value="' + json['zone'][i]['zone_id'] + '"';
					if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
						html += ' selected="selected"';
					}
				html += '>' + json['zone'][i]['name'] + '</option>';
			}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}
			$('select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
$('select[name=\'country_id\']').trigger('change');
//--></script>
<script type="text/javascript"><!--
$('input[name=\'paypalorcheque\']').on('change', function() {
	var value = $(this).val();
	if(value == 1){
		$('#paypal-tr').show();
		$('#cheque-tr').hide();
		$('#bankname-tr').hide();
		$('#accountnumber-tr').hide();
		$('#accountname-tr').hide();
		$('#branch-tr').hide();
		$('#ifsccode-tr').hide();
	}else if(value == 2){
		$('#paypal-tr').hide();
		$('#cheque-tr').hide();
		$('#bankname-tr').show();
		$('#accountnumber-tr').show();
		$('#accountname-tr').show();
		$('#branch-tr').show();
		$('#ifsccode-tr').show();
	}
	else{
		$('#paypal-tr').hide();
		$('#cheque-tr').show();
		$('#bankname-tr').hide();
		$('#accountnumber-tr').hide();
		$('#accountname-tr').hide();
		$('#branch-tr').hide();
		$('#ifsccode-tr').hide();
	}
});
$('input[name=\'paypalorcheque\']:checked').trigger('change');
//--></script>
<script type="text/javascript"><!--
$('select[name=\'country_id2\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=account/account/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id2\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('input[name=\'postcode2\']').parent().parent().addClass('required');
			} else {
				$('input[name=\'postcode2\']').parent().parent().removeClass('required');
			}
			html = '<option value=""><?php echo $text_select; ?></option>';
			if (json['zone']) {
				for (i = 0; i < json['zone'].length; i++) {
					html += '<option value="' + json['zone'][i]['zone_id'] + '"';
					if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
						html += ' selected="selected"';
					}
				html += '>' + json['zone'][i]['name'] + '</option>';
			}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}
			$('select[name=\'zone_id2\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
$('select[name=\'country_id2\']').trigger('change');
//--></script>
<?php echo $footer; ?>