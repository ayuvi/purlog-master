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
				<h1>Penerimaan</h1>
			</section>
			<section class="invoice">
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<button class="btn btn-primary" onclick='ViewData(0)'>
									<i class='fa fa-plus'></i> Add Penerimaan
								</button>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="form-group col-lg-12">
										<label>Cabang</label>
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
								<div class="nav-tabs-custom">
									<ul class="nav nav-tabs">
										<li class="active disabled"><a href="#tab_1" class="disabled" data-toggle="tab" aria-expanded="true">List Penerimaan</a></li>
										<li class=" disabled"><a href="#tab_2" class="disabled" aria-expanded="false">Penerimaan Detail</a></li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="tab_1">
											<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
															<h4 class="Form-add-bu" id="addModalLabel">Form Add Penerimaan</h4>
														</div>
														<div class="modal-body">
															<input type="hidden" id="id_penerimaan" name="id_penerimaan" value='' />
															<div class="form-group">
																<label>No. Pengiriman</label>
																<input type="text" id="no_pengiriman" name="no_pengiriman" class="form-control" placeholder="No. Pengiriman">
															</div>
															<div class="form-group">
																<label>ID Pemesanan</label>
																<input type="text" id="id_pemesanan" name="id_pemesanan" class="form-control" placeholder="ID Pemesanan" onkeyup="tampilDataPemesanan(this.value)">
																<small><font color="blue"><b>* Silahkan Input ID Pemesanan</b></font></small>
															</div>
															<div class="form-group">
																<label>Supplier</label>
																<input type="text" id="supplier" name="supplier" class="form-control" placeholder="Supplier" readonly>
																<input type="hidden" id="id_supplier" name="id_supplier">
															</div>
															<div class="form-group">
																<label>Cabang</label>
																<input type="text" id="cabang" name="cabang" class="form-control" placeholder="Cabang" readonly>
																<input type="hidden" id="id_bu" name="id_bu">
															</div>
															<div class="form-group">
																<label>Note</label>
																<input type="text" id="note" name="note" class="form-control" placeholder="Note" readonly>
															</div>
															<div class="form-group">
																<label>Tanggal</label>
																<input type="text" id="tanggal" name="tanggal" class="form-control" placeholder="Tanggal" readonly>
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
												<table class="table table-striped table-bordered table-hover" id="penerimaanTable">
													<thead>
														<tr>
															<!-- <th width="50px">Action</th> -->
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

												<div class="modal-body">
													<input type="hidden" name="id_penerimaan_header" id="id_penerimaan_header">
													<input type="hidden" name="id_pemesanan_header" id="id_pemesanan_header">
													<div class="dataTable_wrapper">
														<table class="table table-striped table-bordered table-hover" id="penerimaanTableDetail">
															<thead>
																<tr>
																	<th width="50px">Action</th>
																	<th>Detail</th>
																</tr>
															</thead>
															<tfoot id="detail_skor_mhs">
																
															</tfoot>
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
				</section>
			</div>
		</div>
		<?= $this->load->view('basic_js'); ?>
		<script type='text/javascript'>

			var base_url = '<?php echo base_url();?>';

			var penerimaanTable = $('#penerimaanTable').DataTable({
				"ordering" : false,
				"scrollX": true,
				"processing": true,
				"serverSide": true,
				ajax: 
				{
					url: "<?= base_url()?>penerimaan/ax_data_penerimaan/",
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
				// 	data: "id_penerimaan", render: function(data, type, full, meta){
				// 		var id1 = "'"+data+"','"+full['active']+"'";
				// 		var str = '';
				// 		str += '<div class="btn-group">';
				// 		str += '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>';
				// 		str += '<ul class="dropdown-menu">';
				// 		str += '<li><a onclick="DetailData(' + data +',' +full['id_pemesanan']+')"><i class="fa fa-list"></i> Detail</a></li>';
				// 		if(full['active'] ==1){
				// 			str += '<li><a onclick="CheckOut(' + id1 + ',' + "'Pengajuan'"+')"><i class="fa fa-cart-arrow-down"></i> Pengajuan</a></li>';
				// 		}else if(full['active'] ==2){
				// 			str += '<li><a onclick="CheckOut(' + id1 + ',' + "'penerimaan'"+')"><i class="fa fa-sign-out"></i> penerimaan</a></li>';
				// 		}

				// 		str += '<li><a onclick="ViewData(' + data + ')"><i class="fa fa-pencil"></i> Edit</a></li>';
				// 		str += '<li><a onClick="DeleteData(' + data + ')"><i class="fa fa-trash"></i> Delete</a></li>';
				// 		str += '</ul>';
				// 		str += '</div>';
				// 		return str;
				// 	}
				// },
				// { data: "id_penerimaan", render: function(data, type, full, meta){
				// 	var str = '';
				// 	str += `
				// 	<div>
				// 	<strong>(`+full['no_pengiriman']+`) `+full['nm_supplier']+`</strong><br>
				// 	<b>Tanggal</b> : `+full['rdate']+`&emsp;
				// 	<b>ID Pemesanan</b> : `+full['id_pemesanan']+`&emsp;<br>
				// 	<b>Note</b> : `+full['note']+` <br>
				// 	</div>`;
				// 	return str;
				// }}
				data: "id_penerimaan", render: function(data, type, full, meta){
						var id1 = "'"+data+"','"+full['active']+"'";
						var str = '';
						if(full['active'] ==1){
						var stat = 'warning';
						var stat_nm = 'Penerimaan';
						}else{
						var stat = 'success';
						var stat_nm = 'BBM';
					}
						str += '<table>';
						str += '<tr><td width="10px" style="font-size: large;text-align: left;">';
						str += '<div class="btn-group">';
						str += '<div class="btn-group">';
						str += '<button type="button" class="btn btn-info dropdown-toggle float-right" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>';
						str += '<ul class="dropdown-menu">';
						str += '<li><a onclick="DetailData(' + data +',' +full['id_pemesanan']+')"><i class="fa fa-list"></i> Detail</a></li>';
						if(full['active'] ==1){
							str += '<li><a onclick="CheckOut(' + id1 + ',' + "'Pengajuan'"+')"><i class="fa fa-download"></i> BBM</a></li>';
						}else if(full['active'] ==2){
							str += '<li><a onclick="CheckOut(' + id1 + ',' + "'penerimaan'"+')"><i class="fa fa-sign-out"></i> penerimaan</a></li>';
						}

						str += '<li><a onclick="ViewData(' + data + ')"><i class="fa fa-pencil"></i> Edit</a></li>';
						str += '<li><a onClick="DeleteData(' + data + ')"><i class="fa fa-trash"></i> Delete</a></li>';
						str += '</ul>';
						str += '</div><button class="btn btn-'+ stat +'">'+ stat_nm +'</button><button class="btn btn-default">&emsp;<b>#'+full['id_penerimaan'] +'</b></button></div>';
					

						
						str += '</td></tr>';
						
						str += '<tr ><td style="font-size: large; text-align: left;" colspan="2"><b>'+full['nm_supplier']+'&emsp;</b></td></tr>';
						
						str += '<tr ><td style="font-size: medium; text-align: left;" colspan="2"><b>ID Pemesanan</b> : '+full['id_pemesanan']+'&emsp;<b>Pesan</b> : '+full['rdate']+'&emsp;</td></tr>';
						str += '<tr ><td style="font-size: medium; text-align: left;" colspan="2"><b>Note</b> : '+full['note']+'&emsp;</td></tr>';
						str += '</table>';
						return str;
					}
				}
				]
			});

			var penerimaanTableDetail = $('#penerimaanTableDetail').DataTable({
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
					'pageLength', 'copy', 'csv', 'excel', //'pdf', 'print'
					],

					ajax: 
					{
						url: "<?= base_url()?>penerimaan/ax_data_pemesanan_detail/",
						type: 'POST',
						data: function ( d ) {
							return $.extend({}, d, { 
								"id_pemesanan": $("#id_pemesanan_header").val()
							});
						}
					},
					columns: 
					[
					{ data: "id_pemesanan_detail", render: function(data, type, full, meta){
						var str = '';
						str += '<input type="number" class="update_penerimaan form-control" data-id="'+data+'" data-name="data_penerimaan"  style="width: 100px">';
						return str;

					}},
					{ data: "id_pemesanan_detail", render: function(data, type, full, meta){
						var str = '';
						str += `
						<div>
						<strong>(`+full['kd_barang']+`) `+full['nama_barang']+`</strong><br>
						<b>Jml</b> : `+formatNumber(full['jumlah'])+` `+full['nm_satuan']+`&emsp;
						<b>Harga</b> : `+formatNumber(full['harga'])+`&emsp;
						<b>Total</b> : `+formatNumber(full['total'])+`<br>
						<b>Deskripsi</b> : `+full['deskripsi']+` <br>
						<font color="blue"><b>Diterima</b></font> : `+formatNumber(full['diterima'])+`&emsp;<font color="red"><b>Sisa</b></font> : `+formatNumber(full['sisa'])+`
						</div><br>`;
						var oke = data;

						str += "<?php
						 echo $this->model_penerimaan->listPenerimaanDetail(1);
						 ?>";
						return str;
					}}
					]
				});

			function add_tr(id_pemesanan_detail) {

				var url = "<?php echo site_url('penerimaan/listPenerimaanDetail')?>";
				$.ajax({
					url : url,
					type: "POST",
					data: {id_pemesanan_detail:id_pemesanan_detail},
					dataType: "JSON",
					success: function(data)
					{
						detail = '';
						arr=0;
						for (var i = 0; i < data.length; i++) {
							detail += `
							Input data ke `+(arr+=1)+` : `+data[i].jumlah+`<br>
							`;
						}
						return detail;

						// $('#detail_skor_mhs').html(detail);
						// console.log(detail);
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						alert('Error get data!');
					}
				});
			}

			$(document).on('change', '.update_penerimaan', function(){
				var id = $(this).data("id");
				var column_name = $(this).data("name");
				var value = $(this).val();
				update_penerimaan_detail(id,column_name,value);
			});

			function update_penerimaan_detail(id,column_name,value) {
				var dataJson = { 
					id_pemesanan_detail:id, 
					column_name:column_name, 
					value:value, 
					id_penerimaan:$('#id_penerimaan_header').val() 
				};

				$.ajax({
					url:"<?php echo base_url('penerimaan/ax_set_data_detail'); ?>",
					method:"POST",
					data: dataJson,
					dataType: "JSON",
					success:function(data)
					{
						if(data.status==true)
						{
							alertify.success("Data Disimpan.");
							penerimaanTableDetail.ajax.reload();

						}else if(data.status=="melebihisisa"){
							alertify.alert("Warning", "Input data tidak boleh melebihi sisa");
							alertify.error("Data Gagal Disimpan.");
						}else{
							alertify.error("Data Gagal Disimpan.");

						}
					}
				});
				setInterval(function(){
				}, 5000);
			}

			$('#btnSave').on('click', function () {
				if($('#no_pengiriman').val() == '' || $('#id_pemesanan').val() == '')
				{
					alertify.alert("Warning", "Nomor Pengiriman dan ID Pemesanan Tidak Boleh Kosong");
				}else if($('#id_supplier').val() == ''){
					alertify.alert("Warning", "Data ID Pemesanan Tidak Ada Dalam Database Pemesanan");
				}
				else
				{
					var url = '<?=base_url()?>penerimaan/ax_set_data';
					var data = {
						id_penerimaan: $('#id_penerimaan').val(),
						no_pengiriman: $('#no_pengiriman').val(),
						id_pemesanan: $('#id_pemesanan').val(),
						id_supplier: $('#id_supplier').val(),
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
							penerimaanTable.ajax.reload();
						}
					});
				}
			});

			$('#btnSaveDetail').on('click', function () {
				if($('#kd_barang').val() == '0'){
					alertify.alert("Warning", "Cari Barang Belum di Pilih.");
				}else if($('#harga').val() == ''){
					alertify.alert("Warning", "Harga Belum di Isi.");
				}else if($('#jumlah').val() == ''){
					alertify.alert("Warning", "Jumlah Belum di Isi.");
				}else {
					var url = '<?=base_url()?>penerimaan/ax_set_data_detail_XXX';
					var data = {
						id_penerimaan_detail: $('#id_penerimaan_detail').val(),
						id_penerimaan_header: $('#id_penerimaan_header').val(),
						kd_barang: $('#kd_barang').val(),
						nm_barang: $('#nm_barang').val(),
						id_satuan: $('#id_satuan').val(),
						harga: $('#harga').val(),
						jumlah: $('#jumlah').val(),
						gambar: $('#gambar').val(),
						deskripsi: $('#deskripsi').val(),
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
							if(data['status'] == "1")
							{
								alertify.success("Data Disimpan.");
								$('#kd_barang').val(null).trigger('change');
								$('#select2-kd_barang-container').html("-- Data Barang --");
								penerimaanTableDetail.ajax.reload();
							}else{
								alertify.error("Data setoran Gagal Disimpan.");
								penerimaanTableDetail.ajax.reload();
							}
						});
					}
				});

			function ViewData(id_penerimaan)
			{
				if(id_penerimaan == 0)
				{
					$('#addModalLabel').html('Add Penerimaan');
					$('#id_penerimaan').val('');
					$('#no_pengiriman').val('');
					$('#id_pemesanan').val('');
					$('#id_supplier').val('');
					$('#supplier').val('');
					$('#id_bu').val('');
					$('#cabang').val('');
					$('#note').val('');
					$('#tanggal').val('');
					$('#addModal').modal('show');
				}
				else
				{
					var url = '<?=base_url()?>penerimaan/ax_get_data_by_id';
					var data = {
						id_penerimaan: id_penerimaan
					};

					$.ajax({
						url: url,
						method: 'POST',
						data: data
					}).done(function(data, textStatus, jqXHR) {
						var data = JSON.parse(data);
						$('#addModalLabel').html('Edit Penerimaan');
						// $('#id_penerimaan').val(data['id_penerimaan']);
						// $('#id_supplier').val(data['id_supplier']).trigger('change');
						// $('#id_bu').val(data['id_bu']).trigger('change');
						// $('#note').val(data['note']);
						// $('#tanggal').val(data['rdate']);

						$('#id_penerimaan').val(id_penerimaan);
						$('#no_pengiriman').val(data.no_pengiriman);

						$('#id_pemesanan').val(data.id_pemesanan);

						$('#id_supplier').val(data.id_supplier);
						$('#supplier').val(data['nm_supplier']);
						$('#id_bu').val(data.id_bu);
						$('#cabang').val(data.nm_bu);
						$('#note').val(data.note);
						$('#tanggal').val(data.rdate);

						$('#addModal').modal('show');
					});
				}
			}

			function DeleteData(id_penerimaan)
			{
				alertify.confirm(
					'Confirmation', 
					'Are you sure you want to delete this data?', 
					function(){
						var url = '<?=base_url()?>penerimaan/ax_unset_data';
						var data = {
							id_penerimaan: id_penerimaan
						};

						$.ajax({
							url: url,
							method: 'POST',
							data: data
						}).done(function(data, textStatus, jqXHR) {
							var data = JSON.parse(data);
							penerimaanTable.ajax.reload();
							alertify.error('Data deleted.');
						});
					},
					function(){ }
					);
			}

			// $('#id_supplier').select2({
			// 	'placeholder': "--Supplier--",
			// 	'allowClear': true
			// });

			// $('#id_bu_filter').select2({
			// 	'placeholder': "--Cabang--",
			// 	'allowClear': true
			// });

			$( "#tanggal").datepicker({
				changeMonth: true,
				changeYear: true,
				dateFormat: "yy-mm-dd"
			});

			function reloadPemesananTable() {
				penerimaanTable.ajax.reload();
			}

			function closeTab(){
				$('.nav-tabs a[href="#tab_1"]').tab('show');
				penerimaanTable.columns.adjust().draw();
			}

			function reset_form_penerimaan_detail() {
				$('#id_penerimaan_header').val('');
				$('#id_pemesanan_header').val('');
			}

			function DetailData(id_penerimaan, id_pemesanan){
				reset_form_penerimaan_detail();

				$('#id_penerimaan_header').val(id_penerimaan);
				$('#id_pemesanan_header').val(id_pemesanan);
				$('.nav-tabs a[href="#tab_2"]').tab('show');
				penerimaanTableDetail.ajax.reload();
				penerimaanTableDetail.columns.adjust().draw();
			}

			$('#kd_barang').on("change", function (e) {

				reset_form_penerimaan_detail();

				$.ajax({
					type : "POST",
					dataType : "json",
					data : ({kd_barang:$('#kd_barang').val()}),
					url : '<?=base_url()?>penerimaan/ax_get_barang',
					success : function(data){
						$.each(data,function(a,b){
							$('#nm_barang').val(b["nm_barang"]);
							$('#id_satuan').val(b["id_satuan"]);
							$('#nm_satuan').val(b["nm_satuan"]);
							$('#harga').val(b["harga"]);
							$('#deskripsi').val(b["deskripsi"]);
							$('#gambar').val(b["gambar"]);

							if(b["gambar"])
							{
								$('#photo-preview').html('<a href="'+base_url+'uploads/master/barang/'+b['gambar']+'" target="_blank"><img src="'+base_url+'uploads/master/barang/'+b['gambar']+'" class="img-responsive" height="150px" width="150px"></a>');
							}
							else
							{
								$('#photo-preview').text('(No photo)');
							}
						});
					}
				});
			});


			function CheckOut(id_penerimaan, active, status){
				alertify.confirm(
					'Confirmation', 
					'Apakah anda yakin ingin Checkout '+status+' ?', 
					function(){
						var url = '<?=base_url()?>penerimaan/ax_change_active';
						var data = {
							id_penerimaan: id_penerimaan,
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
								penerimaanTable.ajax.reload();
							}

							// alertify.success('CheckOut Setoran Berhasil.');
						});
					},
					function(){ }
					);

			}

			function tampilDataPemesanan(id_pemesanan) {
				$.ajax({
					url : "<?php echo site_url('penerimaan/tampilDataPemesanan')?>",
					type: "POST",
					data :{id_pemesanan: id_pemesanan},
					dataType: "JSON",
					success: function(data)
					{   
						$('#id_pemesanan').val(id_pemesanan);
						$('#id_supplier').val(data.id_supplier);
						$('#supplier').val(data.nm_supplier);
						$('#id_bu').val(data.id_bu);
						$('#cabang').val(data.nm_bu);
						$('#note').val(data.note);
						$('#tanggal').val(data.rdate);
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						$('#id_pemesanan').val('');
						$('#id_supplier').val('');
						$('#supplier').val('');
						$('#id_bu').val('');
						$('#cabang').val('');
						$('#note').val('');
						$('#tanggal').val('');
					}
				});
			}

			function formatNumber(num) {
				return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
			}

		</script>
	</body>
	</html>
