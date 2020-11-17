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
				<h1>Barang</h1>
			</section>
			<section class="invoice">
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<button class="btn btn-primary" onclick='ViewData()'>
									<i class='fa fa-plus'></i> Add Barang
								</button>
								<div class="modal fade" id="addModal" tabindex="" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-lg">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="Form-add-bu" id="addModalLabel">Form Add Barang</h4>
											</div>
											<div class="modal-body">
												<form action="#" id="form">
													<input type="hidden" id="id_barang" name="id_barang" value='' />

													<div class="col-lg-6">
														<div class="form-group">
															<label>Kode</label>
															<input type="text" id="kd_barang" name="kd_barang" class="form-control" placeholder="Kode Barang">
														</div>
														<div class="form-group">
															<label>Kategori</label>
															<select class="form-control select2" style="width: 100%;" id="id_kategori" name="id_kategori">
																<option disabled selected value="0">--Kategori--</option>
																<?php
																foreach ($combobox_kategori->result() as $rowmenu) {
																	?>
																	<option value="<?= $rowmenu->id_kategori?>"  ><?= $rowmenu->nm_kategori?></option>
																	<?php
																}
																?>
															</select>
														</div>
														<div class="form-group">
															<label>Harga</label>
															<input type="number" id="harga" name="harga" class="form-control" placeholder="Harga">
														</div>
														<div class="form-group">
															<label id="label-photo">Gambar</label>
															<input type="file" id="gambar" name="gambar" class="form-control" placeholder="Gambar">
															<small><font color="blue"><b>* File Type : png| jpg| jpeg <i>(max size :200 Kb)</i></b></font></small>
															<span class="help-block"></span>
														</div>
														<center>
															<div>
																<div class="form-group" id="photo-preview">
																	(No Photo)
																	<span class="help-block"></span>
																</div>
															</div>
														</center>


													</div>
													<div class="col-lg-6">
														<div class="form-group">
															<label>Nama</label>
															<input type="text" id="nm_barang" name="nm_barang" class="form-control" placeholder="Nama">
														</div>
														<div class="form-group">
															<label>Satuan</label>
															<select class="form-control select2" style="width: 100%;" id="id_satuan" name="id_satuan">
																<option disabled selected value="0">--Satuan--</option>
																<?php
																foreach ($combobox_satuan->result() as $rowmenu) {
																	?>
																	<option value="<?= $rowmenu->id_satuan?>"  ><?= $rowmenu->nm_satuan?></option>
																	<?php
																}
																?>
															</select>
														</div>
														<div class="form-group">
															<label>Minimal Stok</label>
															<input type="number" id="min_stok" name="min_stok" class="form-control" placeholder="Minimal Stok">
														</div>
														<div class="form-group">
															<label>Active</label>
															<select class="form-control" id="active" name="active">
																<option value="1" <?php echo set_select('myselect', '1', TRUE); ?> >Active</option>
																<option value="0" <?php echo set_select('myselect', '0'); ?> >Not Active</option>
															</select>
														</div>
														<div class="form-group">
															<label>Deskripsi</label>
															<textarea id="deskripsi" name="deskripsi" rows="4" cols="50" class="form-control"></textarea>
														</div>
													</div>
												</form>
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
												<th>Kode</th>
												<th>Barang</th>
												<th>Kategori</th>
												<th>Satuan</th>
												<th>Harga</th>
												<th>Min.Stok</th>
												<th>Status</th>
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

		var save_method;
		var base_url = '<?php echo base_url();?>';

		$(document).ready(function() {

			document.getElementById('kd_barang').onkeydown = function (e) {
				var value =  e.target.value;
				if (!e.key.match(/[a-zA-Z0-9,]/) || (e.key == ',' && value[value.length-1] == ',')) {
					e.preventDefault();  
				}
			};

		});

		var buTable = $('#buTable').DataTable({
			"ordering" : false,
			"scrollX": true,
			"processing": true,
			"serverSide": true,
			ajax: 
			{
				url: "<?= base_url()?>barang/ax_data_barang/",
				type: 'POST'
			},
			columns: 
			[
			{
				data: "id_barang", render: function(data, type, full, meta){
					var str = '';
					str += '<div class="btn-group">';
					str += '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>';
					str += '<ul class="dropdown-menu">';
					str += '<li><a onclick="EditData(' + data + ')"><i class="fa fa-pencil"></i> Edit</a></li>';
					str += '<li><a onClick="DeleteData(' + data + ')"><i class="fa fa-trash"></i> Delete</a></li>';
					str += '</ul>';
					str += '</div>';
					return str;
				}
			},

			{ data: "kd_barang" },
			{ data: "nm_barang" },
			{ data: "nm_kategori" },
			{ data: "nm_satuan" },
			{ data: "harga", render: $.fn.dataTable.render.number( ',', '.',0 ) },
			{ data: "min_stok", render: $.fn.dataTable.render.number( ',', '.',0 ) },

			{ data: "active", render: function(data, type, full, meta){
				if(data == 1)
					return '<span class="label label-primary">Active</span>';
				else return '<span class="label label-warning">Not Active</span>';
			}
		}
		]
	});

		$('#btnSave').on('click', function ()
		{
			var url;

			if(save_method == 'add') {
				url = "<?php echo site_url('barang/ax_set_data')?>";
			} else {
				url = "<?php echo site_url('barang/ax_set_data_update')?>";
			}

			if($('#kd_barang').val() == '' || $('#nm_barang').val() == '' || $('#id_kategori').val() == null|| $('#id_satuan').val() == null || $('#harga').val() == ''|| $('#min_stok').val() == '' || $('#active').val() == null)
			{
				alertify.alert("Warning", "Isi semua data");
			}
			else
			{
				var formData = new FormData($('#form')[0]);
				$.ajax({
					url : url,
					type: "POST",
					data: formData,
					contentType: false,
					processData: false,
					dataType: "JSON",
					statusCode: {
						500: function() {
							alertify.alert("Warning","Data Duplicate");
						}
					},
					success: function(data)
					{

						if(data.status == true){
							alertify.success("Data Saved.");
							$('#addModal').modal('hide');
							buTable.ajax.reload();
						}else{
							alertify.error(data.message);
							alertify.alert("Warning", data.message);
						}

					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						alertify.alert("Warning", "Error Add data");

					}
				});
			}
		});

		function ViewData()
		{
			save_method = 'add';
			$('#form')[0].reset(); 
			$('#addModal').modal('show'); 
			$('#addModalLabel').html('Add barang');
			$('#photo-preview').hide();
			$('#label-photo').text('Upload Gambar'); 
		}

		function DeleteData(id_barang)
		{
			alertify.confirm(
				'Confirmation', 
				'Are you sure you want to delete this data?', 
				function(){
					var url = '<?=base_url()?>barang/ax_unset_data';
					var data = {
						id_barang: id_barang
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


		function EditData(id_barang)
		{
			save_method = 'update';
			$('#form')[0].reset();
			$('#photo-preview').html('');

			$.ajax({
				url : "<?php echo site_url('barang/ax_get_data_by_id')?>",
				type: "POST",
				data :{id_barang: id_barang},
				dataType: "JSON",
				success: function(data)
				{

					$('#addModalLabel').html('Edit barang');
					$('#id_barang').val(data['id_barang']);
					$('#kd_barang').val(data['kd_barang']);
					$('#nm_barang').val(data['nm_barang']);
					$('#id_kategori').val(data['id_kategori']).trigger('change');
					$('#id_satuan').val(data['id_satuan']).trigger('change');

					$('#harga').val(data['harga']);
					$('#min_stok').val(data['min_stok']);
					$('#active').val(data['active']);
					$('#deskripsi').val(data.deskripsi);

					$('#photo-preview').show();


					if(data.gambar)
					{
						$('#label-photo').text('Change Photo'); 
						$('#photo-preview').html('<a href="'+base_url+'uploads/master/barang/'+data['gambar']+'" target="_blank"><img src="'+base_url+'uploads/master/barang/'+data['gambar']+'" class="img-responsive" height="100px" width="100px"></a>'); 
						$('#photo-preview').append('<input type="checkbox" name="remove_photo" value="'+data['gambar']+'"/> Remove photo when saving'); 
					}
					else
					{
						$('#label-photo').text('Upload Photo ya');
						$('#photo-preview div').text('(No photo)');
					}
					$('#addModal').modal('show'); 
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					alert('Error get data from ajax');
				}
			});
		}

		$('#id_kategori').select2({
			'placeholder': "--Kategori--",
			'allowClear': true
		});

		$('#id_satuan').select2({
			'placeholder': "--Satuan--",
			'allowClear': true
		});
	</script>
</body>
</html>
