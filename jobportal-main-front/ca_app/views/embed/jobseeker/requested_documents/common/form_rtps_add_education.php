<!-- Starts add Jobseeker Education-->
<div class="modal-dialog">
  <form name="frm_add_education" id="frm_add_education" role="form" method="post">
  <div class="modal-content">
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Registrar educación</h4>
    </div>
    <div class="modal-body">
    <div class="box-body">
    <div id="emsg_profle"></div>
      <input type="hidden" name="id" value="">
      <div class="form-group">
      <label>Nivel académico</label>
      <select name="degree_title" id="degree_title" class="form-control" required>
      <option value="" selected="selected">Seleccione</option>
      <?php foreach($degrees_studies as $row_degree): ?>
      <option value="<?php echo $row_degree->text;?>"><?php echo $row_degree->text;?></option>
      <?php endforeach;?>
      </select></div>
      
      <div class="form-group">
      <label>Título</label>
      <input type="text" class="form-control"  id="major_subject" name="major_subject" value="" placeholder="Titulo" required>
      </div>

      <div class="form-group">
      <label>País</label>
        <select name="edu_country" id="edu_country" class="form-control" required>
        <option value="">Seleccione</option>
        <?php foreach ($countries as $key => $country): ?>
          <option value="<?php echo $country->country_name; ?>">
          <?php echo $country->country_name; ?>
          </option>
        <?php endforeach; ?>
        </select>              	
      </div>

      <div class="form-group">
      <label>Regimen</label>
        <div class="row">                  
        <div class="col-md-12">
          <select class="form-control" name="institution_educ_type" required>
          <option value="">Seleccione</option>
          <?php foreach ($institution_educ_types as $row): ?>
            <option value="<?php echo $row->id; ?>" ><?php e($row->name); ?></option>
          <?php endforeach; ?>
          </select>  
        </div>
        </div>
      </div> 

      <div class="form-group">
      <label>Clase</label>
        <div class="row">                  
        <div class="col-md-12">
          <select class="form-control" name="institution_educ_class" required>
          <option value="">Seleccione</option>
          <?php foreach ($institution_educ_class as $row): ?>
            <option value="<?php echo $row->id; ?>" ><?php e($row->name); ?></option>
          <?php endforeach; ?>
          </select>  
        </div>
        </div>
      </div> 
      
      <div class="form-group">
      <label>Tipo institución</label>
        <div class="row">                  
        <div class="col-md-12">
          <select class="form-control" name="institution_type" required>
          <option value="">Seleccione</option>
          <?php foreach ($institution_types as $row): ?>
            <option value="<?php echo $row->id; ?>" ><?php e($row->name); ?></option>
          <?php endforeach; ?>
          </select>  
        </div>
        </div>
      </div> 

      <div class="form-group">
        <label>Institución educativa</label>
          <div class="row">                  
            <div class="col-md-12">
              <select class="form-control" name="institution" required>
                <option value="">Seleccione</option>
                
                <?php foreach ($institutions as $row): ?>
                  <option value="<?php echo $row->code; ?>">
                    <?php e($row->name); ?>
                  </option>
                <?php endforeach; ?>
              </select>  
            </div>
          </div>
      </div> 

      <div class="form-group">
        <label>Carrera</label>
          <div class="row">                  
            <div class="col-md-12">
              <select class="form-control" name="career" required>
                <option value="">Seleccione</option>
                <?php foreach ($careers as $row): ?>
                  <option value="<?php echo $row->code; ?>">
                    <?php e($row->name); ?>
                  </option>
                <?php endforeach; ?>
              </select>  
            </div>
          </div>
      </div> 
    
      <div class="form-group">
      <label>Fecha inicio</label>
        <div class="row">
        <div class="col-md-4">
          <select class="form-control" name="year_start_date" id="year_start_date" required>
          <option value="" selected="selected">Año</option>
          <?php for ($cyear=date("Y"); $cyear>=1950; $cyear--):?>
          <option value="<?php echo $cyear;?>"><?php echo $cyear;?></option>
          <?php endfor;?>
          </select>      
        </div>                  
        <div class="col-md-4">
          <select class="form-control" name="month_start_date" id="month_start_date" disabled="true" required>
          <option value="">Mes</option>
          <?php for ($mnth = 1; $mnth <= 12; $mnth++):
            $month = sprintf("%02s", $mnth);
            $selected = "";
            $dummy_date = '2014-'.$month.'-'.'01';
          ?>
          <option value="<?php echo $month;?>" <?php echo $selected;?>><?php echo ucwords(_date_locale_format(strtotime($dummy_date), 'MMMM')); ?></option>
          <?php endfor; ?>
          </select>  
        </div>
        </div>
      </div> 

      <div class="form-group">
      <label>Fecha fin</label>
        <div class="row">
        <div class="col-md-4">
          <select class="form-control" name="year_end_date" id="year_end_date" disabled="true" required>
          <option value="" selected="selected">Año</option>
          <?php for ($cyear=date("Y"); $cyear>=1950; $cyear--):?>
          <option value="<?php echo $cyear;?>"><?php echo $cyear;?></option>
          <?php endfor;?>
          </select>      
        </div>                  
        <div class="col-md-4">
          <select class="form-control" name="month_end_date" id="month_end_date" disabled="true" required>
          <option value="">Mes</option>
          
          <?php for ($mnth = 1; $mnth <= 12; $mnth++):
            $month = sprintf("%02s", $mnth);
            $selected = "";
            $dummy_date = '2014-'.$month.'-'.'01';
          ?>
          
          <option value="<?php echo $month;?>" <?php echo $selected;?>><?php echo ucwords(_date_locale_format(strtotime($dummy_date), 'MMMM')); ?></option>
          <?php endfor; ?>
          </select>
        </div>
        </div>
      </div>

      <div class="form-group">
      <label>Nro. colegiatura (Opcional)</label>
      <input type="text" name="tuition_number" class="form-control" placeholder="Nro. colegiatura" autocomplete="off">
      </div>  
      <div class="form-group">
      <label style="font-weight: normal;"><input id="studying" type="checkbox" name="studying" value="true"> Actualmente estoy estudiando</label>
      </div>               
    </div>
    </div>
    <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
    <button type="submit" name="js_education_submitter" class="btn btn-primary">Guardar</button>
    </div>
  </div>
  </form>
