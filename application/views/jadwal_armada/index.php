<!DOCTYPE html>
<html>
<head>
	<?= $this->load->view('head'); ?>
</head>
<body class="sidebar-mini wysihtml5-supported <?= $this->config->item('color')?>">
	<div class="wrapper">
		<?= $this->load->view('nav'); ?>
		<?= $this->load->view('menu_groups'); ?>
		<div class="content-wrapper">
			<section class="content-header">
				<h1>Jadwal Armada</h1>
			</section>
			<section class="invoice">
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<div class="modal fade" id="addModal" tabindex="" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-lg">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="Form-add-bu" id="addModalLabel">Form Add Jadwal Armada</h4>
											</div>
											<div class="modal-body">
												<div class="col-lg-7">
													<input type="hidden" id="id_jadwal_armada" name="id_jadwal_armada" value='' />
													<div class="form-group">
														<label>Armada</label>
														<select class="form-control select2" style="width: 100%;" id="kd_armada">
															<option value="0">--Armada--</option>	
														</select>
													</div>
													<div class="form-group">
														<label>Trayek</label>
														<select class="form-control select2" style="width: 100%;" id="kd_trayek">
															<option value="0">--Trayek--</option>	
														</select>
													</div>

													<div class="form-group">
														<label>RIT</label>
														<select class="form-control" style="width: 100%;" id="rit" name="rit">
															<option value="1">1</option>
															<option value="2">2</option>
															<option value="3">3</option>
															<option value="4">4</option>
															<option value="5">5</option>
															<option value="6">6</option>
															<option value="7">7</option>
															<option value="8">8</option>
															<option value="9">9</option>
															<option value="10">10</option>
															<option value="11">11</option>
															<option value="12">12</option>
															<option value="13">13</option>
															<option value="14">14</option>
															<option value="15">15</option>

														</select>
													</div>
												</div>

												<div class="col-lg-5">
													<div class="form-group">
														<label>KM Trayek</label>
														<input type="number" id="km_trayek" name="km_trayek" class="form-control" placeholder="KM Trayek">
													</div>
													<div class="form-group">
														<label>KM Empty</label>
														<input type="number" id="km_empty" name="km_empty" class="form-control" placeholder="KM Empty">
													</div>
													<div class="form-group">
														<label>KM Bandara</label>
														<input type="number" id="km_band" name="km_band" class="form-control" placeholder="KM Bandara">
													</div>
													<div class="form-group">
														<label>KM Rombongan</label>
														<input type="number" id="km_rombongan" name="km_rombongan" class="form-control" placeholder="KM Rombongan">
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
													<button type="button" class="btn btn-primary" id='btnSave'>Save</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="form-group col-lg-4">
										<small><b>Cabang</b></small>
										<select class="form-control select2 " style="width: 100%;" id="id_bu_filter" name="id_bu_filter">
											<?php
											foreach ($combobox_cabang->result() as $rowmenu) {
											?>
											<option value="<?= $rowmenu->id_bu?>"  ><?= $rowmenu->nm_bu?></option>
											<?php
										}
										?>
									</select>
								</div>
								<div class="col-lg-3">
									<small><b>Segment</b></small>
									<select class="form-control select2 " style="width: 100%;" id="id_segment_filter" name="id_segment_filter">
										<?php
										foreach ($combobox_segment->result() as $rowmenu) {
										?>
										<option value="<?= $rowmenu->id_segment?>"  ><?= $rowmenu->nm_segment?></option>
										<?php
									}
									?>
								</select>
							</div>
							<div class="col-lg-2">
								<small><b>Tanggal</b></small>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control pull-right" id="tanggal_filter" name="tanggal_filter" value="<?= date('Y-m-d')?>">
								</div>
							</div>
							<div class="col-lg-3">
								<p style="line-height: 10px">.</p>
								<div>
									<div class="btn-group">
										<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-print"></i> Print <span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a onclick="print_laporan(1)"><i class="fa fa-print"></i> PDF</a></li>
											<li><a onclick="print_laporan(2)"><i class="fa fa-print"></i> Excell</a></li>
										</ul>
									</div>
									<button class="btn btn-primary" onclick='ViewData(0)'>
										<i class='fa fa-plus'></i> Add Jadwal Armada
									</button>
								</div>
							</div>
						</div>
						<br>
						<div class="dataTable_wrapper">
							<table class="table table-striped table-bordered table-hover" id="buTable">
								<thead>
									<tr>
										<th>Options</th>
										<th>#</th>
										<th>Tanggal</th>
										<th>KD Armada</th>
										<th>KD Trayek</th>
										<th>Awal</th>
										<th>Akhir</th>
										<th>Total KM</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
