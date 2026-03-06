<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view('common/meta_tags'); ?>
        <title><?php echo $title;?></title>
        <?php $this->load->view('common/before_head_close'); ?>
        <style type="text/css"> 
            .formwraper p{
                font-size:13px;
            }

            #modal-filter-request .modal-content {
                max-width: 370px;
                margin:0 auto;
            }

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

            .text-info {
                color:#555;
                font-style: italic;
                display: block;
                font-size: 12px;
                margin-top: 2px;
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
                    <?php $this->load->view('jobseeker/common/jobseeker_menu');?>
                </div>
                </div>
                    <div class="col-md-9"> 
                        <?php echo $this->session->flashdata('msg');?>
                        <div class="formwraper">
                            <div class="titlehead">
                                <div class="row">
                                    <div class="col-md-12">
                                        <b>Mis Encuestas</b>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Description -->
                            <div class="table-responsive">
                                <table width="100%" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th width="5%" style="text-align: center;">
                                                Código
                                            </th>
                                            <th>
                                                Encuesta
                                            </th>
                                            <th>
                                                Respondida
                                            </th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($results as $index => $poll): ?>
                                            <tr>
                                                <td width="5%" style="text-align: center;">
                                                    <?php e($poll->form_assignment_id); ?>
                                                </td>
                                                <td>
                                                    <?php e($poll->form_name); ?>
                                                </td>
                                                <td>
                                                    <?php echo $poll->form_poll_answered ? 'SI' : 'NO'; ?>
                                                </td>
                                                <td>
                                                    <?php if ($poll->form_poll_answered): ?>
                                                        <a href="<?php echo site_url('jobseeker/forms/show/' .  $poll->form_assignment_id); ?>">
                                                            Ver       
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="<?php echo site_url('jobseeker/forms/answer/' .  $poll->form_assignment_id); ?>">
                                                            Responder      
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                             </div>
                            
                            <?php if (count($results) == 0): ?>
                                <div align="center" class="text-red" style="padding: 20px;">
                                    <h4>Sin registros</h4>
                                </div>              
                            <?php endif; ?>
                           
                        </div>
                        <!--Pagination-->
                        <div class="paginationWrap pag-wrap-v2"> <?php echo ($results) ? $links : '';?> </div>
                    </div>
                </div>
            </div>

            <?php $this->load->view('common/bottom_ads');?>
            <!--Footer-->
            <?php $this->load->view('common/footer'); ?>
            <!-- Profile Popups -->
            <?php $this->load->view('common/before_body_close'); ?>
        </div>
    </body>
</html>