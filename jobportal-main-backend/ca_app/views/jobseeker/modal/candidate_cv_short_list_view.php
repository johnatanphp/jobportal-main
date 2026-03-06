<style type="text/css">
	@media (min-width: 800px) {
		#modal-view-profile .modal-dialog {
			width: 800px;
		}
	}
</style>

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
		border-bottom: 1px solid #ccc;
		margin-bottom: 10px;
	}

	.candidate-section-content-title {
		padding: 8px 18px;
		font-size: 13px;
		text-transform: uppercase;
		background: #eee;
		color: #444;
		display: inline-block;
		border: 1px solid #ccc;
		border-bottom: none;
		font-weight: bold; 
	}

	.wrapper-candidate-detail-table td {
		padding: 5px;
	}

	.btn-show-options {
		background: #fff;
		border: 1px solid #ccc;
		padding: 4px 5px;
	}

	.wrapper-candidate-header {
		background: linear-gradient(to right, rgba(234, 238, 241, 1) 10%, rgba(222, 229, 234, 1) 45%, rgba(181, 194, 203, 1) 100%);
		padding: 10px;
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

	.list-detail-cv {
		background: #fefefe;
		padding: 10px 15px;
		border-radius: 3px;
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

	#modal-show-form-detail .formwraper {
		border: 0;
	}

	.tab-documents {
		padding-top:8px;
		background: linear-gradient(to right, rgba(234, 238, 241 ,1) 10%, rgba(222, 229, 234,1) 45%, rgba(181, 194, 203, 1) 100%);
	}

</style>

<div class="modal-body">

	<div>
		<div class="wrapper-candidate-header" data-candidate-id="<?php echo $candidate_process->ID; ?>" style="height: auto;border: none;">
			<div style="padding: 10px 7px;border-radius: 5px;">
				<div class="row">
					<div class="col-xs-2">
						<img width="100" 
							src="<?php echo img_pic_candidate($row->photo); ?>" style="border: 1px solid #cccccc;">
					</div>

					<div class="col-xs-10">
						<ul class="list-detail-cv">
							<li>
								<h4 style="font-weight: bold;padding: 5px 0;">
									<?php echo mb_strtoupper(trim($row->first_name . ' ' . $row->last_name)); ?>
								</h4>
							</li>
							<?php if ($job): ?>
								<li>
									<b>Empleo:</b> <?php echo $job->job_title; ?>
								</li>
							<?php endif; ?>
							<li>
								<b>Etapa actual:</b> <?php echo $stage_items[$candidate_process->stage]; ?>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--/Header-->

	<div>
		<ul class="nav nav-tabs tab-documents">
			<li class="pull-left active"><a href="#aaa" class="menu-option-candidate-item" data-content-id="rs-detail-cv-candidate">Ver CV</a></li>
			<li class="pull-left dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
					Documentos del reclutamiento <span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<?php foreach ($rys_documents as $row_doc): ?>
						<?php 
							$permitted_document_ids = [
								3, //Certificado Screening
								5, //Validación de competencias
								6, //Informe por competencias
								7, //Verificación domiciliaria
								8, //Evaluaciones candidato
								9, //Referencias laborales
								13, //Otros documentos
							]; 
						?>

						<?php 
							if (!in_array($row_doc->id, $permitted_document_ids)) {
								continue;
							}
						?>
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
								data-url="<?php echo site_url('candidate/load_rs_other_documents/' . $job_id . '/' . $candidate_id); ?>">
									<?php e($row_doc->name); ?>
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
			<li class="pull-left dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
				Mas <span class="caret"></span>
				</a>
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
				</ul>
			</li>
		</ul>
	</div>
	<br>
	<div class="content-main-detail">
		<div id="rs-detail-cv-candidate" class="content-detail">
			<?php $this->load->view('jobseeker/common/cv_template_view'); ?>
		</div>
	</div>
</div>

<script type="text/javascript">

	$( ".menu-option-candidate-item, .menu-rs-option-more-detail" ).click(function(){
		
		li = $(this).closest('li.pull-left');

		$( '.tab-documents li' ).removeClass('active');
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
			contentLoad.html("Cargando...");
			contentLoad.load(url, function(response) {
				$(this).html(response);
			});

			$( ".content-main-detail" ).append(contentLoad);
		}		

		contentLoad.show();
	}); 
</script>

<script type="text/javascript">

</script>
