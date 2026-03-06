<div id="modal-add-rightful-claimant" class="modal fade" role="dialog">
  <div class="modal-dialog" style="">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Guardar derechohabientes</h4>
      </div>
      <div class="modal-body beneficiaries">
        <div class="section-rightful-claimant">
          <div class="formwraper">
            <input id="rc-id" type="hidden" value="2">
            <div class="input-group row">
              <label class="input-group-addon col-12 col-sm-3">Primer nombre <span>*</span></label>
              <div class="col-12 col-sm-9">
                <input id="rc-name" type="text" name="first_name" class="form-control" value="" placeholder="Primer nombre">
              </div>
            </div>
            <div class="input-group row">
              <label class="input-group-addon col-12 col-sm-3">Segundo nombre <span></span></label>
              <div class="col-12 col-sm-9">
                <input id="rc-second-name" type="text" name="second_name" class="form-control" value="" placeholder="Segundo nombre">
              </div>
            </div>
            <div class="input-group row">
              <label class="input-group-addon col-12 col-sm-3">Apellido Paterno <span>*</span></label>
              <div class="col-12 col-sm-9">
                <input id="rc-paternal-name" type="text" name="paternal_last_name" class="form-control" value="" placeholder="Apellido paterno">
              </div>
            </div>
            <div class="input-group row">
              <label class="input-group-addon col-12 col-sm-3">Apellido Materno <span>*</span></label>
              <div class="col-12 col-sm-9">
                <input id="rc-maternal-name" type="text" name="maternal_last_name" class="form-control" value="" placeholder="Apellido materno">
              </div>
            </div>
            <div class="input-group row">
              <label class="input-group-addon col-12 col-sm-3">Documento de identidad <span>*</span></label>
              <div class="col-12 col-sm-9">
                <table width="100%">
                  <tr>
                    <td width="35%">
                      <select id="rc-document-type" name="document_type" class="form-control" style="width: 88%;">
                        <option value="">Tipo</option>
                        <?php foreach ($rightful_claimants_document_types as $doc_type): ?>
                          <option value="<?php echo $doc_type->id; ?>">
                            <?php e($doc_type->name); ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </td>
                    <td>
                      <input id="rc-document-number" name="document_number" type="text" class="form-control" placeholder="Número de documento" value="">
                    </td>
                  </tr>
                </table>
              </div>
            </div>
            <div class="input-group row">
              <label class="input-group-addon col-12 col-sm-3">Fecha de nacimiento <span>*</span></label>
              <div class="col-12 col-sm-9">
                  <input id="rc-birthdate" type="date" name="birthdate" class="form-control" value="" max="<?php echo date('Y-m-d'); ?>">
              </div>
            </div>
            <div class="input-group row">
              <label class="input-group-addon col-12 col-sm-3">Sexo <span>*</span></label>
              <div class="col-12 col-sm-9">
                <div class="row">
                  <div class="form-check form-check-inline col-12 col-sm-6">
                      <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="2">
                      <label class="form-check-label" for="genderFemale">Femenino</label>
                  </div>
                  <div class="form-check form-check-inline col-12 col-sm-6">
                      <input class="form-check-input" type="radio" name="gender" id="genderMale" value="1">
                      <label class="form-check-label" for="genderMale">Masculino</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="input-group row mb-2">
              <label class="input-group-addon col-12 col-sm-3">Vinculo familiar <span>*</span></label>
              <div class="col-12 col-sm-9">
                <select id="rc-kinship" name="kinship" class="form-control select-kinship" style="width: 100%;">
                  <option value="">Seleccione</option>
                  <?php foreach ($kinship_types as $row): ?>
                    <option value="<?php echo $row->id; ?>"><?php e($row->name); ?></option>
                  <?php endforeach; ?>
                </select> 
              </div>
            </div>

          </div>
        </div>
      </div>
      <div class="modal-footer custom-modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cerrar</button>
        <button id="save-rightful-claimant" type="button" class="btn btn-sm btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>
