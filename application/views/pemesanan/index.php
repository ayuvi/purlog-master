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
				<h1>Pemesanan</h1>
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
											<option value="3">Pemesanan</option>
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
											<button class="btn btn-success pull-left" onclick='ViewData(0)'>
												<i class='fa fa-print'></i> Print

											</button>
										</div>
									</div>
								</div>
							</div>
							<div class="panel-body">
								
								<div class="nav-tabs-custom">
									<ul class="nav nav-tabs">
										<li class="active disabled"><a href="#tab_1" class="disabled" data-toggle="tab" aria-expanded="true">List Pemesanan</a></li>
										<li class=" disabled"><a href="#tab_2" class="disabled" aria-expanded="false">Pemesanan Detail</a></li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="tab_1">
											<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
															<h4 class="Form-add-bu" id="addModalLabel">Form Add pemesanan</h4>
														</div>
														<div class="modal-body">
															<input type="hidden" id="id_pemesanan" name="id_pemesanan" value='' />
															<input type="hidden" id="id_bu" name="id_bu" value='' />
															<div class="form-group">
																<label>Segment</label>
																<select class="form-control select2" style="width: 100%;" id="id_segment" name="id_segment">
																	<option value="0">--Segment--</option>
																	<?php
																	foreach ($combobox_segment->result() as $rowmenu) {
																		?>
																		<option value="<?= $rowmenu->id_segment?>"  ><?= $rowmenu->nm_segment?></option>
																		<?php
																	}
																	?>
																</select>
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
												<table class="table table-striped table-bordered table-hover" id="pemesananTable">
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
																<h6 class="box-title">Tambah Detail Pemesanan</h6>
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
																		<input type="hidden" id="id_pemesanan_header" name="id_pemesanan_header" class="form-control">
																		<input type="hidden" id="tanggal_pemesanan" name="tanggal_pemesanan" class="form-control">

																		<div class="col-lg-12">
																			<table class="table">
																				<tr>
																					<td colspan="2">
																						<small><b>Kode Barang</b></small>
																						<div class="input-group col-md-12">
																							<input type="text" name="kd_barang" class="form-control" placeholder="Input Kode Barang" id="kd_barang" onkeyup="tampilDataBarang(this.value)">
																							<span class="input-group-btn">
																								<button class="btn btn-info" type="button" onclick="cariBarang()" id="cari_nama"><i class="glyphicon glyphicon-search"></i> Cari Barang</button>
																							</span>
																						</div>
																					</td>
																				</tr>
																				<tr>
																					<td>
																						<small><b>Harga</b></small>
																						<input type="number" id="harga" name="harga" class="form-control" placeholder="Harga">
																					</td>
																					<td>
																						<small><b>Jumlah</b></small>
																						<input type="number" id="jumlah" name="jumlah" class="form-control" placeholder="Jumlah">
																					</td>
																				</tr>
																				<tr>
																					<td colspan="2">
																						<small><b>Merek Barang</b></small>
																						<select class="form-control select2" style="width: 100%" id="id_merek" name="id_merek">
																							<option value="0">- Pilih Merek -</option>
																							<?php
																							foreach ($combobox_merek->result() as $rowmenu) { ?>
																								<option value="<?= $rowmenu->id_merek?>"  ><?= $rowmenu->nm_merek?></option>
																							<?php } ?>
																						</select>
																					</td>
																				</tr>
																				<tr>
																					<td><small><b>Nama Barang</b></small><br><p id="nm_barang"> </p></td>
																				</tr>
																				<tr>
																					<td><small><b>Satuan</b></small><br><p id="nm_satuan"> </p></td>
																				</tr>
																				<tr>
																					<td><small><b>Deskripsi</b></small><br><p id="deskripsi"> </p></td>
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
																					<input type="hidden" id="id_pemesanan_detail" name="id_pemesanan_detail" /> 
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
															<table class="table table-striped table-bordered table-hover" id="pemesananTableDetail">
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
							<h4 class="Form-add-bu" id="barangModalLabel">Form Data Barang</h4>
							<input type="hidden" id="id_lmb_rit" name="id_lmb_rit" class="form-control">
							<input type="hidden" id="armada_rit" name="armada_rit" class="form-control">
						</div>
						<div class="modal-body">
							<div class="dataTable_wrapper">
								<table class="table table-striped table-bordered table-hover" id="dataBarangTable">
									<thead>
										<tr>
											<th>Action</th>
											<th>KD Barang</th>
											<th>Nama Barang</th>
											<th>Kategori</th>
											<th>Satuan</th>
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

	<div class="row" >
		<div class="col-lg-12">
			<div class="modal fade" id="modalEstimasiTanggal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h5 class="Form-add-bu" id="barangModalLabel">Estimasi Tanggal Terima Barang</h5>
							<input type="hidden" id="id_pemesanan_est" name="id_pemesanan_est" class="form-control">
							<input type="hidden" id="active_est" name="active_est" class="form-control">
						</div>
						<div class="modal-body">
							<input type="hidden" id="id_divisi" name="id_divisi" value='' />
							<div class="form-group">
								<label>Tanggal Estimasi Terima</label>
								<input type="text" id="tgl_est" name="tgl_est" class="form-control" placeholder="Tanggal Estimasi Terima" value="<?=date('Y-m-d')?>">
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary" id='btnSaveTglEstimasi'>Save</button>
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

		var pemesananTable = $('#pemesananTable').DataTable({
			"ordering" : false,
			"scrollX": true,
			"processing": true,
			"serverSide": true,
			ajax: 
			{
				url: "<?= base_url()?>pemesanan/ax_data_pemesanan/",
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
				// data: "id_pemesanan", render: function(data, type, full, meta){
				// 	var id1 = "'"+data+"','"+full['active']+"'";
				// 	var str = '';
				// 	str += '<div class="btn-group">';
				// 	str += '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>';
				// 	str += '<ul class="dropdown-menu">';
				// 	str += '<li><a onclick="DetailData(' + data +',' +full['rdate']+')"><i class="fa fa-list"></i> Detail</a></li>';
				// 	if(full['active'] ==1){
				// 		str += '<li><a onclick="CheckOut(' + id1 + ',' + "'Pengajuan'"+')"><i class="fa fa-cart-arrow-down"></i> Pengajuan</a></li>';
				// 	}else if(full['active'] ==2){
				// 		str += '<li><a onclick="CheckOut(' + id1 + ',' + "'Pemesanan'"+')"><i class="fa fa-sign-out"></i> Pemesanan</a></li>';
				// 	}

				// 	str += '<li><a onclick="ViewData(' + data + ')"><i class="fa fa-pencil"></i> Edit</a></li>';
				// 	str += '<li><a onClick="DeleteData(' + data + ')"><i class="fa fa-trash"></i> Delete</a></li>';
				// 	str += '</ul>';
				// 	str += '</div>';
				// 	return str;
				// }

				data: "id_pemesanan", render: function(data, type, full, meta){
					var id1 = "'"+data+"','"+full['active']+"'";
					var str = '';
					if(full['active'] ==1){
						var stat = 'warning';
						var stat_nm = 'Draft';
					}else if(full['active'] ==2){
						var stat = 'primary';
						var stat_nm = 'Pengajuan';
					}else{
						var stat = 'success';
						var stat_nm = 'Pemesanan';
					}
					str += '<table width="100%">';
					str += '<tr><td width="10px" style="font-size: large;text-align: left;">';
					str += '<div class="btn-group">';
					str += '<div class="btn-group">';
					str += '<button type="button" class="btn btn-info dropdown-toggle float-right" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>';
					str += '<ul class="dropdown-menu">';
					str += '<li><a onclick="DetailData(' + data +',' +full['rdate']+')"><i class="fa fa-list"></i> Detail</a></li>';
					if(full['active'] ==1){
						str += '<li><a onclick="CheckOut(' + id1 + ',' + "'Pengajuan'"+')"><i class="fa fa-cart-arrow-down"></i> Pengajuan</a></li>';
					}else if(full['active'] ==2){
						str += '<li><a onclick="ModalEstimasi(' + id1 + ',' + "'Pemesanan'"+')"><i class="fa fa-sign-out"></i> Pemesanan</a></li>';
					}

					if(full['active'] ==1 || full['active'] ==2){
						str += '<li><a onclick="ViewData(' + data + ')"><i class="fa fa-pencil"></i> Edit</a></li>';
						str += '<li><a onClick="DeleteData(' + data + ')"><i class="fa fa-trash"></i> Delete</a></li>';
					}
					str += '<li><a onclick="printLaporanPengajuan(' + "'0'" + ',' + data+')"><i class="fa fa-print"></i> Print Pengajuan</a></li>';
					str += '<li><a onclick="printLaporan(' + "'0'" + ',' + data+')"><i class="fa fa-print"></i> Print Pemesanan</a></li>';
					str += '</ul>';
					str += '</div><button class="btn btn-'+ stat +'">'+ stat_nm +'</button><button class="btn btn-default">&emsp;<b>#'+full['id_pemesanan'] +'</b></button></div>';

					str += '</td></tr>';

					str += '<tr ><td style="font-size: large; text-align: left;" colspan="2"><b>'+full['nm_supplier']+'&emsp;</b></td></tr>';

					str += '<tr ><td style="font-size: medium; text-align: left;" colspan="2"><b>ID Pemesanan</b> : '+data+'&emsp;<b>Pesan</b> : '+full['rdate']+'&emsp;</td></tr>';
					str += '<tr ><td style="font-size: medium; text-align: left;" colspan="2"><b>Note</b> : '+full['note']+'&emsp;</td></tr>';
					str += '</table>';
					return str;
				}
			}
			]
		});

