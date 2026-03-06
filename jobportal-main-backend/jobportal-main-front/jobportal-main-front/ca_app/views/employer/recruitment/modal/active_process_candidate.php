<?php 
  $stage_items = get_RS_stages();
?>

<style type="text/css">
  .table-active-process th {
    background:  #555;
    color: #fff;
    padding: 5px 4px;
  }

  .table-active-process td {
    padding: 5px;
  }

  .table-active-process tr:nth-child(even) {
    background-color: #eee;
  }

  .table-active-process tr:nth-child(odd) {
    background-color: #fff;
  }
</style>
<div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Otros procesos abiertos actualmente</h4>
    </div>
    <div class="modal-body">
    	<div>
        <table class="table-active-process" width="100%;">
          <tr>
            <th>RyS ID</th>
            <th>Solicitud Cod</th>
            <th>Proceso</th>
            <th>Etapa actual</th>
            <th style="text-align: center;">Fecha de ingreso</th>
          </tr>
        <?php foreach ($candidate_process as $row_process): ?>
          <tr>
            <td>
              <?php echo $row_process->process_id; ?>
            </td>
            <td>
              <?php echo $row_process->request_ID ? $row_process->request_ID : ''; ?>
            </td>
            <td>
              <a href="<?php echo site_url('employer/recruitment_processes/' . $row_process->job_ID); ?>" target="_blank">
                <?php echo $row_process->job_title; ?>
              </a>
            </td>
            <td>
              <?php echo $stage_items[$row_process->stage]; ?>
            </td>
            <td style="text-align: center;">
              <?php echo $row_process->creation_date == null || '0000-00-00' ? '--' : $row_process->creation_date; ?>
            </td>
          </tr>
        <?php endforeach; ?>
        </table>
      </div>
    </div>
  </div>
</div>