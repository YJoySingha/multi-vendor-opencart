<?php if (!empty($logged)) { ?>
<?php echo $header; ?>
<?php echo $column_left; ?>
<style type="text/css">
  .fa {
    font-size: 18px;
  }
</style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $order_total;?></h3>
              <p>Orders</p>
            </div>
            <div class="icon">
              <i class="fa fa-shopping-cart"></i>
            </div>
            <a href="<?php echo $orders_link ?>"class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $seller_total_sales;?></h3>
              <p>Sales</p>
            </div>
            <div class="icon">
              <i class="fa fa-money"></i>
            </div>
            <a href="<?php echo $transaction?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $balance;?></h3>
              <p>Earnings</p>
            </div>
            <div class="icon">
              <i class="fa  fa-exchange"></i>
            </div>
            <a href="<?php echo $transaction?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $seller_total_products;?></h3>
              <p>Products</p>
            </div>
            <div class="icon">
              <i class="fa fa-tags"></i>
            </div>
            <a href="<?php echo $products_link ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-6 connectedSortable">
          <!-- -->
          <div class="nav-tabs-custom">
           <div class="panel panel-default">
             <div class="panel-heading">
               <h3 class="panel-title"><i class="fa fa-shopping-cart"></i> <?php echo $text_seller_recent_orders; ?></h3>
             </div>
             <div class="table-responsive">
               <table class="table">
                 <thead>
                   <tr>
                     <td><?php echo $column_customer; ?></td>
                     <td><?php echo $column_status; ?></td>
                     <td><?php echo $column_date_added; ?></td>
                     <td class="text-right"><?php echo $column_total; ?></td>
                     <td class="text-right"><?php echo $column_action; ?></td>
                   </tr>
                 </thead>
                 <tbody>
                   <?php if ($orders) { ?>
                   <?php foreach ($orders as $order) { ?>
                   <tr>
                     <td><?php echo $order['customer']; ?></td>
                     <td><?php echo $order['status']; ?></td>
                     <td><?php echo $order['date_added']; ?></td>
                     <td class="text-right"><?php echo $order['total']; ?></td>
                     <td class="text-right"><a href="<?php echo $order['href']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a></td>
                   </tr>
                   <?php } ?>
                   <?php } else { ?>
                   <tr>
                     <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
                   </tr>
                   <?php } ?>
                 </tbody>
               </table>
             </div>
           </div>
          </div>
          <!-- /.nav-tabs-custom -->
        </section>
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-6 connectedSortable">
          <div class="nav-tabs-custom">
           <div class="box">
             <div class="box-header">
               <h3 class="box-title"><?php echo $text_recent_activity;?></h3>
             </div>
             <!-- /.box-header -->
             <div class="box-body">
               <table class="table table-bordered table-hover">
                 <tr>
                   <td><?php echo $column_date_added; ?></td>
                   <td><?php echo $column_comment; ?></td>
                 </tr>
                 <tbody>
                   <?php if ($transactions) { ?>
                   <?php foreach ($transactions as $transaction) { ?>
                   <tr>
                     <td><?php echo $transaction['date_added']; ?></td>
                     <td ><?php echo $transaction['description']; ?><br><?php echo $transaction['amount']; ?></td>
                   </tr>
                   <?php } ?>
                   <?php } else { ?>
                   <tr>
                     <td><?php echo $text_no_results; ?></td>
                   </tr>
                   <?php } ?>
                 </tbody>
               </table>
             </div>
             <!-- /.box-body -->
           </div>
           <!-- /.box -->
          </div>
          <!-- /.nav-tabs-custom -->
        </section>
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
    </div> 
<?php } else { ?>
 echo "Not logged in";
<?php } ?>
<?php echo $footer?>