<script type="text/javascript">
  $(function(){
    $( '.manage-cost-centers' ).click(function(){
      $( '#modal-cost-centers' ).modal('show');
      $( '#modal-cost-centers' ).data('company-id', $(this).data('company-id'));

      $( '#modal-cost-centers-add input[name="company_id"]' ).val($(this).data('company-id'));
      search($(this).data('company-id'));
    });

    function search(companyId) 
    {
      var dtSearchCandidate = $( '#tbl-manage-cost-centers' ).DataTable({
        "language": {
            "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
        },
        "destroy": true,
        "bAutoWidth": false,
        "deferRender": true,
        "iDisplayLength": 25,
        "bProcessing": true,
        ajax: {
          url: "<?php echo site_url('admin/workflow_cost_centers/list'); ?>",
          type: 'GET',
          data: {
            company_id: companyId
          }
        },
        columns: [
            {data:'cia_name', 'className': 'style_td text-left'},
            {data:'business_unit_name', 'className': 'style_td text-left'},
            {data:'client_name', 'className': 'style_td text-left'},
            {data:'code', 'className': 'style_td text-left'},
            {
              data: null, render:function(data) {
                if (data.has_penalty == '1') {
                  return 'SI';
                }

                if (data.has_penalty == '0') {
                  return 'NO';
                }     

                return ``;
              }, 'className': 'style_td text-left'
            },  
            {
              data: null, render:function(data) {
                if (data.active == '1') {
                  return 'Activo';
                }

                if (data.active == '0') {
                  return 'Inactivo';
                }     

                return ``;
              }, 'className': 'style_td text-left'
            },          
          {
            data: null, render:function(data) {
              return `
                <button class="btn btn-success btn-xs js-btn-cost-centers-edit"
                        data-id="${data.id}">
                  Editar
                </button>
              `;
            }, 'className': 'style_td text-left'
          },
        ]
      });
    }

    $( '#btn-cost-centers-add' ).click(function() {
      $( '#modal-cost-centers-add' ).modal('show');
      url = "<?php echo site_url('admin/workflow_cost_centers/load_form_add'); ?>";
      data = {
        'company_id': $( '#modal-cost-centers' ).data('company-id')
      };

      $( '#modal-cost-centers-add .modal-content-body' ).html("Cargando...");

      $.get(url, data, function(response){
        $( '#modal-cost-centers-add .modal-content-body' ).html(response);
      });
    });

    $( '#modal-cost-centers-add form' ).submit(function(e){
      e.preventDefault();

      url = $(this).prop('action');
      data = $(this).serialize();
      form = $(this);

      $.post(url, data, function(response) {
          var status = response.success;
          if (status) {
            toastr["success"](response.message);
            $( '#tbl-manage-cost-centers' ).DataTable().ajax.reload();
            $( '#modal-cost-centers-add' ).modal('hide');
            (form[0]).reset();

          } else {
            toastr["error"](response.message);
          }
      }, 'json')
      .fail(function(){
          alert("¡Ha ocurrido un error!");
      });

      return false;
    });

    $( document ).on('click', '.js-btn-cost-centers-edit', function() {
      $( '#modal-cost-centers-edit' ).modal('show');

      url = "<?php echo site_url('admin/workflow_cost_centers/load_form_edit'); ?>";
      data = {
        'id': $( this ).data('id')
      };

      $( '#modal-cost-centers-edit .modal-content-body' ).html("Cargando...");

      $.get(url, data, function(response){
        $( '#modal-cost-centers-edit .modal-content-body' ).html(response);
      });
    });

    $( '#modal-cost-centers-edit form' ).submit(function(e){
      e.preventDefault();

      url = $(this).prop('action');
      data = $(this).serialize();
      form = $(this);

      $.post(url, data, function(response) {
          var status = response.success;
          if (status) {
            toastr["success"](response.message);
            $( '#tbl-manage-cost-centers' ).DataTable().ajax.reload();
            $( '#modal-cost-centers-edit' ).modal('hide');
            (form[0]).reset();

          } else {
            toastr["error"](response.message);
          }
      }, 'json')
      .fail(function(){
          alert("¡Ha ocurrido un error!");
      });

      return false;
    });


    // $( document ).on('change', '#modal-cost-centers-add select[name=cia_code]', function(){
    //   client_input =  $('#modal-cost-centers-add select[name=client_code]');
    //   cia_code = $(this).val();
    //   uni_code = $('#modal-cost-centers-add select[name=business_unit_code]') .val();
    //   company_id = $('#modal-cost-centers').data('company-id');
    //   console.log(company_id);

    //   getClientsCompany(client_input, cia_code, uni_code, company_id);
    // });

    // $( document ).on('change', '#modal-cost-centers-add select[name=business_unit_code]', function(){
    //   client_input =  $('#modal-cost-centers-add select[name=client_code]');
    //   uni_code = $(this).val();
    //   cia_code = $('#modal-cost-centers-add select[name=cia_code]') .val();
    //   company_id = $('#modal-cost-centers').data('company-id');

    //   getClientsCompany(client_input, cia_code, uni_code, company_id);
    // });


    // $( document ).on('change', '#modal-cost-centers-edit select[name=cia_code]', function(){
    //   client_input =  $('#modal-cost-centers-edit select[name=client_code]');
    //   cia_code = $(this).val();
    //   uni_code = $('#modal-cost-centers-edit select[name=business_unit_code]') .val();
    //   company_id = $('#modal-cost-centers').data('company-id');
    //   console.log(company_id);

    //   getClientsCompany(client_input, cia_code, uni_code, company_id);
    // });

    // $( document ).on('change', '#modal-cost-centers-edit select[name=business_unit_code]', function(){
    //   client_input =  $('#modal-cost-centers-edit select[name=client_code]');
    //   uni_code = $(this).val();
    //   cia_code = $('#modal-cost-centers-edit select[name=cia_code]') .val();
    //   company_id = $('#modal-cost-centers').data('company-id');

    //   getClientsCompany(client_input, cia_code, uni_code, company_id);
    // });


    // function getClientsCompany(client_input, cia, uni, company_id) 
    // {
    //     var url = "<?php echo site_url('admin/workflow_cost_centers/get_clients'); ?>";
    //     var data = {
    //       cia_code: cia,
    //       unit_code: uni,
    //       company_id: company_id
    //     }

    //     $(client_input).html('<option value="">Cargando...</option>').prop('disabled', true);

    //     $.post(url, data, function(data) {
        
    //       var clients =  data.success ? data.clients : [];

    //       console.log(clients);

    //       $.each(clients, function(i, row) {
    //         $(client_input).append('<option value="' + row.code + '">' + row.name + '</option>');
    //       });      
    //     }, 'json')
    //     .fail(function() {
    //       alert('¡Ha ocurrido un error al tratar de listar las empresas clientes!');
    //     }).always(function() {
    //       $( client_input ).find("option:eq(0)").text("Cliente");
    //       $( client_input ).prop('disabled', false);
    //     }); 
    //   }
  });
</script>