</div>
<script>
  $(function(){

    validate_range_dates('#year_start_date', '#month_start_date', '#year_end_date', '#month_end_date', '#studying');
            
    $( "#studying" ).change(function(){

      var isSelected = $(this).is(':checked');

      $( "#month_end_date" ).attr({'disabled': isSelected, 'required': true});
      $( "#year_end_date" ).attr({'disabled': isSelected, 'required': true});

      if (isSelected) {
        $( "#month_end_date" ).val('');
        $( "#year_end_date" ).val('');
        $( "#month_end_date" ).closest('div').removeClass( "has-error" ); 
        $( "#month_end_date" ).removeAttr('required');
        $( '.month_end_date_err').remove();
        $( "#year_end_date" ).closest('div').removeClass( "has-error" ); 
        $( '.year_end_date_err').remove();
        $( "#year_end_date" ).removeAttr('required');
      }
    });

    function getCareers(select, params) {
      const url = "<?php echo site_url('general/jobseeker/seeker_academics/get_careers'); ?>";
      $(select).html('<option value="">Cargando...</option>').prop('disabled', true);

      $.get(url, params, function(res) {

          const results =  res.data ? res.data : [];
      
          $.each(results, function(i, row) {
            $(select).append(`<option value="${row.code}" >${row.name}</option>`);
          });      
      }, 'json')
      .fail(function() {
          console.error('¡Ha ocurrido un error al tratar de listar las carreras!');
      }).always(function() {
          $(select).find("option:eq(0)").text("Seleccione");
          $(select).prop('disabled', false);
      }); 
    }

    function getInstitutions(select, params) {
      const url = "<?php echo site_url('general/jobseeker/seeker_academics/get_institutions'); ?>";
      $(select).html('<option value="">Cargando...</option>').prop('disabled', true);

      $.get(url, params, function(res) {

          const results =  res.data ? res.data : [];
      
          $.each(results, function(i, row) {
            $(select).append(`<option value="${row.code}" >${row.name}</option>`);
          });      
      }, 'json')
      .fail(function() {
          console.error('¡Ha ocurrido un error al tratar de listar las instituciones!');
      }).always(function() {
          $(select).find("option:eq(0)").text("Seleccione");
          $(select).prop('disabled', false);
      }); 
    }

    function saveEducation() {
    
      let id = $( 'input[name="id"]', '#frm_add_education').val();

      if (id == '') {
        id = $( "#degree_obtained" ).generateSequence() * -1;
      }

      $( `.education-inputs-${id}`).remove();

      const contentInput  = $( `<div class="education-inputs-${id}">`);

      $( '#frm_add_education' ).find(':input').each(function(i, element) {

        const elementName = $.trim($(element).prop('name'));
        const elementType = $.trim($(element).prop('type'));

        if (elementName == 'id') {
          const inputHidden = $("<input/>").attr({
            'type': 'hidden',
            'name': 'education[' + id + '][' + element.name + ']', 
            'value': id,
          });
          contentInput.append(inputHidden);
        } else if (elementType == 'checkbox') {
          const inputHidden = $("<input/>").attr({
            'type': 'hidden',
            'name': 'education[' + id + '][' + element.name + ']', 
            'value': $(element).is(':checked') ? 'true' : 'false',
          });
          contentInput.append(inputHidden);
        } else if ($(element).val() != '' && elementName && elementName != 'file') {
          const inputHidden = $("<input/>").attr({
            'type': 'hidden',
            'name': 'education[' + id + '][' + element.name + ']', 
            'value': element.value,
          });
          contentInput.append(inputHidden);
        }
      });

      $( '#container-educations' ).append(contentInput);

      const degreeTitle = $( 'select[name="degree_title"]', '#frm_add_education').val();
      const title = $( 'input[name="major_subject"]', '#frm_add_education').val();
      const year = $( 'select[name="year_end_date"]', '#frm_add_education').val();
      const institutionText = $( 'select[name="institution"] option:selected', '#frm_add_education').text();

      if ($( '#degree_obtained option:selected' ).data('id') == id) {

        const selectedIndex = $( '#degree_obtained' )[0].selectedIndex;

        $( '#degree_obtained option:selected').replaceWith(`
          <option 
            value="${degreeTitle}"
            data-id="${id}"
            data-year="${year}"
            data-speciality="${title}"
            data-institution="${institutionText}">
            ${degreeTitle} - ${title}
          </option>`
        );

        $( '#degree_obtained').prop('selectedIndex', selectedIndex);
       
      } else {
        $( '#degree_obtained').append(`
          <option 
            value="${degreeTitle}"
            data-id="${id}"
            data-year="${year}"
            data-speciality="${title}"
            data-institution="${institutionText}">
            ${degreeTitle} - ${title}
          </option>`
        );

        $( '#degree_obtained option:last' ).prop('selected', 'selected');
      }

      $( '#degree_obtained').trigger('change');
      $( '#degree_obtained').change();
      $( "#modal-education-add" ).modal('hide');
    }

    $( '#frm_add_education' ).submit(function(e){
      e.preventDefault();
      saveEducation();
      return false;
    });

    $( '#frm_add_education select[name="institution_educ_type"]' ).change(function(e){
      const select = $( '#frm_add_education select[name="institution"]' );
      
      getInstitutions(select, {
          'institution_educ_type': $( '#frm_add_education select[name="institution_educ_type"]').val(),
          'institution_type': $( '#frm_add_education select[name="institution_type"]').val(),
      });
    });

    $( '#frm_add_education select[name="institution_type"]' ).change(function(e){
      const select = $( '#frm_add_education select[name="institution"]' );
      
      getInstitutions(select, {
          'institution_educ_type': $( '#frm_add_education select[name="institution_educ_type"]').val(),
          'institution_type': $( '#frm_add_education select[name="institution_type"]').val(),
      });
    });

    $( '#frm_add_education select[name="institution"]' ).change(function(e){
      const select = $( '#frm_add_education select[name="career"]' );
      
      getCareers(select, {
          'institution': $( '#frm_add_education select[name="institution"]').val(),
      });
    });

    $( "#studying" ).change();
  });
</script>
<!-- Ends add Jobseeker Education --> 