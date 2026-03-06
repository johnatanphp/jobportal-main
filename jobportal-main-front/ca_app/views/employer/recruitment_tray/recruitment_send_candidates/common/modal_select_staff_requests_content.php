<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title">Solicitudes para <?php e($client->name); ?></h4>
</div>
<div class="modal-body">
  <div class="table-responsive">
    <table id="select-staff-requests-list" width="100%" class="table">
      <thead>
        <tr>
          <th></th>
          <th>
            Solicitud
          </th>
          <th>Creada</th>
          <th>Solicitante</th>
          <th>Tipo</th>
          <th>Consultora</th>
          <th>Unidad Negocio</th>
          <th>Centro costo</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>

<script type="text/javascript">
(function(){

  function initTableStaffRequests() {

    const tableStaffRequest= $( '#select-staff-requests-list' ).DataTable({
      "language": {
        "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
      },
      "destroy": true,
      "bAutoWidth": false,
      "deferRender": true,
      "iDisplayLength": 25,
      "bProcessing": true,
      "ordering": false,
      ajax: {
        url: "<?php echo site_url('employer/recruitment_tray/recruitment_send_candidates/staff_request_list'); ?>",
        type: 'GET',
        data: {
          client_code: "<?php echo $client->code; ?>"
        }
      },
      // 'columnDefs': [{
      //   'targets': [1],
      //   'orderable': true,
      // }],
      columns: [
        {
          data: null, render:function(data) {

            const statusRequestEnabled = ['published', 'assigned'];

            if (statusRequestEnabled.includes(data.status_id)) {
              return `
                <button class="btn btn-sm btn-primary btn-style-1 btn-select-request"
                        data-id="${data.request_id}"
                        data-name="${data.job_title}">
                  Seleccionar
                </button>`;
            }

            return `<button class="btn btn-sm btn-default" disabled>Seleccionar</button>`;
          
          }, 'className': 'style_td text-left'
        },
        {
          data: null, render:function(data) {
            return `
              <div>${data.job_title}<div>
              <div>Id: ${data.request_id}<div>
            `;          
          }, 'className': 'style_td text-left'
        },
        {
          data: null, render:function(data) {
            const dateOriginal = new Date(data.creation_date);

            const options = {
              day: '2-digit',   // Día con dos dígitos (03)
              month: 'short', // Mes abreviado (dic)
              year: 'numeric' // Año completo
            };

            return new Intl.DateTimeFormat(undefined, options).format(dateOriginal);

          }, 'className': 'style_td text-left'
        },
        {data:'recruiter_first_name', 'className': 'style_td text-left'},
        {data:'request_type_name', 'className': 'style_td text-left'},
        {data:'consultant_name', 'className': 'style_td text-left'},
        {data:'business_unit_name', 'className': 'style_td text-left'},
        {data:'cost_center', 'className': 'style_td text-left'},
        {data:'status_name', 'className': 'style_td text-left'},
      ],
      "drawCallback": function(settings) {}
    });
  }
  
  function selectRequest(e) {
    
    const button = e.target;

    if (!button.classList.contains('btn-select-request')) {
      return;
    }

    const requestId = button.dataset.id;
    const requestName = button.dataset.name;
    
    const selectContentRequest = `
      <div class="selector-request">
        <span>Codigo: ${requestId}</span>
        <span style="text-transform: uppercase;">
          ${requestName}
        </span>
        <input type="hidden" name="request_id" value="${requestId}">
      </div>
      <div class="selector-change">
        <a href="#" class="select-requests">Cambiar</a>
      </div>`;

      document.querySelector('.container-select-request').innerHTML = selectContentRequest;
      $(document.querySelector('#modal-hiring-send-select-staff-requests')).modal('hide');
  }

  function init() {
    document.addEventListener('click', selectRequest);
    initTableStaffRequests();
  }

  init();

})();
</script>