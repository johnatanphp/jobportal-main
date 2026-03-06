<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<style type="text/css"> 
    .formwraper p{font-size:13px;}
</style>
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
                <?php $this->load->view('employer/common/menu/sidebar');?>
            </div>
        </div>
        <div class="col-md-9"> 
            <?php echo $this->session->flashdata('msg');?>
            <div class="formwraper">
                <div class="titlehead">
                    <div class="row">
                        <div class="col-md-12">
                            <b>Gestionar usuarios</b>                 
                        </div>
                    </div>
                </div>

                <div style="padding: 8px;">
                    <a href="<?php echo site_url('employer/users/add_user');?>" 
                       class="btn btn-sm btn-primary pull-right">
                        Agregar usuario
                    </a>
                    <div class="clear"></div>
                </div>

                <div class="table-search">
                    <?php echo form_open('employer/users/list_users/search', array('method' => 'get')); ?>  
                        <table width="100%">
                            <tr>
                                <td width="90%">
                                    <input type="text" name="query" class="form-control" value="<?php echo html_escape($filters['query']); ?>" placeholder="Buscar cuentas y/o usuarios">            
                                </td>
                                <td width="10%">
                                    <button type="submit" class="btn btn-block btn-search">
                                        <i class="glyphicon glyphicon-search"></i>
                                    </button>           
                                </td>
                            </tr>
                        </table>
                    <?php echo form_close(); ?>
                </div>
                <div class="table-responsive">
                    <table width="100%" class="table table-striped">
                        <thead>
                            <tr>
                                <th width="40%">
                                    Nombre
                                </th>
                                <th>
                                    Correo
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($result_employers as $row_employer): ?>

                                <tr>
                                    <td width="40%">
                                        <?php echo $row_employer->first_name; ?>
                                    </td>
                                    <td>
                                        <?php echo $row_employer->email; ?>
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-default" href="<?php echo site_url('employer/users/profile_actions/assign_profile_filters/index/' . $row_employer->ID); ?>"> 
                                            Filtros
                                        </a>
                                        <a class="btn btn-sm btn-default" href="<?php echo site_url('employer/users/profiles/index/' . $row_employer->ID); ?>">
                                            Perfiles
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if (count($result_employers) == 0): ?>
                    <div align="center" class="text-red" style="padding: 20px;">
                        <h3>Ningún empleador encontrado</h3>
                    </div>              
                <?php endif; ?>
            
            </div>
            <!--Pagination-->
            <div class="paginationWrap pag-wrap-v2"> <?php echo ($result_employers) ? $links : '';?> </div>
        </div>
    </div>
</div>
</div>
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<!-- Profile Popups -->
<?php $this->load->view('employer/common/employers_popup_forms'); ?>
<?php $this->load->view('common/before_body_close'); ?>
<script src="<?php echo base_url('public/js/validate_employer.js');?>" type="text/javascript"></script>
</body>
</html>