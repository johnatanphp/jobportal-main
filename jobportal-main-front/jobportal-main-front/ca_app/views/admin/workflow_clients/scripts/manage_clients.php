<script type="text/javascript">
  $(function(){
    $( '.manage-clients' ).click(function(){
      $( '#modal-clients' ).modal('show');
      $( '#modal-clients' ).data('company-id', $(this).data('company-id'));

      $( '#modal-clients-add input[name="company_id"]' ).val($(this).data('company-id'));
      search($(this).data('company-id'));
    });

    function search(companyId) 
    {
      var dtSearchCandidate = $( '#tbl-manage-clients' ).DataTable({
        "language": {
            "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
        },
        "destroy": true,
        "bAutoWidth": false,
        "deferRender": true,
        "iDisplayLength": 25,
        "bProcessing": true,
        ajax: {
          url: "<?php echo site_url('admin/workflow_clients/list'); ?>",
          type: 'GET',
          data: {
            company_id: companyId
          }
        },
        columns: [
            {data:'code', 'className': 'style_td text-left'},
            {data:'name', 'className': 'style_td text-left'},
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
                <button class="btn btn-success btn-xs js-btn-clients-edit"
                        data-id="${data.id}">
                  Editar
                </button>
              `;
            }, 'className': 'style_td text-left'
          },
        ]
      });
    }

    $( '#btn-clients-add' ).click(function() {
      $( '#modal-clients-add' ).modal('show');
      url = "<?php echo site_url('admin/workflow_clients/load_form_add'); ?>";
      data = {
        'company_id': $( '#modal-clients' ).data('company-id')
      };

      $( '#modal-clients-add .modal-content-body' ).html("Cargando...");

      $.get(url, data, function(response){
        $( '#modal-clients-add .modal-content-body' ).html(response);
      });
    });

    $( '#modal-clients-add form' ).submit(function(e){
      e.preventDefault();

      url = $(this).prop('action');
      data = $(this).serialize();
      form = $(this);

      $.post(url, data, function(response) {
          var status = response.success;
          if (status) {
            toastr["success"](response.message);
            $( '#tbl-manage-clients' ).DataTable().ajax.reload();
            $( '#modal-clients-add' ).modal('hide');
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

    $( document ).on('click', '.js-btn-clients-edit', function() {
      $( '#modal-clients-edit' ).modal('show');

      url = "<?php echo site_url('admin/workflow_clients/load_form_edit'); ?>";
      data = {
        'id': $( this ).data('id')
      };

      $( '#modal-clients-edit .modal-content-body' ).html("Cargando...");

      $.get(url, data, function(response){
        $( '#modal-clients-edit .modal-content-body' ).html(response);
      });
    });

    $( '#modal-clients-edit form' ).submit(function(e){
      e.preventDefault();

      url = $(this).prop('action');
      data = $(this).serialize();
      form = $(this);

      $.post(url, data, function(response) {
          var status = response.success;
          if (status) {
            toastr["success"](response.message);
            $( '#tbl-manage-clients' ).DataTable().ajax.reload();
            $( '#modal-clients-edit' ).modal('hide');
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
  });
</script>
