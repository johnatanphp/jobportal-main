<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style type="text/css"> 
    .formwraper p{font-size:13px;}

    #modal-filter-jobs .modal-content {
        max-width: 370px;
        margin:0 auto;
    }

    #modal-filter-jobs .modal-body {
        padding: 0px 15px;
    }

    #modal-filter-jobs .modal-title {
        font-size: 23px;
    }

    .formwraper p{font-size:13px;}

    .label-check i,
    .label-radio i {
        color: #333;
        vertical-align:text-bottom;
        font-size: 20px;
    }

    .panel-filter {
        padding: 10px 0px;
    }
    .panel-filter label {
        display: block;
        font-size: 16px;
        font-weight: normal;
    }

    .panel-filter .filter-title {
        padding: 6px 0px;
        border-bottom: 2px solid #1ba6df;
        margin-bottom: 6px;
    }

    .panel-filter .filter-title h4 {
        font-weight: bold;
    }

    .dropdown-options-job .dropdown-toggle {
        background: transparent;
        padding: 1px;
    }

    .text-info {
        color:#555;
        font-style: italic;
        display: block;
        font-size: 12px;
        margin-top: 2px;
    }

    .wrapper-table table td {
        padding: 4px;
    }

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
            <?php echo $this->session->flashdata('msg'); ?>
            <!--Job Application-->
            <div class="formwraper">
                <div class="titlehead">
                    <div class="row">
                        <div class="col-md-12">
                            <b>Formularios creados</b>
                        </div>
                    </div>
                </div>
                
                <!--Job Description-->
                <div class="row" style="padding: 8px;">
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <a href="<?php echo site_url('employer/rys_forms/save'); ?>"
                           class="btn btn-xs btn-primary pull-right">
                            Crear
                        </a>
                    </div>
                </div>  
                <div class="table-responsive">
                    <!--Job Row-->  
                    <table width="100%" class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    ID
                                </th>
                                <th>
                                    Nombre
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($forms as $form): ?> 
                                <tr>
                                    <td>
                                        <?php echo $form->form_id; ?>
                                    </td>
                                    <td>
                                        <?php echo $form->name; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo site_url('employer/rys_forms/save/' . $form->form_id); ?>">
                                            Editar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>    
                </div>
                
                <?php if ($total_rows == 0): ?>
                    <div align="center" class="text-red" style="padding: 20px 10px;">
                        <h4>Ningún resultado</h4>
                    </div>              
                <?php endif; ?>
            </div>
            <div class="paginationWrap pag-wrap-v2">
                <?php echo $forms ? $links : '';?>      
            </div>
        </div>
        <!--/Job Detail-->
        <!--Pagination-->
    </div>
</div>
</div>
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<!-- Profile Popups -->
<?php $this->load->view('employer/common/employers_popup_forms'); ?>
<?php $this->load->view('common/before_body_close'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        
    });
</script>
</body>
</html>