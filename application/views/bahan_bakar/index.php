<!DOCTYPE html>
<html>

<head>
	<?= $this->load->view('head'); ?>
</head>

<body class="sidebar-mini wysihtml5-supported <?= $this->config->item('color') ?>">
	<div class="wrapper">
		<?= $this->load->view('nav'); ?>
		<?= $this->load->view('menu_groups'); ?>
		<div class="content-wrapper">
			<section class="content-header">
				<h1>Bahan Bakar</h1>
			</section>
			<section class="invoice">
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<button class="btn btn-primary" onclick='ViewData(0)'>
									<i class='fa fa-plus'></i> Add Bahan Bakar
								</button>
								<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="Form-add-bu" id="addModalLabel">Form Add Bahan Bakar</h4>
											</div>
											<div class="modal-body">
												<input type="text" id="id_bbm" name="id_bbm" value='' />
												<div class="form-group">
													<label>Tanggal Nota</label>
													<input type="text" id="tgl_bbm" name="tgl_bbm" class="form-control" placeholder="Tanggal Nota">
												</div>
												<div class="form-group">
													<label>KD Armada</label>
													<select class="form-control select2" style="width: 100%;" id="kd_armada" name="kd_armada">
														<option value="0">--KD Armada--</option>
														<?php
														foreach ($combobox_armada->result() as $rowmenu) {
														?>
															<option value="<?= $rowmenu->kd_armada ?>"><?= $rowmenu->kd_armada . ' - ' . $rowmenu->plat_armada ?></option>
														<?php
														}
														?>
													</select>
												</div>
												<div class="form-group">
													<label>Segment</label>
													<select class="form-control select2" style="width: 100%;" id="id_segment" name="id_segment">
														<option value="0">--Segment--</option>
														<?php
														foreach ($combobox_segment->result() as $rowmenu) {
														?>
															<option value="<?= $rowmenu->id_segment ?>"><?= $rowmenu->nm_segment ?></option>
														<?php
														}
														?>
													</select>
												</div>
												<div class="form-group">
													<label>Barang</label>
													<select class="form-control select2" style="width: 100%;" id="kd_barang" name="kd_barang">
														<option disabled selected value="0">--Barang--</option>
														<?php
														foreach ($combobox_barang->result() as $rowmenu) {
														?>
															<option value="<?= $rowmenu->kd_barang ?>"><?= $rowmenu->nm_barang ?></option>
														<?php
														}
														?>
													</select>
												</div>
												<div class="form-group">
													<label>Deskripsi</label>
													<textarea id="keterangan_bbm" name="keterangan_bbm" rows="4" cols="50" class="form-control"></textarea>
												</div>
												<div class="form-group">
													<label>Harga</label>
													<input type="text" id="harga_bbm" name="harga_bbm" class="form-control" placeholder="Harga Bbm">
												</div>
												<div class="form-group">
													<label>Jumlah Liter</label>
													<input type="text" id="jumlah_bbm" name="jumlah_bbm" class="form-control" placeholder="jumlah Liter">
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
							<div class="panel-body">
								<div class="dataTable_wrapper">
									<table class="table table-striped table-bordered table-hover" id="buTable">
										<thead>
											<tr>
												<th>Options</th>
												<th>#</th>
												<th>KD Armada</th>
												<th>Tanggal</th>
												<th>Segment</th>
												<th>Barang</th>
												<th>Harga</th>
												<th>Liter</th>
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
			"ordering": false,
			"scrollX": true,
			"processing": true,
			"serverSide": true,
			ajax: {
				url: "<?= base_url() ?>bahan_bakar/ax_data_bahan_bakar/",
				type: 'POST'
			},
			columns: [{
					data: "id_bbm",
					render: function(data, type, full, meta) {
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

				{
					data: "id_bbm"
				},
				{
					data: "kd_armada"
				},
				{
					data: "tanggal_bbm"
				},
				{
					data: "nm_segment"
				},
				{
					data: "nm_barang"
				},
				{
					data: "harga_bbm"
				},
				{
					data: "jumlah_bbm"
				}

			]
		});

		$('#btnSave').on('click', function() {
			if ($('#tgl_bbm').val() == '') {
				alertify.alert("Warning", "Please fill Tanggal BBM.");
			} else if ($('#kd_armada').val() == '0') {
				alertify.alert("Warning", "Please fill Armada.");
			} else if ($('#id_segment').val() == '0') {
				alertify.alert("Warning", "Please fill segment.");
			} else if ($('#kd_barang').val() == null) {
				alertify.alert("Warning", "Please fill barang.");
			} else if ($('#keterangan_bbm').val() == '') {
				alertify.alert("Warning", "Please fill Keterangan.");
			} else if ($('#harga_bbm').val() == '') {
				alertify.alert("Warning", "Please fill harga bbm.");
			} else if ($('#jumlah_bbm').val() == '') {
				alertify.alert("Warning", "Please fill Jumlah Liter.");
			} else {
				var url = '<?= base_url() ?>bahan_bakar/ax_set_data';
				var data = {
					id_bbm: $('#id_bbm').val(),
					tgl_bbm: $('#tgl_bbm').val(),
					kd_armada: $('#kd_armada').val(),
					id_segment: $('#id_segment').val(),
					kd_barang: $('#kd_barang').val(),
					keterangan_bbm: $('#keterangan_bbm').val(),
					harga_bbm: $('#harga_bbm').val(),
					jumlah_bbm: $('#jumlah_bbm').val()
				};

				$.ajax({
					url: url,
					method: 'POST',
					data: data
				}).done(function(data, textStatus, jqXHR) {
					var data = JSON.parse(data);
					if (data['status'] == "success") {
						alertify.success("Data Saved.");
						$('#addModal').modal('hide');
						buTable.ajax.reload();
					}
				});
			}
		});

		$("#tgl_bbm").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: "yy-mm-dd"
		});

		function ViewData(id_bbm) {
			if (id_bbm == 0) {
				$('#addModalLabel').html('Add Bahan bakar');
				$('#id_bbm').val(''),
					$('#tgl_bbm').val(''),
					$('#kd_armada').val('0'),
					$('#id_segment').val('0'),
					$('#kd_barang').val(''),
					$('#keterangan_bbm').val(''),
					$('#harga_bbm').val(''),
					$('#jumlah_bbm').val('')
				$('#active').val('1');
				$('#addModal').modal('show');
			} else {
				var url = '<?= base_url() ?>bahan_bakar/ax_get_data_by_id';
				var data = {
					id_bbm: id_bbm
				};

				$.ajax({
					url: url,
					method: 'POST',
					data: data
				}).done(function(data, textStatus, jqXHR) {
					var data = JSON.parse(data);
					$('#addModalLabel').html('Edit merek');
					$('#id_bbm').val(id_bbm);
					$('#tgl_bbm').val(data['tanggal_bbm']);
					$('#kd_armada').val(data['kd_armada']).trigger('change');
					$('#id_segment').val(data['id_segment']).trigger('change');
					$('#nm_segment').val(data['nm_segment']);
					$('#keterangan_bbm').val(data['keterangan_bbm']);
					$('#harga_bbm').val(data['harga_bbm']);
					$('#jumlah_bbm').val(data['jumlah_bbm']);
					$('#kd_barang').val(data['kd_barang']).trigger('change');
					$('#addModal').modal('show');
				});
			}
		}

		function DeleteData(id_bbm) {
			alertify.confirm(
				'Confirmation',
				'Are you sure you want to delete this data?',
				function() {
					var url = '<?= base_url() ?>bahan_bakar/ax_unset_data';
					var data = {
						id_bbm: id_bbm
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
				function() {}
			);
		}
	</script>
</body>

</html>