<?php echo form_open('employer/recruitment_candidates/move_candidates_hired', ['id' => 'form-move-candidates-hired']); ?>
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">
      Mover candidatos a contratados
    </h4>
  </div>
  <div class="modal-body">
    <div class="suspend-info">
      Por favor asigne los gestores de nómina para poder mover los candidatos a contratación.
    </div>
    <br />
    <div>
      <input type="hidden" name="process_id" value="<?php echo $process->id; ?>" />
      <div class="form-group">
        <div class="row">
          <div class="col-md-6">
            <label>Notificar al postulante</label>
          </div>
        </div>
        <div class="suspend-info">
          Se enviará un correo para solicitar la carga de documentos para la contratación.
        </div>
        <div style="margin-top: 8px;">
          <div class="row">
            <div class="col-md-6">
              <label style="font-weight: normal;">
                Enviar también por WhatsApp
              </label>
            </div>
            <div class="col-md-6">
              <div class="checkbox-wrapper-2" style="float: right;">
                <input class="tgl tgl-light" id="notify-candidate-by-whatsapp-2" type="checkbox" name="notify_candidate_by_whatsapp" value="1" />
                <label class="tgl-btn" for="notify-candidate-by-whatsapp-2" style="width: 38px;height: 21px;">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group" style="display: none;">
        <label>Grupo de RR. HH. <span style="color: red;">*</span></label>
        <select class="form-control select-rrhh-group" name="rrhh_group_id[]" style="width: 100%;" autocomplete="false">
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
          <?php foreach ($rrhh_responsibles as $user): ?>
            <tr>  
              <td width="75%">
                <input type="hidden" name="rrhh_user_id[]" value="<?php echo $user->id; ?>" data-rrhh-user-id="<?php echo $user->id; ?>">
                <?php echo $user->first_name . ' (' . $user->email . ')'; ?>
              </td>
              <td>
                <button type="button" class="btn btn-xs btn-danger" onclick="$(this).closest('tr').remove();">Quitar</button>
              </td>
            </tr>
          <?php endforeach; ?>   
        </table>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="submit" class="btn btn-default" data-dismiss="modal">Cancelar</button>
    <input type="submit" class="btn btn-primary" value="Mover">
  </div>
<?php echo form_close(); ?>

<script>
$(function(){
  $( "#form-move-candidates-hired" ).keydown(function(e) {
    if (e.key == "Enter") {
      e.preventDefault();
      return;
    }
  });

  $( "#form-move-candidates-hired" ).submit(function(e) {
    e.preventDefault();

    if ($( '.select-rrhh-group', $(this)).val() == '' &&
      $( 'input[name="rrhh_user_id[]"]', $(this)).length == 0) {
      toastr["error"]("¡Por favor seleccione al menos un grupo o agregue un usuario!");
      return;
    }

    $( '#modal-move-candidate-hired .modal-content' ).addClass('load load-image');

    var data = $( "#wrapper-candidates :input" ).serialize() + "&" + $(this).serialize();
    var btnMoveHired = $(this).find('input[type="submit"]');
    btnMoveHired.val('Moviendo...');

    $.post($(this).prop('action'), data, function(response) {
        
      if (response.status === true) {
        toastr["success"](response.message);                 
        window.location.reload();
        return;
      }

      $( '#modal-move-candidate-hired .modal-content' ).removeClass('load load-image');
      btnMoveHired.val('Mover');
      toastr["error"](response.message);    
        
    }, 'json')
    .fail(function(){
      $( '#modal-move-candidate-hired .modal-content' ).removeClass('load load-image');
      btnMoveHired.val('Mover');
      toastr["error"]('¡Ha ocurrido un error!');    
    })
    .always(function(){});

    return false;
  });

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
});
</script>