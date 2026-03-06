<div class="modal-header">                
    <table width="100%">
        <tr>
            <td width="20">
                <a href="#" class="close" data-dismiss="modal">
                    <i class="fa fa-arrow-left"></i>
                </a>
            </td>
            <td>
                <h4> 
                    Screening <?php echo $screening_type->name; ?>
                </h4>
            </td>
            <td align="right">
                <a href="<?php echo site_url('candidate/screening_show_pdf/' . $this->custom_encryption->encrypt_data($screening->id)); ?>" target="_blank">
                    <i class="glyphicon glyphicon-save"></i>
                    &nbsp;Descargar
                </a>
            </td>
        </tr>
    </table>
</div>
<div class="modal-body" style="padding: 5px 10px;">
    <ul class="nav nav-pills" style="margin-bottom: 3px;">
        <li class="active"><a data-toggle="pill" href="#show">Detalle</a></li>
        <?php if ($this->config->item('env') != 'production'): ?>
            <li><a data-toggle="pill" href="#data">Datos</a></li>
        <?php endif; ?>
    </ul>
    <div class="tab-content">
        <div id="show" class="tab-pane fade in active" style="background: #e0e0e0; padding: 20px 0;">
            <div>
                <div style="max-width: 800px;margin: 0 auto; padding: 15px;border:1px solid #eee;background: #fff; box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;">
                    
                    <?php if ($screening->type_id == '1'): ?>
                        <?php $this->load->view('employer/screening/template/screening_basic'); ?>
                    <?php endif; ?>

                    <?php if ($screening->type_id == '2'): ?>
                        <?php $this->load->view('employer/screening/template/screening_vip'); ?>
                    <?php endif; ?>

                </div>
            </div>
        </div>
        <?php if ($this->config->item('env') != 'production'): ?>
            <div id="data" class="tab-pane fade">    
                <div id="cont-display-json">
                    <pre>
                        <?php echo $screening->response; ?>
                    </pre>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>