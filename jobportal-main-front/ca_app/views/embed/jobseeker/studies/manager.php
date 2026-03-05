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
    <div class="col-md-12" style="padding:0;margin:0;">
        <div><?php echo $this->session->flashdata('msg');?></div>

        <div>
            <?php $this->load->view('embed/jobseeker/studies/common/add_study'); ?>
            <div id="studies-edit" class="innerbox2" style="margin:0;display:none;"></div>
        </div>        
        <!--Education-->
        <div id="studies-list" class="innerbox2" style="margin:0;">
            <div class="titlebar">
                <div class="row">
                    <div class="col-xs-6"><b>Educación</b> </div>
                    <div class="col-xs-6" style="text-align:right;">
                        <?php if ($education_min): ?>
                            <div style="padding-left: 10px;font-size:14px;font-style:italic;display:block;"> Educación mínima:
                                <a href="#"
                                    class="btn-modal-education-detail" 
                                    style="padding:0;"
                                    data-edu-detail="<?php echo $education_min_detail; ?>">
                                    <?php echo $education_min->text; ?>
                                    &nbsp;
                                    <i class="fa fa-info-circle"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                        <a href="javascript:;" id="add_education" class="editlink" style="margin-top:5px;">
                            Añadir
                        </a> 
                    </div>
                </div>
            </div>
            
            <!--Job Description-->
            <div class="experiance">
            <?php 
                if($studies):
                    foreach($studies as $row_qualification):
                ?>
            <div class="row expbox" id="edu_<?php echo $row_qualification->ID;?>">
                <div class="col-md-12">
                <?php
                $start_date = ucwords(_date_locale_format(strtotime($row_qualification->start_date), 'MMM y'));
                $end_date = ($row_qualification->end_date != null || $row_qualification->end_date =! '0000-00-00') ? ucwords(_date_locale_format(strtotime($row_qualification->end_date), 'MMM y')) : 'Presente';
                ?>

                <div class="title-education">
                    <h4><?php echo $row_qualification->major; ?></h4>
                    <span>(<?php echo $start_date; ?> - <?php echo $end_date;?>)</span>
                </div>
                <ul class="useradon">
                    <li><?php echo $row_qualification->degree_title;?></li>
                </ul>
                <div class="action"><a href="javascript:;" class="btn-edit-education" data-id="<?php echo $row_qualification->ID;?>" title="Editar" class="edit-ico"><i class="fa fa-pencil">&nbsp;</i></a> <a href="javascript:;" onClick="del_edu(<?php echo $row_qualification->ID;?>);" title="Eliminar" class="delete-ico"><i class="fa fa-times">&nbsp;</i></a></div>
                </div>
            
                <div style="padding: 0 20px;" class="wrapper-document-load" data-study-id="<?php echo $row_qualification->ID; ?>">
                    <div class="document-view" style="background: #fff; <?php echo $row_qualification->attached_certificate ? 'display: block': 'display: none'; ?>">
                        
                        <?php if (isset($_GET['platform']) && $_GET['platform'] == 'mobile'): ?>
                            <button class="document-link js-file-send-email" 
                                data-url="<?php echo $row_qualification->attached_certificate ? file_url($row_qualification->attached_certificate) : ''; ?>"
                                data-description="Certificado estudio"
                                data-seeker-id="<?php echo $row_qualification->seeker_ID; ?>"
                                style="background:none;border:none;color:#337ab7;">
                                <i class="glyphicon glyphicon-file"></i>
                                    Certificado
                            </button>
                        <?php else:?>
                            <a class="document-link" 
                                href="<?php echo $row_qualification->attached_certificate ? file_url($row_qualification->attached_certificate) : ''; ?>"
                                target="_blank">
                                <i class="glyphicon glyphicon-file"></i>
                                    Certificado
                            </a>
                        <?php endif; ?>
                        &nbsp;
                        <button class="btn btn-default btn-xs load-certificate">Cambiar</button>
                    </div>
                    <div class="document-load" style="<?php echo $row_qualification->attached_certificate ? 'display: none': 'display: block'; ?>"">
                        <button class="btn btn-default btn-xs load-certificate">Agregar certificado</button>
                    </div>
                    <div class="document-loader" style="display: none;">Cargando...</div>
                    <div class="document-error-load" style="text-align: center;display: none;">  
                        <span class="document-error-info" style="color: red;"></span>
                        <a href="#" class="load-certificate">Volver a intentar</a>
                    </div>
                </div>
            </div>
            <?php endforeach; endif;?>
            <div class="clear"></div>
            </div>
        </div>
    </div> 
  </div>
