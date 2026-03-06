<?php
	$gantt_start_date = $this->Staff_request_gantt_activity->get_start_date_gantt($gantt_row->ID);
	$gantt_end_date = $this->Staff_request_gantt_activity->get_end_date_gantt($gantt_row->ID);
	$gantt_activities = $this->Staff_request_gantt_activity->get_gantt_activities($gantt_row->ID);
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Gantt de actividades</title>
		<style type="text/css">
			.content-main-gantt {
				font-family: arial, 'sanf serif';
				padding-bottom: 15px;
				overflow-y: hidden;
				overflow-x: auto;
			}

			.bg-header {
				background: #14476e;
				color: #fff;
			}

			.title-header {
				border: 1px solid #000;
				text-transform: uppercase;
			}

			.tbl-content-gantt tr td.selected {
				background: #1d8be0;
			}

			.tbl-header-gantt tr td {
				padding: 6px;
				font-size: 16px;
				font-weight: bold;
			}
			
			.tbl-content-gantt tr th,
			.tbl-content-gantt tr td {
				padding: 4px 6px;
			}
			
			.tbl-content-gantt tr th {
				font-size: 13px;
				text-align: center;
			}

			.tbl-content-gantt tr td {
				font-weight: bold;
				font-size: 12px
			}
		</style>
	</head>
	<body>
		<div class="content-main-gantt">
			<table class="tbl-header-gantt"  width="100%">
				<tr>
					<td>
						<img src="<?php echo base_url('public/images/overall_blue.png'); ?>">
					</td>
				</tr>
				<tr>
					<td class="bg-header title-header">GANTT DE <?php e($gantt_row->gantt_type_name); ?></td>
				</tr>
			</table>
			<br />
			<br />
			<table class="tbl-content-gantt" border="1" width="100%">
				<thead>
					<tr>
						<th rowspan="2" class="bg-header">N°</th>
						<th rowspan="2" class="bg-header" style="min-width: 220px;">Actividades</th>
						<th rowspan="2" class="bg-header">Fecha de inicio</th>
						<th rowspan="2" class="bg-header">Fecha final</th>
						<?php echo sr_gantt_create_header_months($gantt_start_date, $gantt_end_date); ?>
					</tr>
					<tr>
						<?php echo sr_gantt_create_header_days($gantt_start_date, $gantt_end_date); ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($gantt_activities as $index => $row_activity):
					?>
						<tr>
							<td align="center"><?php echo ($index + 1 ); ?></td>
							<?php	if($row_activity->activity_name == 'OTROS') : ?>
							<td style="width: 200px;"><?php echo $row_activity->other_activity;?></td>
							<?php else: ?>
							<td style="width: 200px;"><?php echo $row_activity->activity_name;?></td>
							<?php endif; ?>
							<td><?php echo _date_locale_format(strtotime($row_activity->start_date), 'dd/MM/y'); ?></td>
							<td><?php echo _date_locale_format(strtotime($row_activity->end_date), 'dd/MM/y'); ?></td>
							<?php echo sr_gantt_create_content_gantt_activities($gantt_row, $gantt_start_date, $gantt_end_date, $row_activity); ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</body>
</html>