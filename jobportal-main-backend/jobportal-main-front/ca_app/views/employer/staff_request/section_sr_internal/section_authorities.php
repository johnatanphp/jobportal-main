<div id="authorities">
  <div class="step-title">Autoridades encargadas de aprobar la solicitud</div>
  <div class="step-inputs" style="max-width: 500px; margin: 0 auto;margin-top: 10px;">
    <div class="form-group <?php echo (form_error('authorities[1][]'))?'has-error':'';?>">
      <label class="label-authority"><b>Aprobador de Unidad de Negocio</b> <span></span></label>
      <table id="table-authorities-DR">
        <tbody></tbody>
      </table>
    </div>

    <div class="form-group <?php echo (form_error('authorities[2]'))?'has-error':'';?>">
      <label class="label-authority"><b>Gerente Administrativo</b> <span>*</span></label>
      <select name="authorities[2]" class="form-control">
        <option value="">Seleccione</option>
        <?php foreach ($authorities2 as $row_authority): ?>
          <option value="<?php echo $row_authority->name . ',' . $row_authority->email; ?>"><?php echo $row_authority->name; ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group <?php echo (form_error('authorities[3]'))?'has-error':'';?>">
      <label class="label-authority"><b>Gerente / Jefe de Area</b> <span>*</span></label>
      <select name="authorities[3]" class="form-control">
        <option value="">Seleccione</option>
        <?php foreach ($authorities3 as $row_authority): ?>
          <option value="<?php echo $row_authority->name . ',' . $row_authority->email; ?>"><?php echo $row_authority->name; ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group <?php echo (form_error('authorities[4]'))?'has-error':'';?>">
      <label class="label-authority">
        <b>
          GERENTE DE GESTIÓN DE DESARROLLO HUMANO
        </b> 
        <span>*</span>
      </label>
      <select name="authorities[4]" class="form-control">
        <option value="">Seleccione</option>
        <?php foreach ($authorities4 as $row_authority): ?>
          <option value="<?php echo $row_authority->name . ',' . $row_authority->email; ?>"><?php echo $row_authority->name; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
</div>