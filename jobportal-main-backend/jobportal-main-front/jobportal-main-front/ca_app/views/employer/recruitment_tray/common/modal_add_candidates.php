<style>
  
  #modal-add-candidates .modal-header {
    border: 0;
  }
  
  #modal-add-candidates .modal-body {
    padding: 0;
  }

  .nav-tabs-search-candidates {
    padding-left: 25px;
  }

  .nav-tabs-search-candidates>li>a {
    padding: 8px 16px;
    text-align: center;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: 24px;
  }

  .nav-tabs>li.active>a {
    color: var(--global-01-primary, #007BFF);
  }

</style>

<div id="modal-add-candidates" class="modal modal-style-1" role="dialog">
  <div class="modal-dialog" style="width: 100%; max-width: 650px;">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Agregar candidatos</h4>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs nav-tabs-search-candidates">
          <li class="active"><a data-toggle="tab" href="#tab-search-candidates">Número de DNI</a></li>
          <li><a data-toggle="tab" href="#tab-import-candidates">Importar</a></li>
          <li><a data-toggle="tab" href="#tab-register-candidates">Registar</a></li>
        </ul>
        <div class="tab-content">
          <div id="tab-search-candidates" class="tab-pane fade in active">
            <?php $this->load->view('employer/recruitment_tray/common/content_tab_search_candidates'); ?>
          </div>
          <div id="tab-import-candidates" class="tab-pane fade">
            <?php $this->load->view('employer/recruitment_tray/common/content_tab_import_candidates'); ?>
          </div>
          <div id="tab-register-candidates" class="tab-pane fade"></div>
        </div>
      </div>
      <div class="modal-footer">
        <div data-tab-footer="#tab-search-candidates">
          <button type="button" class="btn btn-style-1" data-dismiss="modal">Cerrar</button>
          <button id="btn-add-process-candidates" type="button" class="btn btn-primary btn-style-1" disabled>Agregar</button>
        </div>
        <div data-tab-footer="#tab-import-candidates" style="display: none;">
          <button type="button" class="btn btn-style-1" data-dismiss="modal">Cerrar</button>
          <button id="btn-import-process-candidates" type="button" class="btn btn-primary btn-style-1" disabled>Agregar</button>
        </div>
        <div data-tab-footer="#tab-register-candidates" style="display: none;">
          <button type="button" class="btn btn-style-1" data-dismiss="modal">Cerrar</button>
          <button id="btn-register-candidates" type="button" class="btn btn-primary btn-style-1" disabled>Agregar</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="module">
$(function(){ 
  $( 'a[data-toggle="tab"]' ).on('show.bs.tab', function (e)  {
    const href = e.target.getAttribute("href");
    $('div[data-tab-footer="#tab-search-candidates"]').hide();
    $('div[data-tab-footer="#tab-import-candidates"]').hide();
    $('div[data-tab-footer="#tab-register-candidates"]').hide();
    $( 'div[data-tab-footer="' + href + '"]' ).show();

    if (href == '#tab-register-candidates' && $(e.target).data('load-content') !== true ) {
      loadFormCandidateRegister();
      $(e.target).data('load-content', true);
    }

  });

  $(document).on('click', 'button[data-target="#modal-add-candidates"]', function(e) {
    e.preventDefault();
    $( '.list-candidates', '#modal-add-candidates').empty().hide();
    $( '#btn-add-process-candidates' ).prop('disabled', true);
    $( '#modal-add-candidates' ).modal('show');
   
    return false;
  });

  function loadFormCandidateRegister() {
    const url = "<?php echo site_url('employer/recruitment_tray/candidate_register/form_create'); ?>";
    const params = {
      'client_code': "<?php echo $client->code; ?>"
    };

    $( '#tab-register-candidates' ).html(`<span style="padding: 60px 0;display: block;" class="load load-image">.</span>`);

    $.get(url, params, function(res) {
      $( '#tab-register-candidates' ).html(res);
    })
    .fail(function(e) {
      toastr["error"]('Ha ocurrido un error');
    })
    .always(function() {});
  }

});
</script>