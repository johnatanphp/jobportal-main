<div class="formwraper">
  <table class="table-style-1" style="width: 100%;">
    <thead>
      <tr>
        <th style="border-top-left-radius: 12px;">
          Clientes
        </th>
        <th style="border-top-right-radius: 12px;"></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($results as $row): ?>
      <tr>
        <td>
          <?php e($row->client_name); ?>
        </td>
        <td align="right">
          <a href="<?php echo site_url('/employer/recruitment_tray/process_candidates_list/index/' . $row->client_code); ?>" style="display:none;" class="link-botton">
            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="15" viewBox="0 0 10 15" fill="none">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M2.34575 0.5L9.20872 7.52082H5.68597L0.5 2.34289L2.34575 0.5ZM2.34743 14.5417L0.501672 12.6988L5.68764 7.52083H9.21039L2.34743 14.5417Z" fill="#333333"/>
            </svg>
          </a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  
  <?php if (count($results) == 0): ?>
  <div align="center" class="text-red" style="padding: 20px;">
      <h4>Sin resultados</h4>
  </div>              
  <?php endif; ?>
</div>

<div class="paginationWrap pag-wrap-v3">
  <?php echo ($results) ? $links : ''; ?>        
</div>