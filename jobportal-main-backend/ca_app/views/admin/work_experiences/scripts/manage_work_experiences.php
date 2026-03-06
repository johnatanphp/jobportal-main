<script type="text/javascript">
  $(function(){
    $( '.manage-work-experiences' ).click(function(){
      $( '#modal-work-experiences' ).modal('show');
      $( '#modal-work-experiences-add input[name="country_id"]' ).val($(this).data('country-id'));
      searchInternalAreas($(this).data('country-id'));
    });

    function searchInternalAreas(countryId) 
    {
      var dtSearchCandidate = $( '#tbl-manage-work-experiences' ).DataTable({
        "language": {
            "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
        },
        "destroy": true,
        "bAutoWidth": false,
        "deferRender": true,
        "iDisplayLength": 25,
        "bProcessing": true,
        ajax: {
          url: "<?php echo site_url('admin/work_experiences/list'); ?>",
          type: 'GET',
          data: {
            country_id: countryId
          }
        },
        columns: [
            {data:'name', 'className': 'style_td text-left'},
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
                <button class="btn btn-success btn-xs js-btn-work-experiences-edit"
                        data-id="${data.id}"
                        data-name="${data.name}"
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

    $( '#btn-work-experiences-add' ).click(function() {
      $( '#modal-work-experiences-add' ).modal('show');
    });

    $( '#modal-work-experiences-add form' ).submit(function(e){
      e.preventDefault();

      url = $(this).prop('action');
      data = $(this).serialize();
      form = $(this);

      $.post(url, data, function(response) {
          var status = response.success;
          if (status) {
            toastr["success"](response.message);
            $( '#tbl-manage-work-experiences' ).DataTable().ajax.reload();
            $( '#modal-work-experiences-add' ).modal('hide');
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

    $( document ).on('click', '.js-btn-work-experiences-edit', function() {
      $( '#modal-work-experiences-edit' ).modal('show');
      $( '#modal-work-experiences-edit input[name="id"]').val($(this).data('id'));
      $( '#modal-work-experiences-edit input[name="name"]').val($(this).data('name'));
      $( '#modal-work-experiences-edit select[name="active"]').val($(this).data('active'));
      $( '#modal-work-experiences-edit input[name="valorization_score"]').val($(this).data('val-score'));
      $( '#modal-work-experiences-edit input[name="valorization_grade"]').val($(this).data('val-grade'));
    });

    $( '#modal-work-experiences-edit form' ).submit(function(e){
      e.preventDefault();

      url = $(this).prop('action');
      data = $(this).serialize();
      form = $(this);

      $.post(url, data, function(response) {
          var status = response.success;
          if (status) {
            toastr["success"](response.message);
            $( '#tbl-manage-work-experiences' ).DataTable().ajax.reload();
            $( '#modal-work-experiences-edit' ).modal('hide');
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
