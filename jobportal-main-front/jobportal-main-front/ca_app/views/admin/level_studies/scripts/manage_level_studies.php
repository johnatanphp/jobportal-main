<script type="text/javascript">
  $(function(){
    $( '.manage-level-studies' ).click(function(){
      $( '#modal-level-studies' ).modal('show');
      $( '#modal-level-studies-add input[name="country_id"]' ).val($(this).data('country-id'));
      searchInternalAreas($(this).data('country-id'));
    });

    function searchInternalAreas(countryId) 
    {
      var dtSearchCandidate = $( '#tbl-manage-level-studies' ).DataTable({
        "language": {
            "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
        },
        "destroy": true,
        "bAutoWidth": false,
        "deferRender": true,
        "iDisplayLength": 25,
        "bProcessing": true,
        ajax: {
          url: "<?php echo site_url('admin/level_studies/list'); ?>",
          type: 'GET',
          data: {
            country_id: countryId
          }
        },
        columns: [
            {data:'text', 'className': 'style_td text-left'},
            {data:'valorization_score', 'className': 'style_td text-left'},
            {data:'valorization_grade', 'className': 'style_td text-left'},
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
                <button class="btn btn-success btn-xs js-btn-level-studies-edit"
                        data-id="${data.ID}"
                        data-name="${data.text}"
                        data-val-score="${data.valorization_score}"
                        data-val-grade="${data.valorization_grade}"
                        data-active="${data.active}">
                  Editar
                </button>
              `;
            }, 'className': 'style_td text-left'
          },
        ]
      });
    }

    $( '#btn-level-studies-add' ).click(function() {
      $( '#modal-level-studies-add' ).modal('show');
    });

    $( '#modal-level-studies-add form' ).submit(function(e){
      e.preventDefault();

      url = $(this).prop('action');
      data = $(this).serialize();
      form = $(this);

      $.post(url, data, function(response) {
          var status = response.success;
          if (status) {
            toastr["success"](response.message);
            $( '#tbl-manage-level-studies' ).DataTable().ajax.reload();
            $( '#modal-level-studies-add' ).modal('hide');
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

    $( document ).on('click', '.js-btn-level-studies-edit', function() {
      $( '#modal-level-studies-edit' ).modal('show');
      $( '#modal-level-studies-edit input[name="id"]').val($(this).data('id'));
      $( '#modal-level-studies-edit input[name="name"]').val($(this).data('name'));
      $( '#modal-level-studies-edit input[name="valorization_score"]').val($(this).data('val-score'));
      $( '#modal-level-studies-edit input[name="valorization_grade"]').val($(this).data('val-grade'));
      $( '#modal-level-studies-edit select[name="active"]').val($(this).data('active'));
    });

    $( '#modal-level-studies-edit form' ).submit(function(e){
      e.preventDefault();

      url = $(this).prop('action');
      data = $(this).serialize();
      form = $(this);

      $.post(url, data, function(response) {
          var status = response.success;
          if (status) {
            toastr["success"](response.message);
            $( '#tbl-manage-level-studies' ).DataTable().ajax.reload();
            $( '#modal-level-studies-edit' ).modal('hide');
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
