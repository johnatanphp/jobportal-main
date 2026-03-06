<style>
    .checkbox-wrapper-s1 .round {
    position: relative;
  }

  .checkbox-wrapper-s1 .round label {
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 50%;
    cursor: pointer;
    height: 24px;
    width: 24px;
    display: block;
  }

 .checkbox-wrapper-s1 .round label:after {
    border: 2px solid #e0e0e0;
    border-top: none;
    border-right: none;
    content: "";
    height: 6px;
    left: 6px;
    opacity: 0;
    position: absolute;
    top: 9px;
    transform: rotate(-50deg);
    width: 12px;
  }

 .checkbox-wrapper-s1 .round input[type="checkbox"] {
    visibility: hidden;
    display: none;
    opacity: 0;
  }

 .checkbox-wrapper-s1 .round input[type="checkbox"]:checked + label {
    background-color: #005da4;
    border-color: #005da4;
  }

 .checkbox-wrapper-s1 .round input[type="checkbox"]:checked + label:after {
    opacity: 1;
  }

  .checkbox-wrapper-s1 .round input[type="checkbox"]:disabled + label {
    opacity: 0.5;
    cursor: no-drop;
  }
</style>
<div id="modal-recruitment-stages-config" class="modal" role="dialog">
  <div class="modal-dialog" style="max-width: 400px;margin: 0 auto;margin-top: 5%;">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Etapas del proceso</h4>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(function(){
    $( '#btn-rys-stages-config' ).click(function(){
      const url = "<?php echo site_url('employer/recruitment/process_stages/index'); ?>";

      $( '#modal-recruitment-stages-config .modal-content' ).html('<div class="modal-header">Cargando...</div>');
      $( '#modal-recruitment-stages-config' ).modal('show');

      const data = {
        'job_id': $(this).data('job-id')
      }

      $.get(url, data, function(response) {
        $( '#modal-recruitment-stages-config .modal-content' ).html(response);
      });
    });
  });
</script>