<div class="modal-body" style="padding: 0;">
	<style type="text/css">
		ul {
			list-style: none;
		}

		.content-detail {
			padding: 15px 0px;
		}

		.menu-rs-option-more-detail {
			cursor: pointer;
		}

		.candidate-section-content {
			--border-bottom: 1px solid #ccc;
			margin-bottom: 10px;
		}

		.candidate-section-content-title {
			font-family: 'Open Sans', 'sans-serif', Arial, Helvetica, sans-serif;
			padding: 8px 18px;
			font-size: 18px;
			color: #333;
			display: block;
			font-weight: bold; 
			text-align: center;
		}

		.wrapper-candidate-detail-table td {
			padding: 5px;
		}

		.btn-show-options {
			background: #fff;
			border: 1px solid #ccc;
			padding: 4px 5px;
		}

    	.attach-file-item {
    		border: 1px solid #888;
    		padding: 15px 5px;
    		margin-top: 5px;
    		position: relative;
    		text-align: center;
    		font-size: 16px;
    		background: #eee;
    	}
    
    	.document-ok {
    		color: green;
    	}
    
    	.list-detail-cv li {
    		padding: 3px;
    		border-bottom: 1px solid #ccc;
    	}
    
    	.userinfoWrp .username {
    		display: none;
    	}
    
    	.userinfoWrp .uploadPhoto {
    		display: none;
    	}
    
    	.userinfoWrp {
    		border: 1px solid #ccc;
    		padding: 10px 30px;
    	}
    
    	.userinfoWrp .col-md-8 {
    		width: 100%;
    		min-width: 100%;
    		max-width: 100%;
    	}
    
    	.userinfoWrp .usercel {
    		border-bottom: 1px solid #ccc;
    	}
    
    	.menu-candidate-profile {
    		padding-left: 20px;
    	}
    	
    	.menu-candidate-profile .dropdown .dropdown-toggle {
    		padding: 10px;
    		background: transparent;
    	}
    
    	.menu-candidate-profile > li.active > a,
    	.menu-candidate-profile > li.active > a:focus,
    	.menu-candidate-profile > li.active > a:hover {
    		--font-weight: bold;
    		color: #337ab7;
    	}
    
    	.menu-candidate-profile > li > a {
    		color: #666666;
    	}
    	
    	.candidate-detail-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 0 !important;
    	}
	</style>

	<div style="background: #f4f4f4;">
		<div class="info-options wrapper-candidate" data-candidate-id="<?php echo $candidate_process->ID; ?>" style="margin: 0;height: auto;border: none;background: #f4f4f4;padding: 15px;">
			<div style="padding: 10px 7px;border-radius: 5px;">
				<div class="row">
					<div class="col-xs-2">
						<a href="#" 
						   data-content-id="rs-detail-cv-candidate">
							<img width="100" 
							     style="border: 1px solid #dddddd;border-radius: 10px;"
								 src="<?php echo img_pic_candidate($row->photo); ?>">
						</a>
					</div>

					<div class="col-xs-10">
						<ul class="list-detail-cv" style="background: #ffffff;padding: 15px;border-radius: 10px;border: 1px solid #dddddd;">
							<li class="candidate-detail-title">
								<h4 style="font-weight: bold;padding: 5px 0;">
									<a href="#" 
									   style="color: #333;"
									   data-content-id="rs-detail-cv-candidate">
										<?php echo mb_strtoupper(trim($row->first_name . ' ' . $row->last_name)); ?>
									</a>
								</h4>
								<span class="badge">ID <?php echo $candidate_id; ?></span>
							</li>
							<li>
								<b>Proceso ID:</b> <?php echo $candidate_process->process_id; ?>
							</li>
							<?php if ($job): ?>
								<li>
									<b>Empleo:</b> <?php echo $job->job_title; ?>
								</li>
							<?php endif; ?>
										
							<li>
								<b>Etapa actual:</b> <?php echo $candidate_process->stage_name; ?>								
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<ul class="menu-candidate-profile nav nav-tabs">
			<li class="active">
				<a href="#" 
				class="menu-option-candidate-item"
				data-content-id="rs-detail-cv-candidate">Perfil</a>
			</li>
			<li class="parent-dropdown-menu">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">Doc. de reclutamiento
				<span class="caret"></span></a>
				<ul class="dropdown-menu">
				<?php foreach ($rys_documents as $row_doc): ?>
					<?php if ($row_doc->option_type_id == 1): ?>
						<li>
							<a class="menu-rs-option-more-detail"
								data-url="<?php echo site_url('candidate/load_rs_documents/' . $job_id . '/' . $candidate_id . '/' . $row_doc->key); ?>">
								<?php e($row_doc->name); ?>
								<?php if (isset($rs_document_counter[$row_doc->key]) && $rs_document_counter[$row_doc->key] > 0): ?>
									<i class="glyphicon glyphicon-ok document-ok"></i>
								<?php endif; ?>
							</a>
						</li>
					<?php endif; ?>

					<?php if ($row_doc->option_type_id == 2 && $row_doc->id == 3): ?>
						<li>
							<a class="menu-rs-option-more-detail"
								data-url="<?php echo site_url('candidate/load_document_screening/' . $job_id . '/' . $candidate_id); ?>">
								<?php e($row_doc->name); ?>
								<?php if (isset($rs_document_counter[$row_doc->key]) && $rs_document_counter[$row_doc->key] > 0): ?>
									<i class="glyphicon glyphicon-ok document-ok"></i>
								<?php endif; ?>
							</a>
						</li>
					<?php endif; ?>

					<?php if ($row_doc->option_type_id == 2 && $row_doc->id == 13): ?>
						<li>
							<a class="menu-rs-option-more-detail"
							data-url="<?php echo site_url('candidate/load_rs_documents/' . $job_id . '/' . $candidate_id . '/' . $row_doc->key); ?>">
								<?php e($row_doc->name); ?>
								<?php if (isset($rs_document_counter[$row_doc->key]) && $rs_document_counter[$row_doc->key] > 0): ?>
									<i class="glyphicon glyphicon-ok document-ok"></i>
								<?php endif; ?>
							</a>
						</li>
					<?php endif; ?>

					<?php if ($row_doc->option_type_id == 2 && in_array($row_doc->id, [1, 10])): ?>
						<li>
							<a class="menu-rs-option-more-detail"
							data-url="<?php echo site_url('candidate/load_document_exam_request_results/' . $job_id . '/' . $candidate_id . '/' . $row_doc->key); ?>">
								<?php e($row_doc->name); ?>
								<?php if (isset($rs_document_counter[$row_doc->key]) && $rs_document_counter[$row_doc->key] > 0): ?>
									<i class="glyphicon glyphicon-ok document-ok"></i>
								<?php endif; ?>
							</a>
						</li>
					<?php endif; ?>

				<?php endforeach; ?>
				</ul>
			</li>
			<?php if ($process_company->system_internal): ?>
			    <?php if (isset($config['contract_documents_show']) && $config['contract_documents_show'] == true): ?>
    			    <li>
    					<a href="#" 
    					class="menu-rs-option-more-detail"
    					data-url="<?php echo site_url('candidate/load_detail_requested_documents/' . $recruitment_process->id . '/' . $candidate_id); ?>">Doc. de contratación</a>
    				</li>
				<?php endif; ?>
				
				<li class="parent-dropdown-menu">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">Más <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li>
							<a class="menu-rs-option-more-detail"
								data-url="<?php echo site_url('candidate/video_interview/' . $job_id . '/' . $candidate_id); ?>">
								Video entrevista
							</a>
						</li>
						<li>
							<a class="menu-rs-option-more-detail"
								data-url="<?php echo site_url('candidate/form_candidate_list/' . $job_id . '/' . $candidate_id); ?>">
								Encuestas
							</a>
						</li>
						<li>
							<a class="menu-rs-option-more-detail"
							data-url="<?php echo base_url('candidate/search_experience_overall/' . $candidate_id);?>">
								Experiencia en Overall
							</a>
						</li>
						<li>
							<a target="_blank"
								href="<?php echo base_url('candidate/export_pdf_entry_form/' . $job_id . '/' . $candidate_id); ?>">
								Planilla de ingreso del trabajador
							</a>
						</li>
					</ul>
				</li>
			<?php endif; ?>
		</ul>
	</div>

	<!--/Header-->
	<div class="content-main-detail" style="padding: 15px;min-height: 250px;">
		<div id="rs-detail-cv-candidate" class="content-detail">
			<div class="candidate-section-content hide">
				<h4 class="candidate-section-content-title hide">
					CV Candidato
				</h4>
			</div>
			<?php $this->load->view('jobseeker/common/cv_template_view'); ?>
		</div>
	</div>
</div>

<script type="text/javascript">

	$( ".menu-option-candidate-item, .menu-rs-option-more-detail" ).click(function(){

		li = $(this).closest('li.parent-dropdown-menu');
		if (li.length == 0) {
			li = $(this).closest('li');
		}
		$( '.menu-candidate-profile li' ).removeClass('active');
		li.addClass('active');

		$( ".menu-option-candidate-item.selected" ).removeClass('selected');
		$( ".content-detail" ).hide();
		
		var contentId = $(this).data('content-id');
		$(this).addClass('selected');

		if (!contentId) {
			contentId = 'rs-content-' + Date.now();
			$(this).data('content-id', contentId);		
		}
	
		var contentLoad = $( "#" + contentId); 
		if (contentLoad.length == 0) {
			contentLoad = $( "<div id='" + contentId + "' class='content-detail'/>");
			var url = $(this).data('url');
			contentLoad.html("<span style='display: block;text-align: center;'>Cargando...</span>");
			contentLoad.load(url, function(response) {
				$(this).html(response);
			});

			$( ".content-main-detail" ).append(contentLoad);
		}		

		contentLoad.show();
	}); 
</script>
