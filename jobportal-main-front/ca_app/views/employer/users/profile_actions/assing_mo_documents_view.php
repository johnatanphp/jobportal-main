<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view('common/meta_tags'); ?>
        <title><?php echo $title;?></title>
        <?php $this->load->view('common/before_head_close'); ?>
    </head>
    <body>
        <?php $this->load->view('common/after_body_open'); ?>
        <div class="siteWraper">
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

                    <?php 
                        echo form_open(
                            'employer/users/profile_actions/assign_mo_documents/save/' . $user_id
                        );
                    ?>
                    <div class="col-md-9">
                        <?php echo $this->session->flashdata('msg');?>
                          <!--Account info-->
                          <div class="formwraper">
                            <div class="titlehead">
                                <a href="<?php echo site_url('employer/users/profiles/index/' . $user_id); ?>" style="color:#fff;">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                </a>
                                Asignar documentos a usuarios
                            </div>
                            <div class="formint">
                                <div class="input-group <?php echo (form_error('old_password'))?'has-error':'';?>">
                                    <label class="input-group-addon">Documentos<span></span></label>
                                    <select id="mo-documents" name="mo_documents[]" multiple="true" class="form-control">
                                        <option value="">Seleccione</option>
                                        <?php foreach ($mo_doc_list as $doc): ?>
                                            <?php 
                                                $doc_selected = isset($mo_doc_selected[$doc->id]) ? 'selected="selected"' : '';
                                            ?>
                                            <option value="<?php echo $doc->id; ?>" <?php echo $doc_selected; ?>>
                                                <?php echo $doc->name; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div align="center">
                                    <input type="submit" name="submit_button" id="submit_button" value="Guardar" class="btn btn-primary" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/Job Detail-->
                    <?php echo form_close(); ?>
                </div>
            </div>
            <?php $this->load->view('common/bottom_ads');?>
            <!--Footer-->
            <?php $this->load->view('common/footer'); ?>
            <?php $this->load->view('common/before_body_close'); ?>
            <script type="text/javascript">
                    $( "#mo-documents").select2({
                        placeholder: 'Seleccione',
                        closeOnSelect: false
                    });
            </script>
        </div>
    </body>
</html>
