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
      <?php if ($orders) { ?>
    <div class="table-responsive">
	  <table class="table table-bordered table-hover">
        <tr>
	       <td style="width:200px">Order ID:
           <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" id="filter_order_id" class="form-control" size="12" />
          </td>
          <td style="width:200px">Date Start:
            <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date-start" size="12"  class="form-control"/></td>
          <td style="width:200px">Date End:
            <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date-end" size="12" class="form-control" />
          </td>
          <td style="text-align: right;"><a onclick="filter();" class="btn btn-primary">Filter</a></td>
        </tr>
      </table>
	  <br/>
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-right"><?php echo $column_order_id; ?></td>
              <td class="text-left"><?php echo $column_status; ?></td>
              <td class="text-left"><?php echo $column_date_added; ?></td>
              <td class="text-right"><?php echo $column_product; ?></td>
              <td class="text-left"><?php echo $column_customer; ?></td>
              <td class="text-right"><?php echo $column_total; ?></td>
              <td></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $order) { ?>
            <tr>
              <td class="text-right">#<?php echo $order['order_id']; ?></td>
              <td class="text-left"><?php echo $order['status']; ?></td>
              <td class="text-left"><?php echo $order['date_added']; ?></td>
              <td class="text-right"><?php echo $order['products']; ?></td>
              <td class="text-left"><?php echo $order['name']; ?></td>
              <td class="text-right"><?php echo $order['total']; ?></td>
              <td class="text-right"><a href="<?php echo $order['href']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" 
			  class="btn btn-info"><i class="fa fa-eye"></i></a>
			  &nbsp;&nbsp;<a class="btn btn-info" target="_blank" href="<?php echo $order['invoice']; ?>" title="Print Invoice"
			  ><i class="fa fa-print"></i></a>
			  </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="text-right"><?php echo $pagination; ?></div>
	  <script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=seller/order';
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	var filter_order_id = $('input[name=\'filter_order_id\']').val();
	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}	
	location = url;
}
//--></script> 
<script src="catalog/view/javascript/jquery/datetimepicker/moment.js" type="text/javascript"></script>
<script src="catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
 <script type="text/javascript"><!--
$('input[name^=\'selected\']').on('change', function() {
	$('#button-shipping, #button-invoice').prop('disabled', true);
	var selected = $('input[name^=\'selected\']:checked');
	if (selected.length) {
		$('#button-invoice').prop('disabled', false);
	}
	for (i = 0; i < selected.length; i++) {
		if ($(selected[i]).parent().find('input[name^=\'shipping_code\']').val()) {
			$('#button-shipping').prop('disabled', false);
			break;
		}
	}
});
$('input[name^=\'selected\']:first').trigger('change');
$('a[id^=\'button-delete\']').on('click', function(e) {
	e.preventDefault();
	if (confirm('<?php echo $text_confirm; ?>')) {
		location = $(this).attr('href');
	}
});
//--></script> 
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date-start').datetimepicker({dateFormat: 'yy-mm-dd',pickDate: true,pickTime: false});
	$('#date-end').datetimepicker({dateFormat: 'yy-mm-dd',pickDate: true,pickTime: false});
});
//--></script> 
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      <div class="buttons clearfix">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div></div>
</div>
<?php echo $footer; ?>