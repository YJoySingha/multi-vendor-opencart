<?php echo $header; ?><?php echo $column_left; ?>
<link href="catalog/view/javascript/uploadimage/uploadfile.css" rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="catalog/view/javascript/uploadimage/jquery.uploadfile.min.js"></script>
<script type="text/javascript"><!--
$(document).ready(function()
{
var settings = {
    url: "index.php?route=seller/uploadimages/upload&parent=<?php echo $parent; ?>",
    dragDrop:true,
    fileName: "myfile",
    allowedTypes:"jpg,png,gif,doc,pdf,zip",	
    returnType:"json",
	 onSuccess:function(files,data,xhr)
    {
        //alert(data[0]);
    },
    showDelete:true,
    deleteCallback: function(data,pd)
	{
    for(var i=0;i<data.length;i++)
    {
        $.post("delete.php",{op:"delete",name:data[i]},
        function(resp, textStatus, jqXHR)
        {
            //Show Message  
            $("#status").append("<div>File Deleted</div>");      
        });
     }      
    pd.statusbar.hide(); //You choice to hide/not.
}
}
var uploadObj = $("#mulitplefileuploader").uploadFile(settings);
});
//--></script> 
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
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
        <div id="tab-general">
          <div id="mulitplefileuploader">Upload</div>
				<div id="status"></div>
          <a href="<?php echo $imageshref;?>" target="_blank" class="btn btn-primary" style="float:right">View your Images</a>
        </div>
		<div class="buttons">
		<div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-primary"><?php echo $button_back; ?></a></div>
		</div>
      </div>
    </div>
  </div>
</section>
</div>
</div>
<?php echo $footer; ?>