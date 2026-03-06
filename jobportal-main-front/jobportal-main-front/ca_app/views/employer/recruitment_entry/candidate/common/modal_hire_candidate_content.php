
<?php echo form_open('employer/recruitment_entry/recruitment_candidates/hire_candidate', ['id' => 'form-hire-candidate']); ?>
    <div class="modal-header">
        <h4 class="modal-title">Contratar candidato</h4>
    </div>
    <div class="modal-body">
        <div style="margin: 10px 0;">
            Para terminar el proceso de contratación por favor ingrese los datos solicitados y haga clic en "Contratar".
            <br>
            <br>
        </div>
        <div class="info-required">
            <span>*</span> Datos obligatorios
        </div>
        <input type="hidden" name="process_id" value="<?php echo $process_candidate->process_id; ?>">
        <input type="hidden" name="seeker_id" value="<?php echo $process_candidate->seeker_ID; ?>">
        <input type="hidden" name="request_id" value="<?php echo @$staff_request->ID; ?>">
        <input type="hidden" name="no_cia" value="<?php echo $staff_request->no_cia; ?>">
        <input type="hidden" name="ignore_rightful_claimants" value="0">
    
        <label>Vigencia del contrato <span>*</span></label>
        <table class="table" style="margin-bottom: 0;">
            <tr>
                <td>
                    <label>Desde</label>
                    <input type="date" 
                            name="contract_start_date" 
                            value="<?php echo $staff_request->start_date_work; ?>" 
                            class="form-control" 
                            min="<?php echo date('Y-m-d'); ?>"
                            required/>
                </td>
                <td>
                    <label>Hasta</label>
                    <input type="date" 
                            name="contract_end_date" 
                            value="<?php echo $staff_request->end_date_work; ?>" 
                            class="form-control" 
                            min="<?php echo date('Y-m-d'); ?>"
                            required/>
                </td>
            </tr>
        </table>

        <?php if ($this->config->item('system_payroll') == 'ca'): ?> 
            <br />
            <label>Trabajador categoria <span>*</span></label>
            <select class="form-control" name="employee_category_id" required>
                <?php foreach ($employee_categories as $row): ?>
                    <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                <?php endforeach; ?>
            </select>
            
            <br />
            <label>Trabajador tipo <span>*</span></label>
            <select class="form-control" name="employee_type_id" required>
                <option value="">Seleccione</option>
                <?php foreach ($employee_types as $row): ?>
                    <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                <?php endforeach; ?>
            </select>

        <?php endif; ?>    

        <?php if ($process_country && $process_country->iso_3166_1_alpha2 != 'PE'): ?>
            <br />
            <label>Modelo de contrato <span>*</span></label>
            <select class="form-control" name="contract_type_model" required style="width: 100%;">
                <option value="">Seleccione</option>
                <?php foreach ($contract_models as $row): ?>
                    <option value="<?php echo $row->code . '||' . $row->name; ?>"><?php echo $row->code . ' - ' . $row->name; ?></option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
        
        <br>
        <div class="panel-group accordion-notifications" id="accordion-notifications" style="margin-bottom: 5px;">
            <div class="panel panel-default">
                <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion-notifications" href="#collapse1" style="justify-content: end;">
                        <label style="margin: 0 10px 0 0;color: #666;font-size: 13px;">
                            <span class="glyphicon glyphicon-bell" style="color: #666;margin-right: 5px;"></span>
                            ¿Notificar al postulante?
                        </label>
                        <span class="glyphicon glyphicon-chevron-right"></span>
                    </a>
                </h4>
                </div>
                <div id="collapse1" class="panel-collapse collapse">
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-xs-7">
                                <label style="font-weight: normal;font-size: 13px;">Por correo</label>
                            </div>
                            <div class="col-xs-5">
                                <div class="checkbox-wrapper-2" style="float: right;">
                                    <input class="tgl tgl-light" id="notify-candidate-by-mail" type="checkbox" name="notify_candidate_by_mail" value="1" />
                                    <label class="tgl-btn" for="notify-candidate-by-mail" style="width: 35px;height: 19px;">
                                </div>
                            </div>    
                        </div>
                        <div class="row">
                            <div class="col-xs-7">
                                <label style="font-weight: normal;font-size: 13px;">Por WhatsApp</label>
                            </div>
                            <div class="col-xs-5">
                                <div class="checkbox-wrapper-2" style="float: right;">
                                    <input class="tgl tgl-light" id="notify-candidate-by-whatsapp" type="checkbox" name="notify_candidate_by_whatsapp" value="1" />
                                    <label class="tgl-btn" for="notify-candidate-by-whatsapp" style="width: 35px;height: 19px;">
                                </div>
                            </div>    
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-group accordion-notifications" id="accordion-notifications-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion-notifications-2" href="#collapse2" style="justify-content: end;">
                        <label style="margin: 0 10px 0 0;color: #666;font-size: 13px;">
                            <span class="glyphicon glyphicon-bell" style="color: #666;margin-right: 5px;"></span>
                            ¿Notificar al reclutador?
                        </label>
                        <span class="glyphicon glyphicon-chevron-right"></span>
                    </a>
                </h4>
                </div>
                <div id="collapse2" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-7">
                                <label style="font-weight: normal;font-size: 13px;">Por Correo</label>
                            </div>
                            <div class="col-xs-5">
                                <div class="checkbox-wrapper-2" style="float: right;">
                                    <input class="tgl tgl-light" id="notify-employer-by-mail" type="checkbox" name="notify_employer_by_mail" value="1" />
                                    <label class="tgl-btn" for="notify-employer-by-mail" style="width: 35px;height: 19px;">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button id="btn-hire-candidate" class="btn btn-primary" type="submit">Contratar</button>
    </div>
