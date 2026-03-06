<div id="section-request-type">
    <div class="row">
      <div class="col-md-12">
        <div class="step-title">Datos de la solicitud</div>
        <div class="step-inputs">
        <div class="input-group" >
            <label class="input-group-addon">Tipo de requerimiento <span>*</span></label>
            <select id="type-requirement" name="type_requirement" class="form-control" style="width: 100%;">
              <option value="">Seleccione</option>
              <option value="EVALUACIÓN">EVALUACIÓN</option>
              <option value="CONTRATACIÓN">CONTRATACIÓN</option>
              <option value="RECLUTAMIENTO Y SELECCIÓN">RECLUTAMIENTO Y SELECCIÓN</option>
              <option value="INDIGACIÓN SALARIAL">INDIGACIÓN SALARIAL</option>
            </select>
          </div>
          <div class="input-group" >
            <label class="input-group-addon">Tipo de egreso <span>*</span></label>
            <select id="type-expense" name="type_expense" class="form-control" style="width: 100%;">
              <option value="">Seleccione</option>
              <option value="EI">EI</option>
            </select>
          </div>
          <div class="input-group" >
            <label class="input-group-addon">Consultora <span>*</span></label>
            <select  id="consultant" name="consultant_name" class="form-control" style="width: 100%;">
              <option value="">Seleccione</option>    
            </select>
          </div>

          <div class="input-group" >
            <label class="input-group-addon">Unidad de negocio <span>*</span></label>
            <select id="business_unit" name="business_unit_name" class="form-control" style="width: 100%;">
              <option value="">Seleccione</option>
  
              <?php foreach ($business_units as $row): ?>
                <option data-uni_neg="<?php echo $row->business_unit_code; ?>" 
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
            </select>
          </div>

          <div class="input-group" >
            <label class="input-group-addon">Centro de costo <span>*</span></label>
            <select id="cost_center" name="cost_center" class="form-control" style="width: 100%;">
              <option value="">Seleccione</option>
            </select>
          </div>

          <div class="input-group">
            <label class="input-group-addon">Centro de costo cliente<span></span></label>
            <input type="text" name="cost_center_client" class="form-control">
          </div>

          <div class="input-group">
            <label class="input-group-addon">Código modelo contrato<span></span></label>
            <input type="text" name="contract_type_model_code" class="form-control" >
          </div>
          
        </div>
      </div>
    </div>
</div>
