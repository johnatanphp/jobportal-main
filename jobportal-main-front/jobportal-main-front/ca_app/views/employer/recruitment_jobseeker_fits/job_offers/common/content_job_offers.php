<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Notificar Ofertas</h4>
</div>
<div class="modal-body">
    <table id="tbl-job-offers" class="table table-striped" width="100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Empleo</th>
                <th>Fecha publicación</th>
                <th>Publicado por</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($jobs as $job_row): ?>
                <tr>
                    <td><?php e($job_row->ID); ?></td>
                    <td>
                        <a href="<?php echo site_url('jobs/' . $job_row->job_slug); ?>" 
                        target="_blank">
                            <?php echo ellipsize(humanize($job_row->job_title), 40, 1); ?>
                        </a>
                    </td>
                    <td>
                        <?php e($job_row->dated); ?>
                    </td>
                    <td>
                        <?php e($job_row->employer_name); ?>
                    </td>
                    <td>
                        <?php echo form_open('employer/recruitment_jobseeker_fits/job_offers/notify', ['class' => 'form-send-job-offers']); ?>
                            <input type="hidden" name="job_id" value="<?php echo $job_row->ID; ?>">
                            <?php foreach ($seeker_ids as $seeker_id): ?>
                                <input type="hidden" name="seeker_ids[]" value="<?php echo $seeker_id; ?>">
                            <?php endforeach; ?> 
                            <button type="submit" 
                                    class="btn btn-xs btn-primary send-job-offers">
                                    Enviar Oferta
                            </button>
                        <?php echo form_close(); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>        
    </table>   
</div>

<script>
    $(function(){

        $( '#tbl-job-offers' ).DataTable({
            "language": {
                "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
            },
            "bLengthChange": false,
            columnDefs:[{
                targets:[0, 1, 2, 3, 4],
                orderable: false
            }]
        });

        $( '.form-send-job-offers' ).submit(function(e){
            e.preventDefault();

            var url = $(this).prop('action');
            var data = $(this).serialize();

            parentTd = $(this).closest('td');
            button = $(this).find('button[type="submit"]');

            $.post(url, data, function(res) {
                if (!res.status) {
                    button.html('Enviar oferta');
                    toastr["error"](res.message);
                    return;
                }

                toastr["success"](res.message);
                parentTd.html(`<span><i class="glyphicon glyphicon-ok"></i><i></i> Enviado</span>`);

            }, 'json')
            .fail(function(){
                toastr["error"]('Error en el envio de la operación');
                button.html('Enviar oferta');
            });

            return false;
        });
    });


</script>
