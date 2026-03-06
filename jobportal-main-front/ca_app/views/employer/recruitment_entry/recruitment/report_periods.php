<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view('common/meta_tags'); ?>
        <title><?php echo $title;?></title>
        <?php $this->load->view('common/before_head_close'); ?>

        <style type="text/css">
            .list-options {
                list-style: none;
                padding-bottom: 3px;
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
                <div class="formwraper">
                    <div class="titlehead">
                        <div class="row">
                            <div class="col-md-12">
                                Reporte Auto-determlnación de porcentajes
                            </div>
                        </div>
                    </div>
                    <div class="formint">

                        <?php echo form_open('employer/recruitment_entry/recruitment_candidates/generate_report_periods', ['method' => 'get']); ?>
                       <div>
                            <div style="padding: 12px 0;">
                                <div class="input-group">
                                    <label class="input-group-addon">Consultora</label>
                                    <select class="form-control" name="no_cia" required="true" style="width:100%">
                                        <option value="">Seleccione</option>
                                        <?php foreach ($consultants as $row): ?>
                                            <option value="<?php echo $row->NO_CIA; ?>">
                                                <?php echo $row->CONSULTORA; ?>
                                            </option>
                                        <?php endforeach; ?>                                 
                                    </select>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-group-addon">Año</label>
                                    <select class="form-control" name="period_year" required="true">
                                        <option value="">Seleccione</option>
                                        <?php for ($i = 2021; $i <= date('Y'); $i++): ?>
                                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php endfor; ?>    
                                    </select>
                                </div>

                                <div class="input-group">
                                    <label class="input-group-addon">Mes</label>
                                    <select class="form-control" name="period_month" required="true">
                                        <option value="">Seleccione</option>
                                        <?php for ($i = 1; $i <= 12; $i++): ?>
                                            <option value="<?php echo $i < 10 ? '0' . $i : $i; ?>"><?php echo $i < 10 ? '0' . $i : $i; ?></option>
                                        <?php endfor; ?>    
                                    </select>
                                </div>

                                <div style="text-align: center;">
                                    <input type="submit" value="Generar reporte" class="btn btn-primary">
                                </div>
                            </div>
                        </div>
                        <?php form_close(); ?>
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

    <script type="text/javascript">
        $(document).ready(function(){
            

        });
    </script>
</body>
</html>