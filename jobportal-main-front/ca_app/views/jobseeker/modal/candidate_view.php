<style type="text/css">
	@media (min-width:768px) {
		#modal-view-profile .modal-dialog {
			width: 700px;
		}
	}
</style>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-body">
			<div class="modal-header" style="border-bottom: 0px;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true" style="font-size: 25px;">&times;</span>
				</button>
			</div>
			<!--/Header-->
			<?php $this->load->view('jobseeker/common/cv_template_view'); ?>
		 <!-- End Body Modal -->
		</div>
	</div>
</div>
<?php if (isset($applied_id)): ?>
	<script type="text/javascript">
		$( "#application-seen-<?php echo $applied_id; ?>" ).show();
		$( "#btn-interesting-cv-<?php echo $applied_id;; ?>" ).show();
	</script>
<?php endif; ?>

