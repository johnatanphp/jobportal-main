<div id="modal-staff-request-show-assigned-employers" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Empleadores Asignados</h4>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Empleador</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
      </div>
    </div>
  </div>

  <script>
  $(function(){

      $( document ).on('click', '.sr-show-assigned-employers', function(e){
          e.preventDefault();
          requestId = $(this).data('request-id');

          $( '#modal-staff-request-show-assigned-employers' ).modal('show');

          $( '#modal-staff-request-show-assigned-employers table' ).DataTable({
              "language": {
                  "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
              },
              "destroy": true,
              "bAutoWidth": false,
              "deferRender": true,
              "iDisplayLength": 25,
              "bProcessing": true,
              paging: false,
              ajax: {
                  url: "<?php echo site_url('employer/staff_requests/show_assigned_employers'); ?>",
                  type: 'GET',
                  data: {
                      request_id: requestId
                  }
              },
              columns: [
                  {data:'employer_first_name', 'className': 'style_td text-left'},
                  {data:'employer_email', 'className': 'style_td text-left'}
              ]
          });
      });
  });
  </script>
</div>