</div>
<?= $this->load->view('basic_js'); ?>
<script type='text/javascript'>
	var buTable = $('#buTable').DataTable({
		"ordering" : false,
		"scrollX": true,
		"processing": true,
		"serverSide": true,
		ajax: 
		{
			url: "<?= base_url()?>jadwal_armada/ax_data_jadwal_armada/",
			type: 'POST',
			data: function ( d ) {
				return $.extend({}, d, { 
					"id_bu": $("#id_bu_filter").val(),
					"id_segment": $("#id_segment_filter").val()
				});
			}
		},
		columns: 
		[
		{
			data: "id_jadwal_armada", render: function(data, type, full, meta){
				var str = '';
				str += '<div class="btn-group">';
				str += '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>';
				str += '<ul class="dropdown-menu">';
				str += '<li><a onclick="ViewData(' + data + ')"><i class="fa fa-pencil"></i> Edit</a></li>';
				str += '<li><a onClick="DeleteData(' + data + ')"><i class="fa fa-trash"></i> Delete</a></li>';
				str += '</ul>';
				str += '</div>';
				return str;
			}
		},

		{ data: "id_jadwal_armada" },
		{ data: "tanggal" },
		{ data: "kd_armada" },
		{ data: "kd_trayek" },
		{ data: "nm_point_awal" },
		{ data: "nm_point_akhir" },
		{ data: "total" }
		]
	});

	$('#btnSave').on('click', function () {
		if($('#kd_armada').val() == '0')
		{
			alertify.alert("Warning", "Pilih Armada.");
		}else if($('#kd_trayek').val() == '0')
		{
			alertify.alert("Warning", "Pilih Trayek.");
		}else if($('#rit').val() == '')
		{
			alertify.alert("Warning", "Pilih RIT.");
		}else if($('#km_trayek').val() == '')
		{
			alertify.alert("Warning", "Isi KM Trayek.");
		}else if($('#km_empty').val() == '')
		{
			alertify.alert("Warning", "Isi KM Empty.");
		}else if($('#km_band').val() == '')
		{
			alertify.alert("Warning", "Isi KM Bandara.");
		}else if($('#km_rombongan').val() == '')
		{
			alertify.alert("Warning", "Isi KM Rombongan.");
		}
		else
		{
			var url = '<?=base_url()?>jadwal_armada/ax_set_data';
			var data = {
				id_jadwal_armada	: $('#id_jadwal_armada').val(),
				kd_armada 			: $('#kd_armada').val(),
				kd_trayek 			: $('#kd_trayek').val(),
				rit 				: $('#rit').val(),
				km_trayek 			: $('#km_trayek').val(),
				km_empty 			: $('#km_empty').val(),
				km_band 			: $('#km_band').val(),
				km_rombongan 		: $('#km_rombongan').val(),
				id_bu 				: $('#id_bu_filter').val(),
				id_segment 			: $('#id_segment_filter').val(),
				tanggal 			: $('#tanggal_filter').val(),
			};

			$.ajax({
				url: url,
				method: 'POST',
				data: data
			}).done(function(data, textStatus, jqXHR) {
				var data = JSON.parse(data);
				if(data['status'] == "success")
				{
					alertify.success("Data Saved.");
					$('#addModal').modal('hide');
					buTable.ajax.reload();
				}
			});
		}
	});

	function ViewData(id_jadwal_armada)
	{
		if(id_jadwal_armada == 0)
		{
			$('#addModalLabel').html('Add Jadwal Armada');
			$('#id_jadwal_armada').val('');
			$('#select2-kd_armada-container').html('--Armada--');
			$('#select2-kd_trayek-container').html('--Trayek--');
			$('#rit').val('1');
			$('#km_trayek').val('0');
			$('#km_empty').val('0');
			$('#km_band').val('0');
			$('#km_rombongan').val('0');
			$('#addModal').modal('show');
		}
		else
		{
			var url = '<?=base_url()?>jadwal_armada/ax_get_data_by_id';
			var data = {
				id_jadwal_armada: id_jadwal_armada
			};

			$.ajax({
				url: url,
				method: 'POST',
				data: data
			}).done(function(data, textStatus, jqXHR) {
				var data = JSON.parse(data);
				$('#addModalLabel').html('Edit Jadwal Armada');
				$('#id_jadwal_armada').val(data['id_jadwal_armada']);
				$('#select2-kd_armada-container').html(data['kd_armada']);
				$('#select2-kd_trayek-container').html(data['kd_trayek']);
				// $('#kd_armada').val(data['kd_armada']).trigger('change');
				$('#rit').val(data['rit']);
				$('#km_trayek').val(data['km_trayek']);
				$('#km_empty').val(data['km_empty']);
				$('#km_band').val(data['km_band']);
				$('#km_rombongan').val(data['km_rombongan']);
				$('#addModal').modal('show');
			});
		}
	}

	function DeleteData(id_jadwal_armada)
	{
		alertify.confirm(
			'Confirmation', 
			'Are you sure you want to delete this data?', 
			function(){
				var url = '<?=base_url()?>jadwal_armada/ax_unset_data';
				var data = {
					id_jadwal_armada: id_jadwal_armada
				};

				$.ajax({
					url: url,
					method: 'POST',
					data: data
				}).done(function(data, textStatus, jqXHR) {
					var data = JSON.parse(data);
					buTable.ajax.reload();
					alertify.error('Data deleted.');
				});
			},
			function(){ }
			);
	}

	$( "#tanggal_filter").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "yy-mm-dd"
	});

	$('#id_bu_filter').select2({
		'placeholder': "Cabang",
		'allowClear': true
	}).on("change", function (e) {
		buTable.ajax.reload();
		armadalist();
		trayeklist();
	});

	$('#id_segment_filter').select2({
		'placeholder': "Cabang",
		'allowClear': true
	}).on("change", function (e) {
		buTable.ajax.reload();
	});

	function armadalist(){
		$.ajax({
			type: "POST", 
			url: "<?= base_url() ?>jadwal_armada/ax_get_armada", 
			data: {id_cabang : $("#id_bu_filter").val()},  
			dataType: "json",
			beforeSend: function(e) {
				if(e && e.overrideMimeType) {
					e.overrideMimeType("application/json;charset=UTF-8");
				}
			},
			success: function(response){ 

				$("#kd_armada").html(response.data_armada).show();
				$('#select2-kd_armada-container-container').html('--Armada--');
				$('#kd_armada').val('0');
			},
			error: function (xhr, ajaxOptions, thrownError) { 
				alert(thrownError); 
			}
		});
	}

	function trayeklist(){
		$.ajax({
			type: "POST", 
			url: "<?= base_url() ?>jadwal_armada/ax_get_trayek", 
			data: {id_cabang : $("#id_bu_filter").val()}, 
			dataType: "json",
			beforeSend: function(e) {
				if(e && e.overrideMimeType) {
					e.overrideMimeType("application/json;charset=UTF-8");
				}
			},
			success: function(response){ 

				$("#kd_trayek").html(response.data_trayek).show();
				$('#select2-kd_trayek-container-container').html('--Trayek--');
				$('#kd_trayek').val('0');
			},
			error: function (xhr, ajaxOptions, thrownError) { 
				alert(thrownError); 
			}
		});
	}
</script>
</body>
</html>
