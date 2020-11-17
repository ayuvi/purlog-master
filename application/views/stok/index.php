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
				<h1>Stok</h1>
			</section>
			<section class="invoice">
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">

								<div class="row">
									<div class="form-group col-lg-12">
										<small><b>Cabang</b></small>
										<select class="form-control select2 " style="width: 100%;" id="id_bu_filter" name="id_bu_filter" onchange="reloadPemesananTable()">
											<?php
											foreach ($combobox_cabang->result() as $rowmenu) {
												?>
												<option value="<?= $rowmenu->id_bu?>"  ><?= $rowmenu->nm_bu?></option>
												<?php
											}
											?>
										</select>
									</div>
								</div>

								<div class="modal fade" id="addModal" tabindex="" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="Form-add-bu" id="addModalLabel">Form Add Stok</h4>
											</div>
											<div class="modal-body">
												<input type="hidden" id="id_stok" name="id_stok" value='' />
												<div class="form-group">
													<label>Barang</label>
													<select class="form-control select2" style="width: 100%;" id="kd_barang" name="kd_barang">
														<option value="0">--Barang--</option>
														<?php
														foreach ($combobox_barang->result() as $rowmenu) {
															?>
															<option value="<?= $rowmenu->kd_barang?>"  ><?= $rowmenu->nm_barang?></option>
															<?php
														}
														?>
													</select>
												</div>
												<div class="form-group">
													<label>Detail</label>
													<input type="text" id="detail" name="detail" class="form-control" placeholder="Detail">
												</div>
												
												<div class="form-group">
													<label>Supplier</label>
													<select class="form-control select2" style="width: 100%;" id="id_supplier" name="id_supplier">
														<option value="0">--Supplier--</option>
														<?php
														foreach ($combobox_supplier->result() as $rowmenu) {
															?>
															<option value="<?= $rowmenu->id_supplier?>"  ><?= $rowmenu->nm_supplier?></option>
															<?php
														}
														?>
													</select>
												</div>
												<div class="form-group">
													<label>Jumlah</label>
													<input type="number" id="qty" name="qty" class="form-control" placeholder="Jumlah">
												</div>
												<div class="form-group">
													<label>Type</label>
													<select class="form-control" id="type" name="type">
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
							</div>
							<div class="panel-body">
								<div class="dataTable_wrapper">
									<table class="table table-striped table-bordered table-hover" id="buTable">
										<thead>
											<tr>
												<th>#</th>
												<th>Kode Brg</th>
												<th>Nama Barang</th>
												<th>Merek</th>
												<th>Harga</th>
												<th>Jumlah</th>
												<th>Active</th>
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
				url: "<?= base_url()?>stok/ax_data_stok/",
				type: 'POST',
				data: function ( d ) {
					return $.extend({}, d, { 
						"id_bu": $("#id_bu_filter").val()
					});
				}
			},
			columns: 
			[
			// {
			// 	data: "id_stock", render: function(data, type, full, meta){
			// 		var str = '';
			// 		str += '<div class="btn-group">';
			// 		str += '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>';
			// 		str += '<ul class="dropdown-menu">';
			// 		str += '<li><a onclick="ViewData(' + data + ')"><i class="fa fa-pencil"></i> Edit</a></li>';
			// 		str += '<li><a onClick="DeleteData(' + data + ')"><i class="fa fa-trash"></i> Delete</a></li>';
			// 		str += '</ul>';
			// 		str += '</div>';
			// 		return str;
			// 	}
			// },
			{ data: "id_stock" },
			{ data: "kd_barang" },
			{ data: "nm_barang" },
			{ data: "nm_merek" },
			{ data: "harga", render: $.fn.dataTable.render.number( ',', '.',0 ) },
			{ data: "jumlah", render: $.fn.dataTable.render.number( ',', '.',0 ) },
			{ data: "active", render: function(data, type, full, meta){
				if(data == 1)
					return '<span class="label label-primary">Active</span>';
				else return '<span class="label label-warning">Not Active</span>';
			}}
			]
		});

		$('#btnSave').on('click', function () {
			if($('#kd_barang').val() == null || $('#type').val() == '' || $('#detail').val() == ''|| $('#id_supplier').val() == null || $('#qty').val() == '')
			{
				alertify.alert("Warning", "Isi semua data");
			}
			else
			{
				var url = '<?=base_url()?>stok/ax_set_data';
				var data = {
					id_stok		: $('#id_stok').val(),
					kd_barang 	: $('#kd_barang').val(),
					type 		: $('#type').val(),
					detail 		: $('#detail').val(),
					id_supplier : $('#id_supplier').val(),
					qty 		: $('#qty').val()
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

		function ViewData(id_stok)
		{
			if(id_stok == 0)
			{
				$('#addModalLabel').html('Add stok');
				$('#id_stok').val('');
				$('#kd_barang').val('').trigger('change');
				$('#type').val('1');
				$('#detail').val('');
				$('#id_supplier').val('').trigger('change');
				$('#qty').val('');
				$('#addModal').modal('show');
			}
			else
			{
				var url = '<?=base_url()?>stok/ax_get_data_by_id';
				var data = {
					id_stok: id_stok
				};

				$.ajax({
					url: url,
					method: 'POST',
					data: data
				}).done(function(data, textStatus, jqXHR) {
					var data = JSON.parse(data);
					$('#addModalLabel').html('Edit stok');
					$('#id_stok').val(data['id_stok']);
					$('#kd_barang').val(data['kd_barang']).trigger('change');;
					$('#type').val(data['type']);
					$('#detail').val(data['detail']);
					$('#id_supplier').val(data['id_supplier']).trigger('change');;
					$('#qty').val(data['qty']);
					$('#addModal').modal('show');
				});
			}
		}

		function DeleteData(id_stok)
		{
			alertify.confirm(
				'Confirmation', 
				'Are you sure you want to delete this data?', 
				function(){
					var url = '<?=base_url()?>stok/ax_unset_data';
					var data = {
						id_stok: id_stok
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

		function reloadPemesananTable() {
			buTable.ajax.reload();
		}

		$('#kd_barang').select2({
			'placeholder': "--Barang--",
			'allowClear': true
		});

		$('#id_supplier').select2({
			'placeholder': "--Supplier--",
			'allowClear': true
		});
	</script>
</body>
</html>
