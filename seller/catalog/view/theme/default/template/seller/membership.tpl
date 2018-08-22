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
        <h2><?php echo $heading_title; ?></h2>
        </div>
     <div class="table-responsive">
 	  <table class="table table-bordered table-hover" id="ver-zebra">
	  <thead>
    	<tr>
            <th id="vzebra-comedy" scope="col"><?php echo $column_plan;?></th>
		   <th id="vzebra-comedy" scope="col"><?php echo $column_duration;?></th>
            <th id="vzebra-comedy" scope="col"><?php echo $column_charges;?></th>
		   <th id="vzebra-comedy" scope="col"><?php echo $column_about;?></th>
		   <th id="vzebra-comedy" scope="col"><?php echo $column_upgrade;?></th>
        </tr>
    </thead>
    <tbody>
	     <?php if($getmemberships) { ?>
           <?php foreach ($getmemberships as $getmembership) {
		   ?>
    	<tr>
        	<td id="vzebra-comedy1" scope="col"><?php echo strtoupper($getmembership['commission_name']);?><?php
			if($getmembership['amt']>0){
			}
			else{
			echo "(Free)"; }?>	</td>
			<td id="vzebra-comedy1" scope="col">
			<?php if($getmembership['per']){
			 if($getmembership['duration_id']){
			echo $getmembership['per'].'/'.$durations[$getmembership['duration_id']];
			}}?></td>
			<td id="vzebra-comedy1" scope="col"><?php
			if($getmembership['amt']>0){
			echo  $getmembership['amount'];
			}
			else{
			echo "Free"; }?>	</td>
         <td id="vzebra-comedy1" scope="col"><?php echo $getmembership['product_limit'];?>	</td>
			<td>
		  <?php if($commission_id == $getmembership['commission_id']){
		   echo "<b>Current Plan</b>";
		  }else{?>
		<?php if($getmembership['amt'] > 0){?>

			<form action="<?php echo $action ?>" method="post"
			name="PayPalForm<?php echo $getmembership['commission_id']; ?>" id="PayPalForm<?php echo $getmembership['commission_id']; ?>"  target="_top">
				<input type="hidden" name="cmd" value="_xclick" />
	            <input type="hidden" name="upload" value="1" />
				<input type="hidden" name="business" value="<?php echo $email; ?>" />
				<input type="hidden" name="currency_code" value="<?php echo $currency_code; ?>">
				<input type="hidden" name="item_name" value="<?php echo $getmembership['commission_name']; ?>">
				<input type="hidden" name="item_number" value="1">
				<input type="hidden" name="rm" value="2" />
	            <input type="hidden" name="no_note" value="1" />
				<input type="hidden" name="lc" value="<?php echo $lc; ?>" />
				<input type="hidden" name="address_override" value="0" />
	           <input type="hidden" name="charset" value="utf-8" />
				<input type="hidden" name="amount" value="<?php echo $getmembership['amt']; ?>">
				<input type="hidden" name="custom" value="<?php echo $custom.'#'.$getmembership['commission_id']; ?>">
				<input type="hidden" name="cancel_return" value="<?php echo $cancelURL;?>">
				<input type="hidden" name="return" value="<?php echo $notify_url; ?>">
				<input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>">
				<input type="submit" class="btn btn-primary" value="Upgrade" onclick="paypal<?php echo $getmembership['commission_id']; ?>();"/>
			</form>
		   <script>
			function paypal<?php echo $getmembership['commission_id']; ?>(){
					document.PayPalForm<?php echo $getmembership['commission_id']; ?>.submit();
			}
			</script>
		<?php }else{ ?>
		 <input type="button" class="btn btn-primary" value="Upgrade"
		   onclick="updatemember<?php echo; $getmembership['commission_id'];?>
		   ('<?php echo $getmembership['commission_id;;;;'];?>';)">
                <script>
				 function updatemember<?php echo; $getmembership['commission_id'];?>(commission_id); {
				$.ajax({
				url: 'index.php?route=seller/membership/update',
				type: 'post',
				data: 'commission_id=' + commission_id,
				dataType: 'json',
				success: function(json) {
					$('.success, .warning, .attention, .information, .error').remove();
					if (json['success']) {
						$('#ver-zebra').before('<div class="alert alert-success">' + json['success'] + '</div><button type="button" class="close" data-dismiss="alert">&times;</button>');
						$('.success').fadeIn('slow');
						$('html, body').animate({ scrollTop: 0 }, 'slow');
						location.reload();
					}
				}
			});
		}
		</script>
		 <?php } }?>
		  </td>
         </tr>
		 <?php } }?>
    </tbody>
    </table>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>