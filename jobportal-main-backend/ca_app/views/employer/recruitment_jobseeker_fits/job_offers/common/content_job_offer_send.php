<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Ofertas enviadas</h4>
</div>
<div class="modal-body">

    <table class="table table-striped" width="100%">
        <tr>
            <th>Id</th>
            <th>
                Empleo
            </th>
            <th>
                Enviado por
            </th>
            <th>
                Telefono
            </th>
            <th>
                Fecha de envio
            </th>
            <th>
                Estado
            </th>
        </tr>

        <?php foreach ($job_offers as $offer): ?>
            <tr>
                <td>
                    <?php e($offer->job_id); ?>
                </td>
                <td>
                    <a href="<?php echo site_url('jobs/' . $offer->job_slug); ?>" 
                       target="_blank">
                        <?php echo ellipsize(humanize($offer->job_title), 40, 1); ?>
                    </a>
                </td>
                <td>
                    <?php e($offer->employer_name); ?>
                </td>
                <td>
                    <?php e($offer->mobile); ?>
                </td>
                <td>
                    <?php e($offer->offer_sent_date ? $offer->offer_sent_date : '-'); ?>
                </td>
                <td>
                    <?php e($offer->offer_sent ? 'Enviado' : 'En proceso'); ?>
                </td>
            </tr>
        <?php endforeach; ?>

        <?php if (count($job_offers) == 0): ?>
            <tr>
                <td colspan="6" align="center">
                    Sin resultados
                </td>
            </tr>
        <?php endif; ?>
    </table>
</div>

