<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <h3><?php echo $text_location; ?></h3>
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            <div class="col-sm-3"><strong><?php echo $store; ?></strong><br />
              <address>
               <?php foreach ($addresses as $result) { ?>
               <?php echo $result['address']; ?>
               <?php } ?>
             </address>
           </div>
           <div class="col-sm-3"><strong><?php echo $text_telephone; ?></strong><br>
            <?php echo $telephone; ?><br />
            <br />
            <?php if ($fax) { ?>
            <strong><?php echo $text_fax; ?></strong><br>
            <?php echo $fax; ?>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
      <fieldset>
        <h3><?php echo $text_contact; ?></h3>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-name">Product Name:</label>
          <div class="col-sm-10">
           <input type="text" name="product_name" value="<?php echo $product_name; ?>" readonly class="form-control" />
           <input type="hidden" name="product_id" value="<?php echo $product_id; ?>"/>
           <input type="hidden" name="seller_email" value="<?php echo $seller_email; ?>"/>
         </div>
       </div>
       <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
        <div class="col-sm-10">
          <input type="text" name="name" value="<?php echo $name; ?>" id="input-name" class="form-control" />
          <?php if ($error_name) { ?>
          <div class="text-danger"><?php echo $error_name; ?></div>
          <?php } ?>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-phone">Telephone:</label>
        <div class="col-sm-10">
          <input type="text" name="phone" value="<?php echo $phone; ?>" id="input-phone" class="form-control" />
        </div>
      </div>
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
        <div class="col-sm-10">
          <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" class="form-control" />
          <?php if ($error_email) { ?>
          <div class="text-danger"><?php echo $error_email; ?></div>
          <?php } ?>
        </div>
      </div>
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-enquiry"><?php echo $entry_enquiry; ?></label>
        <div class="col-sm-10">
          <textarea name="enquiry" rows="10" id="input-enquiry" class="form-control"><?php echo $enquiry; ?></textarea>
          <?php if ($error_enquiry) { ?>
          <div class="text-danger"><?php echo $error_enquiry; ?></div>
          <?php } ?>
        </div>
      </div>
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-captcha"><?php echo $entry_captcha; ?></label>
        <div class="col-sm-10">
          <input type="text" name="captcha" id="input-captcha" class="form-control" />
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-10 pull-right">
          <img src="index.php?route=tool/captcha" alt="" />
          <?php if ($error_captcha) { ?>
          <div class="text-danger"><?php echo $error_captcha; ?></div>
          <?php } ?>
        </div>
      </div>
    </fieldset>
    <div class="buttons">
      <div class="pull-right">
        <input class="btn btn-primary" type="submit" value="Submit" />
      </div>
    </div>
  </form>
  <?php echo $content_bottom; ?></div>
  <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
