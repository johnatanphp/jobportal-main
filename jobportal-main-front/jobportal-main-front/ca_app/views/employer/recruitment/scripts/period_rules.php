<script>
    $(function(){

        $( "#period-rules-add" ).click(function(){
            var index = $( "#tbl-period-rules" ).generateSequence() * -1;

            $( "#tbl-period-rules tbody" ).append(`
                <tr>
                    <td>
                       <input type="number" name="period_rules[${index}][start]" class="form-control" value="" step="0.01" required/>
                    </td>
                    <td>
                        <input type="number" name="period_rules[${index}][end]" class="form-control" value="" step="0.01"/>
                    </td>
                    <td>
                        <input type="color" name="period_rules[${index}][color]"  value="" required/>
                    </td>
                    <td>
                        <button class="btn-remove-item" onclick="$(this).closest('tr').remove();">
                            <i class="glyphicon glyphicon-remove"></i>
                        </button>
                    </td>
                </tr>
            `);
        });

        $( "#form-period-rules" ).submit(function(e){
            e.preventDefault();
            var btn = $(this).find('input[type="submit"]');
            btn.prop('disabled', true);
            
            $.post($(this).prop('action'), $(this).serialize(), function(response) {
                
                if (response.success === true) {
                    toastr["success"]("¡Las reglas se han guardado con éxito");                 
                    setTimeout(function(){
                        window.location.reload();
                    }, 1300);
                    return;
                }

                if (!response.success) {
                    toastr["error"]("¡Hubo un error al guardar los datos!");  
                    return;
                }
            }, 'json')
            .fail(function(){
                toastr["error"]("¡Ha ocurrido un error!");  
            }).always(function(){
                btn.prop('disabled', false);
            });

            return false;
        });

        function loadEmployeeOverallExperiences(docNumber) {
            var url = "<?php echo site_url('employer/overall_employees/employee_overall_experiences/'); ?>" + docNumber;
            $( "#content-detail-employees" ).html("Buscando...");

            $( "#content-detail-employees" ).load(url, function(response){
                $(this).html(response);
            });
        }

        $(document).on('click', ".alert-work-experiences-overall", function() {
            var docNumber = $(this).data('document-number');
            $( "#modal-employee-overall-experiences" ).modal('show');
            loadEmployeeOverallExperiences(docNumber);
        });
    });
</script>