<script type="text/javascript">
  $(function(){
    $( '.manage-business-units' ).click(function(){
      $( '#modal-business-units' ).modal('show');
      $( '#modal-business-units-add input[name="company_id"]' ).val($(this).data('company-id'));
      search($(this).data('company-id'));
    });

    function search(companyId) 
    {
      var dtSearchCandidate = $( '#tbl-manage-business-units' ).DataTable({
        "language": {
            "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
        },
        "destroy": true,
        "bAutoWidth": false,
        "deferRender": true,
        "iDisplayLength": 25,
        "bProcessing": true,
        ajax: {
          url: "<?php echo site_url('admin/business_units/list'); ?>",
          type: 'GET',
          data: {
            company_id: companyId
          }
        },
        columns: [
            {data:'business_unit_code', 'className': 'style_td text-left'},
            {data:'business_unit_name', 'className': 'style_td text-left'},
            {data:'acronym_code', 'className': 'style_td text-left'},
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
                <button class="btn btn-success btn-xs js-btn-business-units-edit"
                        data-id="${data.ID}"
                        data-code="${data.business_unit_code}"
                        data-name="${data.business_unit_name}"
                        data-acronym-code="${data.acronym_code}"
                        data-active="${data.active}">
                  Editar
                </button>
              `;
            }, 'className': 'style_td text-left'
          },
        ]
      });
    }

    $( '#btn-business-units-add' ).click(function() {
      $( '#modal-business-units-add' ).modal('show');
    });

    $( '#modal-business-units-add form' ).submit(function(e){
      e.preventDefault();

      url = $(this).prop('action');
      data = $(this).serialize();
      form = $(this);

      $.post(url, data, function(response) {
          var status = response.success;
          if (status) {
            toastr["success"](response.message);
            $( '#tbl-manage-business-units' ).DataTable().ajax.reload();
            $( '#modal-business-units-add' ).modal('hide');
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

    $( document ).on('click', '.js-btn-business-units-edit', function() {
      $( '#modal-business-units-edit' ).modal('show');
      $( '#modal-business-units-edit input[name="id"]').val($(this).data('id'));
      $( '#modal-business-units-edit input[name="code"]').val($(this).data('code'));
      $( '#modal-business-units-edit input[name="name"]').val($(this).data('name'));
      $( '#modal-business-units-edit input[name="acronym_code"]').val($(this).data('acronym-code'));
      $( '#modal-business-units-edit select[name="active"]').val($(this).data('active'));
    });

    $( '#modal-business-units-edit form' ).submit(function(e){
      e.preventDefault();

      url = $(this).prop('action');
      data = $(this).serialize();
      form = $(this);

      $.post(url, data, function(response) {
          var status = response.success;
          if (status) {
            toastr["success"](response.message);
            $( '#tbl-manage-business-units' ).DataTable().ajax.reload();
            $( '#modal-business-units-edit' ).modal('hide');
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
