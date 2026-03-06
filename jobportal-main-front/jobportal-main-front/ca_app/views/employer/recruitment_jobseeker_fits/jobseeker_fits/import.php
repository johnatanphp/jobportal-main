<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view('common/meta_tags'); ?>
        <title><?php echo $title;?></title>
        <?php $this->load->view('common/before_head_close'); ?>
        <link href="<?php echo site_url('public/css/jquery-ui.css');?>" rel="stylesheet" type="text/css" />
    </head>
<body>
<?php $this->load->view('common/after_body_open'); ?>
<div class="siteWraper">
<!--Header-->
<?php $this->load->view('common/header'); ?>
<!--/Header--> 

    <!--Detail Info-->
    <div class="container detailinfo">
        <div class="row">
            <div class="col-md-3">
                <div class="dashiconwrp">
                    <?php $this->load->view('employer/common/menu/sidebar');?>
                </div>
            </div>
            <div class="col-md-9">
                <div class="formwraper">
                    <div class="titlehead">
                        <div class="row">
                            <div class="col-md-12">
                                <a style="color:#fff;" href="<?php echo site_url('employer/recruitment_jobseeker_fits/jobseeker_fits/search'); ?>">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                </a>
                                Importar postulantes al sistema
                            </div>
                        </div>
                    </div>
                    <div class="formint">
                        <?php echo $this->session->flashdata('msg'); ?>

                        <?php echo form_open_multipart('employer/recruitment_jobseeker_fits/jobseeker_fits/do_import', ['id' => 'form-jobseekers-import']); ?>
                            
                            <div>
                                <a href="<?php echo site_url('public/documents/templates/candidate_other_site/importar_candidatos_reclutamiento.xlsx'); ?>" 
                                   class="pull-right" 
                                   style="text-decoration: underline;">
                                    <i class="fa fa-file"></i>
                                    Descargar Plantilla
                                </a>
                            </div>
                            
                            <div class="input-group">
                                <label class="input-group-addon">Archivo a importar <span>*</span></label>
                                <input type="file" name="file_import" required>
                            </div>
                
                            <div class="input-group" style="text-align: center;">
                                <input type="submit" value="Importar" class="btn btn-sm btn-primary">
                            </div>

                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
            <!--/Job Detail--> 
        </div>
    </div>
</div>

<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/before_body_close'); ?>
<script src="<?php echo base_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){

        $( "#form-jobseekers-import" ).submit(function(e) {
    
            var confirm = window.confirm("¿Desea importar los postulantes?");

            if (!confirm) {
                e.preventDefault();
                return false;
            }

            return true;
        });
    });
</script>