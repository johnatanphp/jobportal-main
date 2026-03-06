<!-- Start section select request type -->
<div id="section-request-type">
  <div class="row">
    <div class="col-md-12">
      <div class="step-title">Datos de la solicitud</div>
      <div class="step-inputs">
        <input type="hidden" name="request_id" value="<?php echo $request->ID; ?>">
        <div class="input-group" >
          <label class="input-group-addon">Tipo de requerimiento <span>*</span></label>
          <select id="type-requirement" name="type_requirement" class="form-control" style="width: 100%;">
            <option value="">Seleccione</option>
            <option value="EVALUACIÓN" <?php echo $request->type_requirement == 'EVALUACIÓN' ? 'selected' : ''; ?>>EVALUACIÓN</option>
            <option value="CONTRATACIÓN" <?php echo $request->type_requirement == 'CONTRATACIÓN' ? 'selected' : ''; ?>>CONTRATACIÓN</option>
            <option value="RECLUTAMIENTO Y SELECCIÓN" <?php echo $request->type_requirement == 'RECLUTAMIENTO Y SELECCIÓN' ? 'selected' : ''; ?>>RECLUTAMIENTO Y SELECCIÓN</option>
            <option value="INDIGACIÓN SALARIAL" <?php echo $request->type_requirement == 'INDIGACIÓN SALARIAL' ? 'selected' : ''; ?>>INDIGACIÓN SALARIAL</option>
          </select>
        </div>
        
        <div class="input-group" >
          <label class="input-group-addon">Tipo de egreso <span>*</span></label>
          <select id="type-expense" name="type_expense" class="form-control" style="width: 100%;">
            <option value="">Seleccione</option>
            <option value="EONF" <?php echo $request->type_expense == 'EONF' ? 'selected' : ''; ?>>EONF</option>
            <option value="EOF" <?php echo $request->type_expense == 'EOF' ? 'selected' : ''; ?>>EOF</option>
            <option value="EOFDP" <?php echo $request->type_expense == 'EOFDP' ? 'selected' : ''; ?>>EOFDP</option>
          </select>
        </div>

        <div class="input-group" >
          <label class="input-group-addon">Consultora <span>*</span></label>
          <select  id="consultant" name="consultant_name" class="form-control" style="width: 100%;">
            <option value="">Seleccione</option>
            <?php foreach ($consultants as $consultant): ?>
              <?php 
                $consultant_val = $consultant['NO_CIA'] . '|' . $consultant['CONSULTORA'];
                $consultant_selected = $consultant_val == $request->no_cia . '|' . $consultant['CONSULTORA'];
              ?>
              <option value="<?php echo $consultant_val; ?>"
                      data-no_cia="<?php echo $consultant['NO_CIA']; ?>"
                      <?php echo $consultant_selected ? 'selected="selected"' : ''; ?>>
                <?php echo $consultant['CONSULTORA']; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="input-group" >
          <label class="input-group-addon">Unidad de negocio <span>*</span></label>
          <select id="business_unit" name="business_unit_name" class="form-control" style="width: 100%;">
            <option value="">Seleccione</option>

            <?php foreach ($business_units as $row): ?>
              <option data-uni_neg="<?php echo $row->business_unit_code; ?>" 
                      <?php echo $row->business_unit_code == $request->cod_business_unit ? 'selected' : ''; ?>
                      value="<?php echo $row->business_unit_code . '|' . $row->business_unit_name; ?>">
                  <?php echo $row->business_unit_name; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        
        <div class="input-group" >
          <label class="input-group-addon">Empresa cliente <span>*</span></label>
          <select id="client_company" name="client_company_name" class="form-control" style="width: 100%;">
            <option value="">Seleccione</option>
            <?php foreach ($clients as $row): ?>
              <?php 
                $client_val = $row['COD_CLIE'] . '|' . $row['CLIENTE'];
                $client_selected = $client_val == $request->cod_clie . '|' . $row['CLIENTE'];
              ?>
              <option value="<?php echo $client_val; ?>"
                      data-cod_clie="<?php echo $row['COD_CLIE']; ?>"
                      <?php echo $client_selected ? 'selected="selected"' : ''; ?>>
                <?php echo $row['CLIENTE']; ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="input-group" >
          <label class="input-group-addon">Centro de costo <span>*</span></label>
          <select id="cost_center" name="cost_center" class="form-control" style="width: 100%;">
            <option value="">Seleccione</option>
            <?php foreach ($cost_centers as $row): ?>
              <?php 
                $cost_center_val =  $row['COD_CCOSTO'];
                $cost_center_selected = $cost_center_val == $request->cost_center;
              ?>
              <option value="<?php echo $cost_center_val; ?>"
                      <?php echo $cost_center_selected ? 'selected="selected"' : ''; ?>>
                <?php echo $row['COD_CCOSTO']; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="input-group">
          <label class="input-group-addon">Centro de costo cliente<span></span></label>
          <input type="text" name="cost_center_client" class="form-control" value="<?php e($request->cost_center_client); ?>">
        </div>

        <div class="input-group container-wf-areas" style="display:none;">
          <label class="input-group-addon">Area<span></span></label>
          <select id="wf-areas" name="wf_area_code" class="form-control" style="width: 100%;">
            <option value="">Seleccione</option>
          </select>
        </div>

        <div class="input-group">
          <label class="input-group-addon">EECC <span></span></label>
          
          <table id="tbl-field-select-eecc" width="100%">
            <?php if (!$request->eecc_code): ?>
              <tr>
                <td align="left" style="vertical-align: top;padding:5px 8px;display: none;">
                
                </td>
                <td width="10" align="left" style="vertical-align: top;padding: 8px;cursor:default;" class="btn-show-select-eecc">
                  Seleccione
                </td>
                <td width="10" `align="left" style="vertical-align: top;padding: 8px;display: none;">
                  <a id="btn-remove-select-eecc" href="#">
                    <i class="glyphicon glyphicon-remove"></i>
                  </a>        
                </td>
              </tr>
            <?php endif; ?>

            <?php if ($request->eecc_code): ?>
              <tr>
                <td align="left" style="vertical-align: top;padding:5px 8px;">
                  <?php e($request->eecc_code); ?>
                  <br>
                  <?php e($request->eecc_form_id); ?>
                  <input type="hidden" name="eecc_code" value="<?php e($request->eecc_code); ?>">
                  <input type="hidden" name="eecc_description" value="<?php e($request->eecc_description); ?>">
                  <input type="hidden" name="eecc_form_id" value="<?php e($request->eecc_form_id); ?>">
                  <input type="hidden" name="eecc_job_code" value="<?php e($request->eecc_job_code); ?>">
                  <input type="hidden" name="eecc_job_vacancies" value="<?php e($request->eecc_job_vacancies); ?>">
                </td>
                <td width="10" align="left" style="vertical-align: top;padding: 8px;cursor:default;" class="btn-show-select-eecc">
                  <a id="btn-select-eecc" href="#">
                    <i class="glyphicon glyphicon-pencil"></i>
                  </a>
                </td>
                <td width="10" `align="left" style="vertical-align: top; padding: 8px;">
                  <a id="btn-remove-select-eecc" href="#">
                    <i class="glyphicon glyphicon-remove"></i>
                  </a>        
                </td>
              </tr>
            <?php endif; ?>
          </table>
        </div>
        
        <div class="input-group">
          <label class="input-group-addon">Plantilla <span>*</span></label>
          <select name="request_template" type="text" class="form-control" id="request_template" style="width: 100%;">
            <option value="">Seleccione</option>
            <?php if ($this->config->item('job_profile_module_enabled')): ?>
              <option value="1">Perfil de puesto</option>
            <?php endif; ?>
            <?php if ($this->config->item('job_layouts_module_enabled')): ?>
              <option value="2" selected>Layout de puesto</option>
            <?php endif; ?>
          </select>
        </div>

        <div class="input-group" style="display:none;">
          <label class="input-group-addon">Perfil de puesto <span>*</span></label>
          <select  id="job-profile" name="job_profile" class="form-control" style="width: 100%;">
            <option value="">Seleccione</option>
          </select>
        </div>

        <div class="input-group">
          <label class="input-group-addon">Layout de puesto <span>*</span></label>
          <select name="job_layout" type="text" class="form-control" id="job_layout" style="width: 100%;">
            <option value="">Seleccione</option>
            <?php foreach ($job_layouts as $row): ?>
              <option value="<?php echo $row->id; ?>" 
                      data-code-integration="<?php echo $row->code_integration; ?>"
                      <?php echo $request->job_layout_id == $row->id ? 'selected' : ''; ?>>
                <?php e($row->code . ' - ' . $row->job_title); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="input-group">
          <label class="input-group-addon">Código modelo contrato<span></span></label>
          <input type="text" name="contract_type_model_code" class="form-control" value="<?php e($request->contract_type_model_code); ?>" >
        </div>
        
      </div>
    </div>
  </div>
</div>
<!-- End section select request type -->