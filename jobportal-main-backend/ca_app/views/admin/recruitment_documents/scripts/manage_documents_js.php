<script type="text/javascript">
  $(function(){
    $( '.manage-rys-documents' ).click(function(){
      $( '#modal-rys-documents' ).modal('show');
      $( '#modal-rys-documents-add input[name="country_id"]' ).val($(this).data('country-id'));
      search($(this).data('country-id'));
    });

    function search(countryId) 
    {
      var dtSearchCandidate = $( '#tbl-manage-rys-documents' ).DataTable({
        "language": {
            "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
        },
        "destroy": true,
        "bAutoWidth": false,
        "deferRender": true,
        "iDisplayLength": 25,
        "bProcessing": true,
        ajax: {
          url: "<?php echo site_url('admin/recruitment_documents/list'); ?>",
          type: 'GET',
          data: {
            country_id: countryId
          }
        },
        columns: [
            {data:'id', 'className': 'style_td text-left'},
            {data:'name', 'className': 'style_td text-left'},
            {data:'option_type_name', 'className': 'style_td text-left'},
            {
              data: null, render:function(data) {
                if (data.active == 1) {
                  return 'Activo';
                }

                if (data.active == 0) {
                  return 'Inactivo';
                }     

                return ``;
              }, 'className': 'style_td text-left'
          },          
          {
            data: null, render:function(data) {
              return `
                <button class="btn btn-success btn-xs js-btn-rys-documents-edit"
                        data-id="${data.id}"
                        data-name="${data.name}"
                        data-option-type-id="${data.option_type_id}"
                        data-allowed-files="${data.allowed_files}"
                        data-max-size="${data.max_size}"
                        data-active="${data.active}">
                  Editar
                </button>
              `;
            }, 'className': 'style_td text-left'
          },
        ]
      });
    }

    $( '#btn-rys-documents-add' ).click(function() {
      $( '#modal-rys-documents-add' ).modal('show');
    });

    $( '#form-rys-documents-add').submit(function(e){
      e.preventDefault();

      type = $( 'select[name="option_type_id"]' , this).val();

      if (type == 1) {
        formats = $( 'input[name="attach_allowed_file[]"]:checked' , this);

        if (formats.length == 0) {
          toastr["error"]('Debe seleccionar al menos 1 formato');
          return;
        }

        max_size = $.trim($( 'select[name="attach_max_size"]' , this).val());

        if (max_size == '') {
          toastr["error"]('Debe seleccionar el máximo del tamaño del archivo');
          return;
        }
      }
   
      url = $(this).prop('action');
      data = $(this).serialize();

      $.post(url, data, function(response) {
          var status = response.success;
          if (status) {
            toastr["success"](response.message);
            $( '#tbl-manage-rys-documents' ).DataTable().ajax.reload();
            $( '#modal-rys-documents-add' ).modal('hide');
            ($( '#form-rys-documents-add' )[0]).reset();

          } else {
            toastr["error"](response.message);
          }
      }, 'json')
      .fail(function(){
          toastr["error"]("¡Ha ocurrido un error!");
      });

      return false;
    });

    $( document ).on('click', '.js-btn-rys-documents-edit', function() {
      $( '#form-rys-documents-edit input[name="id"]').val($(this).data('id'));
      $( '#form-rys-documents-edit input[name="name"]').val($(this).data('name'));
      $( '#form-rys-documents-edit select[name="option_type_id"]').val($(this).data('option-type-id'));
      $( '#form-rys-documents-edit select[name="attach_max_size"]').val($(this).data('max-size'));
      $( '#form-rys-documents-edit select[name="active"]').val($(this).data('active'));

      $( '#form-rys-documents-edit input[name="attach_allowed_file[]"]' ).prop('checked', false);

      var allowedFiles = $.trim($(this).data('allowed-files'));
      allowedFiles.split(',').map(function(ext) {

        if (ext == '') {
          return;
        }

        $( `#form-rys-documents-edit input[name="attach_allowed_file[]"][value="${ext}"]` ).prop('checked', true);

      });

      $( '#form-rys-documents-edit select[name="option_type_id"]').change();

      $( '#modal-rys-documents-edit' ).modal('show');
    });

    $( '#form-rys-documents-edit' ).submit(function(e){
      e.preventDefault();

      type = $( 'select[name="option_type_id"]' , this).val();

      if (type == 1) {
        formats = $( 'input[name="attach_allowed_file[]"]:checked' , this);

        if (formats.length == 0) {
          toastr["error"]('Debe seleccionar al menos 1 formato');
          return;
        }

        max_size = $.trim($( 'select[name="attach_max_size"]' , this).val());

        if (max_size == '') {
          toastr["error"]('Debe seleccionar el máximo del tamaño del archivo');
          return;
        }
      }
   
      url = $(this).prop('action');
      data = $(this).serialize();

      $.post(url, data, function(response) {
          var status = response.success;
          if (status) {
            toastr["success"](response.message);
            $( '#tbl-manage-rys-documents' ).DataTable().ajax.reload();
            $( '#modal-rys-documents-edit' ).modal('hide');
            ($( '#form-rys-documents-edit' )[0]).reset();

          } else {
            toastr["error"](response.message);
          }
      }, 'json')
      .fail(function(){
          alert("¡Ha ocurrido un error!");
      });

      return false;
    });

    $( '#form-rys-documents-add select[name="option_type_id"]' ).change(function(e){
    
      value = $(this).val();

      if (value == 1) {
        $( '#form-rys-documents-add .container-attach' ).show();
      }

      if (value == 2) {
        $( '#form-rys-documents-add .container-attach' ).hide();
      }

    });

    $( '#form-rys-documents-edit select[name="option_type_id"]' ).change(function(e){
    
      value = $(this).val();

      if (value == 1) {
        $( '#form-rys-documents-edit .container-attach' ).show();
      }

      if (value == 2) {
        $( '#form-rys-documents-edit .container-attach' ).hide();
      }
    });
    
  });
</script>
