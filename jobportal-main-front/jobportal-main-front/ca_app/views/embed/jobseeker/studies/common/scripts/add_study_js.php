<script type="text/javascript">
$(function(){
    
    function getCareers(select, params) {
        const url = "<?php echo site_url('general/jobseeker/seeker_academics/get_careers'); ?>";
        $(select).html('<option value="">Cargando...</option>').prop('disabled', true);

        $.get(url, params, function(res) {
    
            const results =  res.data ? res.data : [];
        
            $.each(results, function(i, row) {
                $(select).append(`<option value="${row.code}" >${row.name}</option>`);
            });      
        }, 'json')
        .fail(function() {
            console.error('¡Ha ocurrido un error al tratar de listar las carreras!');
        }).always(function() {
            $(select).find("option:eq(0)").text("Seleccione");
            $(select).prop('disabled', false);
        }); 
    }

    function getInstitutions(select, params) {
        const url = "<?php echo site_url('general/jobseeker/seeker_academics/get_institutions'); ?>";
        $(select).html('<option value="">Cargando...</option>').prop('disabled', true);

        $.get(url, params, function(res) {
    
            const results =  res.data ? res.data : [];
        
            $.each(results, function(i, row) {
                $(select).append(`<option value="${row.code}" >${row.name}</option>`);
            });      
        }, 'json')
        .fail(function() {
            console.error('¡Ha ocurrido un error al tratar de listar las instituciones!');
        }).always(function() {
            $(select).find("option:eq(0)").text("Seleccione");
            $(select).prop('disabled', false);
        }); 
    }

    $( '#frm_add_education' ).submit(function(e){
        e.preventDefault();

        const data = $(this).serialize();
        const form = $(this);
        form.find('button[type="submit"]').prop('disabled', true);

        $.ajax({
            type: "POST",
            url: $(this).prop('action'),
            data: data,
            dataType: "json",
        })
        .done(function( res ) {
            if (res.success) {
                $('#add_education_modal').modal('hide');
                location.reload();
            } else {
                toastr["error"](res.message);
                form.find('button[type="submit"]').prop('disabled', false);
            }
        });

        return false;
    });

    $( "#studying" ).change(function(){

        var isSelected = $(this).is(':checked');

        $( "#month_end_date" ).attr({'disabled': isSelected, 'required': true});
        $( "#year_end_date" ).attr({'disabled': isSelected, 'required': true});

        if (isSelected) {
        $( "#month_end_date" ).closest('div').removeClass( "has-error" ); 
        $( "#month_end_date" ).removeAttr('required');
        $( '.month_end_date_err').remove();
        $( "#year_end_date" ).closest('div').removeClass( "has-error" ); 
        $( '.year_end_date_err').remove();
        $( "#year_end_date" ).removeAttr('required');
        }
    });

    $(document).on('change', '#frm_add_education select[name="institution_educ_type"]', function(e){
        const select = $( '#frm_add_education select[name="institution"]' );
        
        getInstitutions(select, {
            'institution_educ_type': $( '#frm_add_education select[name="institution_educ_type"]').val(),
            'institution_type': $( '#frm_add_education select[name="institution_type"]').val(),
        });
    });

    $(document).on('change', '#frm_add_education select[name="institution_type"]', function(e){
        const select = $( '#frm_add_education select[name="institution"]' );
        
        getInstitutions(select, {
            'institution_educ_type': $( '#frm_add_education select[name="institution_educ_type"]').val(),
            'institution_type': $( '#frm_add_education select[name="institution_type"]').val(),
        });
    });

    $(document).on('change', '#frm_add_education select[name="institution"]', function(e){
        const select = $( '#frm_add_education select[name="career"]' );
        
        getCareers(select, {
            'institution': $( '#frm_add_education select[name="institution"]').val(),
        });
    });

    validate_range_dates('#year_start_date', '#month_start_date', '#year_end_date', '#month_end_date', '#studying');    
    $( "#studying" ).change();
    
});
</script>