<?php echo form_close(); ?>
 
<script type="text/javascript">
$(function(){
    function getContractModels(params, valSelected) {

        valSelected = valSelected || false;
        const url = "<?php echo site_url('general/overall_web_services/get_contract_models'); ?>";
        const data = params;

        const select = $( 'select[name="contract_type_model"]', '#modal-hire-candidate');
        select.html('<option value="">Cargando...</option>').prop('disabled', true);

        $.post(url, data, function(response) {

            const results =  response.data;
            
            $.each(results, function(i, row) {

                const isSelected = row.contract_model_code == valSelected;
                select.append(`
                    <option value="${row.contract_model_code}||${row.contract_model_name}" 
                            ${isSelected ? 'selected' : ''}>
                        ${row.contract_model_code} - ${row.contract_model_name}
                    </option>
                `);
            });     
        }, 'json')
        .fail(function() {
            toastr["error"]('¡Ha ocurrido un error al tratar de listar los modelos de contratos!');
        }).always(function() {
            select.find("option:eq(0)").text("Seleccione");
            select.prop('disabled', false);
        }); 
    }

    $( 'input[name=date_admission]', '#modal-hire-candidate' ).change(function(){

        var url = "<?php echo site_url('employer/recruitment_entry/recruitment_candidates/get_foraign_totals'); ?>";
        var data = {
            'date_admission' : $(this).val(), 
            'no_cia': $( "#no_cia" ).val()
        }

        $( "#wrapper-totals" ).html("Consultando...").show();

        $.post(url, data, function(response) {
            var status = response.success;
            if (status) {

                $( "#wrapper-totals" ).html(
                    "<div><label>Periodo: </label> " + response.period + "</div><div><label>Cant. disponible para contratar:</label> " + response.total_trab + "</div><div><label>Remuneración disponible: </label>  S/. " + response.total_amount + "</div>"
                );
            } else {
                $( "#wrapper-totals" ).html("").hide();
                toastr["error"](response.message);
            }
        }, 'json')
        .fail(function(){
            $( "#wrapper-totals" ).html("").hide();
            alert("¡Ha ocurrido un error!");
        })
        .always(function(){
            //$( "#btn-request-documents" ).attr({'disabled': false });
        });
    });

    $( 'input[name=contract_start_date]', '#modal-hire-candidate' ).change(function(){
        $( 'input[name=contract_end_date]', '#modal-hire-candidate').val('');
        $( 'input[name=contract_end_date]', '#modal-hire-candidate' ).prop('min', $(this).val());
    });

    $( 'form', '#modal-hire-candidate' ).submit(function(e) {
        
        e.preventDefault();

        const url = $(this).prop('action');
        const data = $(this).serialize();

        const buttons = $( '#modal-hire-candidate .modal-footer' ).find('button');
        const btnConfirmHire = $( "#btn-confirm-hire-candidate" );
        const btnHire = $( '#btn-hire-candidate' );

        buttons.prop('disabled', true);
        btnHire.html('Contratando...');

        $.post(url, data, function(response) {
            
            const status = response.status;

            if (status) {
                buttons.hide();
                toastr["success"](response.message);
                setTimeout(function(){
                    window.location.reload();
                }, 500);
                return;
            }

            const rightfulClaimantsError = response.rightful_claimants_error || false;

            if (rightfulClaimantsError == true) {
                $( '#modal-confirm-ignore-rightful-claimants' ).modal('show');
                return;    
            }

            toastr["error"](response.message);     

            setTimeout(function(){
                window.location.reload();
            }, 1500);
        }, 'json')
        .fail(function(){
            toastr["error"]('¡Ha ocurrido un error!');
        })
        .always(function() {
            $( '#form-hire-candidate' ).find('input[name="ignore_rightful_claimants"]').val('0');
            buttons.prop('disabled', false);
            btnHire.html('Contratar');
        });

        return false;
    });

    $( '#btn-confirm-hire-candidate' ).click(function(){        
        
        <?php if ($this->config->item('system_payroll') == 'ca'): ?> 
            const contractTypeModelCode = "<?php echo $staff_request->contract_type_model_code; ?>";
            getContractModels({
                no_cia: "<?php echo $staff_request->no_cia; ?>",
                client_code: "<?php echo $staff_request->cod_clie; ?>",
            }, contractTypeModelCode);
        <?php endif; ?>
        
        $( '#modal-hire-candidate' ).modal('show');
    });

    $( 'select[name="contract_type_model"]' ).select2();
});
</script>
