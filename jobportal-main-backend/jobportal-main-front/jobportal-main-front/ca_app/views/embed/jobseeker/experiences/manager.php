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

        <div>
            <?php $this->load->view('embed/jobseeker/experiences/common/add_experience'); ?>
            <?php $this->load->view('embed/jobseeker/experiences/common/edit_experience'); ?>
        </div>
        
        <!--Experiance-->
        <div id="experiences-list" class="innerbox2" style="margin:0;">
            <div class="titlebar">
            <div class="row">
                <div class="col-xs-9"><b>Experiencias laborales</b></div>
                <div class="col-xs-3 text-right">
                <a href="javascript:;" id="add_exp" class="editlink">
                    Añadir
                </a> 
                </div>
            </div>
            </div>
            
            <!--Job Description-->
            <div class="experiance">
        <?php 
                if($result_experience):
                    foreach($result_experience as $row_experience):
            $start_date = ucwords(_date_locale_format(strtotime($row_experience->start_date), 'MMM y'));
                    $end_date = ($row_experience->end_date != null || $row_experience->end_date =! '0000-00-00') ? ucwords(_date_locale_format(strtotime($row_experience->end_date), 'MMM y')) : 'Presente';
            ?>
            <div class="row expbox" id="exp_<?php echo $row_experience->ID;?>">
                <div class="col-md-12">
                <div class="title-experience">
                    <h4><?php echo $row_experience->job_title;?></h4> 
                    <span> (<?php echo $start_date; ?> - <?php echo $end_date;?>)</span>
                </div>
                <ul class="useradon">
                    <li class="company">
                    <span>
                        <?php echo $row_experience->company_name;?>  
                    </span>
                    <span class="country">
                    (<?php echo $row_experience->country;?>) 
                    </span>
                    </li>
                    <li>
                    <?php echo ellipsize(strip_tags($row_experience->description), 200); ?>
                    </li>
                </ul>
                <div class="action"><a href="javascript:;" onClick="load_edit_js_exp(<?php echo $row_experience->ID;?>);" title="Editar" class="edit-ico"><i class="fa fa-pencil">&nbsp;</i></a> <a href="javascript:;" onClick="del_exp(<?php echo $row_experience->ID;?>);" title="Eliminar" class="delete-ico"><i class="fa fa-times">&nbsp;</i></a></div>
                </div>
                <div style="padding: 0 20px;" class="wrapper-document-load" data-experience-id="<?php echo $row_experience->ID; ?>">
                    <div class="document-view" style="background: #fff; <?php echo $row_experience->attached_certificate ? 'display: block': 'display: none'; ?>">

                    <?php if (isset($_GET['platform']) && $_GET['platform'] == 'mobile'): ?>
                        <button class="document-link js-file-send-email" 
                                data-url="<?php echo $row_experience->attached_certificate ? file_url($row_experience->attached_certificate) : ''; ?>"
                                data-description="Certificado Experiencia"
                                data-seeker-id="<?php echo $row_experience->seeker_ID; ?>" 
                                style="background:none;border:none;color:#337ab7;">
                            <i class="glyphicon glyphicon-file"></i>
                                Certificado
                        </button>
                    <?php else: ?>
                        <a class="document-link" 
                           href="<?php echo $row_experience->attached_certificate ? file_url($row_experience->attached_certificate) : ''; ?>"
                           target="_blank">
                            <i class="glyphicon glyphicon-file"></i>
                                Certificado
                        </a>
                    <?php endif; ?>
                        &nbsp;
                        <button class="btn btn-default btn-xs load-certificate">Cambiar</button>
                    </div>
                    <div class="document-load" style="<?php echo $row_experience->attached_certificate ? 'display: none': 'display: block'; ?>"">
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
        <!-- -->
    </div> 
  </div>
</div>

<!--Footer-->

