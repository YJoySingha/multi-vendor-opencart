<?php echo $header; ?>
<?php echo $column_left; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
 <!-- Main content -->
 <section class="content">
 <div class="row">
  <div class="col-xs-12">
   <div class="box">
     <div class="box-header">
      <h3><?php echo $heading_title; ?></h3>
      </div>
    <?php if($messageInfo) {    ?>
      <div class="panel-body">
       
          <div class="timeline-item">
          <span class="time pull-right"><i class="fa fa-clock-o"></i> <?php echo $messageInfo['date_added']; ?></span>
            
           <div class="timeline-body">
            <p><b><?php echo $text_customer; ?> </b><?php echo $messageInfo['customer']; ?></p>
             <p><b><?php echo $text_product_name; ?> </b> <?php echo $messageInfo['product_name']; ?> </p> 
             <p><b><?php echo $text_email; ?> </b> <?php echo $messageInfo['email']; ?> </p> 
             <p><b><?php echo $text_phone; ?> </b> <?php echo $messageInfo['phone']; ?></p> 
             <p><b><?php echo $text_enquiry; ?> </b> <?php echo $messageInfo['message']; ?></p>

             <div class="form-group">
             <label>Reply Message</label>
             <textarea class="form-control" id="seller-message" rows="4" placeholder="Enter message"></textarea> 
             </div>
           </div>
           <div class="timeline-footer">
             <a class="btn btn-primary btn-xs" id="reply-message" onclick="replyMessage('<?= $messageInfo['message_id'];?>')" >Reply</a>
             <a class="btn btn-danger btn-xs" style="display: none;"  id="delete-message" onclick="confirm('Are you sure?') ? deleteMessage('<?= $messageInfo['message_id'];?>' ) : false;"><i class="fa fa-trash-o"></i></a>
           </div>
         </div>

       <?php }  else { ?>
        <h2><?php echo $text_no_reply; ?></h2>
       
      </div><?php }  ?>
      <div>
      <h3>History</h3>
      <div class="alert alert-success" style="display: none;" >
      <i class="fa fa-check-circle"></i> 
      <p id="message-success"></p>
      </div>
      <div class="alert alert-danger" style="display: none;" >
      <i class="fa fa-check-circle"></i> 
      <p id="message-fail"></p>
      </div>
      <?php if( $history ) {  ?>
         <?php  foreach($history as $item ) {  ?>
           <div class="timeline-item">
             <span class="time pull-right"><i class="fa fa-clock-o"></i> <?php echo $item['date_added']; ?></span>

             <hp class="timeline-header no-border"><?php echo $item['content']; ?></p>
           </div>
         <?php } ?>

      <?php  } else {  ?>
          <div class="timeline-item">
             <h3 class="timeline-header no-border"><?php echo $text_no_reply; ?></h3>
           </div>

      <?php } ?>


      </div>

      <div class="buttons clearfix">
        <div class="pull-right">
         <a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a>
        </div>
      </div>
      </div>
</div></div></div>
<script type="text/javascript">
  function deleteMessage(message_id) {
    alert(message_id);
    // $.ajax({
    //   url: 'index.php?route=tool/upload',
    //   type: 'post',
    //   dataType: 'json',
    //   data: new FormData($(this).parent()[0]),
    //   cache: false,
    //   contentType: false,
    //   processData: false,
    //   beforeSend: function() {
    //     $(node).button('loading');
    //   },
    //   complete: function() {
    //     $(node).button('reset');
    //   },
    //   success: function(json) {
    //     $('.text-danger').remove();
    //     if (json['error']) {
    //       $(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
    //     }
    //     if (json['success']) {
    //       alert(json['success']);
    //       $(node).parent().find('input').attr('value', json['file']);
    //     }
    //   },
    //   error: function(xhr, ajaxOptions, thrownError) {
    //     alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    //   }
    // });
  }

  function replyMessage(message_id) {
    if (!$('#seller-message').val()) {
      alert("Message cannot be empty");
      return false;
    }
    var messageBody =  {};
    messageBody.seller_id = '<?= $messageInfo["seller_id"]; ?>';
    messageBody.customer_email = '<?= $messageInfo["email"]; ?>';
    messageBody.replyContent = $('#seller-message').val();
    messageBody.message_id = message_id;
    console.log(messageBody);
    var url = 'index.php?route=seller/messages/reply';
    //ajax request
    $.ajax({
      url: url,
      type: 'POST',
      dataType: 'json',
      data: messageBody,
      cache: false,
      success: function(json) {
        $('#message-fail').html('');
        $('#message-success').html('');
        if (json['error']) {
          $('#message-fail').append(json['error']).show();
        }
        if (json['success']) {
          //alert(json['message']);
          $('#message-success').append(json['message']).show();
         window.location = "index.php?route=seller/messages/message_info&message_id="+message_id;
        }
        //alert('message send');
        console.log(json);
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });

    

    //alert(message_id);
  }
</script>
<?php echo $footer; ?>