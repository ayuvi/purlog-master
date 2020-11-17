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
				<h1>Odometer</h1>
			</section>
			<section class="invoice">
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								
								<div class="modal fade" id="addModal" tabindex="" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="Form-add-bu" id="addModalLabel">Form Add Odometer</h4>
											</div>
											<div class="modal-body">
												<input type="hidden" id="id_odometer" name="id_odometer" value='' />
												<div class="form-group">
													<label>Segment</label>
													<select class="form-control select2" style="width: 100%;" id="id_segment" name="id_segment">
														<option disabled selected value="0">--Segment--</option>
														<?php
														foreach ($combobox_segment->result() as $rowmenu) { ?>
															<option value="<?= $rowmenu->id_segment?>"  ><?= $rowmenu->nm_segment?></option>
														<?php } ?>
													</select>
												</div>
												<div class="form-group">
													<label>Armada</label>
													<select class="form-control select2" style="width: 100%;" id="kd_armada">
														<option value="0"> -- Armada -- </option>
													</select>
												</div>
												<div class="form-group">
													<label>Tanggal</label>
													<input type="text" id="tanggal" name="tanggal" class="form-control" placeholder="Tanggal" value="<?php echo date('Y-m-d');?>">
												</div>
												<div class="form-group">
													<label>Active</label>
													<select class="form-control" id="active" name="active">
														<option value="1" <?php echo set_select('myselect', '1', TRUE); ?> >Active</option>
														<option value="0" <?php echo set_select('myselect', '0'); ?> >Not Active</option>
													</select>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
												<button type="button" class="btn btn-primary" id='btnSave'>Save</button>
											</div>
										</div>
									</div>
								</div>

								<div class="modal fade" id="addModalDetail" tabindex="" role="dialog" aria-labelledby="addModalLabelDetail" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="Form-add-bu" id="addModalLabelDetail">Form Add Odometer</h4>
											</div>
											<div class="modal-body">
												<input type="hidden" id="id_odometer_header" name="id_odometer_header" value='' />
												<input type="hidden" id="id_odometer_detail" name="id_odometer_detail" value='' />
												<div class="form-group">
													<label>Trayek</label>
													<select class="form-control select2" style="width: 100%;" id="kd_trayek">
													</select>
												</div>
												<div class="form-group">
													<label>KM Trayek</label>
													<input type="text" id="km_trayek" name="km_trayek" class="form-control" placeholder="KM Trayek">
												</div>
												<div class="form-group">
													<label>KM Empty</label>
													<input type="text" id="km_empty" name="km_empty" class="form-control" placeholder="KM Empty">
												</div>
												<div class="form-group">
													<label>KM Rombongan</label>
													<input type="text" id="km_rombongan" name="km_rombongan" class="form-control" placeholder="KM Rombongan">
												</div>
												<div class="form-group">
													<label>Active</label>
													<select class="form-control" id="active_detail" name="active_detail">
														<option value="1" <?php echo set_select('myselect', '1', TRUE); ?> >Active</option>
														<option value="0" <?php echo set_select('myselect', '0'); ?> >Not Active</option>
													</select>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
												<button type="button" class="btn btn-primary" id='btnSaveDetail'>Save</button>
											</div>
										</div>
									</div>
								</div>

							</div>
							<div class="panel-body">
								<div class="nav-tabs-custom">
									<ul class="nav nav-tabs">
										<li class="active disabled"><a href="#tab_1" class="disabled" data-toggle="tab" aria-expanded="true">List Odometer</a></li>
										<li class=" disabled"><a href="#tab_2" class="disabled" aria-expanded="false">Odometer Detail</a></li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="tab_1">
											<div class="row">
												<div class="form-group col-lg-10">
													<small><b>Cabang</b></small>
													<select class="form-control select2 " style="width: 100%;" id="id_bu_filter" name="id_bu_filter">
														<?php
														foreach ($combobox_cabang->result() as $rowmenu) {
															?>
															<option value="<?= $rowmenu->id_bu?>"  ><?= $rowmenu->nm_bu?></option>
														<?php } ?>
													</select>
												</div>
												<div class="col-lg-2">
													<p style="height: 10px"></p>
													<button class="btn btn-primary" onclick='ViewData(0)'>
														<i class='fa fa-plus'></i> Add Odometer
													</button>
												</div>
											</div>
											<br>
											<div class="dataTable_wrapper">
												<table class="table table-striped table-bordered table-hover" id="odometerTable">
													<thead>
														<tr>
															<th>Options</th>
															<th>#</th>
															<th>Segment</th>
															<th>Armada</th>
															<th>No Polisi</th>
															<th>Tanggal</th>
															<th>Active</th>
														</tr>
													</thead>
												</table>
											</div>
										</div>
										<div class="tab-pane" id="tab_2">
											<div class="row">
												<div class="col-lg-12">
													<button class="btn btn-primary" onclick='ViewDataDetail(0)'>
														<i class='fa fa-plus'></i> Add Odometer Detail
													</button>
													<button type="button" class="btn bg-purple btn-default pull-right" onClick='closeTab()'><i class="fa  fa-arrow-circle-left"></i> Kembali</button>	
												</div>
											</div>
											<br>

											<div class="dataTable_wrapper">
												<table class="table table-striped table-bordered table-hover" id="odometerDetail">
													<thead>
														<tr>
															<th>Options</th>
															<th>#</th>
															<th>KD Trayek</th>
															<th>Awal</th>
															<th>Akhir</th>
															<th>KM Trayek</th>
															<th>KM Empty</th>
															<th>KM Rombongan</th>
															<th>Total</th>
														</tr>
													</thead>
												</table>
											</div>
										</div>

									</div>
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
		var save_method;
		var base_url = '<?php echo base_url();?>';

		var odometerTable = $('#odometerTable').DataTable({
			"ordering" : false,
			"scrollX": true,
			"processing": true,
			"serverSide": true,
			ajax: 
			{
				url: "<?= base_url()?>odometer/ax_data_odometer/",
				type: 'POST',
				data: function ( d ) {
					return $.extend({}, d, { 
						"id_bu": $("#id_bu_filter").val()
					});
				}
			},
			columns: 
			[
			{
				data: "id_odometer", render: function(data, type, full, meta){
					var str = '';
					str += '<div class="btn-group">';
					str += '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>';
					str += '<ul class="dropdown-menu">';
					str += '<li><a onclick="DetailData(' + data + ')"><i class="fa fa-list"></i> Detail</a></li>';
					str += '<li><a onclick="ViewData(' + data + ')"><i class="fa fa-pencil"></i> Edit</a></li>';
					str += '<li><a onClick="DeleteData(' + data + ')"><i class="fa fa-trash"></i> Delete</a></li>';
					str += '</ul>';
					str += '</div>';
					return str;
				}
			},

			{ data: "id_odometer" },
			{ data: "segment" },
			{ data: "kd_armada" },
			{ data: "plat_armada" },
			{ data: "tanggal" },

			{ data: "active", render: function(data, type, full, meta){
				if(data == 1)
					return "Active";
				else return "Not Active";
			}
		}
		]
	});

		var odometerDetail = $('#odometerDetail').DataTable({
			"ordering" : false,
			"scrollX": true,
			"processing": true,
			"serverSide": true,
			ajax: 
			{
				url: "<?= base_url()?>odometer/ax_data_odometer_detail/",
				type: 'POST',
				data: function ( d ) {
					return $.extend({}, d, { 
						"id_odometer": $("#id_odometer_header").val()
					});
				}
			},
			columns: 
			[
			{
				data: "id_odometer_detail", render: function(data, type, full, meta){
					var str = '';
					str += '<div class="btn-group">';
					str += '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>';
					str += '<ul class="dropdown-menu">';
					str += '<li><a onclick="ViewDataDetail(' + data + ')"><i class="fa fa-pencil"></i> Edit</a></li>';
					str += '<li><a onClick="DeleteDataDetail(' + data + ')"><i class="fa fa-trash"></i> Delete</a></li>';
					str += '</ul>';
					str += '</div>';
					return str;
				}
			},

			{ data: "id_odometer_detail" },
			{ data: "kd_trayek" },
			{ data: "nm_point_awal" },
			{ data: "nm_point_akhir" },
			{ data: "km_trayek" },
			{ data: "km_empty" },
			{ data: "km_rombongan" },
			{ data: "total" }
			]
		});

		$('#btnSave').on('click', function () {
			if($('#id_segment').val() == null)
			{
				alertify.alert("Warning", "Pilih Segment.");
			}else if($('#kd_armada').val() == '0')
			{
				alertify.alert("Warning", "Pilih Armada.");
			}
			else
			{
				var url = '<?=base_url()?>odometer/ax_set_data';
				var data = {
					id_odometer: $('#id_odometer').val(),
					id_segment: $('#id_segment').val(),
					kd_armada: $('#kd_armada').val(),
					tanggal: $('#tanggal').val(),
					active: $('#active').val(),
					id_bu: $('#id_bu_filter').val()
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
						odometerTable.ajax.reload();
					}
				});
			}
		});

		$('#btnSaveDetail').on('click', function () {
			if($('#kd_trayek').val() == '0')
			{
				alertify.alert("Warning", "Pilih Trayek");
			}else if($('#km_trayek').val() == '')
			{
				alertify.alert("Warning", "Isi KM Trayek.");
			}
			else if($('#km_empty').val() == '')
			{
				alertify.alert("Warning", "Isi KM Empty.");
			}
			else if($('#km_rombongan').val() == '')
			{
				alertify.alert("Warning", "Isi KM Rombongan.");
			}
			else
			{
				var url = '<?=base_url()?>odometer/ax_set_data_detail';
				var data = {
					id_odometer_detail: $('#id_odometer_detail').val(),
					id_odometer: $('#id_odometer_header').val(),
					kd_trayek: $('#kd_trayek').val(),
					km_trayek: $('#km_trayek').val(),
					km_empty: $('#km_empty').val(),
					km_rombongan: $('#km_rombongan').val(),
					active: $('#active_detail').val()
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
						$('#addModalDetail').modal('hide');
						odometerDetail.ajax.reload();
					}
				});
			}
		});

		function ViewData(id_odometer)
		{
			if(id_odometer == 0)
			{
				$('#addModalLabel').html('Add Odometer');
				$('#id_odometer').val('');
				$('#id_segment').val('').trigger('change');
				$('#kd_armada').val('').trigger('change');
				$('#select2-kd_armada-container').html('--Armada--');
				$('#active').val('1');
				$('#addModal').modal('show');
			}
			else
			{
				var url = '<?=base_url()?>odometer/ax_get_data_by_id';
				var data = {
					id_odometer: id_odometer
				};

				$.ajax({
					url: url,
					method: 'POST',
					data: data
				}).done(function(data, textStatus, jqXHR) {
					var data = JSON.parse(data);
					$('#addModalLabel').html('Edit Odometer');
					$('#id_odometer').val(data['id_odometer']);
					$('#id_segment').val(data['id_segment']).trigger('change');
					$('#kd_armada').val(data['kd_armada']).trigger('change');
					$('#tanggal').val(data['tanggal']);
					$('#active').val(data['active']);
					$('#addModal').modal('show');
				});
			}
		}

		function ViewDataDetail(id_odometer_detail)
		{
			if(id_odometer_detail == 0)
			{
				$('#addModalLabelDetail').html('Add Odometer Detail');
				$('#id_odometer_detail').val('');
				$('#kd_trayek').val('').trigger('change');
				$('#km_trayek').val('');
				$('#select2-kd_trayek-container').html('--Trayek--');
				$('#km_empty').val('');
				$('#km_rombongan').val('');
				$('#active_detail').val('1');
				$('#addModalDetail').modal('show');
				combo_trayek();
			}
			else
			{
				var url = '<?=base_url()?>odometer/ax_get_data_by_id_detail';
				var data = {
					id_odometer_detail: id_odometer_detail
				};

				$.ajax({
					url: url,
					method: 'POST',
					data: data
				}).done(function(data, textStatus, jqXHR) {
					var data = JSON.parse(data);
					$('#addModalLabelDetail').html('Edit Odometer');
					$('#id_odometer_detail').val(data['id_odometer_detail']);
					$('#id_odometer_header').val(data['id_odometer']);
					$('#kd_armada').val(data['kd_armada']).trigger('change');
					$('#km_trayek').val(data['km_trayek']);
					$('#km_empty').val(data['km_empty']);
					$('#km_rombongan').val(data['km_rombongan']);
					$('#active_detail').val(data['active']);
					$('#addModalDetail').modal('show');
				});
			}
		}

		function DeleteData(id_odometer)
		{
			alertify.confirm(
				'Confirmation', 
				'Are you sure you want to delete this data?', 
				function(){
					var url = '<?=base_url()?>odometer/ax_unset_data';
					var data = {
						id_odometer: id_odometer
					};

					$.ajax({
						url: url,
						method: 'POST',
						data: data
					}).done(function(data, textStatus, jqXHR) {
						var data = JSON.parse(data);
						odometerTable.ajax.reload();
						alertify.error('Data deleted.');
					});
				},
				function(){ }
				);
		}

		function DeleteDataDetail(id_odometer_detail)
		{
			alertify.confirm(
				'Confirmation', 
				'Are you sure you want to delete this data?', 
				function(){
					var url = '<?=base_url()?>odometer/ax_unset_data_detail';
					var data = {
						id_odometer_detail: id_odometer_detail
					};

					$.ajax({
						url: url,
						method: 'POST',
						data: data
					}).done(function(data, textStatus, jqXHR) {
						var data = JSON.parse(data);
						odometerDetail.ajax.reload();
						alertify.error('Data deleted.');
					});
				},
				function(){ }
				);
		}

		function DetailData(id_odometer){
			$('#id_odometer_header').val(id_odometer);
			$('.nav-tabs a[href="#tab_2"]').tab('show');
			odometerDetail.ajax.reload();
			odometerDetail.columns.adjust().draw();
		}

		function list_armada(){
			$.ajax({
				type: "POST", 
				url: "<?= base_url() ?>odometer/ax_get_armada", 
				data: {id_bu : $("#id_bu_filter").val()}, 
				dataType: "json",
				beforeSend: function(e) {
					if(e && e.overrideMimeType) {
						e.overrideMimeType("application/json;charset=UTF-8");
					}
				},
				success: function(response){ 
					$("#kd_armada").html(response.data_armada).show();
					$('#select2-kd_armada-container').html('--Armada--');
					$('#kd_armada').val('0');
				},
				error: function (xhr, ajaxOptions, thrownError) { 
					alert(thrownError); 
				}
			});
		}

		function combo_trayek(){
			$.ajax({
				type: "POST", 
				url: "<?= base_url() ?>odometer/ax_get_trayek", 
				data: {
					id_cabang : $("#id_bu_filter").val()
				},
				dataType: "json",
				beforeSend: function(e) {
					if(e && e.overrideMimeType) {
						e.overrideMimeType("application/json;charset=UTF-8");
					}
				},
				success: function(response){ 
					$("#kd_trayek").html(response.data_trayek).show();
					$('#select2-kd_trayek-container').html(nm_trayek);
					$('#kd_trayek').val(kd_trayek);
				},
				error: function (xhr, ajaxOptions, thrownError) { 
					alert(thrownError); 
				}
			});
		}

		$('#id_bu_filter').select2({
			'allowClear': true
		}).on("change", function (e) {
			list_armada();
			odometerTable.ajax.reload();
		});

		$('#id_segment').select2({
			'placeholder': "--Segment--",
			'allowClear': true
		});

		$( "#tanggal").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: "yy-mm-dd"
		});

		$( "#tanggal" ).inputmask("yyyy-mm-dd",{"placeholder": "yyyy-mm-dd"});

		function closeTab(){
			$('.nav-tabs a[href="#tab_1"]').tab('show');
			odometerTable.columns.adjust().draw();
		}
	</script>
</body>
</html>
