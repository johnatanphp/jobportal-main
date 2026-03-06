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
                  Codigo
              </th>
              <th>
                  Descripción
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
        $( '#cost_center' ).val() == '') {
        toastr["error"]("¡Debe seleccionar Consultora, Cliente y Centro de Costo!");
      return;
    }

    $( '#modal-select-eecc' ).modal('show');
    selectEECC();
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
              class="btn btn-xs btn-primary btn-select-eecc">
              Seleccionar
            </button>`;
        }},
        {data:'eecc_code','className':'text-left'},
        {data:'eecc_description','className':'text-left'}
      ]
    });

    $( document ).on('click', '.btn-select-eecc', function(){

      code = $(this).data('code');
      desc = $(this).data('description');

      eecc_td1 = $( '#tbl-field-select-eecc tr:eq(0)' ).find('td:eq(0)');
      eecc_td2 = $( '#tbl-field-select-eecc tr:eq(0)' ).find('td:eq(1)');
      eecc_td3 = $( '#tbl-field-select-eecc tr:eq(0)' ).find('td:eq(2)');
      
      eecc_td1.html(`
        ${code}
        <input type="hidden" name="eecc_code" value="${code}">
        <br>
        ${desc}
        <input type="hidden" name="eecc_description" value="${desc}">
      `);
      eecc_td1.show();
      
      eecc_td2.html(`
        <a id="btn-select-eecc" href="#">
          <i class="glyphicon glyphicon-pencil"></i>
        </a>
      `);

      eecc_td2.show();
      eecc_td3.show();

      $( '#btn-remove-select-eecc' ).show();
      $( '#modal-select-eecc' ).modal('hide');
    });
  } 
});
</script>