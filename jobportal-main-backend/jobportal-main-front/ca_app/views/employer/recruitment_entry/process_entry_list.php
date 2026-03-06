<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view('common/meta_tags'); ?>
        <title>
            <?php echo $title; ?>        
        </title>
        <?php $this->load->view('common/before_head_close'); ?>
        <style type="text/css">
            .dropdown-menu li {
                margin: 0;
                padding: 1px;
                border: none;
            }

            .aboutloc {
                font-style: italic;
                color: #888888;
            }

            #content-log {
                padding-top: 10px;
                font-style: italic;
            }

            #content-log span {
                display: block;
                margin: 8px 0;
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
                            <?php $this->load->view('employer/common/menu/sidebar'); ?>
                        </div>
                    </div>

                    <div class="col-md-9"> 
                        <?php echo $this->session->flashdata('msg'); ?>
                        <!--Job Application-->
                        <div class="formwraper">
                            <div class="titlehead">
                                <div class="row">
                                    <div class="col-md-12">
                                        <b>
                                            Lista procesos
                                        </b>
                                    </div>
                                </div>
                            </div>

                            <div class="table-search">
                                <?php echo form_open('employer/recruitment_entry/process_entry_list/search', array('method' => 'get')); ?>  
                                    <table width="100%">
                                        <tr>
                                            <td width="5"></td>
                                            <td width="90%">
                                                <input type="text" name="query" class="form-control" value="<?php e($filters['query']); ?>" placeholder="Buscar procesos"> 
                                            </td>
                                            <td width="10%">
                                                <button type="submit" class="btn btn-block btn-search">
                                                <i class="glyphicon glyphicon-search"></i>
                                            </button>     
                                            </td>
                                            <td align="right"></td>
                                        </tr>
                                    </table>
                                <?php echo form_close(); ?>
                
                            </div>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>
                                            RyS Id
                                        </th>
                                        <th>
                                            Solicitud Id
                                        </th>
                                        <th>Proceso</th>
                                        <th>
                                            Total Candidatos
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($results as $row): ?>
                                        <tr>
                                            <td>
                                                <?php e($row->job_ID); ?>
                                            </td>
                                            <td>
                                                <?php e($row->request_ID); ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo site_url('employer/recruitment_entry/entry_list/search?job_id=' . $row->job_ID . '&view=process'); ?>">
                                                    <?php e($row->job_title); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?php e($row->candidate_totals); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            
                            <?php if (count($results) == 0): ?>
                                <div align="center" class="text-red" style="padding: 20px;">
                                    <h4>Sin resultados</h4>
                                </div>              
                            <?php endif; ?>
                        </div>
                        
                        <div class="paginationWrap pag-wrap-v2">
                            <?php echo ($results) ? $links : ''; ?>        
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php $this->load->view('common/bottom_ads');?>
        <!--Footer-->
        <?php $this->load->view('common/footer'); ?>
        <!-- Profile Popups -->
        <?php $this->load->view('common/before_body_close'); ?>
    </body>
</html>