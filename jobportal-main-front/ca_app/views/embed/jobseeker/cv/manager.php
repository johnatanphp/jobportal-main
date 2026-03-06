<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<link href="<?php echo base_url('public/css/jquery-ui.css');?>" rel="stylesheet" type="text/css" />

<style type="text/css">
  .ui-autocomplete { 
    z-index:99999999; 
  }
  
  .ui-autocomplete-input {
    width: 100%;
  }

</style>
</head>
<body>
<?php $this->load->view('common/after_body_open'); ?>
<div>
<!--/Header-->
<div class="containe">
  <div class="row" style="padding:0;margin:0;">  
    <div class="col-md-12">
    
    <div><?php echo $this->session->flashdata('msg');?></div>

      <div class="innerbox2">
        <div class="titlebar">
          <div class="row">
            <div class="col-xs-9"><b>Gestionar CV</b></div>
            <div class="col-xs-3 text-right">
              <button class="upload_cv editlink" type="button" title="Subir CV" style="color:#337ab7;">
                Subir CV
              </button> 
            </div>
          </div>
        </div>
        
        <!--Job Description-->
        <div class="companydescription">
          
          <div class="row">
            <div class="col-md-12">
              <ul class="myjobList">
            <?php if($result_resume): 
            foreach($result_resume as $row_resume):
                $file_name = ($row_resume->is_uploaded_resume)?$row_resume->file_name:'';
                $file_array = explode('.',$file_name);
                $file_array = array_reverse($file_array);
                $icon_name = get_extension_name($file_array[0]);
                ?>
            <li class="row" id="cv_<?php echo $row_resume->ID;?>">
              <div class="col-md-4">
              <i class="fa fa-file-<?php echo $icon_name;?>-o">&nbsp;</i>
              <?php if($row_resume->is_uploaded_resume): ?>
                <?php if (isset($_GET['platform']) && $_GET['platform'] == 'mobile'): ?>

                  <button data-url="<?php echo file_url($row_resume->file_name); ?>"
                          data-description="CV"
                          data-seeker-id="<?php echo $row_resume->seeker_ID; ?>"
                          class="js-file-send-email"
                          style="background:none;border:none;color:#337ab7;">
                    Mi CV
                  </button>
        
                  <?php else: ?>
                    <a  target="_blank"
                        href="<?php echo file_url($row_resume->file_name); ?>">
                        Mi CV
                    </a>
                  <?php endif; ?>  
              <?php else: ?>
            <a href="#">Mi CV</a>
            <?php endif;?>
              </div>
              <div class="col-md-4"><?php echo _date_locale_format(strtotime($row_resume->dated), "dd MMM, y");?></div>
              <div class="col-md-2"><?php //echo ($row_resume->is_default_resume=='yes')?'Default':'Mark as Default';?></div>
              <div class="col-md-2 text-right">
                <button 
                        onClick="remove_cv(<?php echo $row_resume->ID;?>)" 
                        title="Eliminar" class="delete-ico"
                        style="background:none;border:none;">
                  <i class="fa fa-times">&nbsp;</i>
                </button>
              </div>
            </li>
            <?php  endforeach; 
          else:?>
            ¡Ningún currículum cargado todavía!
            <?php endif;?>
          </ul>
            </div>
          </div>
        </div>
        </div>
    </div>
    <!--/Job Detail-->   
  </div>
</div>

<div id="modal-file-upload" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-body">
				<div class="ref-content">

					
				</div>
			</div>
		</div>
	</div>
</div>
<!--Footer-->

<!-- Modal -->
<!-- Profile Popups -->
<?php $this->load->view('common/before_body_close'); ?>
<script src="<?php echo base_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script> 
<script src="<?php echo base_url('public/js/jquery-upload/js/vendor/jquery.ui.widget.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/jquery-upload/js/jquery.iframe-transport.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/jquery-upload/js/jquery.fileupload.js'); ?>" type="text/javascript"></script>

<script type="text/javascript">

  function remove_cv(id, fl) {
        confirmed = confirm("¿Seguro que quieres eliminar tu currículum?");
        if(confirmed){
            $('#cv_'+id).fadeOut();
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('embed/jobseeker/cv/manage/delete'); ?>",
                data: { 
                  'id': id,
                  "<?php echo embed_token_name(); ?>" : "<?php echo embed_token(); ?>"
                }
            })
            .done(function( msg ) {
                if(msg=='done'){
                        
                }
            });
        }
    }

    function uploadCv(elementTarget) {
      
      var fileDocument = $( '<input type="file" name="file" style="display: none;"  accept="application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document">');
      var url = "<?php echo site_url('embed/jobseeker/cv//manage/upload/' . $seeker->ID . ''); ?>";

      $(fileDocument).fileupload({
        dataType: 'json',
        url: url,
        formData: {
          "<?php echo embed_token_name(); ?>" : "<?php echo embed_token(); ?>" 
        },
        autoUpload: true,
        add: function (e, data) {
          $( '#modal-file-upload' ).modal('show');
          data.submit();
        },
        progress: function(e, data) {
          var progress = parseInt(data.loaded / data.total * 100, 10);
          
          if (progress == 100) {
            $( '#modal-file-upload .ref-content' ).html("Completando carga, espere un momento por favor." );
          } else {
            $( '#modal-file-upload .ref-content' ).html("Cargando CV (" + progress + "%)" );
          }
        }, 
        done: function (e, data) {

          if (!data.result.success) {
            $( '#modal-file-upload .ref-content' ).html(`
              <i class="fa fa-exclamation-circle">&nbsp;</i>
              ${data.result.message}
              <br>
              <br>
              <button type="button"
                      data-dismiss="modal"
                      class="btn btn-sm btn-primary">
                  OK
              </button>`
            );
            return;
          }

          $( '#modal-file-upload .ref-content' ).html(`
            <i class="fa fa-check">&nbsp;</i>
            ${data.result.message}
            <br>
            <br>
            <button type="button"
                    onclick="window.location.reload();"
                    class="btn btn-sm btn-primary">
                OK
            </button>`
          );
        }
      });

      fileDocument[0].click();
    }

    $( '.upload_cv' ).click(function(e){
      e.preventDefault();
      uploadCv(this);
      return false;
    });
</script>
</body>
</html>