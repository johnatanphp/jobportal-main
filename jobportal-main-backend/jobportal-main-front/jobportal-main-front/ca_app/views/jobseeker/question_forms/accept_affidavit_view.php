<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view('common/meta_tags'); ?>
        <title><?php echo $title;?></title>
        <?php $this->load->view('common/before_head_close'); ?>
        <style>

            .formwraper .formint .btn-rc-action {
                background: #e0e0e0;
                border: 1px solid #bbb;
            }
            
            input:focus {
                outline: none !important;
                outline-width: 0 !important;
                box-shadow: none;
                -moz-box-shadow: none;
                -webkit-box-shadow: none;
            }            

            input:invalid, textarea:invalid, select:invalid {
                _border: 1px solid red;
            }
        </style>
    </head>
    <body>
        <?php $this->load->view('common/after_body_open'); ?>
        <!--<div class="siteWraper">-->
        <!--Header-->
        <?php $this->load->view('common/header'); ?>
        <!--/Header-->
        <div class="container detailinfo">
            <div class="row"> 
                <div <?php echo $this->session->userdata('menu') != '0' ? 'class="col-md-3"' : 'class="col-md-2"'; ?>>
                    <div class="dashiconwrp">
                        <?php if ($this->session->userdata('menu') != '0'): ?>
                        <?php $this->load->view('jobseeker/common/jobseeker_menu'); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-9">  
                    <?php echo $this->session->flashdata('msg'); ?>
                    <div class="formwraper">
                        <div class="titlehead">
                            <a href="#" style="color:#fff;" class="_link-back">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            </a>
                            Aceptar declaración jurada
                        </div>
                        <div class="formint" style="text-align: center;">

                            <div style="text-align: right;padding: 10px 0;">
                                <a href="#" 
                                   class="show-rs-doc-comments" 
                                   data-document="rys_form_affidavit" 
                                   data-ref-id="<?php echo $assignment_form->assignment_id; ?>"
                                >
                                    Ver comentarios recibidos
                                </a>  
                            </div>
                            <?php if ($assignment_form->affidavit_accept == 1): ?>
                                
                                <span><i class="glyphicon glyphicon-ok document-ok"></i> ¡Declaración jurada aceptada!</span>
                        
                            <?php else: ?>
                                <?php echo form_open_multipart('jobseeker/forms/do_accept_affidavit/', ['id' => 'form-accept-affidavit']); ?>
                                   
                                    <input type="hidden" name="assignment_id" value="<?php echo $assignment_form->assignment_id; ?>">
                                    <div style="text-align: center">
                                        <?php echo $form->affidavit_header; ?>
                                        <br />
                                        <br />
                                        <input type="submit" value="Aceptar declaración jurada" class="btn btn-primary">
                                    </div>           
                    
                                <?php echo form_close(); ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php $this->load->view('jobseeker/question_forms/partials/form_answer'); ?>
                </div>
            </div>
        </div>
        <div id="modal-rs-doc-comments" class="modal fade" role="dialog"></div>

        <?php $this->load->view('common/bottom_ads');?>
        <!--Footer-->
        <?php $this->load->view('common/footer'); ?>
        <?php $this->load->view('common/before_body_close'); ?>

        <script type="text/javascript">
        
            $(document).ready(function(){

                $( '#form-accept-affidavit' ).submit(function() {
                    return confirm(
                        "¿Está seguro de aceptar la declación jurada de este documento?"
                    );
                });
            });
        </script>
    </body>
</html>
