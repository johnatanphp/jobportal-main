<?php if ($rs_process->sts != 'active'): ?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title">
    Asignación Gestores de Nómina
  </h4>
</div>
<div class="modal-body">
  <?php if (count($rrhh_group_selected) > 0): ?>
    <div style="display: none;">
      <label>Grupo RR. HH.</label>
      <table class="table" width="100%">
        <?php foreach ($rrhh_group_selected as $row): ?>
          <tr>
            <td><?php echo $row->name; ?></td>
          </tr>
        <?php endforeach; ?>   
      </table>
    </div>
  <?php endif; ?>
  <?php if (count($manual_rrhh_users) > 0): ?>
    <table class="table" width="100%">
      <label for="">RR. HH. Asignados</label>
      <?php foreach ($manual_rrhh_users as $user): ?>
        <tr>  
          <td>
            <?php echo $user->first_name . ' ( ' . $user->email . ')'; ?>
          </td>
        </tr>
      <?php endforeach; ?> 
    </table>
  <?php endif; ?>
  <?php if (count($manual_rrhh_users) == 0 && count($rrhh_group_selected) == 0): ?>
    <h5>No hay asignaciones</h5>
  <?php endif; ?>
</div>
<?php endif; ?>

<?php if ($rs_process->sts == 'active'): ?>
<?php echo form_open('employer/recruitment_candidates/save_rrhh_assignment', ['id' => 'form-save-rrhh-assignment']); ?>
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">
      Asignación Gestores de Nómina
    </h4>
  </div>
  <div class="modal-body">
    <div class="suspend-info">
      Por favor asigne los gestores de nómina que podrá visualizar a los candidatos en etapa de contratación.
    </div>
    <br />
    <input type="hidden" name="process_id" value="<?php echo $rs_process->id; ?>" />
    
    <div class="form-group" style="display: none;">
      <label>Grupo RR. HH. <span style="color: red;"></span></label>
      <select class="form-control select-rrhh-group" name="rrhh_group_id[]" style="width: 100%;">
        <option value="">Seleccione</option>
        <?php foreach ($rrhh_groups as $row): ?>
          <option value="<?php echo $row->id; ?>" <?php echo $row->id == $row->rrhh_group_id ? 'selected="selected"' : ''; ?>>
              <?php echo $row->name; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label>Agregar gestor de nómina <span style="color: red;"></span></label>
      <input type="text" class="form-control rrhh-users-autocomplete" placeholder="Buscar gestor de nómina por email o nombre">
      <br>
      <table class="table table-rrhh-users" width="100%">
        <?php foreach ($manual_rrhh_users as $user): ?>
          <tr>  
            <td width="75%">
              <input type="hidden" name="rrhh_user_id[]" value="<?php echo $user->id; ?>" data-rrhh-user-id="<?php echo $user->id; ?>">
              <?php echo $user->first_name . ' ( ' . $user->email . ')'; ?>
            </td>
            <td>
              <button type="button" class="btn btn-xs btn-danger" onclick="$(this).closest('tr').remove();">Quitar</button>
            </td>
          </tr>
        <?php endforeach; ?>   
      </table>
    </div>
  </div>
  <div class="modal-footer">
    <input class="btn btn-primary" type="submit" value="Asignar" />
  </div>
<?php echo form_close(); ?>
<?php endif; ?>

<script>
$(function(){

  if ($( '.rrhh-users-autocomplete' ).length) {
    $( '.rrhh-users-autocomplete' ).each(function(i, e) {
        $(e).autocomplete({
        source: function (query, done) {
          result = $.map(JSON.parse('<?php echo $rrhh_users; ?>'), function(item) {
            return {
              label: item.first_name + ' (' + item.email + ')',
              value: item.id,
            };
          });

          var matcher = new RegExp($.ui.autocomplete.escapeRegex( query.term ), "i" );

          done($.grep( result, function( item ){
            return matcher.test( item.label );
          }));
        },

        minLength: 3,
        select: function(event, ui ) {
          $(event.target).val("");

          if ($( 'input[data-rrhh-user-id="' + ui.item.value + '"]' ).length) {
              toastr["info"]("¡Usuario ya está agregado!");
              return false;
          }

          row = `<tr>
              <td width="75%">
                <input type="hidden" name="rrhh_user_id[]" value="${ui.item.value}" data-rrhh-user-id="${ui.item.value}">
                ${ui.item.label}
              </td>
              <td>
                <button type="button" class="btn btn-xs btn-danger" onclick="$(this).closest('tr').remove();">Quitar</button>
              </td>
          </tr>`;

          table = $(event.target).closest('.form-group').find('table');
          table.append(row);

          return false;
        }
      });
    });
  }

  $( "#form-save-rrhh-assignment" ).keydown(function(e) {
    if (e.key == "Enter") {
      e.preventDefault();
      return;
    }
  });

  $( "#form-save-rrhh-assignment" ).submit(function(e) {
    e.preventDefault();

    if ($( '.select-rrhh-group', $(this)).val() == '' &&
      $( 'input[name="rrhh_user_id[]"]', $(this)).length == 0) {
      toastr["error"]("¡Por favor seleccione al menos un grupo o agregue un usuario!");
      return;
    }

    var btn = $(this).find('input[type="submit"]');
    btn.prop('disabled', true);

    $.post($(this).prop('action'), $(this).serialize(), function(response) {
        
        if (response.status === true) {
          toastr["success"](response.message);                 
          reloadDataCandidates();
          return;
        }

        if (!response.status) {
          toastr["error"]("No se pudo guardar la asignación!");  
          return;
        }
    }, 'json')
    .fail(function(){
      toastr["error"]("¡Hubo un error al guardar la asignación!");
    }).always(function(){
      btn.prop('disabled', false);
    });

    return false;
  });

});
</script>
   