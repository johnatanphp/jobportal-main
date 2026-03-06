<div id="section-job-requeriments">
  <div class="step-title">
    Requisitos del puesto
  </div>
  <div class="step-inputs">
    <!--
    <div class="input-group <?php echo (form_error('preferred_profession'))?'has-error':'';?>">
      <label class="input-group-addon">Profesión preferentes <span>*</span></label>
      <input name="preferred_profession" type="text" class="form-control" id="preferred_profession" value="<?php echo set_value('preferred_profession') ? set_value('preferred_profession') : @$request->preferred_profession; ?>">
      <?php echo form_error('preferred_profession'); ?>
    </div>
    <div class="input-group <?php echo (form_error('related_profession'))?'has-error':'';?>">
      <label class="input-group-addon">Profesión afines<span></span></label>
      <input name="related_profession" type="text" class="form-control" id="related_profession" value="<?php echo set_value('related_profession') ? set_value('related_profession') : @$request->related_profession; ?>">
      <?php echo form_error('related_profession'); ?>
    </div>
  
    <div class="input-group <?php echo (form_error('level_education'))?'has-error':'';?>">
      <label class="input-group-addon">Nivel de educación <span>*</span></label>
      <select name="level_education" class="form-control" id="level_education">
        <option value="">Seleccione</option>
        <?php foreach ($qualifications as $row_qualification): ?>
          <?php $level_education = set_value('level_education') ? set_value('level_education') : @$request->level_education; ?>
          <option value="<?php echo $row_qualification->text; ?>" <?php echo $level_education == $row_qualification->text ? 'selected="selected"' : ''; ?>><?php echo $row_qualification->text; ?></option>
        <?php endforeach; ?>
      </select>
      <?php echo form_error('level_education'); ?>
    </div>
    
    <div class="input-group <?php echo (form_error('position_experience_time'))?'has-error':'';?>">
      <label  class="input-group-addon" style="white-space: normal;line-height: 1.2;">Tiempo mínimo de experiencia para el puesto <span>*</span></label>
      <select name="position_experience_time" class="form-control" id="position_experience_time">
        <?php $position_experience_time = set_value('position_experience_time') ? set_value('position_experience_time') : @$request->position_experience_time; ?>
        <option value="">Seleccione</option>
        <option value="fresh" <?php echo ($position_experience_time == 'fresh') ? 'selected="selected"' : '';?>>Sin experiencia</option>
        <option value="3m" <?php echo ($position_experience_time == '3m') ? 'selected="selected"' : '';?>>
          3 Meses
        </option>
        <option value="6m" <?php echo ($position_experience_time == '6m') ? 'selected="selected"' : '';?>>
          6 Meses
        </option>
        <?php for ($i = 1; $i <= 10; $i++):
          $selected = ($position_experience_time == $i) ? 'selected="selected"':'';
        ?>
          <option value="<?php echo $i;?>" <?php echo $selected;?>>
            <?php echo $i . ' ' . ($i == 1 ? 'Año' : 'Años'); ?>    
          </option>
        <?php endfor; ?>
        <option value="10+" <?php echo ($position_experience_time == '10+') ? 'selected="selected"' : ''; ?>>
          Más de 10 Años
        </option>
      </select>
      <?php echo form_error('position_experience_time'); ?>
    </div>
    
    <div class="input-group <?php echo (form_error('previous_experience'))?'has-error':'';?>">
      <label class="input-group-addon">Experiencia previa en <span></span></label>
      <input name="previous_experience" type="text" class="form-control" id="previous_experience" value="<?php echo set_value('previous_experience') ? set_value('previous_experience') : @$request->previous_experience; ?>">
      <?php echo form_error('previous_experience'); ?>
    </div>
    -->
    <div class="input-group <?php echo (form_error('gender'))?'has-error':'';?>">
      <label class="input-group-addon">Sexo <span></span></label>
      <select name="gender" class="form-control">
        <?php $gender = set_value('gender') ? set_value('gender') : @$request->gender; ?>
        <option value="" >Seleccione</option>
        <option value="male" <?php echo $gender == 'male' ? 'selected="selected"' : ''; ?>>Hombre</option>
        <option value="female" <?php echo $gender == 'female' ? 'selected="selected"' : ''; ?>>Mujer</option>
        <option value="both" <?php echo $gender == 'both' ? 'selected="selected"' : ''; ?>>Ambos</option>
      </select>
      <?php echo form_error('gender'); ?>
    </div>
    <!--
    <div class="input-group <?php echo (form_error('specialization_or_diploma'))?'has-error':'';?>">
      <label class="input-group-addon">Especialización/Diplomado <span></span></label>
      <input name="specialization_or_diploma" type="text" class="form-control" id="specialization_or_diploma" value="<?php echo set_value('specialization_or_diploma') ? set_value('specialization_or_diploma') : @$request->specialization_or_diploma; ?>">
      <?php echo form_error('specialization_or_diploma'); ?>
    </div>
    -->
    <div class="input-group <?php echo (form_error('labor_experience_time'))?'has-error':'';?>">
      <label class="input-group-addon" style="white-space: normal;line-height: 1.2;">Tiempo mínimo de experiencia laboral<span></span></label>
      
      <select name="labor_experience_time" class="form-control" id="labor_experience_time">
        <?php $labor_experience_time = set_value('labor_experience_time') ? set_value('labor_experience_time') : @$request->labor_experience_time; ?>
        <option value="">Seleccione</option>
        <?php foreach ($work_experiences as $row): ?>
          <?php $labor_experience_time_selected = $row->code == $labor_experience_time ? 'selected="selected"' : ''; ?>
          <option value="<?php echo $row->code; ?>" <?php echo $labor_experience_time_selected; ?>>
              <?php echo $row->name; ?>
          </option>
        <?php endforeach; ?>
      </select>
      <?php echo form_error('labor_experience_time'); ?>
    </div>                   
    <div class="input-group <?php echo (form_error('minimum_age') || form_error('maximum_age'))?'has-error':'';?>">
      <label class="input-group-addon">Rango de edad <span></span></label>
      <table id="table-range-age">
        <tr>
          <td>
            <input name="minimum_age" type="text" class="form-control" id="minimum_age" value="<?php echo set_value('minimum_age') ? set_value('minimum_age') : @$request->minimum_age; ?>" placeholder="Mínimo">
          </td>
          <td class="separator">a</td>
          <td>
            <input name="maximum_age" type="text" class="form-control" id="maximum_age" value="<?php echo set_value('maximum_age') ? set_value('maximum_age') : @$request->maximum_age; ?>" placeholder="Máxima">
          </td>
        </tr>
      </table>
      
      <?php echo form_error('minimum_age'); ?>
      <?php echo form_error('maximum_age'); ?>
      
    </div>
  </div>
</div>