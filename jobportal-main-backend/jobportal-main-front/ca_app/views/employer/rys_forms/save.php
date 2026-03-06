<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view('common/meta_tags'); ?>
        <title><?php echo $title;?></title>
        <link href="<?php echo base_url('public/css/jquery-ui.css');?>" rel="stylesheet" type="text/css" />
        <?php $this->load->view('common/before_head_close'); ?>
        <link rel="stylesheet" href="http://jquery-ui.googlecode.com/svn/tags/1.8.7/themes/base/jquery.ui.all.css">
        <link rel="stylesheet" href="<?php echo base_url('public/css/select2/select2.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('public/autocomplete/demo.css'); ?>">
        <style>
            .label-text {
              color: #333 !important;
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
                <div class="col-md-3">
                    <div class="dashiconwrp">
                        <?php $this->load->view('employer/common/menu/sidebar'); ?>
                    </div>
                </div>

                <div class="col-md-9">
                    <?php echo form_open('employer/rys_forms/do_save', ['id' => 'form-save']); ?>
                        <div class="formwraper">
                            <div class="titlehead">
                                <a href="#" style="color:#fff;" class="_link-back">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                </a>
                                <?php echo $form ? 'Editar' : 'Nuevo'; ?> formulario</div>
                        
                            <div class="formint">

                                <div class="info-required">
                                    <span>*</span> Campos obligatorios 
                                </div>
                                <input type="hidden" name="form_id" value="<?php echo $form ? $form->form_id : 0; ?>">
                                <div class="input-group">
                                    <label class="input-group-addon">Nombre<span> *</span></label>
                                    <input name="name" 
                                           type="text" 
                                           class="form-control" 
                                           placeholder="Nombre" 
                                           value="<?php echo @$form->name; ?>" 
                                           maxlength="150"
                                           required="true">
                                </div>

                                <div class="input-group">
                                    <label class="input-group-addon">Etapa <span>*</span></label>
                                    <select class="form-control" 
                                            name="stage"
                                            required="true" 
                                            style="width: 100%;">
                                        <option value="">Seleccione</option>
                                        <option value="0" <?php echo @$form->stage == 0 ? 'selected="selected"' : ""; ?>>
                                            FILTRO CURRICULAR
                                        </option>
                                        <option value="1" <?php echo @$form->stage == 1 ? 'selected="selected"' : ""; ?>>
                                            FILTRO TELÉFONICO
                                        </option>
                                        <option value="2" <?php echo @$form->stage == 2 ? 'selected="selected"' : ""; ?>>
                                            LONG LIST
                                        </option>
                                        <option value="3" <?php echo @$form->stage == 3 ? 'selected="selected"' : ""; ?>>
                                            ENTREVISTA
                                        </option>
                                        <option value="4" <?php echo @$form->stage == 4 ? 'selected="selected"' : ""; ?>>
                                            EVALUACIÓN
                                        </option>
                                        <option value="5" <?php echo @$form->stage == 5 ? 'selected="selected"' : ""; ?>>
                                            TERNA O SHORT LIST
                                        </option>
                                        <option value="6" <?php echo @$form->stage == 6 ? 'selected="selected"' : ""; ?>>
                                            SELECCIÓN PERSONAL
                                        </option>
                                        <option value="7" <?php echo @$form->stage == 7 ? 'selected="selected"' : ""; ?>>
                                            CONTRATACIÓN
                                        </option>
                                    </select>
                                </div>

                                <div class="input-group">
                                    <label class="input-group-addon">Cabecera<span></span></label>
                                    <textarea name="header" 
                                           type="text" 
                                           class="form-control" 
                                           placeholder="Cabecera"
                                           rows="6"><?php echo @$form->header; ?></textarea>
                                </div>

                                <div class="input-group">
                                    <label class="input-group-addon">Pie de página<span></span></label>
                                    <textarea name="footer" 
                                           type="text" 
                                           class="form-control" 
                                           placeholder="Pie de página"
                                           rows="6"><?php echo @$form->footer; ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div id="form-sections"></div>

                        <div style="padding: 0 15px;">
                            <br />
                            <a href="#" id="add-section" style="font-size: 14px;">
                                <i class="glyphicon glyphicon-plus"></i>
                                Agregar sección
                            </a>
                        </div>
                        <br />
                        <div class="formwraper" style="background: #eee;">
                            <div class="formint">
                                <div align="center" class="">  
                                    <input type="submit" name="submit_button" id="submit_button" value="Guardar" class="btn btn-primary" /> 
                                </div>
                            </div>
                        </div>

                    <?php echo form_close(); ?> 
                </div>
            </div>
        </div>

        <script id="tpl-section" type="text/template">        
            <?php $this->load->view('employer/rys_forms/template/form_section_tpl'); ?>
        </script>
        
        <script id="tpl-question" type="text/template">
            <?php $this->load->view('employer/rys_forms/template/form_question_tpl'); ?>
        </script>

        <script id="manager-items-options" type="text/template">
            <?php $this->load->view('employer/rys_forms/template/form_items_option_tpl'); ?>
        </script>

        <script id="new-item-option" type="text/template">
            <?php $this->load->view('employer/rys_forms/template/form_option_tpl'); ?>
        </script>

        <?php $this->load->view('common/bottom_ads');?>
        <!--Footer-->
        <?php $this->load->view('common/footer'); ?>
        <script src="<?php echo base_url('public/js/bad_words.js'); ?>"></script>
        <?php $this->load->view('common/before_body_close'); ?>
        <script src="<?php echo base_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script> 
        <script src="<?php echo base_url('public/js/select2/select2.min.js'); ?>" type="text/javascript"></script> 
        <script type="text/javascript" src="<?php echo base_url('public/js/mustache.2.3.0.min.js');?>"></script>

        <?php $this->load->view('employer/rys_forms/scripts/save_form_js'); ?>
    </body>
</html>