var pemesananTableDetail = $('#pemesananTableDetail').DataTable({
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
		url: "<?= base_url()?>pemesanan/ax_data_pemesanan_detail/",
		type: 'POST',
		data: function ( d ) {
			return $.extend({}, d, { 
				"id_pemesanan": $("#id_pemesanan_header").val()
			});
		}
	},
	columns: 
	[
			// { data: "id_pemesanan_detail", render: function(data, type, full, meta){
			// 	var str = '';
			// 	str += '<div class="btn-group">';
			// 	str += '<a type="button" class="btn btn-sm btn-primary" title="Edit" onclick="editDetail(' + data + ')"><i class="fa fa-pencil"></i> </a>';
			// 	str += '<a type="button" class="btn btn-sm btn-danger" title="Delete" onclick="hapusDetail(' + data + ')"><i class="fa fa-trash"></i> </a>';

			// 	str += '</div>';
			// 	return str;
			// }},
			{ data: "id_pemesanan_detail", render: function(data, type, full, meta){
				var nama_merek = '';
				if(full['nm_merek'] != null){
					nama_merek = ' - '+full['nm_merek'];
				}else{
					nama_merek = '';
				}
				var str = '';
				str += `
				<div>
				<div class="btn-group">
				<a type="button" class="btn btn-sm btn-info" title="Edit" onclick="editDetail(` + data + `)"><i class="fa fa-pencil"></i> </a>
				<a type="button" class="btn btn-sm btn-danger" title="Delete" onclick="hapusDetail(` + data + `)"><i class="fa fa-trash"></i> </a>
				<a type="button" class="btn btn-sm btn-default">#`+data+`</a>
				</div><br>
				<strong>(`+full['kd_barang']+`) `+full['nm_barang']+`</strong>`+nama_merek+`<br>
				<b>Jml</b> : `+formatNumber(full['jumlah'])+` `+full['nm_satuan']+`&emsp;
				<b>Harga</b> : `+formatNumber(full['harga'])+`&emsp;
				<b>Total</b> : `+formatNumber(full['total'])+`<br>
				<b>Deskripsi</b> : `+full['deskripsi']+` 
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
		url: "<?= base_url()?>pemesanan/ax_data_barang/",
		type: 'POST',
		data: function ( d ) {
			return $.extend({}, d, { 
				"id_pemesanan": $("#id_pemesanan_header").val()
			});
		}
	},
	columns: 
	[
	{ data: "id_barang", render: function(data, type, full, meta){
		var str = '';
		var kd_barang = "'"+full['kd_barang']+"'";

		str += '<div class="btn-group">';
		str += '<a type="button" class="btn btn-sm btn-default" onclick="ambilBarang(' + kd_barang + ')"><i class="fa fa-cloud-download"></i> Ambil</a>';

		str += '</div>';
		return str;
	}},
	{ data: "kd_barang" },
	{ data: "nm_barang" },
	{ data: "nm_kategori" },
	{ data: "nm_satuan" }

	]
});

$('#btnSave').on('click', function () {
	if($('#id_supplier').val() == null || $('#id_segment').val() == null)
	{
		alertify.alert("Warning", "Isi Semua data.. Data tidak boleh ada yang kosong");
	}
	else
	{
		var url = '<?=base_url()?>pemesanan/ax_set_data';
		var data = {
			id_pemesanan: $('#id_pemesanan').val(),
			id_supplier: $('#id_supplier').val(),
			id_bu: $('#id_bu').val(),
			note: $('#note').val(),
			tanggal: $('#tanggal').val(),
			id_segment: $('#id_segment').val(),
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
				pemesananTable.ajax.reload();
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
	}else if($('#id_merek').val() == '0'){
		alertify.alert("Warning", "Merek Barang Belum di Pilih.");
	}else if($('#id_satuan').val() == ''){
		alertify.alert("Warning", "Kode Barang atau Data Barang tidak ada di dalam database");
	}else {
		var url = '<?=base_url()?>pemesanan/ax_set_data_detail';
		var data = {
			id_pemesanan_detail: $('#id_pemesanan_detail').val(),
			id_pemesanan_header: $('#id_pemesanan_header').val(),
			kd_barang: $('#kd_barang').val(),
			id_merek: $('#id_merek').val(),
			harga: $('#harga').val(),
			jumlah: $('#jumlah').val(),
			id_bu: $('#id_bu_filter').val(),
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
					pemesananTableDetail.ajax.reload();
					reset_form_pemesanan_detail();

				}else{
					alertify.error("Data setoran Gagal Disimpan.");
					pemesananTableDetail.ajax.reload();
					reset_form_pemesanan_detail();
				}
			});
		}
	});

$('#btnSaveTglEstimasi').on('click', function () {
	if($('#tgl_est').val() == ''){
		alertify.alert("Warning", "Tanggal Estimasi Belum di Isi.");
	}else {
		var url = '<?=base_url()?>pemesanan/ax_set_data_tgl_estimasi';
		var data = {
			id_pemesanan: $('#id_pemesanan_est').val(),
			active: $('#active_est').val(),
			tgl_pemesanan: $('#tgl_est').val(),
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
					alertify.success("Data Disimpan dan diubah status ke Pemesanan");
					$('#modalEstimasiTanggal').modal('hide');
					pemesananTable.ajax.reload();

				}else{
					alertify.error("Data setoran Gagal Disimpan.");
					pemesananTable.ajax.reload();
				}
			});
		}
	});

function ViewData(id_pemesanan)
{
	$('#tanggal').val($('#tanggal_filter').val());

	if(id_pemesanan == 0)
	{
		$('#addModalLabel').html('Add Pemesanan');
		$('#id_pemesanan').val('');
		$('#id_supplier').val('').trigger('change');
		$('#id_segment').val('').trigger('change');
		$('#id_bu').val($('#id_bu_filter').val());
		$("#tanggal").css('pointer-events', 'none');
		$('#note').val('');
		$('#addModal').modal('show');
	}
	else
	{
		var url = '<?=base_url()?>pemesanan/ax_get_data_by_id';
		var data = {
			id_pemesanan: id_pemesanan
		};

		$.ajax({
			url: url,
			method: 'POST',
			data: data
		}).done(function(data, textStatus, jqXHR) {
			var data = JSON.parse(data);
			$('#addModalLabel').html('Edit Pemesanan');
			$('#id_pemesanan').val(data['id_pemesanan']);
			$('#id_supplier').val(data['id_supplier']).trigger('change');
			$('#id_segment').val(data['id_segment']).trigger('change');
			$('#id_bu').val(data['id_bu']);
			$('#note').val(data['note']);
			$('#tanggal').val(data['rdate']);
			$('#addModal').modal('show');
		});
	}
}

function DeleteData(id_pemesanan)
{
	alertify.confirm(
		'Confirmation', 
		'Are you sure you want to delete this data?', 
		function(){
			var url = '<?=base_url()?>pemesanan/ax_unset_data';
			var data = {
				id_pemesanan: id_pemesanan
			};

			$.ajax({
				url: url,
				method: 'POST',
				data: data
			}).done(function(data, textStatus, jqXHR) {
				var data = JSON.parse(data);
				pemesananTable.ajax.reload();
				alertify.error('Data deleted.');
			});
		},
		function(){ }
		);
}

$('#id_supplier').select2({
	'placeholder': "--Supplier--",
	'allowClear': true
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


$('#id_bu_filter').select2({
	'allowClear': true
}).on("change", function (e) {
	pemesananTable.ajax.reload();
});

$( "#status_filter" ).on("change", function (e) {
	pemesananTable.ajax.reload();
});

$( "#tanggal_filter" ).on("change", function (e) {
	pemesananTable.ajax.reload();
});	

$( "#tanggal_filter").datepicker({
	changeMonth: true,
	changeYear: true,
	dateFormat: "yy-mm-dd"
});
$( "#tgl_est").datepicker({
	changeMonth: true,
	changeYear: true,
	dateFormat: "yy-mm-dd"
});

function closeTab(){
	$('.nav-tabs a[href="#tab_1"]').tab('show');
	pemesananTable.columns.adjust().draw();
}

function reset_form_pemesanan_detail() {
			// $('#kd_barang').val('');
			$('#harga').val('');
			$('#harga').val('');
			$('#jumlah').val('');
			$('#id_merek').val('0').trigger('change');
			$('#nm_barang').html('');
			$('#nm_satuan').html('');
			$('#deskripsi').html('');

			$('#id_satuan').val('');
			$('#gambar').val('');
			$('#id_pemesanan_detail').val('');
			$('#photo-preview').html('');
		}

		function DetailData(id_pemesanan, tanggal){
			$('#kd_barang').val('');
			reset_form_pemesanan_detail();

			$('#id_pemesanan_header').val(id_pemesanan);
			$('#tanggal_pemesanan').val(tanggal);
			$('.nav-tabs a[href="#tab_2"]').tab('show');
			pemesananTableDetail.ajax.reload();
			pemesananTableDetail.columns.adjust().draw();
		}

		function tampilDataBarang(kd_barang) {
			reset_form_pemesanan_detail();

			$.ajax({
				type : "POST",
				dataType : "json",
				data : ({kd_barang:kd_barang}),
				url : '<?=base_url()?>pemesanan/ax_get_barang',
				success : function(data){
					$.each(data,function(a,b){
						$('#nm_barang').html(b["nm_barang"]);
						$('#nm_satuan').html(b["nm_satuan"]);
						$('#id_satuan').val(b["id_satuan"]);
						$('#deskripsi').html(b["deskripsi"]);
						$('#harga').val(b["harga"]);
						$('#gambar').val(b["gambar"]);

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

		function editDetail(id_pemesanan_detail)
		{
			reset_form_pemesanan_detail();

			$.ajax({
				url : "<?php echo site_url('pemesanan/ax_get_data_by_id_pemesanan_detail')?>",
				type: "POST",
				data :{id_pemesanan_detail: id_pemesanan_detail},
				dataType: "JSON",
				success: function(data)
				{

					$('#kd_barang').val(data['kd_barang']);
					$('#id_merek').val(data['id_merek']).trigger('change');
					$('#nm_barang').html(data["nm_barang"]);
					$('#nm_satuan').html(data["nm_satuan"]);
					$('#deskripsi').html(data["deskripsi"]);

					$('#harga').val(data["harga"]);
					$('#jumlah').val(data["jumlah"]);
					$('#id_satuan').val(data["id_satuan"]);
					$('#gambar').val(data["gambar"]);
					$('#id_pemesanan_detail').val(id_pemesanan_detail);

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

		function hapusDetail(id_pemesanan_detail){
			alertify.confirm(
				'Konfirmasi', 
				'Apa anda yakin akan menghapus data ini?', 
				function(){
					var url = '<?=base_url()?>pemesanan/ax_unset_data_pemesanan_detail';
					var data = {
						id_pemesanan_detail: id_pemesanan_detail
					};
					$.ajax({
						url: url,
						method: 'POST',
						data: data
					}).done(function(data, textStatus, jqXHR) {

						var data = JSON.parse(data);
						if(data['status'] == "1"){
							alertify.success("Data Terhapus.");
							pemesananTableDetail.ajax.reload();
						}else{
							alertify.error("Data Gagal Terhapus.");
							pemesananTableDetail.ajax.reload();
						}
					});
				},
				function(){ }
				);
		}

		function CheckOut(id_pemesanan, active, status){
			alertify.confirm(
				'Confirmation', 
				'Apakah anda yakin ingin Checkout '+status+' ?', 
				function(){
					var url = '<?=base_url()?>pemesanan/ax_change_active';
					var data = {
						id_pemesanan: id_pemesanan,
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
							pemesananTable.ajax.reload();
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

		function ambilBarang(kd_barang) {
			$('#kd_barang').val(kd_barang);
			$('#modalBarang').modal('hide');
			tampilDataBarang(kd_barang);
		}

		function formatNumber(num) {
			return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
		}

		function ModalEstimasi(id_pemesanan, active, status){
			$('#id_pemesanan_est').val(id_pemesanan);
			$('#active_est').val(active);
			$('#modalEstimasiTanggal').modal('show');
		}

		function printLaporan(format,id_pemesanan){
			var url     = "<?= base_url() ?>report/laporan_pemesananan/";
			var id_bu = $('#id_bu_filter').val();
			var format = format;
			var id 		= id_pemesanan;
			window.open(url+"?id_bu="+id_bu+"&format="+format+"&id="+id, '_blank');
			window.focus();
		}

		function printLaporanPengajuan(format,id_pemesanan){
			var url     = "<?= base_url() ?>report/laporan_pengajuan/";
			var id_bu 	= $('#id_bu_filter').val();
			var format 	= format;
			var id 		= id_pemesanan;
			window.open(url+"?id_bu="+id_bu+"&format="+format+"&id="+id, '_blank');
			window.focus();
		}

	</script>
</body>
</html>
