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
       <h2><?php echo $heading_title; ?></h2>
      </div>
      <?php if ($messages) { ?>
  
             <table class="table table-bordered table-hover">
               <thead>
                 <tr>
                   <td class="text-left"><?php echo $column_message_id; ?></td>
                   <td class="text-left"><?php echo $column_date_added; ?></td> 
                   <td class="text-left"><?php echo $column_customer; ?></td>
                   <td class="text-left"><?php echo $column_product; ?></td>
                   <td class="text-left"><?php echo $column_message; ?></td>
                   <td class="text-left"><?php echo $column_action; ?></td>
                 </tr>
               </thead>
               <tbody>
                 <?php foreach ($messages as $message) { ?>
                 <tr>
                   <td class="text-left">#<?php echo $message['message_id']; ?></td>
                   <td class="text-left"><?php echo $message['date_added']; ?></td>
                   <td class="text-left"><?php echo $message['customer']; ?></td>
                   <td class="text-left"><?php echo $message['product_name']; ?></td>
                   <td class="text-left"><?php echo $message['message']; ?></td>
                   <td class="text-left">
                   <a href="<?php echo $message['href']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                  </td>
                 </tr>
                 <?php } ?>
               </tbody>
             </table>
  <?php } else { ?>

  <div class="content"><?php echo $text_empty; ?></div>
  <?php } ?> 
   <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
   <div class="col-sm-6 text-right"><?php echo $results; ?></div>
  </div>
  </div>
  </div>
  </section>
  </div>
<?php echo $footer; ?>