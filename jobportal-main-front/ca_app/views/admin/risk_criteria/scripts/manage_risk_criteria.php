<script type="text/javascript">
  $(function(){
    $( '.manage-risk-criteria' ).click(function(){
      $( '#modal-risk-criteria' ).modal('show');
      $( '#modal-risk-criteria-add input[name="country_id"]' ).val($(this).data('country-id'));
      searchInternalAreas($(this).data('country-id'));
    });

    function searchInternalAreas(countryId) 
    {
      var dtSearchCandidate = $( '#tbl-manage-risk-criteria' ).DataTable({
        "language": {
            "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
        },
        "destroy": true,
        "bAutoWidth": false,
        "deferRender": true,
        "iDisplayLength": 25,
        "bProcessing": true,
        ajax: {
          url: "<?php echo site_url('admin/risk_criteria/list'); ?>",
          type: 'GET',
          data: {
            country_id: countryId
          }
        },
        columns: [
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
                <button class="btn btn-success btn-xs js-btn-risk-criteria-edit"
                        data-id="${data.id}"
                        data-name="${data.name}"
                        data-active="${data.active}">
                  Editar
                </button>
              `;
            }, 'className': 'style_td text-left'
          },
        ]
      });
    }

    $( '#btn-risk-criteria-add' ).click(function() {
      $( '#modal-risk-criteria-add' ).modal('show');
    });

    $( '#modal-risk-criteria-add form' ).submit(function(e){
      e.preventDefault();

      url = $(this).prop('action');
      data = $(this).serialize();
      form = $(this);

      $.post(url, data, function(response) {
          var status = response.success;
          if (status) {
            toastr["success"](response.message);
            $( '#tbl-manage-risk-criteria' ).DataTable().ajax.reload();
            $( '#modal-risk-criteria-add' ).modal('hide');
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

    $( document ).on('click', '.js-btn-risk-criteria-edit', function() {
      $( '#modal-risk-criteria-edit' ).modal('show');
      $( '#modal-risk-criteria-edit input[name="id"]').val($(this).data('id'));
      $( '#modal-risk-criteria-edit input[name="name"]').val($(this).data('name'));
      $( '#modal-risk-criteria-edit select[name="active"]').val($(this).data('active'));
    });

    $( '#modal-risk-criteria-edit form' ).submit(function(e){
      e.preventDefault();

      url = $(this).prop('action');
      data = $(this).serialize();
      form = $(this);

      $.post(url, data, function(response) {
          var status = response.success;
          if (status) {
            toastr["success"](response.message);
            $( '#tbl-manage-risk-criteria' ).DataTable().ajax.reload();
            $( '#modal-risk-criteria-edit' ).modal('hide');
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