</div>
<!--Footer-->

<!-- Profile Popups -->
<?php $this->load->view('embed/jobseeker/studies/common/education_min_detail'); ?>
<?php $this->load->view('common/before_body_close'); ?>
<script src="<?php echo base_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script> 
<script src="<?php echo base_url('public/js/validate_jobseeker.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/jquery-upload/js/vendor/jquery.ui.widget.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/jquery-upload/js/jquery.iframe-transport.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/jquery-upload/js/jquery.fileupload.js'); ?>" type="text/javascript"></script>
<?php $this->load->view('embed/jobseeker/studies/common/scripts/add_study_js'); ?>

<script type="text/javascript">
    $( '.btn-edit-education' ).click(function(e) {

        $('#ed_studying').off('change.checkdate');
        $( "#studies-list" ).hide();
       
        const url = "<?php echo site_url('embed/jobseeker/studies/manage/edit_form'); ?>";
        const data = {
            'id': $(this).data('id'),
            "<?php echo embed_token_name(); ?>" : "<?php echo embed_token(); ?>"
        };
        
        $.post(url, data, function(view){
            $( "#studies-edit" ).html(view).show();
        });
    });

    function del_edu(id) {
	
		confirmed = confirm("¿Seguro que quieres eliminar tu educación?");
		if(confirmed){
			$('#edu_'+ id).fadeOut();
			$.ajax({
                type: "POST",
                url: "<?php echo site_url('embed/jobseeker/studies/manage/delete'); ?>",
                data: { 
                    'id': id,
                    "<?php echo embed_token_name(); ?>" : "<?php echo embed_token(); ?>" 
                }
                })
                .done(function( msg ) {
                    if (msg=='done'){
                        $('#edu_' + id).fadeOut();
                    }
			    }
            );
		}
    }

    function showSelectorCertificates(elementTarget) {
      
      var wrapperDocument = elementTarget.closest('.wrapper-document-load');
      var studyId = wrapperDocument.data('study-id');

      var fileDocument = $( '<input type="file" name="file" style="display: none;" accept="application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document">');
      var url = "<?php echo site_url('embed/jobseeker/studies/manage/upload_certificates'); ?>";
      
      $(fileDocument).fileupload({
        dataType: 'json',
        url: url,
        formData: {
          study_id: studyId,
          "<?php echo embed_token_name(); ?>" : "<?php echo embed_token(); ?>" 
        },
        autoUpload: true,
        add: function (e, data) {
          var wrapperDocument = $(e.target).closest('.wrapper-document-load');
          
          $( ".document-view", wrapperDocument).hide();
          $( ".document-loader", wrapperDocument).text('Cargando...').show();
          $( ".document-load", wrapperDocument).hide();
          $( ".document-error-load", wrapperDocument).hide();

          data.context = wrapperDocument;
          data.submit();
        },
        progress: function(e, data) {
          var wrapperDocument = data.context;
          var progress = parseInt(data.loaded / data.total * 100, 10);

          if (progress == 100) {
            $( ".document-loader", wrapperDocument).text("Completando carga..." );
          } else {
            $( ".document-loader", wrapperDocument).text("Cargando (" + progress + "%)" );
          }
        }, 
        done: function (e, data) {
          var wrapperDocument = data.context;
          var error = data.result.error;
          var urlFile = data.result.url_file;
          
          $( ".document-loader", wrapperDocument).hide();

          if (error) {
            $( ".document-error-load", wrapperDocument).show();
            $( ".document-error-info", wrapperDocument).text(error);
            return;
          }

          $( ".document-link", wrapperDocument).prop('href', urlFile);
          $( ".document-link", wrapperDocument).data('url', urlFile);
          $( ".document-view", wrapperDocument).show();
        }
      });

      wrapperDocument.append(fileDocument);
      fileDocument[0].click();
    }

    $( "#add_education" ).click(function(){
        $( "#studies-list" ).hide();
        $( "#studies-add" ).show();
    });

    $( document ).on('click', ".back", function(){
        $( "#studies-list" ).show();
        $( "#studies-add" ).hide();
        $( "#studies-edit" ).hide();
    });

    $( '.btn-modal-education-detail' ).click(function(){
        $( '#modal-education-detail p').html($(this).data('edu-detail')); 
        $( '#modal-education-detail').modal('show');
    });

    $( ".load-certificate" ).click(function() {
        showSelectorCertificates($(this));
    });

</script>
</body>
</html>
