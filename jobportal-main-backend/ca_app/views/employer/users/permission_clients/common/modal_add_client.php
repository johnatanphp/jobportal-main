<div id="modal-add-companies" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Agregar Empresas</h4>
      </div>
      <div class="modal-body">
        <div id="content-consultant">
          <label>Consultora <span></span></label>
          <br />
          <select  id="consultant" name="consultant_name" class="form-control" style="width: 100%;">
            <option value="">Seleccione</option>
          </select>
        </div>

        <div id="content-business-unit">
          <label>Unidad de negocio <span></span></label>
          <br />
          <select id="business-unit" name="business_unit_name" class="form-control" style="width: 100%;">
            <option value="">Seleccione</option>
            <?php foreach ($business_units as $row): ?>
              <option data-uni_neg="<?php echo $row->business_unit_code; ?>" 
                      value="<?php echo $row->business_unit_code . '|' . $row->business_unit_name; ?>">
                  <?php echo $row->business_unit_name; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div id="content-client-company">
          <label >Empresa cliente <span></span></label>
          <br />
          <select id="client-company" name="client_company_name" class="form-control" style="width: 100%;">
            <option value="">Seleccione</option>
          </select>
        </div>

        <div id="content-cost-center">
          <label>Centro de costo <span></span></label>
          <br />
          <select id="cost-center" name="cost_center" class="form-control" style="width: 100%;">
            <option value="">Seleccione</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button id="add-company" type="button" class="btn btn-primary" >Agregar</button>
      </div>
    </div>
  </div>
</div>