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
                                Reporte programaciones
                            </div>
                        </div>
                    </div>
                    <div class="formint">
                       <div>
                                <div style="padding: 12px 0;">
                                   <div class="input-group">
                                        <label class="input-group-addon">Examen <span></span></label>
                                        <select id="exam-type" class="form-control" name="exam_type" required="true" style="width: 100%;">
                                            <option value="">Seleccione</option>
                                            <option value="1">EMO</option>
                                            <option value="2">COVID 19</option>
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <label class="input-group-addon"><span></span></label>
                                        <input id="radio-date-range" type="radio" name="date" value="range" checked="true">
                                        &nbsp;
                                        <label for="radio-date-range">Rango de fecha</label>
                                        
                                        <br />
                                        <select id="select-date-range" class="form-control">
                                            <?php foreach($date_ranges as $key => $val): ?>
                                                <option value="<?php echo $key; ?>">
                                                    <?php echo $val; ?>
                                                </option>

                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <label class="input-group-addon"><span></span></label>

                                        <input id="radio-date-custom" type="radio" name="date" value="custom">
                                        &nbsp;
                                        <label for="radio-date-custom">Personalizado</label>
                                        <br />
                                        <table>
                                            <tr>
                                                <td>
                                                    <input id="start-date" type="date" name="start_date" class="form-control date-custom" required="true">
                                                </td>
                                                <td>
                                                    <input id="end-date" type="date" name="end_date" class="form-control date-custom" required="true" >
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div style="text-align: center;">
                                        <input id="export" type="button" value="Exportar" class="btn btn-primary">
                                    </div>
                                </div>
                        </div>
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
            
            $( '#export' ).click(function() {

                val = $( 'input[name="date"]:checked' ).val();

                if (val == 'range') {
                    rangeDate = $( '#select-date-range' ).val();
                    range = rangeDate.split('/');
                    data = 'start_date=' + range[0] + '&end_date=' + range[1];
                } else {
                    data = 'start_date=' + $( '#start-date' ).val() + '&end_date=' + $( '#end-date' ).val() 
                }

                data+='&exam_type=' + $( '#exam-type' ).val();

               window.location = 'export/?' + data;
            });

            $( '#select-date-range' ).change(function(){
                $( '#radio-date-range' ).click();
            });

            $( '.date-custom' ).change(function(){
                $( '#radio-date-custom' ).click();
            });

        });
    </script>
</body>
</html>