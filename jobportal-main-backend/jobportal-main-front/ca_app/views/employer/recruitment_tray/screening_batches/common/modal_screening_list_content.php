<style>
  #screening-batches-list_wrapper .dataTables_filter {
    float: left;
  }

  #screening-batches-list_wrapper .dt-buttons {
    float: right;
  }
</style>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title">Screening por lote</h4>
</div>
<div class="modal-body">
  <div class="table-responsive">
    <table id="screening-batches-list" width="100%" class="table">
      <thead>
        <tr>
          <th></th>
          <th>
            Postulante
          </th>
          <th>Tipo</th>
          <th>Puesto</th>
          <th>C. Costo</th>
          <th>Tipo egreso</th>
          <th>Lote fecha</th>
          <th>Expedición</th>
          <th>Vencimiento</th>
          <th style="text-align: center;">Conforme</th>
          <th style="width: 40px;"></th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>
<script>
$(function() {

  function init() {

    dtSearchCandidate = $( '#screening-batches-list').DataTable({
      dom: 'Bfrtip',
      "language": {
        "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
      },
      "destroy": true,
      "bAutoWidth": false,
      "deferRender": true,
      "iDisplayLength": 25,
      "bProcessing": true,
      ajax: {
        url: "<?php echo site_url('employer/recruitment_tray/screening_batches/get_data_list'); ?>",
        type: 'GET',
        data: {
          client_code: "<?php echo $client_code; ?>"
        }
      },
      buttons: [{
        text: '<i class="glyphicon glyphicon-refresh"></i> Recargar',
        className: 'btn btn-sm btn-default',
        action: function (e, dt, node, config) {
          dt.ajax.reload();
        }
      }],
      'columnDefs': [{
        'targets': [0, 1, 2, 3, 4, 5, 9, 10],
        'orderable': false,
      }],
      columns: [
        {
          data: null, render:function(data) {

            if (data.status_id == 1) {
              return `<a class="btn btn-sm btn-block btn-default" disabled>En curso</a>`;
            }
        
            if (data.status_id == 2) {
                
              let items = '';

              data.files.map(function(file){
                items+=`
                <li>
                  <a href="${file.file_url}" 
                    target="_blank">
                    ${file.description}
                  </a>
                </li>
                `;
              });

              return `
                <div class="dropdown dropdown-options-job">
                  <button class="btn btn-block dropdown-toggle btn-default" type="button" data-toggle="dropdown">
                    <span style="color: green; font-size: 12px;">
                      <span class="glyphicon glyphicon-ok"></span>
                    </span>  
                    Generado
                  </button>
                  <ul class="dropdown-menu dropdown-menu-left">${items}</ul>
                </div>
              `;
            }

            if (data.status_id == 3) {
              return `
                <a class="btn btn-sm btn-block btn-default btn-show-error"
                    data-id="${data.id}" 
                    style="color: #c21414;">
                  <span class="glyphicon glyphicon-exclamation-sign"></span> Fallido
                </a>
              `;
            }

            return ``;
          }, 'className': 'style_td text-left'
        },
        {
          data: null, render:function(data) {
            return `
              ${data.first_name} ${data.last_name} 
              <p style="font-size: 11px;">${data.document_type_abbreviation_name} ${data.document_number}</p>
            `;
          }, 'className': 'style_td text-left'
        },
        {data:'type_name', 'className': 'style_td text-left'},
        {data:'job_title', 'className': 'style_td text-left'},
        {data:'cost_center', 'className': 'style_td text-left'},
        {data:'type_expense', 'className': 'style_td text-left'},
        {
          data: null, render:function(data) {

            if (!data.created_at) {
              return '-';
            }

            const dateOriginal = new Date(data.created_at);

            const options = {
              day: '2-digit',   // Día con dos dígitos (03)
              month: 'short', // Mes abreviado (dic)
              year: 'numeric' // Año completo
            };

            return new Intl.DateTimeFormat(undefined, options).format(dateOriginal);

          }, 'className': 'style_td text-left'
        },
        {
          data: null, render:function(data) {

            if (!data.screening_created_at) {
              return '-';
            }
            
            const dateString = data.screening_created_at;
            const dateOriginal = new Date(dateString);

            const options = {
              day: '2-digit',   // Día con dos dígitos (03)
              month: 'short', // Mes abreviado (dic)
              year: 'numeric' // Año completo
            };

            return new Intl.DateTimeFormat(undefined, options).format(dateOriginal);

          }, 'className': 'style_td text-left'
        },
        {
          data: null, render:function(data) {

            if (!data.screening_due_date) {
              return '-';
            }
            
            const dateString = data.screening_due_date;
            const dateOriginal = new Date(dateString);

            const options = {
              day: '2-digit',   // Día con dos dígitos (03)
              month: 'short', // Mes abreviado (dic)
              year: 'numeric' // Año completo
            };

            return new Intl.DateTimeFormat(undefined, options).format(dateOriginal);

          }, 'className': 'style_td text-left'
        },
        {
          data: null, render:function(data) {

            if (data.status_id != 2) {
              return '-';
            }

            if (data.screening_its_data_prosecution == 0) {
              return `
                <span style="color: green; font-size: 12px;">
                  <span class="glyphicon glyphicon-ok"></span>
                </span>
              `;
            }

            if (data.screening_its_data_prosecution == 1) {
              return `
                <span style="color: #c21414; font-size: 12px;">
                  <span class="glyphicon glyphicon-exclamation-sign"></span>
                </span>
              `;
            }
          }, 'className': 'style_td text-center'
        },
        {
          data: null, render:function(data) {

            if (data.screening_remaining_days && data.status_id == 2) {
              return data.screening_remaining_days > 0 ? `<span class="label label-success">Vigente</span>` : `<span class="label label-warning">Vencido</span>`;
            }
           
            return `-`;
          }, 'className': 'style_td text-center'
        },
      ],
      "drawCallback": function(settings) {
        $( '.dt-button' ).removeClass('dt-button');
      }
    });
  }

  init();
});

</script>
