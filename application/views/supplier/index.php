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
				<h1>Supplier</h1>
			</section>
			<section class="invoice">
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<button class="btn btn-primary" onclick='ViewData(0)'>
									<i class='fa fa-plus'></i> Add Supplier
								</button>
								<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="Form-add-bu" id="addModalLabel">Form Add</h4>
											</div>
											<div class="modal-body">
												<!-- <form action="#" id="form" role="form"> -->
													<input type="hidden" id="id_supplier" name="id_supplier" value='' />
													<input type="hidden" id="id_perusahaan" name="id_perusahaan" value='77' />


													<div class="form-group">
														<label>Nama</label>
														<input type="text" id="nm_supplier" name="nm_supplier" class="form-control" placeholder="Nama Supplier">
													</div>

													<div class="form-group">
														<label>Alamat </label>
														<input type="text" id="alamat_supplier" name="alamat_supplier" class="form-control" placeholder="Alamat supplier">
													</div>

													<div class="form-group">
														<label>kategori</label>
														<select class="form-control select2" style="width: 100%;" id="id_kategori_supplier" name="id_kategori_supplier">
															<option value="0">-- Pilih kategori --</option>
															<?php foreach ($kategori_combobox->result() as $rowmenu) { ?>
																<option value="<?= $rowmenu->id_supplier_kategori?>"  ><?= $rowmenu->nm_supplier_kategori?></option>
															<?php } ?>
														</select>
													</div>
													<div class="form-group">
														<label>PIC </label>
														<input type="text" id="pic_supplier" name="pic_supplier" class="form-control" placeholder="PIC">
													</div>
													<div class="form-group">
														<label>Telpon </label>
														<input type="text" id="tlp_supplier" name="tlp_supplier" class="form-control" placeholder="Telp supplier">
													</div>
													<div class="form-group">
														<label>Email </label>
														<input type="text" id="email_supplier" name="email_supplier" class="form-control" placeholder="Email">
													</div>
													<div class="form-group">
														<label>Kota </label>
														<input type="text" id="kota_supplier" name="kota_supplier" class="form-control" placeholder="Kota">
													</div>
													<div class="form-group">
														<label>Kodepos </label>
														<input type="text" id="kodepos_supplier" name="kodepos_supplier" class="form-control" placeholder="Kodepos">
													</div>

													<div class="form-group">
														<label>Active</label>
														<select class="form-control select2" style="width: 100%;" id="active" name="active">
															<option value="1" >Active</option>
															<option value="0" >Not Active</option>
														</select>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
														<button type="button" class="btn btn-primary" id='btnSave'>Save</button>
													</div>
													<!-- </form> -->
												</div>
											</div>
										</div>
									</div>
									<div class="panel-body">
										<div class="row">
											<div class="form-group col-md-6">
												<label>Kelompok</label>
												<select class="form-control select2" style="width: 100%;" id="id_supplier_kategori_filter" name="id_supplier_kategori_filter">
													<option value="0">-- All --</option>
													<?php foreach ($kategori_combobox->result() as $rowmenu) { ?>
														<option value="<?= $rowmenu->id_supplier_kategori?>"  ><?= $rowmenu->nm_supplier_kategori?></option>
													<?php } ?>
												</select>
											</div>
										</div>
										<div class="dataTable_wrapper">
											<table class="table table-striped table-bordered table-hover" id="buTable">
												<thead>
													<tr>
														<th>Options</th>
														<th>Nama</th>
														<th>Alamat</th>
														<th>Kategori</th>
														<th>PIC</th>
														<th>Email</th>
														<th>Telp</th>
														<th>Kota</th>
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
				var buTable = $('#buTable').DataTable({
					"ordering" : false,
					"scrollX": true,
					"processing": true,
					"serverSide": true,
					ajax: 
					{
						url: "<?= base_url()?>supplier/ax_data_supplier/",
						type: 'POST',
						data: function ( d ) {
							return $.extend({}, d, { 
								"kategori": $("#id_supplier_kategori_filter").val()
							});
						}
					},
					columns: 
					[
					{
						data: "id_supplier", render: function(data, type, full, meta){
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

					{ data: "nm_supplier" },
					{ data: "alamat_supplier" },
					{ data: "nm_supplier_kategori" },
					{ data: "pic_supplier" },
					{ data: "tlp_supplier" },
					{ data: "email_supplier" },
					{ data: "kota_supplier" },

					{ data: "active", render: function(data, type, full, meta){
						if(data == 1)
							return "Active";
						else return "Not Active";
					}}
					]
				});

				$('#btnSave').on('click', function () {
					if($('#nm_supplier').val() == '')
					{
						alertify.alert("Warning", "Silahkan isi nama supplier.");
					}else if($('#id_kategori_supplier').val() == null)
					{
						alertify.alert("Warning", "Silahkan pilih kategori supplier.");
					}
					else
					{
						var url = '<?=base_url()?>supplier/ax_set_data';
						var data = {
							id_supplier: $('#id_supplier').val(),
							nm_supplier: $('#nm_supplier').val(),
							alamat_supplier: $('#alamat_supplier').val(),
							id_supplier_kategori: $('#id_kategori_supplier').val(),
							pic_supplier: $('#pic_supplier').val(),
							tlp_supplier: $('#tlp_supplier').val(),
							email_supplier: $('#email_supplier').val(),
							kota_supplier: $('#kota_supplier').val(),
							kodepos_supplier: $('#kodepos_supplier').val(),
							active: $('#active').val()
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

				function ViewData(id_supplier)
				{
					if(id_supplier == 0)
					{
						$('#addModalLabel').html('Add supplier');
						$('#id_supplier').val('');
						$('#nm_supplier').val('');
						$('#alamat_supplier').val('');
						$('#id_kategori_supplier').val('').trigger('change');
						$('#pic_supplier').val('');
						$('#tlp_supplier').val('');
						$('#email_supplier').val('');
						$('#kota_supplier').val('');
						$('#kodepos_supplier').val('');
						$('#active').val('1');
						$('#addModal').modal('show');
					}
					else
					{
						var url = '<?=base_url()?>supplier/ax_get_data_by_id';
						var data = {
							id_supplier: id_supplier
						};

						$.ajax({
							url: url,
							method: 'POST',
							data: data
						}).done(function(data, textStatus, jqXHR) {
							var data = JSON.parse(data);
							$('#addModalLabel').html('Edit supplier');
							$('#addModalLabel').html('Add supplier');

							$('#id_supplier').val(data['id_supplier']);
							$('#nm_supplier').val(data['nm_supplier']);
							$('#alamat_supplier').val(data['alamat_supplier']);
							$('#id_kategori_supplier').val(data['id_supplier_kategori']).trigger('change');
							$('#pic_supplier').val(data['pic_supplier']);
							$('#tlp_supplier').val(data['tlp_supplier']);
							$('#email_supplier').val(data['email_supplier']);
							$('#kota_supplier').val(data['kota_supplier']);
							$('#kodepos_supplier').val(data['kodepos_supplier']);
							$('#active').val(data['']);

							$('#addModal').modal('show');
						});
					}
				}

				function DeleteData(id_supplier)
				{
					alertify.confirm(
						'Confirmation', 
						'Are you sure you want to delete this data?', 
						function(){
							var url = '<?=base_url()?>supplier/ax_unset_data';
							var data = {
								id_supplier: id_supplier
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

				// $('#id_kelompok_filter').on("change", function (e) {
				// 	buTable.ajax.reload();
				// });

				$('#id_supplier_kategori_filter').select2({
					'allowClear': true
				}).on("change", function (e) {
					buTable.ajax.reload();
				});

				$('#id_kategori_supplier').select2({
					'placeholder': "--Kategori--",
					'allowClear': true
				});
			</script>
		</body>
		</html>
