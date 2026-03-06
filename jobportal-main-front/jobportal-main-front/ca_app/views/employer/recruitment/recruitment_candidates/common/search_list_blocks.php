<div class="wrapper-stage-header" style="padding-top: 10px;">

    <div class="row">
        <div class="col-xs-6">
            <h5 style="font-size:12px;"><b>VISTA POR BLOQUE</b></h5>
        </div>
        <div class="col-xs-6">
            <button class="btn btn-xs btn-default pull-right btn-show-candidates-view">
                <span class="glyphicon glyphicon-chevron-right"></span>
            </button>
        </div>
    </div>
</div>
<br>
<div style="background:#fff;">
    <table class="table table-striped" width="100%">
        <thead>
            <tr>
                <th>Titulo</th>
                <th>Fecha de creacion</th>
                <th>Fecha de actualizacion</th>
                <th style="text-align:center">Nro de candidatos</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($blocks as $block_index => $block): ?>
                <tr>
                    <td>
                        <a href="#" 
                           class="block-candidates" 
                           data-creation-date="<?php e($block->creation_date); ?>"
                           data-update-date="<?php e($block->update_date ? $block->update_date : '-1'); ?>"
                           data-block="BLOQUE <?php e($block_index + 1); ?>">
                            BLOQUE <?php e($block_index + 1); ?>
                        </a> 
                    </td>
                    <td>
                        <?php e($block->creation_date); ?>
                    </td>
                    <td>
                        <?php e($block->update_date ? $block->update_date : '-'); ?>
                    </td>
                    <td align="center">
                        <?php e($block->total_candidates); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
