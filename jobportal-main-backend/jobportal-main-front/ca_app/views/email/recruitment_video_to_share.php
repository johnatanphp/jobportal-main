
<style type="text/css">
	.body-content {
		background:#f4f4f4;
		border-radius: 8px;
		border:1px solid #e9e9e9;
		padding: 15px;
		text-align: left;
	}

	.body-content span {
		padding: 5px 0px;
		display: block;
		line-height: 1.5;
	}

	#tbl-seekers a {
		padding: 8px 10px;
		text-align: center;
		color:  #fff;
		background: #005da4;
		border-radius: 5px;
		text-decoration: none;
		font-size: 13px;
	}

	#tbl-seekers tr td {
		padding: 8px 2px;
		border-top: 1px solid #cccccc;
	}
</style>
		
<p>El reclutador del Empleo <b><?php echo $job->job_title;?></b> ha compartido un listado de entrevistas de postulantes.
<br />

<ul>
    <li><b>Empresa:</b> <?php echo $employer->company_name; ?></li>
    <li><b>Puesto:</b> <?php echo $job->job_title; ?></li>
</ul>

<br />
<table id="tbl-seekers" width="600">
	<tr>
		<th align="left">Documento</th>
		<th align="left">Nombre</th>
		<th></th>
	</tr>

	<?php foreach($seeker_videos as $seeker_video): ?>
		<tr>
			<td><?php echo document_type_abbr($seeker_video->document_type) . ' ' . $seeker_video->document_number; ?></td>
			<td><?php echo $seeker_video->first_name . ' ' . $seeker_video->last_name; ?></td>
			<td>
				<?php 
					$share_url = site_url('general/jobseeker/record_video/show/' . $seeker_video->share_token);
				?>
				<a href="<?php echo $share_url; ?>">
					Ver entrevista
				</a>
			</td>
		</tr>
	<?php endforeach; ?>

</table>
	
</p>
<div class="separator"></div>