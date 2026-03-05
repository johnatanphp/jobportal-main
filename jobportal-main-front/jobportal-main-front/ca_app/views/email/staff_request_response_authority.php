<style type="text/css">

	p {
		padding: 4px 0px;
		display: block;
		line-height: 1.5;
		font-size: 15px;
		color: #222;
	}

	h3 {
		color: #222;
		font-size: 17px;
	}

	.wrapper-paragraph {
		text-align: center;
		padding-top: 10px;
	}

	.wrapper-paragraph a {
		padding: 10px 15px;
		text-align: center;
		color:  #fff;
		background: #005da4;
		border-radius: 5px;
		font-weight: bold;
		text-decoration: none;
		font-size: 15px;
	}
	
	.tbl-authority {
		max-width: 550px;
		min-width: 350px;
		margin: 0 auto;
	}

	.tbl-authority th {
		font-weight: normal;
		background: #e0e0e0;
		font-style: italic;
	}

	.tbl-authority td {
		font-weight: bold;
	}

	.tbl-authority td,
	.tbl-authority th {
		padding: 10px;
		font-size: 14px;
		text-align: center;
	}

	.tbl-authority-name {
		border-bottom: 1px solid #333;
	}
	
</style>

<h3>Hola <?php echo $name; ?>,</h3>
<p>Tu solicitud de personal interna <b>"<?php echo $staff_request->job_title; ?>"</b> ha sido autorizada.</p>
<table class="tbl-authority">
	<thead>
		<tr>
			<th>
				Atentamente
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="tbl-authority-name">
				<?php echo $authorization->personal_name; ?>	
			</td>
		</tr>
		<tr>
			<td>
				<?php echo $authorization->type_authority; ?>	
			</td>
		</tr>
	</tbody>
</table>
<p class="wrapper-paragraph">
	<a href="<?php echo $url_link; ?>">Ver solicitud</a>
</p>
<div class="separator"></div>