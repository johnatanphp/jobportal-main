<script type="text/javascript">
  $(function(){
    $( '.manage-job-charges' ).click(function(){
      $( '#modal-job-charges' ).modal('show');
      $( '#modal-job-charges-add input[name="country_id"]' ).val($(this).data('country-id'));
      searchJobCharges($(this).data('country-id'));
    });

    function searchJobCharges(countryId) 
    {
      var dtSearchCandidate = $( '#tbl-manage-job-charges' ).DataTable({
        "language": {
            "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
        },
        "destroy": true,
        "bAutoWidth": false,
        "deferRender": true,
        "iDisplayLength": 25,
        "bProcessing": true,
        ajax: {
          url: "<?php echo site_url('admin/job_charges/list'); ?>",
          type: 'GET',
          data: {
            country_id: countryId
          }
        },
        columns: [
            {data:'charge_name', 'className': 'style_td text-left'},
            {data:'valorization_score', 'className': 'style_td text-left'},
            {data:'valorization_grade', 'className': 'style_td text-left'},
            {
              data: null, render:function(data) {
                if (data.sts == 'active') {
                  return 'Activo';
                }

                if (data.sts == 'inactive') {
                  return 'Inactivo';
                }     

                return ``;
              }, 'className': 'style_td text-left'
          },          
          {
            data: null, render:function(data) {
              return `
              <button class="btn btn-primary btn-xs js-btn-job-charges-skills"
                        data-id="${data.ID}">
                  Ver Habilidades
                </button>
                <button class="btn btn-success btn-xs js-btn-job-charges-edit"
                        data-id="${data.ID}"
                        data-name="${data.charge_name}"
                        data-val-score="${data.valorization_score}"
                        data-val-grade="${data.valorization_grade}"
                        data-status="${data.sts}">
                  Editar
                </button>
              `;
            }, 'className': 'style_td text-left'
          },
        ]
      });
    }

    $( '#btn-job-charges-add' ).click(function() {
      $( '#modal-job-charges-add' ).modal('show');
    });

    $( '#job-charges-form-add' ).submit(function(e){
      e.preventDefault();

      url = $(this).prop('action');
      data = $(this).serialize();

      $.post(url, data, function(response) {
          var status = response.success;
          if (status) {
            toastr["success"](response.message);
            $( '#tbl-manage-job-charges' ).DataTable().ajax.reload();
            $( '#modal-job-charges-add' ).modal('hide');
            ($( '#job-charges-form-add' )[0]).reset();

          } else {
            toastr["error"](response.message);
          }
      }, 'json')
      .fail(function(){
          alert("¡Ha ocurrido un error!");
      });

      return false;
    });

    $( document ).on('click', '.js-btn-job-charges-edit', function() {
      $( '#modal-job-charges-edit' ).modal('show');
      $( '#modal-job-charges-edit input[name="id"]').val($(this).data('id'));
      $( '#modal-job-charges-edit input[name="name"]').val($(this).data('name'));
      $( '#modal-job-charges-edit input[name="valorization_score"]').val($(this).data('val-score'));
      $( '#modal-job-charges-edit input[name="valorization_grade"]').val($(this).data('val-grade'));
      $( '#modal-job-charges-edit select[name="status"]').val($(this).data('status'));
    });

    $( '#job-charges-form-edit' ).submit(function(e){
      e.preventDefault();

      url = $(this).prop('action');
      data = $(this).serialize();

      $.post(url, data, function(response) {
          var status = response.success;
          if (status) {
            toastr["success"](response.message);
            $( '#tbl-manage-job-charges' ).DataTable().ajax.reload();
            $( '#modal-job-charges-edit' ).modal('hide');
            ($( '#job-charges-form-edit' )[0]).reset();

          } else {
            toastr["error"](response.message);
          }
      }, 'json')
      .fail(function(){
          alert("¡Ha ocurrido un error!");
      });

      return false;
    });

    $( document ).on('click', '.js-btn-job-charges-skills', function() {

      job_charge_id = $(this).data('id');

      url = "<?php echo site_url('admin/job_charges/get_skills'); ?>";
      data = {
        'job_charge_id': job_charge_id
      };

      $.get(url, data, function(response) {
          var status = response.success;

          var skills = response.skills;
          var skills_text = '';

          for (var i = 0; i < skills.length; i++) {
            skills_row = skills[i];

            skills_text+=`
              <span>
                <input type="hidden" name="skills[]" value="${skills_row.skill_name}" >${skills_row.skill_name}
                <button type="button" 
                        data-id="${skills_row.id}"
                        class="remove-skill">
                  <i class="glyphicon glyphicon-remove"></i>
                </button>
              </span>
              `;
            }

          $( '#wrapper-skills' ).html(skills_text);
          
          $( 'input[name="job_charge_id"]', '#modal-job-charges-skills-form ').val(job_charge_id);
          $( '#modal-job-charges-skills-form' ).modal('show');
         
      }, 'json')
      .fail(function(){
          alert("¡Ha ocurrido un error!");
      });
    });

    $( '#form-job-charge-skill-add' ).submit(function(e){
      e.preventDefault();

      url = $(this).prop('action');
      data = $(this).serialize();
      form = this;

      $.post(url, data, function(response) {
          var status = response.success;
          if (status) {
            
            skills_text =`
              <span>
                <input type="hidden" name="skills[]" value="${response.skill_name}" >${response.skill_name}
                <button type="button" 
                        class="remove-skill"
                        data-id="${response.skill_id}">

                  <i class="glyphicon glyphicon-remove"></i>
                </button>
              </span>
              `;

              $( '#wrapper-skills' ).prepend(skills_text);
              form.reset();
          } else {
            toastr["error"](response.message);
          }
      }, 'json')
      .fail(function(){
          alert("¡Ha ocurrido un error!");
      });

      return false;
    });

    $( document ).on('click', '.remove-skill', function(){
      var id = $(this).data('id');
      btn = $(this);
      url = "<?php echo site_url('admin/job_charges/skill_remove'); ?>";
      data = {
        'id': id
      };

      $.post(url, data, function(response) {
          var status = response.success;
          if (status) {
            btn.closest('span').remove();
          } else {
            toastr["error"]('No se pudo quitar la habilidad');
          }
      }, 'json')
      .fail(function(){
          alert("¡Ha ocurrido un error!");
      });
    });
  });
</script>
