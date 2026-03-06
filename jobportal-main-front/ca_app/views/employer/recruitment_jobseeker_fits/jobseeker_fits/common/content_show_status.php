<style>
    .modal-body {
        padding-top: 5px;
    }

    .section-info {
        padding: 5px;
        margin: 5px 0 8px 0;
    }

    .section-title {
        font-size: 15px;
        text-transform: uppercase;
        diplay: block;
        border-bottom: 1px solid #ccc;
        padding: 8px 5px;
    }
</style>

<div class="section-info">
    <h3 class="section-title">En procesos</h3>  
    <div style="padding-top:5px;">
        <?php if (count($rc_process) > 0): ?>
            <table class="table table-striped" width="100%">
                <tr>
                    <th>
                        Rys Id
                    </th>
                    <th>Fecha</th>
                    <th>
                        Empleo
                    </th>
                    <th>
                        Etapa
                    </th>
                </tr>
                <?php foreach ($rc_process as $row): ?>
                    <tr>
                        <td>
                            <a href="<?php echo site_url('employer/recruitment_processes/show_process/' . $row->rys_id . '/' . $row->stage_id); ?>"
                               target="_blank">
                                <?php e($row->rys_id); ?>
                            </a>
                        </td>
                        <td>
                            <?php e($row->stage_datetime); ?>
                        </td>
                        <td>
                            <?php e($row->job_title); ?>
                        </td>
                        <td>
                            <?php e($row->stage); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

        <?php if (count($rc_process) == 0): ?>
            <div>
                No tiene procesos en curso
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="section-info">
    <h3 class="section-title">Lista negra</h3>

    <div style="padding-top:5px;">
        <?php if (!$result_blacklist || $result_blacklist->blacklist == 0): ?>
            <div>
               No
            </div>
        <?php endif; ?>

        <?php if ($result_blacklist && $result_blacklist->blacklist == 1): ?>
            <div>
                Si
                <br>
                <b>Observación:</b>
                <br>
                <?php e($result_blacklist->blacklist_observation ? $result_blacklist->blacklist_observation : 'Sin observación'); ?>
            </div>
        <?php endif; ?>
    </div>
</div>