<script id="tpl-add-rightful-claimant" type="text/template">
  <tr data-rc-id="{{rcId}}">
    <td>{{name}} {{lastName}}</td>
    <td>{{kinship}}</td>
    <td>
      <button type="button" class="btn btn-xs btn-rc-action edit-rightful-claimant" style="background-color: transparent;">
        <img src="<?php echo base_url('public/images/edit-table.svg');?>" />
      </button>
      <button type="button" class="btn btn-xs btn-rc-action remove-rightful-claimants" style="background-color: transparent;">
        <img src="<?php echo base_url('public/images/delete-table.svg');?>" />
      </button>
    </td>
  </tr>
</script>
<script type="module">

  (function(){
    function validDataRightfulClaimants() {   
      $(".input-group", ".section-rightful-claimant").removeClass('has-error');

      var elementError = $( ".section-rightful-claimant" ).find("input,select").filter(function() {
        
        var element = $(this),
            elementName = $.trim(element.prop('name'));

        // Verificar si es un radio button
        if (element.prop('type') === 'radio') {
          var name = element.prop('name');
          // Verificar si hay al menos un radio button seleccionado con el mismo nombre
          if ($("input[name='" + name + "']:checked").length === 0) {
            element.closest('.input-group').addClass('has-error');
            return true;
          }
          return false;
        }

        if (element.val() != '' || 
            elementName == 'file' || 
            elementName == 'second_name' ||
            $(element).prop('id') == 'rc-id') {
          return false;
        }
      
        element.closest('.input-group').addClass('has-error');
        return true;
      });
        
      return {
        'errors' : elementError.length,
        'elementError' : elementError
      }    
    }

    function jobseekerHaveSpouse() {
      return $( ".input-kinship" ).filter(function(){
        return $(this).val() == '1' || $(this).val() == '2';
      }).length > 0;
    }
    
    function addRightfulClaimants() {
      $( ".section-rightful-claimant" ).find('input[type="date"],input[type="text"],input[type="hidden"], select').val("");
      $(".input-group", ".section-rightful-claimant").removeClass('has-error');
    
      $( "#modal-add-rightful-claimant" ).modal('show');
      $( "#modal-add-rightful-claimant" ).removeData('row-edit');
      
      if (jobseekerHaveSpouse()) {
        $( "#rc-kinship option[value='1']").prop('disabled', true);
        $( "#rc-kinship option[value='2']").prop('disabled', true);
      } else {
        $( "#rc-kinship option[value='1']").prop('disabled', false);
        $( "#rc-kinship option[value='2']").prop('disabled', false);
      }
      
      $( '#genderMale' ).prop('checked', false);
      $( '#genderFemale').prop('checked', false);
    }
    
    function editRightfulClaimants() {
      const tr = $(this).closest('tr');
      $(".input-group", ".section-rightful-claimant").removeClass('has-error');
      $(".document-view", ".section-rightful-claimant").show();
      $(".document-load", ".section-rightful-claimant").hide();
      $(".document-progress", ".section-rightful-claimant").remove();

      const id = tr.data('rc-id');
      const name = $( "input[name='rightful_claimants[" + id + "][first_name]']").val();
      const secondName = $( "input[name='rightful_claimants[" + id + "][second_name]']").val();
      const paternalLastName = $( "input[name='rightful_claimants[" + id + "][paternal_last_name]']").val();
      const maternalLastName = $( "input[name='rightful_claimants[" + id + "][maternal_last_name]']").val();
      const kinship = $( "input[name='rightful_claimants[" + id + "][kinship]']").val();   
      const documentType = $( "input[name='rightful_claimants[" + id + "][document_type]']").val();
      const documentNumber = $( "input[name='rightful_claimants[" + id + "][document_number]']").val();
      const gender = $("input[name='rightful_claimants[" + id + "][gender]']").val();
      const birthdate = $( "input[name='rightful_claimants[" + id + "][birthdate]']").val();
      
      $( "#rc-document-type" ).val(documentType);
      $( "#rc-document-number" ).val(documentNumber);
      
      $( "#rc-second-name" ).val(secondName);
      $( "#rc-birthdate" ).val(birthdate);
       
      if (gender == 1) {
          $('#genderMale').prop('checked', true);
      } else if (gender == 2) {
          $('#genderFemale').prop('checked', true);
      }
      $( "#rc-kinship" ).val(kinship);
      $( "#rc-id" ).val(id);
      $( "#rc-name" ).val(name);
      $( "#rc-paternal-name" ).val(paternalLastName);
      $( "#rc-maternal-name" ).val(maternalLastName);

      if (kinship != '1' && kinship != '2' && jobseekerHaveSpouse()) {
        $( "#rc-kinship option[value='1']").prop('disabled', true);
        $( "#rc-kinship option[value='2']").prop('disabled', true);
      } else {
        $( "#rc-kinship option[value='1']").prop('disabled', false);
        $( "#rc-kinship option[value='2']").prop('disabled', false);
      }

      $( "#rc-kinship" ).change();

      $( "#modal-add-rightful-claimant" ).data('row-edit', tr);
      $( "#modal-add-rightful-claimant" ).modal('show');
    }

    function removeRightfulClaimants() {
      if (confirm('¿Seguro de remover el derechohabitante?')) {
        $(this).closest('tr').remove();
      }
    }

    function saveRightfulClaimants() {
      var data = validDataRightfulClaimants();

      if (data.errors > 0) {
        toastr["warning"]("¡Existen datos sin completar!");
        return;
      }

      var id = $( "#rc-id" ).val();

      if (id == '') {
        id = $( ".tbl-rightful-claimants" ).generateSequence() * -1;
      }

      $( ".rightful-claimants-inputs" + id).remove();
      var contentInput  = $( "<div class='rightful-claimants-inputs" + id + "'>");

      $( ".section-rightful-claimant" ).find(':input').each(function(i, element) {
        var elementName = $.trim($(element).prop('name'));
        var elementType = $(element).prop('type');
        var elementIsChecked = $(element).is(':checked');
        
        var elementValue = '';
        if (elementType === 'radio') {
          if (elementIsChecked) {
            elementValue = $(element).val();
          }
        } else {
          elementValue = $(element).val();
        }
        //console.log(elementName +  ' ' + elementValue);
        
        if (elementValue != '' && elementName && elementName != 'file') {
          var inputHidden = $("<input/>").attr({
            'type': 'hidden',
            'name': 'rightful_claimants[' + id + '][' + elementName + ']', 
            'value': elementValue,
            'class': (elementName == 'kinship' ? 'input-kinship' : ''),
          });

          contentInput.append(inputHidden);
        }
      });
    
      var rowEdit = $( "#modal-add-rightful-claimant" ).data('row-edit') || false;

      var tplRow = $( "#tpl-add-rightful-claimant" ).html();

      var newRow = Mustache.render(tplRow, {
        rcId: id,
        name: $( "#rc-name").val(),
        lastName: $( "#rc-paternal-name").val() + ' ' + $( "#rc-maternal-name").val(),
        kinship: $( "#rc-kinship  option:selected" ).text()
      });

      var $newRow = $(newRow);
      $newRow.append(contentInput);

      if (rowEdit) {
        rowEdit.replaceWith($newRow);
      } else {
        $( ".tbl-rightful-claimants tbody" ).append($newRow);
      }

      $( "#modal-add-rightful-claimant" ).modal('hide');
    }

    function init() {
      $( "#save-rightful-claimant" ).click(saveRightfulClaimants);
      $( "#add-rightful-claimant" ).click(addRightfulClaimants);
      $(document).on("click", ".edit-rightful-claimant", editRightfulClaimants);
      $(document).on('click', '.remove-rightful-claimants', removeRightfulClaimants);
    }

    init();
  })();
</script>