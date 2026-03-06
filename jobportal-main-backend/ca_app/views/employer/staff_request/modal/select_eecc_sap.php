<!-- Modal -->
<div id="modal-select-eecc" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:70%">
  <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Seleccionar EECC</h4>
      </div>
      <div class="modal-body">
        <table id="tbl-select-eecc" class="table" width="100%">
          <thead> 
            <tr>
              <th>

              </th>
              <th>
                Tipo
              </th>
              <th>
                Codigo
              </th>
              <th>
                Planilla ID
              </th>
              <th>
                Cargo
              </th>
              <th>
                Cantidad
              </th>
            </tr>
          </thead>
          <tbody>
              
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
$(function(){

  $( '.btn-show-select-eecc' ).click(function() {
    if ($( '#consultant' ).val() == '' || 
        $( '#client_company' ).val() == '' || 
        $( '#business_unit' ).val() == '' || 
        $( '#cost_center' ).val() == '') {
        toastr["error"]("¡Debe seleccionar Consultora, Cliente, Unidad de negocio y Centro de costo!");
      return;
    }

    selectEECC();
    $( '#modal-select-eecc' ).modal('show');
    
  });

  $( '#btn-remove-select-eecc' ).click(function(){
    $( '#tbl-field-select-eecc tr:eq(0)' ).find('td:eq(0)').html("").hide();
    $( '#tbl-field-select-eecc tr:eq(0)' ).find('td:eq(1)').html("Seleccione");
    $( '#tbl-field-select-eecc tr:eq(0)' ).find('td:eq(2)').hide();
  });

  function selectEECC()
  {
    $( '#tbl-select-eecc' ).DataTable({
      language:{
        url:"<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
      },
      order:[[0,'desc']],
      destroy:true,
      bAutoWidth: false,
      deferRender:true,
      iDisplayLength: 25,
      ajax:{
        url: "<?php echo site_url('employer/staff_request/staff_requests/get_eecc'); ?>",
        type:'GET',
        data: {
          cia_code: $( '#consultant option:selected' ).data('no_cia'),
          client_code: $( '#client_company option:selected' ).data('cod_clie'),
          business_unit_code: $( '#business_unit option:selected' ).data('uni_neg'),
          cost_center: $( '#cost_center' ).val()
        }
      },
      columns:[
        {data:null,render:function(data){
          return `
            <button 
              data-toggle="tooltip" data-placement="bottom"
              data-code="${data.eecc_code}"
              data-description ="${data.eecc_description}" 
              data-form-id="${data.form_id}"
              data-job-code="${data.job_code}"
              data-job-vacancies="${data.job_vacancies}"
              
              class="btn btn-xs btn-primary btn-select-eecc">
              Seleccionar
            </button>`;
        }},
        {data:'eecc_type_name','className':'text-left'},
        {data:'eecc_code','className':'text-left'},
        {data:'form_id','className':'text-left'},
        {data:'job_name','className':'text-left'},
        {data:'job_vacancies','className':'text-left'},
      ]
    });

    $(document).on('click', '.btn-select-eecc', function(){

      const code = $(this).data('code');
      const desc = $(this).data('description');
      const formId = $(this).data('form-id');
      const jobCode = $(this).data('job-code');
      const vacancies = $(this).data('job-vacancies');

      eecc_td1 = $( '#tbl-field-select-eecc tr:eq(0)' ).find('td:eq(0)');
      eecc_td2 = $( '#tbl-field-select-eecc tr:eq(0)' ).find('td:eq(1)');
      eecc_td3 = $( '#tbl-field-select-eecc tr:eq(0)' ).find('td:eq(2)');
      
      eecc_td1.html(`
        ${code}
        <br>
        ${formId}
        <input type="hidden" name="eecc_code" value="${code}">
        <input type="hidden" name="eecc_description" value="${desc}">
        <input type="hidden" name="eecc_form_id" value="${formId}">
        <input type="hidden" name="eecc_job_code" value="${jobCode}">
        <input type="hidden" name="eecc_job_vacancies" value="${vacancies}">
      `);
      eecc_td1.show();
      
      eecc_td2.html(`
        <a id="btn-select-eecc" href="#">
          <i class="glyphicon glyphicon-pencil"></i>
        </a>
      `);

      eecc_td2.show();
      eecc_td3.show();

      if (jobCode) {
        $( '#job_layout option[data-code-integration="' + jobCode + '"]' ).prop("selected", true);
        $( '#job_layout' ).select2();
      }

      $( '#btn-remove-select-eecc' ).show();
      $( '#modal-select-eecc' ).modal('hide');
    });
  }

});
</script>