<script type="text/javascript">
  $(function(){
    $( '.manage-internal-areas' ).click(function(){
      $( '#modal-internal-areas' ).modal('show');
      $( '#modal-internal-areas-add input[name="country_id"]' ).val($(this).data('country-id'));
      searchInternalAreas($(this).data('country-id'));
    });

    function searchInternalAreas(countryId) 
    {
      var dtSearchCandidate = $( '#tbl-manage-internal-areas' ).DataTable({
        "language": {
            "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
        },
        "destroy": true,
        "bAutoWidth": false,
        "deferRender": true,
        "iDisplayLength": 25,
        "bProcessing": true,
        ajax: {
          url: "<?php echo site_url('admin/internal_areas/list'); ?>",
          type: 'GET',
          data: {
            country_id: countryId
          }
        },
        columns: [
            {data:'area_name', 'className': 'style_td text-left'},
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
                <button class="btn btn-success btn-xs js-btn-internal-areas-edit"
                        data-id="${data.ID}"
                        data-name="${data.area_name}"
                        data-active="${data.active}">
                  Editar
                </button>
              `;
            }, 'className': 'style_td text-left'
          },
        ]
      });
    }

    $( '#btn-internal-areas-add' ).click(function() {
      $( '#modal-internal-areas-add' ).modal('show');
    });

    $( '#modal-internal-areas-add form' ).submit(function(e){
      e.preventDefault();

      url = $(this).prop('action');
      data = $(this).serialize();
      form = $(this);

      $.post(url, data, function(response) {
          var status = response.success;
          if (status) {
            toastr["success"](response.message);
            $( '#tbl-manage-internal-areas' ).DataTable().ajax.reload();
            $( '#modal-internal-areas-add' ).modal('hide');
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

    $( document ).on('click', '.js-btn-internal-areas-edit', function() {
      $( '#modal-internal-areas-edit' ).modal('show');
      $( '#modal-internal-areas-edit input[name="id"]').val($(this).data('id'));
      $( '#modal-internal-areas-edit input[name="name"]').val($(this).data('name'));
      $( '#modal-internal-areas-edit select[name="active"]').val($(this).data('active'));
    });

    $( '#modal-internal-areas-edit form' ).submit(function(e){
      e.preventDefault();

      url = $(this).prop('action');
      data = $(this).serialize();
      form = $(this);

      $.post(url, data, function(response) {
          var status = response.success;
          if (status) {
            toastr["success"](response.message);
            $( '#tbl-manage-internal-areas' ).DataTable().ajax.reload();
            $( '#modal-internal-areas-edit' ).modal('hide');
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
