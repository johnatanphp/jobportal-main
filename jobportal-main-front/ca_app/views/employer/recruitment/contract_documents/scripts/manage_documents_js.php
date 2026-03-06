<script type="text/javascript">
  $(function(){
    $( '#btn-manage-contract-documents' ).click(function(){
      $( '#modal-contract-documents' ).modal('show');
      search();
    });

    function search() 
    {
      var dtSearchCandidate = $( '#tbl-manage-contract-documents' ).DataTable({
        "language": {
            "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
        },
        "destroy": true,
        "bAutoWidth": false,
        "deferRender": true,
        "iDisplayLength": 25,
        "bProcessing": true,
        ajax: {
          url: "<?php echo site_url('employer/recruitment/contract_documents/list'); ?>",
          type: 'GET'
        },
        columns: [
            {data:'id', 'className': 'style_td text-left'},
            {data:'name', 'className': 'style_td text-left'},
            {data:'option_type_name', 'className': 'style_td text-left'},
            {data:'group_name', 'className': 'style_td text-left'},
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
                <button class="btn btn-primary btn-xs js-btn-contract-documents-edit"
                        data-id="${data.id}"
                        data-name="${data.name}"
                        data-option-type-id="${data.option_type_id}"
                        data-allowed-files="${data.allowed_files}"
                        data-max-size="${data.max_size}"
                        data-group-id="${data.group_id}"
                        data-active="${data.active}">
                  Editar
                </button>
              `;
            }, 'className': 'style_td text-left'
          },
        ]
      });
    }

    $( '#btn-contract-documents-add' ).click(function() {
      $( '#modal-contract-documents-add' ).modal('show');
    });

    $( '#form-contract-documents-add' ).submit(function(e){
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
            $( '#tbl-manage-contract-documents' ).DataTable().ajax.reload();
            $( '#modal-contract-documents-add' ).modal('hide');
            ($( '#form-contract-documents-add' )[0]).reset();

          } else {
            toastr["error"](response.message);
          }
      }, 'json')
      .fail(function(){
          toastr["error"]("¡Ha ocurrido un error!");
      });

      return false;
    });

    $( document ).on('click', '.js-btn-contract-documents-edit', function() {
      $( '#form-contract-documents-edit input[name="id"]').val($(this).data('id'));
      $( '#form-contract-documents-edit input[name="name"]').val($(this).data('name'));
      $( '#form-contract-documents-edit select[name="option_type_id"]').val($(this).data('option-type-id'));
      $( '#form-contract-documents-edit select[name="attach_max_size"]').val($(this).data('max-size'));
      $( '#form-contract-documents-edit select[name="active"]').val($(this).data('active'));
      $( '#form-contract-documents-edit select[name="group_id"]').val($(this).data('group-id'));
      $( '#form-contract-documents-edit input[name="attach_allowed_file[]"]' ).prop('checked', false);

      var allowedFiles = $.trim($(this).data('allowed-files'));
      allowedFiles.split(',').map(function(ext) {

        if (ext == '') {
          return;
        }

        $( `#form-contract-documents-edit input[name="attach_allowed_file[]"][value="${ext}"]` ).prop('checked', true);

      });

      $( '#form-contract-documents-edit select[name="option_type_id"]').change();

      $( '#modal-contract-documents-edit' ).modal('show');
    });

    $( '#form-contract-documents-edit' ).submit(function(e){
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
            $( '#tbl-manage-contract-documents' ).DataTable().ajax.reload();
            $( '#modal-contract-documents-edit' ).modal('hide');
            ($( '#form-contract-documents-edit' )[0]).reset();

          } else {
            toastr["error"](response.message);
          }
      }, 'json')
      .fail(function(){
          alert("¡Ha ocurrido un error!");
      });

      return false;
    });

    $( '#form-contract-documents-add select[name="option_type_id"]' ).change(function(e){
    
      value = $(this).val();

      if (value == 1) {
        $( '#form-contract-documents-add .container-attach' ).show();
      }

      if (value == 2 || value == 3) {
        $( '#form-contract-documents-add .container-attach' ).hide();
      }

    });

    $( '#form-contract-documents-edit select[name="option_type_id"]' ).change(function(e){
    
      value = $(this).val();

      if (value == 1) {
        $( '#form-contract-documents-edit .container-attach' ).show();
      }

      if (value == 2 || value == 3) {
        $( '#form-contract-documents-edit .container-attach' ).hide();
      }
    });
    
  });
</script>
