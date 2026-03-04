<div id="modal-filter-candidates" class="modal" id="myModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <?php echo form_open('', ['id' => 'form-filter-candidates']); ?>
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Filtro candidatos</h4>
            </div>
            <div class="modal-body">
                <label>Aptitud estado</label>
                <select name="fit" class="form-control">
                    <option value="">Todos</option>
                    <option value="0">Con Riesgo</option>
                    <option value="1">Apto</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        <?php echo form_close(); ?>
      </div>
    </div>

    <script>
      $(function(){
        $( '#btn-modal-filter-candidate' ).click(function(){
          $( '#modal-filter-candidates' ).modal('show');
        });

        $( '#form-filter-candidates' ).submit(function(e){
          e.preventDefault();
          
          filter_fit_value = $('select[name="fit"]', this ).val();
          
          $( '#modal-filter-candidates' ).modal('hide');

          loadDataCandidates({
              'job_id':  $( "#job_id").val(),
              'stage': $( "#current_stage" ).val(),
              'is_fit': filter_fit_value
          });

          return false;
        });
      });
    </script>
  </div>