<!-- Profile Popups -->
<?php $this->load->view('common/before_body_close'); ?>
<script src="<?php echo base_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script> 
<script src="<?php echo base_url('public/js/validate_jobseeker.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/jquery-upload/js/vendor/jquery.ui.widget.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/jquery-upload/js/jquery.iframe-transport.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/jquery-upload/js/jquery.fileupload.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">

    function add_js_exp() {
		
        var data = $( "#frm_add_exp" ).serialize();
        $( '#js_exp_submit' ).prop('disabled', true);
    
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('embed/jobseeker/experiences/manage_experiences/add'); ?>",
            data: data			  
        })
        .done(function( msg ) {
            if (msg == 'done') {
                location.reload();
            } else {
                $('#emsg_add_exp').html('<span class="label label-warning">' + msg + '</span>');
                $( '#js_exp_submit' ).prop('disabled', false);
            }
        });
    }

    function edit_js_exp() {

        var data = $( "#frm_edit_exp" ).serialize();
        $( '#js_edit_exp_submit' ).prop('disabled', true);

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('embed/jobseeker/experiences/manage_experiences/edit'); ?>",
            data: data
        })
        .done(function( msg ) {
            if (msg == 'done') {
                $('#edit_exp_modal').modal('hide');
                $('#emsg_edit_exp').html('');
                location.reload();
            } else {
                $('#emsg_edit_exp').html('<span class="label label-warning">'+msg+'</span>');
                $( '#js_edit_exp_submit' ).prop('disabled', false);
            }
        });
    }

    function load_edit_js_exp(id) {
        $('#ed_exp_working').off('change.checkdate');

        $( "#experiences-list" ).hide();
        $( "#experiences-edit" ).show();

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('embed/jobseeker/experiences/manage_experiences/experience_by_id'); ?>",
            data: { 
                'id': id,
                "<?php echo embed_token_name(); ?>" : "<?php echo embed_token(); ?>"
            }
            })
            .done(function( data ) {
                obj = jQuery.parseJSON(data);

                var start_date = obj.start_date;
                var end_date = obj.end_date;
                var pieces_start_date = start_date.split("-");
                var completion_year = "";
                var completion_month = "";

                $("#ed_exp_id").val(obj.ID);
                $("#ed_job_title").val(obj.job_title);
                $("#ed_company_name").val(obj.company_name);

                select_value('ed_exp_country',obj.country);

                $( "#ed_exp_industry" ).val(obj.industry);
                $( "#ed_exp_job_level" ).val(obj.job_level);
                $( "#ed_exp_area" ).val(obj.area);
                $( "#ed_exp_description" ).val(obj.description);

                $( "#ed_exp_start_year" ).val(pieces_start_date[0]);
                $( "#ed_exp_start_month" ).val(pieces_start_date[1]);
                
                $( "#ed_exp_working" ).prop('checked', true);

                if (end_date != null && end_date != '0000-00-00') {
                    var pieces_end_date = end_date.split("-");
                    completion_year = pieces_end_date[0];
                    completion_month = pieces_end_date[1];
                    $( "#ed_exp_working" ).prop('checked', false);
                }

                $( "#ed_exp_completion_year" ).val(completion_year);
                $( "#ed_exp_completion_month" ).val(completion_month);
                $( "#ed_exp_working" ).change();

                validate_range_dates('#ed_exp_start_year', '#ed_exp_start_month', '#ed_exp_completion_year', '#ed_exp_completion_month', '#ed_exp_working');
            }
        );
    }

    function del_exp(id){
		
		confirmed = confirm("¿Seguro que quieres eliminar tu experiencia?");

		if (confirmed) {
			$('#edu_' + id).fadeOut();
			$.ajax({
                type: "POST",
                url: "<?php echo site_url('embed/jobseeker/experiences/manage_experiences/delete'); ?>",
                data: { 
                    'id': id,
                    "<?php echo embed_token_name(); ?>" : "<?php echo embed_token(); ?>"
                }
            })
            .done(function( msg ) {
                if (msg == 'done'){
                    $('#exp_' + id).fadeOut();
                }
			});
		}
    }

    function showSelectorCertificates(elementTarget) {
      
      var wrapperDocument = elementTarget.closest('.wrapper-document-load');
      var experienceId = wrapperDocument.data('experience-id');

      var fileDocument = $( '<input type="file" name="file" style="display: none;" accept="application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document">');
      var url = "<?php echo site_url('embed/jobseeker/experiences/manage_experiences/upload_certificate'); ?>";
      
      $(fileDocument).fileupload({
        dataType: 'json',
        url: url,
        formData: {
          experience_id: experienceId,
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

    $( ".load-certificate" ).click(function() {
        showSelectorCertificates($(this));
    });

    $(function(){

        $( "#add_exp" ).click(function(){
            $( "#experiences-list" ).hide();
            $( "#experiences-add" ).show();
        });

        $( ".back" ).click(function(){
            $( "#experiences-list" ).show();
            $( "#experiences-add" ).hide();
            $( "#experiences-edit" ).hide();
        });

        $( "#exp_working" ).change(function(){

            var isSelected = $(this).is(':checked');

            $( "#exp_completion_year" ).attr({'disabled': isSelected});
            $( "#exp_completion_month" ).attr({'disabled': isSelected});

            if (isSelected) {
            $( "#exp_completion_month" ).closest('div').removeClass( "has-error" ); 
            $( '.exp_completion_month_err').remove();
            $( "#exp_completion_year" ).closest('div').removeClass( "has-error" ); 
            $( '.exp_completion_year_err').remove();
            }
        });

        $( "#ed_exp_working" ).change(function(){

            var isSelected = $(this).is(':checked');

            $( "#ed_exp_completion_year" ).attr({'disabled': isSelected});
            $( "#ed_exp_completion_month" ).attr({'disabled': isSelected});

            if (isSelected) {
            $( "#ed_exp_completion_month" ).closest('div').removeClass( "has-error" ); 
            $( '.ed_exp_completion_month_err').remove();
            $( "#ed_exp_completion_year" ).closest('div').removeClass( "has-error" ); 
            $( '.ed_exp_completion_year_err').remove();
            }
        });

        $( "#exp_working" ).change();
    });

</script>
</body>
</html>