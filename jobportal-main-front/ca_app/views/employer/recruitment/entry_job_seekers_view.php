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
                            <a href="#" style="color:#fff;" class="_link-back">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            </a>
                            Importar postulantes al sistema
                        </div>
                    </div>
                </div>
                <div class="formint">
                    <?php echo $this->session->flashdata('msg'); ?>

                    <?php echo form_open_multipart('employer/entry_job_seekers/do_import', array('id' => 'form-jobseekers-import')); ?>
                        <input type="hidden" name="job_id" value="<?php echo $job->ID; ?>">
                        <div>
                            <a href="#" data-toggle="modal" data-target="#modal-import-template" class="pull-right" style="text-decoration: underline;">
                                <i class="fa fa-file"></i>
                                Descargar Plantillas
                            </a>
                        </div>
                        <div class="input-group">
                            <label class="input-group-addon">Empleo a postular</label>
                            <span><?php echo $job->job_title; ?></span>
                        </div>
                        <div class="input-group">
                            <label class="input-group-addon">Plantilla <span>*</span></label>
                            <select name="template" class="form-control" required>
                                <option value="">Seleccione</option>
                                <option value="computrabajo">COMPUTRABAJO</option>
                                <option value="otros">OTROS</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <label class="input-group-addon">Archivo a importar <span>*</span></label>
                            <input type="file" name="file_import" required>
                        </div>
                        <div class="input-group">
                            <label class="input-group-addon">Agregar al proceso R&S<span></span></label>
                            <input type="checkbox" name="add_rys_process" value="1">
                        </div>

                        <div class="input-group" style="text-align: center;">
                            <input type="submit" value="Importar" class="btn btn-primary">
                        </div>

                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
        <!--/Job Detail--> 
    </div>
</div>

<!-- Modal -->
<div id="modal-import-template" class="modal" role="dialog">
    <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Plantillas para importar candidatos</h4>
        </div>
        <div class="modal-body text-center">
            <a class="btn btn-sm btn-success btn-block"
               href="<?php echo site_url('public/documents/templates/candidate_other_site/template_computrabajo_v3.xlsx'); ?>">
                PLANTILLA PARA COMPUTRABAJO
            </a>
            <br />
            <a class="btn btn-sm btn-success btn-block" 
               href="<?php echo site_url('public/documents/templates/candidate_other_site/importar_candidatos_otros_v3.xlsx'); ?>">
                PLANTILLA PARA OTROS
            </a>
        </div>
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