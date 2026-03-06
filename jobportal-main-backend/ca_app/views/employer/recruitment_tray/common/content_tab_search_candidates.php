<style>
  .content-search-candidates {
    padding: 25px 5px;
  }

  .content-search-candidates #form-search-candidates {
    width: 400px;
    display: block;
    margin: 0 auto;
  }

  #form-search-candidates table tr td {
    padding: 0 5px;
  }

  #form-search-candidates .btn-submit-search {
    display: flex;
    padding: 2px 5px;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
    border-radius: 4px;
    border: 1px solid var(--Blue-500, #0D6EFD);
    background: var(--Blue-500, #0D6EFD);
  }

  #form-search-candidates input[name="document_number"] {
    border-radius: 4px;
    border: 1px solid var(--Components-Forms-Input-border, #CED4DA);
    background: #F8F9FA;
  }

  .content-candidates {
    background: #F8F9FA;
    position: relative;
  }
  
  .content-candidates .list-candidates {
    padding: 20px 25px;
    padding-bottom: 3em;
  }
  
  .list-candidates-item {
    border: 1px solid #DFDFDF;
    border-radius: 10px;
    padding: 8px 18px;
    background: #ffffff;
    margin: 10px 0; 
  }

  .list-candidates-item table tr td  {
    color: #000;
    font-size:14px;
    font-style:normal;
    font-weight:600;
    vertical-align: top;
  }
</style>
<div class="content-search-candidates">
  <?php echo form_open('employer/recruitment_tray/process_candidates/search_candidate', ['id' => 'form-search-candidates']); ?>
  <table width="100%">
    <tbody>
      <tr>
        <td>
        <input type="text" name="document_number" class="form-control" placeholder="Buscar por número de DNI..." required>
        </td>
        <td>
        <button class="btn btn-xs btn-primary btn-submit-search" type="submit">
          <img src="<?php echo base_url('public/images/magnifying_glass.svg'); ?>" alt="">
        </button>
        </td>
      </tr>
    </tbody>
  </table>
  <?php echo form_close(); ?>
</div>
<div class="content-candidates">
  <?php echo form_open('employer/recruitment_tray/process_candidates/add_candidates', [ 'id' => 'form-add-candidates']); ?>
  <input type="hidden" name="client_code" value="<?php echo $client->code; ?>">
  <div class="list-candidates" style="display: none;"></div>
  <?php echo form_close(); ?>
</div>

<script type="module">
$(function(){

  $( document ).on('change', '#form-add-candidates .candidates-selected', function(){
    $( '#btn-add-process-candidates' ).prop('disabled', true);
    if ($( '#form-add-candidates .candidates-selected:checked' ).length > 0) {
      $( '#btn-add-process-candidates' ).prop('disabled', false);
    }
  });

  $( '#form-search-candidates' ).submit(function(e){
    e.preventDefault();

    const params = $(this).serialize();
    const url = $(this).prop('action');

    const btnSubmit = $( '.btn-submit-search' );
    btnSubmit.prop('disabled', true);

    $.get(url, params, function(res){
      
      if (!res.status) {
        toastr["error"](res.message);
        return;
      }

      const candidates = res.data;

      if (candidates.length == 0) {
        toastr["warning"]("¡No se encontró ningún postulante por el DNI ingresado!");
        return;
      }

      for (let index in candidates) {
       
        const candidate = candidates[index];
        $( '.list-candidates-item[data-seeker-id="' + candidate.id + '"]' ).remove();
        
        const candidateRowHtml = `
          <div class="list-candidates-item" data-seeker-id="${candidate.id}">
            <table width="100%">
              <tbody>
                <tr>
                  <td width="150">${candidate.document_type_abbreviation_name} ${candidate.document_number}</td>
                  <td>
                    ${candidate.first_name} ${candidate.last_name}
                    <span style="display: block; color: #555;font-weight: normal;font-size: 13px;">${candidate.email}</span>
                    <span style="display: block; color: #555;font-weight: normal;font-size: 13px;">${candidate.mobile}</span>
                  </td>                
                  <td width="20"><input type="checkbox" name="candidates[]" value="${candidate.id}" checked class="candidates-selected"></td>
                </tr>
              </tbody>
            </table>
          </div>`;

        $( '.list-candidates', '#modal-add-candidates' ).prepend(candidateRowHtml).show();

        $( 'input[name="document_number"]', '#form-search-candidates' ).val('');
        $( '#btn-add-process-candidates' ).prop('disabled', false);
      }

    }, 'json')
    .fail(function(){
      toastr["error"]('Ha ocurrido un error');
    })
    .always(function(){
      btnSubmit.prop('disabled', false);
    });

    return false;
  });

  $( '#btn-add-process-candidates' ).click(function(){
    $( '#form-add-candidates' ).submit();
  });

  $( '#form-add-candidates' ).submit(function(e) {
    e.preventDefault();

    if ($( '.candidates-selected:checked' ).length == 0) {
      toastr["warning"]("¡No hay candidatos seleccionados!");
      return;
    }

    const params = $(this).serialize();
    const url = $(this).prop('action');

    const btnSubmit = $( '#btn-add-process-candidates' );
    btnSubmit.text('Agregando...');
    $( '#modal-add-candidates .modal-content' ).addClass('load load-image');

    $.post(url, params, function(res) {

      if (!res.status) {
        toastr["warning"](res.message);
        return false;
      }

      $( '.list-candidates', '#modal-add-candidates' ).empty().hide();
      $( '#btn-add-process-candidates' ).prop('disabled', true);
      toastr["success"](res.message);
      searchCandidates(1, {});
    }, 'json')
    .fail(function(e){
      toastr["error"]('Ha ocurrido un error');
    })
    .always(function(){
      btnSubmit.text('Agregar');
      $( '#modal-add-candidates .modal-content' ).removeClass('load load-image');
    });
    
    return false;
  });
});
</script>