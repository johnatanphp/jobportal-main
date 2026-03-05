<script type="text/javascript">
  
  function reloadDataCandidates(stage) {
        const stageId = stage !== undefined  ? stage : $( "#current_stage" ).val();
        
        $( ".modal" ).modal('hide');
        $( "body" ).removeClass('modal-open');
        $( ".modal-backdrop" ).remove();

        loadDataCandidates({
            'process_id': $( "#global_process_id" ).val(),
            'stage': stageId
        });
    }

    function loadDataCandidates(filters)
    {
        var url = app.siteUrl('employer/recruitment_candidates/search_candidates'); 
        var data = filters || window.candidatesFilters;

        if (filters) {
            window.candidatesFilters = filters;
        }

        var url_image_loading = "<?php echo img_loading_url(); ?>";
        //$( ".content-main-load" ).html(`<img src="${url_image_loading}" style="width:24px; height:24px;"/>`);
        //$( ".content-main-load" ).prop('style', "text-align:center;");
        $( '#wrapper-content-candidates-stages' ).addClass('load load-image');
        $.post(url, data, function(response) {
           
            $( "#wrapper-content-candidates-stages" ).html(response.data);
            $( ".content-main-load" ).prop('style', "text-align:left;");
            $( "#current_stage" ).val(data.stage);
            $( 'select[name="fit"]', '#modal-filter-candidates' ).val(data.is_fit);
            $( '#wrapper-content-candidates-stages' ).removeClass('load load-image');
        
        }, 'json')
        .fail(function(){
            toastr["error"]("¡Ha ocurrido un error! \n Cod: " + e.status + " - " + e.statusText + "!")
            $( '#wrapper-content-candidates-stages' ).removeClass('load load-image');
        });
    }

    $(function() {

        function reloadPage() {

            var url = app.siteUrl('employer/recruitment_processes/' + $( "#job_id" ).val() + '/' + $( "#current_stage").val());
            window.location = url;
        }
        
        function moveCandidatesStage(data, finishCallback) {    
            var url = app.siteUrl('employer/recruitment_candidates/move_candidates_stage');

            $.post(url, data, function(response) {
                
                if (response.success == false) {
                    toastr["error"]("¡No se pudieron mover los candidatos!");
                    return;
                }

                var data = response.data;

                if (data.errors) {
                    toastr["error"]("Algunos candidatos no han sido movidos: <br />" + data.errors);
                }

                if (data.count_success > 0) {
                    toastr["success"]("¡Se han movido " + data.count_success + " candidato(s)!");
                    finishCallback();
                }

            }, 'json')
            .fail(function() {
                alert("¡Ha ocurrido un error!");
            });
        }

        // $(document).on("mouseover", ".wrapper-candidate", function() {
        //     $(this).find('.menu-item').show();
        // }).on("mouseout", ".wrapper-candidate", function() {
        //     $(this).find('.menu-item').hide();
        // });

        $(document).on("click", "#btn-add-candidate", function() {
            $( '#tbl-seeker-search-add-results').DataTable().destroy();
            $( '#tbl-seeker-search-add-results tbody').html('');
            $( "#modal-add-candidate" ).modal("show");
        });

        $(document).on("click", "#remove-candidates-selected", function(e) {

            var candidatesSelected = $( "input[name='candidate_ids[]']:checked" ).length;

            if (!candidatesSelected) {
                return;
            }
            
            var confirm = window.confirm("¿Está seguro de remover los candidatos seleccionados?");

            if (!confirm) {
                return;
            }

            var data = $( "#wrapper-candidates :checked" ).serialize() + "&process_id=" + $( "#global_process_id" ).val();
            
            var url = app.siteUrl('employer/recruitment_candidates/remove_candidates_stage');
            $.post(url, data, function(response) {
                var status = response.success;
                if (status) {                    
                    $( "#wrapper-candidates :checked" ).each(function(i, e){
                        $(e).closest('.col-md-6').remove();
                    });

                    toastr["success"]("¡Los candidatos han sido eliminados!");
                } else {
                    toastr["error"]("¡Ha ocurrido un error!");
                }
            }, 'json')
            .fail(function(){
                toastr["success"]("¡Ha ocurrido un error!");
            });            
        });

        $(document).on("click", ".remove-candidate", function(e) {
            var candidateId = $(this).closest(".wrapper-candidate").data("candidate-id");
            
            if (!window.confirm("¿Está seguro de remover el candidato?")) {
                return;
            }

            var data = "candidate_ids[]=" + candidateId + "&job_id=" + $( "#job_id" ).val();
            removeCandidatesStage(data);
        });

        $(document).on("click", ".move-candidate", function(e) {
            var candidateId = $(this).closest(".wrapper-candidate").data("candidate-id");

            if (window.confirm("¿Está seguro de mover el candidato de etapa?")) {
                var stage = $(this).data('stage');
                var data = "candidate_ids[]=" + candidateId + 
                           "&job_id=" + $( "#job_id" ).val() +
                           "&process_id=" + $( "#global_process_id" ).val() +
                           "&stage=" + stage;
                moveCandidatesStage(data, function(){
                    reloadDataCandidates();
                });
            }
        });

        $(document).on("click", ".follow-up-candidate", function(){
            var candidateId = $( "#modal-detail-candidate-discarded" ).data('candidate-id');
            var containerCandidate = $(".wrapper-candidate[data-candidate-id='" + candidateId + "']");
            var process_id = $( "#global_process_id" ).val();

            var data = "jobseeker_id=" + candidateId + 
                       "&process_id=" + process_id;

            btnFollowUp = $(this);
            btnFollowUp.prop('disabled', true);
            var url = app.siteUrl('employer/recruitment_candidates/follow_up_candidate');
            $.post(url, data, function(response) {
                var status = response.success;
                if (status) {
                    toastr["success"]("¡El candidato está en seguimiento de nuevo!");
                    $( '.modal-open-detail-candidate-discarded', containerCandidate).remove();
                    $( "#modal-detail-candidate-discarded" ).modal('hide');
                    reloadDataCandidates();
                } else {
                    toastr["error"]("¡Ha ocurrido un error!");
                }
            }, 'json')
            .fail(function(){
                toastr["error"]("¡Ha ocurrido un error!");
            }).always(function(){
                btnFollowUp.prop('disabled', false);
            });
        });

        $(document).on("click", "#btn-send-terna-shortlist", function() {
            $( '#modal-short-list-send-by-email' ).modal('show');
            $( 'input[name="process_id"]', '#modal-short-list-send-by-email' ).val($( "#global_process_id" ).val());
        });

        $(document).on("click", ".modal-open-detail-candidate-discarded", function(){
            var note = $(this).data('note-discarded');
            var candidateId = $(this).closest(".wrapper-candidate").data("candidate-id");

            $( "#modal-detail-candidate-discarded" ).data('candidate-id', candidateId);

            $( "#detail-candidate-discarded" ).html(note != '' ? note : 'Ningún detalle');
            $( "#modal-detail-candidate-discarded" ).modal('show');
        });

        $(document).on("click", ".modal-open-active-process-candidate", function() {
            var candidateId = $(this).closest(".wrapper-candidate").data("candidate-id");
            
            var url = app.siteUrl('employer/recruitment_candidates/modal_active_process_candidate/' + candidateId + "/" + $( "#global_process_id" ).val());
            $( "#modal-active-process-candidate" ).load(url, function(response){
                $(this).html(response).modal('show');
            });
        });

        $(document).on('change', '#check-all', function() {
            $( ".check-candidate" ).prop('checked', $(this).is(':checked'));
        });

        $(document).on('change', '.check-candidate', function() {
            var checkLength = $( ".check-candidate" ).length;
            var checkIsCheckedLength = $( ".check-candidate" ).filter(':checked').length;

            $( "#check-all" ).prop('checked', false);
        
            if (checkIsCheckedLength == checkLength) {  
                $( "#check-all" ).prop('checked', true);
            }
        });
 
        $(document).on('click', '.js-load-data-prev-next', function(e){
            e.preventDefault();

            const stage = $(this).data('stage');

            loadDataCandidates({
                'process_id': $( "#global_process_id" ).val(),
                'stage': stage
            });
        });

        $(document).on('click', '.js-load-data', function(e){
            e.preventDefault();

            const stage = $(this).data('stage');

            $( '#tab-steps li' ).removeClass('active');
            $(this).closest('li').addClass('active');

            loadDataCandidates({
                'process_id': $( "#global_process_id" ).val(),
                'stage': stage
            });
        });

        $(document).on('click', '.remove-hiring', function(e){
            e.preventDefault();

            var candidateId = $(this).closest(".wrapper-candidate").data("candidate-id");
            var processId = $( "#global_process_id" ).val();

            var data = {
                process_id: processId,
                seeker_id: candidateId
            };
            
            var url = app.siteUrl("employer/recruitment_candidates/remove_hiring");

            $.post(url, data, function(response) {
                
                if (response.success === true) {
                    toastr["success"](response.message);                 
                    reloadDataCandidates();
                    return;
                }
    
                toastr["error"](response.message);
            }, 'json')
            .fail(function(){
                toastr["error"]("¡Ha ocurrido un error!");
            }).always(function(){});

            return false;
        });
        
        $(document).on('click', '.mark-reentry', function(e){
            e.preventDefault();

            var candidateId = $(this).closest(".wrapper-candidate").data("candidate-id");
            var jobId = $( "#job_id" ).val() 
            var processId = $( "#global_process_id" ).val();

            var data = {
                job_id: jobId,
                process_id: processId,
                seeker_id: candidateId
            };
            
            var url = app.siteUrl("employer/recruitment_candidates/mark_reentry");

            $.post(url, data, function(response) {
                
                if (response.success === true) {
                    toastr["success"](response.message);                 
                    reloadDataCandidates();
                    return;
                }
    
                toastr["error"](response.message);
            }, 'json')
            .fail(function(){
                toastr["error"]("¡Ha ocurrido un error!");
            }).always(function(){});

            return false;
        });

        $(document).on("click", ".open-schedule-exam", function(){ 	
        	
			const stage = $( "#current_stage" ).val();
			const jobId = $( "#job_id" ).val();
			const documentId = $(this).data('document-id');
			const path = 'employer/scheduled_exams/schedule/' + jobId + '/' + documentId + '/' + stage;
			const url = app.siteUrl(path);

			$.get(url, null, function(response){
                $( "#modal-schedule-exam" ).html(response).modal('show');
            });
		});

        $(document).on('submit', '#recruitment-candidate-stage-search', function(e){
            e.preventDefault();

            const searchValue = $( 'input[name="search"]', this).val().trim(); 

            if (searchValue == '') {
                return;
            }

            const filters = {
                'process_id': $( "#global_process_id" ).val(),
                'stage': $( "#current_stage" ).val(),
                'search': searchValue
            };

            loadDataCandidates(filters);

            return false;
        });

        $(document).on('click', '#recruitment-candidate-stage-clear', function(e){
            e.preventDefault();
            loadDataCandidates({
                'process_id': $( "#global_process_id" ).val(),
                'stage': $( "#current_stage" ).val()
            });
            return false;
        });

        $(document).on('click', '#btn-rc-search-active', function(){
            $( '#container-rc-stage-options' ).css('visibility', 'hidden');
            $( '#container-rc-stage-options' ).css('width', '40%');
            $( '#container-rc-stage-search' ).css('visibility', 'visible');
            $( '#container-rc-stage-search' ).css('width', '60%');
            $( '#wrapper-container-rc-stage' ).css('flex-direction', 'row');
            $( 'input[name="search"]', '#recruitment-candidate-stage-search' ).focus();
        });

        $(document).on('click', '#btn-rc-search-return', function(){
            $( '#container-rc-stage-search' ).css('visibility', 'hidden');
            $( '#container-rc-stage-options' ).css('width', '100%');
            $( '#container-rc-stage-search' ).css('width', '0%');
            $( '#container-rc-stage-options' ).css('visibility', 'visible');
            $( '#wrapper-container-rc-stage' ).css('flex-direction', 'row-reverse');
        });

        $( '#btn-rc-search-return' ).click();

        loadDataCandidates({
            'process_id': $( "#global_process_id" ).val(),
            'stage': $( "#current_stage" ).val()
        });
    });
</script>