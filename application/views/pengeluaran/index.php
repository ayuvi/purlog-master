<!DOCTYPE html>
<html>
<head>
	<?= $this->load->view('head'); ?>
</head>
<body class="sidebar-mini sidebar-collapse wysihtml5-supported <?= $this->config->item('color')?>">
	<div class="wrapper">
		<?= $this->load->view('nav'); ?>
		<?= $this->load->view('menu_groups'); ?>
		<div class="content-wrapper">
			<section class="content-header">
				<h1>Pengeluaran</h1>
			</section>
			<section class="invoice">
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<div class="row">
									<div class="form-group col-lg-5">
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
									<div class="col-lg-2">
										<small><b>Status</b></small>
										<select id="status_filter" class="form-control">
											<option value="0">All</option>
											<option value="1">Draft</option>
											<option value="2">Pengajuan</option>
											<option value="3">Pengeluaran</option>
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
											<button class="btn btn-primary pull-right" onclick='ViewData(0)'>
												<i class='fa fa-plus'></i> Tambah
											</button>
											<button class="btn btn-success pull-left" onclick='printPengeluran(0)'>
												<i class='fa fa-print'></i> Print

											</button>
										</div>
									</div>
								</div>
							</div>
							<div class="panel-body">
								
								<div class="nav-tabs-custom">
									<ul class="nav nav-tabs">
										<li class="active disabled"><a href="#tab_1" class="disabled" data-toggle="tab" aria-expanded="true">List Pengeluaran</a></li>
										<li class=" disabled"><a href="#tab_2" class="disabled" aria-expanded="false">Pengeluaran Detail</a></li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="tab_1">
											<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
															<h4 class="Form-add-bu" id="addModalLabel">Form Add Pengeluaran</h4>
														</div>
														<div class="modal-body">
															<input type="hidden" id="id_pengeluaran" name="id_pengeluaran" value='' />
															<input type="hidden" id="id_bu" name="id_bu" value='' />
															<div class="form-group">
																<label>Armada</label>
																<select class="form-control select2" style="width: 100%;" id="kd_armada" name="kd_armada">
																	<option value="0">--Armada--</option>
																</select>
															</div>
															<div class="form-group">
																<label>Note</label>
																<textarea id="note" name="note" rows="4" cols="50" class="form-control"></textarea>
															</div>
															<div class="form-group">
																<label>Tanggal</label>
																<input type="text" id="tanggal" name="tanggal" class="form-control" placeholder="Tanggal" value="<?php echo date('Y-m-d');?>" readonly>
															</div>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
															<button type="button" class="btn btn-primary" id='btnSave'>Save</button>
														</div>
													</div>
												</div>
											</div>

											<div class="dataTable_wrapper">
												<table class="table table-striped table-bordered table-hover" id="pengeluaranTable">
													<thead>
														<tr>
															<th>Detail</th>
														</tr>
													</thead>
												</table>
											</div>
										</div>


										<div class="tab-pane" id="tab_2">
											<div class="modal-content">
												<div class="row">
													<div class="col-lg-12">
														<button type="button" class="btn bg-purple btn-default pull-right" onClick='closeTab()'><i class="fa  fa-arrow-circle-left"></i> Kembali</button>	
													</div>
												</div>

												<div class="row">
													<div class="col-lg-12" style="height: 10px">
													</div>
													<div class="col-lg-6">
														<div class="box box-primary box-solid">
															<div class="box-header with-border">
																<h6 class="box-title">Tambah Detail Pengeluaran</h6>
																<div class="box-tools pull-right">
																	<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
																</div>
															</div>
															<div class="box-body">
																<div class="modal-body">
																	<div class="row">
																		<div class="col-lg-12">
																			<button type="button" class="btn btn-success pull-right" id='btnSaveDetail'><i class="glyphicon glyphicon-floppy-disk"></i> Simpan</button>
																		</div>
																		<input type="hidden" id="id_pengeluaran_header" name="id_pengeluaran_header" class="form-control">
																		<input type="hidden" id="tanggal_pengeluaran" name="tanggal_pengeluaran" class="form-control">

																		<div class="col-lg-12">
																			<table class="table">
																				<tr>
																					<td colspan="2">
																						<small><b>ID Stock</b></small>
																						<div class="input-group col-md-12">
																							<input type="text" name="kd_barang" class="form-control" placeholder="Input ID Stock" id="kd_barang" onkeyup="tampilDataBarang(this.value)">
																							<span class="input-group-btn">
																								<button class="btn btn-info" type="button" onclick="cariBarang()" id="cari_nama"><i class="glyphicon glyphicon-search"></i> Cari Stock</button>
																							</span>
																						</div>
																					</td>
																				</tr>
																				<tr>
																					<td>
																						<small><b>Harga</b></small>
																						<input type="number" id="harga" name="harga" class="form-control" placeholder="Harga" readonly>
																					</td>
																					<td>
																						<small><b>Jumlah</b></small>
																						<input type="number" id="jumlah" name="jumlah" class="form-control" placeholder="Jumlah">
																					</td>
																				</tr>
																				<tr>
																					<td><small><b>KD Barang</b></small><br><p id="kode_barang_view"> </p></td>
																				</tr>
																				<tr>
																					<td><small><b>Nama Barang</b></small><br><p id="nm_barang"> </p></td>
																					<td><small><b>Merek Barang</b></small><br><p id="merek_barang_view"> </p></td>
																				</tr>
																				<tr>
																					<td><small><b>Satuan</b></small><br><p id="nm_satuan"> </p></td>
																				</tr>
																				<tr>
																					<td colspan="2"><small><b>Deskripsi</b></small><br><p id="deskripsi"> </p></td>
																				</tr>
																				<tr>
																					<td><small><b>Gambar</b></small></td>
																					<td>
																						<div class="form-group" id="photo-preview">
																							(Photo)
																							<span class="help-block"></span>
																						</div>
																					</td>

																					<input type="hidden" name="id_satuan" id="id_satuan">
																					<input type="hidden" name="gambar" id="gambar">
																					<input type="hidden" id="id_pengeluaran_detail" name="id_pengeluaran_detail" />
																					<input type="hidden" id="kode_barang" name="kode_barang" />
																					<input type="hidden" id="id_merek" name="id_merek" />
																				</td>
																			</tr>
																		</table>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-lg-6">
													<div class="modal-body">
														<div class="dataTable_wrapper">
															<table class="table table-striped table-bordered table-hover" id="pengeluaranTableDetail">
																<thead>
																	<tr>
																		<th>Detail</th>
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
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>

	<div class="row" >
		<div class="col-lg-12">
			<div class="modal fade" id="modalBarang" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="Form-add-bu" id="barangModalLabel">Form Data Stock Barang</h4>
							<input type="hidden" id="id_lmb_rit" name="id_lmb_rit" class="form-control">
							<input type="hidden" id="armada_rit" name="armada_rit" class="form-control">
						</div>
						<div class="modal-body">
							<div class="dataTable_wrapper">
								<table class="table table-striped table-bordered table-hover" id="dataBarangTable">
									<thead>
										<tr>
											<th>Action</th>
											<th>#</th>
											<th>KD Barang</th>
											<th>Nama Barang</th>
											<th>Harga</th>
											<th>Jumlah</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?= $this->load->view('basic_js'); ?>
	<script type='text/javascript'>

		$(document).ready(function() {
			document.getElementById('kd_barang').onkeydown = function (e) {
				var value =  e.target.value;
				if (!e.key.match(/[a-zA-Z0-9,]/) || (e.key == ',' && value[value.length-1] == ',')) {
					e.preventDefault();  
				}
			};
		});

		var base_url = '<?php echo base_url();?>';

		var pengeluaranTable = $('#pengeluaranTable').DataTable({
			"ordering" : false,
			"scrollX": true,
			"processing": true,
			"serverSide": true,
			ajax: 
			{
				url: "<?= base_url()?>pengeluaran/ax_data_pengeluaran/",
				type: 'POST',
				data: function ( d ) {
					return $.extend({}, d, { 

						"id_bu": $("#id_bu_filter").val(),
						"tanggal": $("#tanggal_filter").val(),
						"active": $("#status_filter").val(),

					});
				}
			},
			columns: 
			[
			{
				// data: "id_pengeluaran", render: function(data, type, full, meta){
				// 	var id1 = "'"+data+"','"+full['active']+"'";
				// 	var str = '';
				// 	str += '<div class="btn-group">';
				// 	str += '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>';
				// 	str += '<ul class="dropdown-menu">';
				// 	str += '<li><a onclick="DetailData(' + data +',' +full['rdate']+')"><i class="fa fa-list"></i> Detail</a></li>';
				// 	if(full['active'] ==1){
				// 		str += '<li><a onclick="CheckOut(' + id1 + ',' + "'Pengajuan'"+')"><i class="fa fa-cart-arrow-down"></i> Pengajuan</a></li>';
				// 	}else if(full['active'] ==2){
				// 		str += '<li><a onclick="CheckOut(' + id1 + ',' + "'pengeluaran'"+')"><i class="fa fa-sign-out"></i> pengeluaran</a></li>';
				// 	}

				// 	str += '<li><a onclick="ViewData(' + data + ')"><i class="fa fa-pencil"></i> Edit</a></li>';
				// 	str += '<li><a onClick="DeleteData(' + data + ')"><i class="fa fa-trash"></i> Delete</a></li>';
				// 	str += '</ul>';
				// 	str += '</div>';
				// 	return str;
				// }

				data: "id_pengeluaran", render: function(data, type, full, meta){
					var id1 = "'"+data+"','"+full['active']+"'";
					var str = '';
					if(full['active'] ==1){
						var stat = 'warning';
						var stat_nm = 'Draft';
					}else{
						var stat = 'success';
						var stat_nm = 'BBK';
					}
					str += '<table width="100%">';
					str += '<tr><td width="10px" style="font-size: large;text-align: left;">';
					str += '<div class="btn-group">';
					str += '<div class="btn-group">';
					str += '<button type="button" class="btn btn-info dropdown-toggle float-right" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>';
					str += '<ul class="dropdown-menu">';
					str += '<li><a onclick="DetailData(' + data +',' +full['rdate']+')"><i class="fa fa-list"></i> Detail</a></li>';
					if(full['active'] ==1){
						str += '<li><a onclick="CheckOut(' + id1 + ',' + "'BBK'"+')"><i class="fa fa-download"></i> BBK</a></li>';
						str += '<li><a onclick="ViewData(' + data + ')"><i class="fa fa-pencil"></i> Edit</a></li>';
						str += '<li><a onClick="DeleteData(' + data + ')"><i class="fa fa-trash"></i> Delete</a></li>';
					}

					str += '</ul>';
					str += '</div><button class="btn btn-'+ stat +'">'+ stat_nm +'</button><button class="btn btn-default">&emsp;<b>#'+full['id_pengeluaran'] +'</b></button></div>';
					


					str += '</td></tr>';

					str += '<tr ><td style="font-size: large; text-align: left;" colspan="2"><b>'+full['kd_armada']+'&emsp;</b></td></tr>';

					str += '<tr ><td style="font-size: medium; text-align: left;" colspan="2"><b>ID Pengeluaran</b> : '+data+'&emsp;<b>Pesan</b> : '+full['rdate']+'&emsp;</td></tr>';
					str += '<tr ><td style="font-size: medium; text-align: left;" colspan="2"><b>Note</b> : '+full['note']+'&emsp;</td></tr>';
					str += '</table>';
					return str;
				}
			}
			]
		});

		var pengeluaranTableDetail = $('#pengeluaranTableDetail').DataTable({
			"ordering" : false,
			"scrollX": true,
			"processing": true,
			"serverSide": true,

			dom: 'Bfrtip',
			lengthMenu: [
			[ 10, 25, 50, 100, 10000 ],
			[ '10 rows', '25 rows', '50 rows', '100 rows', 'Show all' ]
			],
			buttons: [
			'pageLength', 'copy', 'csv', 'excel',
			],

			ajax: 
			{
				url: "<?= base_url()?>pengeluaran/ax_data_pengeluaran_detail/",
				type: 'POST',
				data: function ( d ) {
					return $.extend({}, d, { 
						"id_pengeluaran": $("#id_pengeluaran_header").val()
					});
				}
			},
			columns: 
			[
			// { data: "id_pengeluaran_detail", render: function(data, type, full, meta){
			// 	var str = '';
			// 	str += '<div class="btn-group">';
			// 	str += '<a type="button" class="btn btn-sm btn-primary" title="Edit" onclick="editDetail(' + data + ')"><i class="fa fa-pencil"></i> </a>';
			// 	str += '<a type="button" class="btn btn-sm btn-danger" title="Delete" onclick="hapusDetail(' + data + ')"><i class="fa fa-trash"></i> </a>';

			// 	str += '</div>';
			// 	return str;
			// }},
			{ data: "id_pengeluaran_detail", render: function(data, type, full, meta){
				var str = '';
				str += `
				<div>
				<div class="btn-group">
				<a type="button" class="btn btn-sm btn-primary" title="Edit" onclick="editDetail(` + data + `)"><i class="fa fa-pencil"></i> </a>
				<a type="button" class="btn btn-sm btn-danger" title="Delete" onclick="hapusDetail(` + data + `)"><i class="fa fa-trash"></i> </a>
				</div>
				<strong>(`+full['kd_barang']+`) `+full['nm_barang']+`</strong> - `+full['nm_merek']+`<br>
				<b>Jml</b> : `+formatNumber(full['jumlah'])+`&emsp;
				<b>Harga</b> : `+formatNumber(full['harga'])+`&emsp;
				</div>`;
				return str;
			}}
			]
		});

		var dataBarangTable = $('#dataBarangTable').DataTable({
			"ordering" : false,
			"scrollX": true,
			"processing": true,
			"serverSide": true,

			dom: 'Bfrtip',
			lengthMenu: [
			[ 10, 25, 50, 100, 10000 ],
			[ '10 rows', '25 rows', '50 rows', '100 rows', 'Show all' ]
			],
			buttons: [
			'pageLength',
			],

			ajax: 
			{
				url: "<?= base_url()?>pengeluaran/ax_data_barang/",
				type: 'POST',
				data: function ( d ) {
					return $.extend({}, d, { 
						"id_bu": $("#id_bu_filter").val()
					});
				}
			},
			columns: 
			[
			{ data: "id_stock", render: function(data, type, full, meta){
				var str = '';
				var id_stock = "'"+data+"'";

				str += '<div class="btn-group">';
				str += '<a type="button" class="btn btn-sm btn-default" onclick="ambilBarang(' + id_stock + ')"><i class="fa fa-cloud-download"></i> Ambil</a>';

				str += '</div>';
				return str;
			}},
			{ data: "id_stock" },
			{ data: "kd_barang" },
			{ data: "nm_barang" },
			{ data: "harga", render: $.fn.dataTable.render.number( ',', '.',0 ) },
			{ data: "jumlah", render: $.fn.dataTable.render.number( ',', '.',0 ) },

			]
		});

		$('#btnSave').on('click', function () {
			if($('#kd_armada').val() == '0' || $('#tanggal').val() == '')
			{
				alertify.alert("Warning", "Isi Semua data.. Data tidak boleh ada yang kosong");
			}
			else
			{
				var url = '<?=base_url()?>pengeluaran/ax_set_data';
				var data = {
					id_pengeluaran: $('#id_pengeluaran').val(),
					kd_armada: $('#kd_armada').val(),
					id_bu: $('#id_bu').val(),
					note: $('#note').val(),
					tanggal: $('#tanggal').val()
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
						pengeluaranTable.ajax.reload();
					}
				});
			}
		});

		$('#btnSaveDetail').on('click', function () {
			if($('#kd_barang').val() == ''){
				alertify.alert("Warning", "Cari Barang Belum di Pilih.");
			}else if($('#harga').val() == ''){
				alertify.alert("Warning", "Harga Belum di Isi.");
			}else if($('#jumlah').val() == ''){
				alertify.alert("Warning", "Jumlah Belum di Isi.");
			}else if($('#id_satuan').val() == ''){
				alertify.alert("Warning", "ID Stok atau Stok Barang tidak ada di dalam database");
			}else {
				var url = '<?=base_url()?>pengeluaran/ax_set_data_detail';
				var data = {
					id_pengeluaran_detail: $('#id_pengeluaran_detail').val(),
					id_pengeluaran_header: $('#id_pengeluaran_header').val(),
					id_stock: $('#kd_barang').val(),
					kd_barang: $('#kode_barang').val(),
					id_merek: $('#id_merek').val(),
					nm_barang: $('#nm_barang').val(),
					id_satuan: $('#id_satuan').val(),
					harga: $('#harga').val(),
					jumlah: $('#jumlah').val(),
					gambar: $('#gambar').val(),
					deskripsi: $('#deskripsi').val(),
					id_bu: $('#id_bu_filter').val()
				};
				$.ajax({
					url: url,
					method: 'POST',
					data: data,
					statusCode: {
						500: function() {
							alertify.alert("Warning","Data Duplicate");
						}}
					}).done(function(data, textStatus, jqXHR) {
						var data = JSON.parse(data);
						if(data['status'])
						{
							alertify.success("Data Disimpan.");
							pengeluaranTableDetail.ajax.reload();
							reset_form_pengeluaran_detail();

						}else if(data.gagal=="melebihistok"){
							alertify.alert("Warning", "Input jumlah tidak boleh melebihi Stock..Stock yang tersedia saat ini "+data.jumlah);
							alertify.error("Data Gagal Disimpan.");
						}else{
							alertify.error("Data setoran Gagal Disimpan.");
							pengeluaranTableDetail.ajax.reload();
							reset_form_pengeluaran_detail();
						}
					});
				}
			});

		function ViewData(id_pengeluaran)
		{
			armadalist($('#id_bu_filter').val());
			$('#tanggal').val($('#tanggal_filter').val());

			if(id_pengeluaran == 0)
			{
				$('#addModalLabel').html('Add Pengeluaran');
				$('#id_pengeluaran').val('');
				// $('#id_supplier').val('').trigger('change');
				// $('#id_bu').val('').trigger('change');
				$('#id_bu').val($('#id_bu_filter').val());
				$("#tanggal").css('pointer-events', 'none');
				$('#note').val('');
				$('#addModal').modal('show');
			}
			else
			{
				var url = '<?=base_url()?>pengeluaran/ax_get_data_by_id';
				var data = {
					id_pengeluaran: id_pengeluaran
				};

				$.ajax({
					url: url,
					method: 'POST',
					data: data
				}).done(function(data, textStatus, jqXHR) {
					var data = JSON.parse(data);
					$('#addModalLabel').html('Edit pengeluaran');
					$('#id_pengeluaran').val(data['id_pengeluaran']);
					$('#kd_armada').val(data['kd_armada']).trigger('change');
					// $('#id_bu').val(data['id_bu']).trigger('change');
					$('#id_bu').val(data['id_bu']);
					$('#note').val(data['note']);
					$('#tanggal').val(data['rdate']);
					$('#addModal').modal('show');
				});
			}
		}

		function DeleteData(id_pengeluaran)
		{
			alertify.confirm(
				'Confirmation', 
				'Are you sure you want to delete this data?', 
				function(){
					var url = '<?=base_url()?>pengeluaran/ax_unset_data';
					var data = {
						id_pengeluaran: id_pengeluaran
					};

					$.ajax({
						url: url,
						method: 'POST',
						data: data
					}).done(function(data, textStatus, jqXHR) {
						var data = JSON.parse(data);
						pengeluaranTable.ajax.reload();
						alertify.error('Data deleted.');
					});
				},
				function(){ }
				);
		}

		$( "#tanggal").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: "yy-mm-dd"
		});


		$('#id_bu_filter').select2({
			'allowClear': true
		}).on("change", function (e) {
			pengeluaranTable.ajax.reload();
			armadalist();
		});

		$( "#status_filter" ).on("change", function (e) {
			pengeluaranTable.ajax.reload();
		});

		$( "#tanggal_filter" ).on("change", function (e) {
			pengeluaranTable.ajax.reload();
		});	

		$( "#tanggal_filter").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: "yy-mm-dd"
		});

		function closeTab(){
			$('.nav-tabs a[href="#tab_1"]').tab('show');
			pengeluaranTable.columns.adjust().draw();
		}

		function reset_form_pengeluaran_detail() {
			// $('#kd_barang').val('');
			$('#harga').val('');
			$('#jumlah').val('');
			$('#nm_barang').html('');
			$('#nm_satuan').html('');
			$('#deskripsi').html('');

			$('#id_satuan').val('');
			$('#gambar').val('');
			$('#kode_barang').val('');
			$('#kode_barang_view').html('');
			$('#id_merek').val('');
			$('#merek_barang_view').html('');
			$('#id_pengeluaran_detail').val('');
			$('#photo-preview').html('');
		}

		function DetailData(id_pengeluaran, tanggal){
			$('#kd_barang').val('');
			reset_form_pengeluaran_detail();

			$('#id_pengeluaran_header').val(id_pengeluaran);
			$('#tanggal_pengeluaran').val(tanggal);
			$('.nav-tabs a[href="#tab_2"]').tab('show');
			pengeluaranTableDetail.ajax.reload();
			pengeluaranTableDetail.columns.adjust().draw();
		}

		function tampilDataBarang(kd_barang) {
			reset_form_pengeluaran_detail();

			$.ajax({
				type : "POST",
				dataType : "json",
				data : ({id_stock:kd_barang}),
				url : '<?=base_url()?>pengeluaran/ax_get_barang',
				success : function(data){
					$.each(data,function(a,b){
						$('#nm_barang').html(b["nm_barang"]);
						$('#nm_satuan').html(b["nm_satuan"]);
						$('#id_satuan').val(b["id_satuan"]);
						$('#deskripsi').html(b["deskripsi"]);
						$('#harga').val(b["harga"]);
						$('#gambar').val(b["gambar"]);
						$('#kode_barang').val(b["kd_barang"]);
						$('#kode_barang_view').html(b["kd_barang"]);
						$('#id_merek').val(b["id_merek"]);
						$('#merek_barang_view').html(b["nm_merek"]);
						$('#jumlah').val(b["jumlah"]);

						$('#jumlah').html(b["kd_barang"]);					

						if(b["gambar"])
						{
							$('#photo-preview').html('<a href="'+base_url+'uploads/master/barang/'+b['gambar']+'" target="_blank"><img src="'+base_url+'uploads/master/barang/'+b['gambar']+'" class="img-responsive" height="100px" width="100px"></a>');
						}
						else
						{
							$('#photo-preview').text('(No photo)');
						}
					});
				}
			});
		}

		function editDetail(id_pengeluaran_detail)
		{
			reset_form_pengeluaran_detail();

			$.ajax({
				url : "<?php echo site_url('pengeluaran/ax_get_data_by_id_pengeluaran_detail')?>",
				type: "POST",
				data :{id_pengeluaran_detail: id_pengeluaran_detail},
				dataType: "JSON",
				success: function(data)
				{

					$('#kd_barang').val(data['kd_barang']);
					$('#nm_barang').html(data["nm_barang"]);
					$('#nm_satuan').html(data["nm_satuan"]);
					$('#deskripsi').html(data["deskripsi"]);

					$('#harga').val(data["harga"]);
					$('#jumlah').val(data["jumlah"]);
					$('#id_satuan').val(data["id_satuan"]);
					$('#gambar').val(data["gambar"]);
					$('#id_pengeluaran_detail').val(id_pengeluaran_detail);
					$('#kode_barang').val(data["kd_barang"]);
					$('#kode_barang_view').html(data["kd_barang"]);
					$('#id_merek').val(data["id_merek"]);
					$('#merek_barang_view').html(data["nm_merek"]);


					if(data["gambar"])
					{
						$('#photo-preview').html('<a href="'+base_url+'uploads/master/barang/'+data['gambar']+'" target="_blank"><img src="'+base_url+'uploads/master/barang/'+data['gambar']+'" class="img-responsive" height="100px" width="100px"></a>');
					}
					else
					{
						$('#photo-preview').text('(No photo)');
					}
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					alert('Error get data from ajax');
				}
			});
		}

		function hapusDetail(id_pengeluaran_detail){
			alertify.confirm(
				'Konfirmasi', 
				'Apa anda yakin akan menghapus data ini?', 
				function(){
					var url = '<?=base_url()?>pengeluaran/ax_unset_data_pengeluaran_detail';
					var data = {
						id_pengeluaran_detail: id_pengeluaran_detail
					};
					$.ajax({
						url: url,
						method: 'POST',
						data: data
					}).done(function(data, textStatus, jqXHR) {

						var data = JSON.parse(data);
						if(data['status'] == "1"){
							alertify.success("Data Terhapus.");
							pengeluaranTableDetail.ajax.reload();
						}else{
							alertify.error("Data Gagal Terhapus.");
							pengeluaranTableDetail.ajax.reload();
						}
					});
				},
				function(){ }
				);
		}

		function CheckOut(id_pengeluaran, active, status){
			alertify.confirm(
				'Confirmation', 
				'Apakah anda yakin ingin Checkout '+status+' ?', 
				function(){
					var url = '<?=base_url()?>pengeluaran/ax_change_active';
					var data = {
						id_pengeluaran: id_pengeluaran,
						active : active
					};
					$.ajax({
						url: url,
						method: 'POST',
						data: data,
						statusCode: {
							500: function() {
								alertify.alert("Warning","Data Tidak Berhasil di Setting");
							}
						}
					}).done(function(data, textStatus, jqXHR) {
						var data = JSON.parse(data);

						if(data['status']==true)
						{
							alertify.success("Data Berhasil diubah status ke "+status);
							pengeluaranTable.ajax.reload();
						}

							// alertify.success('CheckOut Setoran Berhasil.');
						});
				},
				function(){ }
				);

		}

		function cariBarang(){
			$('#modalBarang').modal('show');
			dataBarangTable.ajax.reload();
			setTimeout(function(){ dataBarangTable.columns.adjust().draw(); }, 1000);
		}

		function ambilBarang(id_stock) {
			$('#kd_barang').val(id_stock);
			$('#modalBarang').modal('hide');
			tampilDataBarang(id_stock);
		}

		function formatNumber(num) {
			return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
		}

		function armadalist(){
			$.ajax({
				type: "POST", 
				url: "<?= base_url() ?>pengeluaran/ax_get_armada", 
				data: {id_cabang : $("#id_bu_filter").val()}, 
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

		function printPengeluran($format){
			var url     = "<?= base_url() ?>report/laporan_pengeluaran_BBK/";
			var id_bu = $('#id_bu_filter').val();
			var format = $format;
			window.open(url+"?&id_bu="+id_bu+"&format="+format, '_blank');
			window.focus();
		}

	</script>
</body>
</html>
