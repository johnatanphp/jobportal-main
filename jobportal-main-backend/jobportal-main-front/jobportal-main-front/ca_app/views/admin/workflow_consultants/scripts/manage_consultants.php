<script type="text/javascript">
  $(function(){
    $( '.manage-consultants' ).click(function(){
      $( '#modal-consultants' ).modal('show');
      $( '#modal-consultants-add input[name="company_id"]' ).val($(this).data('company-id'));
      search($(this).data('company-id'));
  
    });

    function search(companyId) 
    {
      var dtSearchCandidate = $( '#tbl-manage-consultants' ).DataTable({
        "language": {
            "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
        },
        "destroy": true,
        "bAutoWidth": false,
        "deferRender": true,
        "iDisplayLength": 25,
        "bProcessing": true,
        ajax: {
          url: "<?php echo site_url('admin/workflow_consultants/list'); ?>",
          type: 'GET',
          data: {
            company_id: companyId
          }
        },
        columns: [
            {data:'code', 'className': 'style_td text-left'},
            {data:'name', 'className': 'style_td text-left'},
            {data:'type_service', 'className': 'style_td text-left'},
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
                <button class="btn btn-success btn-xs js-btn-consultants-edit"
                        data-id="${data.id}"
                        data-no-cia="${data.code}"
                        data-name="${data.name}"
                        data-type-service="${data.type_service}"
                        data-active="${data.active}">
                  Editar
                </button>
              `;
            }, 'className': 'style_td text-left'
          },
        ]
      });
    }

    $( '#btn-consultants-add' ).click(function() {
      $( '#modal-consultants-add' ).modal('show');
    });

    $( '#modal-consultants-add form' ).submit(function(e){
      e.preventDefault();

      url = $(this).prop('action');
      data = $(this).serialize();
      form = $(this);

      $.post(url, data, function(response) {
          var status = response.success;
          if (status) {
            toastr["success"](response.message);
            $( '#tbl-manage-consultants' ).DataTable().ajax.reload();
            $( '#modal-consultants-add' ).modal('hide');
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

    $( document ).on('click', '.js-btn-consultants-edit', function() {
      $( '#modal-consultants-edit' ).modal('show');
      $( '#modal-consultants-edit input[name="id"]').val($(this).data('id'));
      $( '#modal-consultants-edit input[name="no_cia"]').val($(this).data('no-cia'));
      $( '#modal-consultants-edit input[name="name"]').val($(this).data('name'));
      $( '#modal-consultants-edit input[name="type_service"]').val($(this).data('type-service'));
      $( '#modal-consultants-edit select[name="active"]').val($(this).data('active'));
    });

    $( '#modal-consultants-edit form' ).submit(function(e){
      e.preventDefault();

      url = $(this).prop('action');
      data = $(this).serialize();
      form = $(this);

      $.post(url, data, function(response) {
          var status = response.success;
          if (status) {
            toastr["success"](response.message);
            $( '#tbl-manage-consultants' ).DataTable().ajax.reload();
            $( '#modal-consultants-edit' ).modal('hide');
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
