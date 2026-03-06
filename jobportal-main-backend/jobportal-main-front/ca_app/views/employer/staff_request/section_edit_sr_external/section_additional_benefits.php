<div id="section-additional-benefits">
  <div class="step-title">
    Beneficios adicionales del puesto
  </div>

  <div class="step-inputs">
    <table id="table-additional-benefits" 
           width="100%" 
           class="table table-striped">
      <tbody>
        <?php foreach ($additional_benefits as $row): ?>

          <tr data-row-benefit-name="<?php e($row->benefit_name); ?>">
            <td width="45%">
              <?php e($row->benefit_name); ?>
              <input type="hidden" name="additional_benefits[<?php e($row->ID); ?>][id]">
            </td>
            <td width="15%">
              <input type="checkbox" name="additional_benefits[<?php e($row->ID); ?>][checked]" checked value="true" class="benefit-checked">
            </td>
            <td width="15%">
              <label>Especificar</label>
            </td>
            <td width="25%">
              <input id="benefit-detail-<?php e($row->ID); ?>" type="text" name="additional_benefits[<?php e($row->ID); ?>][detail]" class="form-control benefit-detail" value="<?php e($row->detail); ?>">
            </td>
          </tr>

        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>