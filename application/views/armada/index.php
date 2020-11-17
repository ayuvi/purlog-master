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
			<?php
			if($this->session->flashdata('msg')==TRUE):
				echo '<div class="alert alert-success" role="alert">';
				echo $this->session->flashdata('msg');
				echo '</div>';
			endif;
			?>
			<section class="content-header">
				<h1>Armada</h1>
			</section>
			<section class="invoice">
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<?php //if($this->session->userdata('login')['id_level'] == 1 || $this->session->userdata('login')['id_level'] == 6){ ?>
									<?php //}?>
									<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-lg">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
													<h4 class="Form-add-bu" id="addModalLabel">Form Add Armada</h4>
												</div>
												<div class="modal-body">
													<!-- <input type="hidden" id="id_armada" name="id_armada" value='0' /> -->
													<div class="row">

														<div class="form-group col-lg-6">
															<label>Cabang</label>
															<select class="form-control select2 " style="width: 100%;" id="id_bu" name="id_bu">
																<option value="0">--Cabang--</option>
																<?php
																foreach ($combobox_bu->result() as $rowmenu) {
																	?>
																	<option value="<?= $rowmenu->id_bu?>"  ><?= $rowmenu->nm_bu?></option>
																	<?php
																}
																?>
															</select>
														</div>

														<div class="form-group col-lg-6">
															<label>Kode Armada</label>
															<input type="text" id="kd_armada" name="kd_armada" class="form-control" placeholder="Kode Armda">
														</div>

														<div class="form-group col-lg-6">
															<label>No. Rangka</label>
															<input type="text" id="rangka_armada" name="rangka_armada" class="form-control" placeholder="Rangka">
														</div>

														<div class="form-group col-lg-6">
															<label>No. Mesin</label>
															<input type="text" id="mesin_armada" name="mesin_armada" class="form-control" placeholder="Mesin">
														</div>

														<div class="form-group col-lg-6">
															<label>Plat</label>
															<input type="text" id="plat_armada" name="plat_armada" class="form-control" placeholder="Plat">
														</div>

														<div class="form-group col-lg-6">
															<label>Tahun Pembuatan</label>
															<input type="text" id="tahun_armada" name="tahun_armada" class="form-control" placeholder="Tahun">
														</div>

														<div class="form-group col-lg-6">
															<label>Type</label>
															<input type="text" id="tipe_armada" name="tipe_armada" class="form-control" placeholder="Type">
														</div>

														<div class="form-group col-lg-6">
															<label>Silinder</label>
															<input type="text" id="silinder_armada" name="silinder_armada" class="form-control" placeholder="Silinder">
														</div>

														<div class="form-group col-lg-6">
															<label>Seat</label>
															<input type="text" id="seat_armada" name="seat_armada" class="form-control" placeholder="Seat">
														</div>

														<div class="form-group col-lg-6">
															<label>Layout</label>
															<select class="form-control select2 " style="width: 100%;" id="id_layout" name="id_layout">
																<option value="0">--Layout--</option>
																<?php
																foreach ($combobox_layout->result() as $rowmenu) {
																	?>
																	<option value="<?= $rowmenu->id_layout?>"  ><?= $rowmenu->nm_layout?></option>
																	<?php
																}
																?>
															</select>
														</div>

														<div class="form-group col-lg-6">
															<label>Segment</label>
															<select class="form-control select2 " style="width: 100%;" id="id_segment" name="id_segment">
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

														<div class="form-group col-lg-6">
															<label>Merek</label>
															<select class="form-control select2 " style="width: 100%;" id="id_merek" name="id_merek">
																<option value="0">--Merek--</option>
																<?php
																foreach ($combobox_merek->result() as $rowmenu) {
																	?>
																	<option value="<?= $rowmenu->id_merek?>"  ><?= $rowmenu->nm_merek?></option>
																	<?php
																}
																?>
															</select>
														</div>

														<div class="form-group col-lg-6">
															<label>Layanan</label>
															<select class="form-control select2 " style="width: 100%;" id="id_layanan" name="id_layanan">
																<option value="0">--Layanan--</option>
																<?php
																foreach ($combobox_layanan->result() as $rowmenu) {
																	?>
																	<option value="<?= $rowmenu->id_layanan?>"  ><?= $rowmenu->nm_layanan?></option>
																	<?php
																}
																?>
															</select>
														</div>

														<div class="form-group col-lg-6">
															<label>Ukuran</label>
															<select class="form-control select2 " style="width: 100%;" id="id_ukuran" name="id_ukuran">
																<option value="0">--Ukuran--</option>
																<?php
																foreach ($combobox_ukuran->result() as $rowmenu) {
																	?>
																	<option value="<?= $rowmenu->id_ukuran?>"  ><?= $rowmenu->nm_ukuran?></option>
																	<?php
																}
																?>
															</select>
														</div>

														<div class="form-group col-lg-6">
															<label>Warna</label>
															<select class="form-control select2 " style="width: 100%;" id="id_warna" name="id_warna">
																<option value="0">--Warna--</option>
																<?php
																foreach ($combobox_warna->result() as $rowmenu) {
																	?>
																	<option value="<?= $rowmenu->id_warna?>"  ><?= $rowmenu->nm_warna?></option>
																	<?php
																}
																?>
															</select>
														</div>

														<div class="form-group col-lg-6">
															<label>Status</label>
															<select class="form-control " style="width: 100%;" id="status_armada" name="status_armada">
																<option value="DAMRI">DAMRI</option>
																<option value="KSO">KSO</option>
															</select>
														</div>



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
									<div class="form-group">
										<label>Cabang</label>
										<select class="form-control select2 " style="width: 100%;" id="id_bu_filter" name="id_bu_filter">
											<?php
											foreach ($combobox_bu->result() as $rowmenu) {
												?>
												<option value="<?= $rowmenu->id_bu?>"  ><?= $rowmenu->nm_bu?></option>
												<?php
											}
											?>
										</select>
									</div>
									<div class="dataTable_wrapper">
										<table class="table table-striped table-bordered table-hover" id="armadaTable">
											<thead>
												<tr>
													<th>#</th>
													<th>Cabang</th>
													<th>Kode</th>
													<th>Rangka</th>
													<th>Mesin</th>
													<th>Plat</th>
													<th>Tahun</th>
													<th>Merek</th>
													<th>Type</th>
													<th>Warna</th>
													<th>Silinder</th>
													<th>Ukuran</th>
													<th>Seat</th>
													<th>Layout</th>
													<th>Segment</th>
													<th>Layanan</th>
													<th>Status</th>
													<th>CUser</th>
													<th>CDate</th>
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
			var armadaTable = $('#armadaTable').DataTable({
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
						url: "<?= base_url()?>armada/ax_data_armada/",
						type: 'POST',
						data: function ( d ) {
							return $.extend({}, d, { 

								"id_bu": $("#id_bu_filter").val()

							});
						}
					},
					columns: 
					[
					{ class:'intro', data: "id_armada" },
					{ class:'intro', data: "nm_bu" },
					{ class:'intro', data: "kd_armada" },
					{ class:'intro', data: "rangka_armada" },
					{ class:'intro', data: "mesin_armada" },
					{ class:'intro', data: "plat_armada" },
					{ class:'intro', data: "tahun_armada" },
					{ class:'intro', data: "nm_merek" },
					{ class:'intro', data: "tipe_armada" },
					{ class:'intro', data: "nm_warna" },
					{ class:'intro', data: "silinder_armada" },
					{ class:'intro', data: "nm_ukuran" },
					{ class:'intro', data: "seat_armada" },
					{ class:'intro', data: "nm_layout" },
					{ class:'intro', data: "nm_segment" },
					{ class:'intro', data: "nm_layanan" },
					{ class:'intro', data: "status_armada" },
					{ class:'intro', data: "cuser" },
					{ class:'intro', data: "cdate" },
					
					]
				});

			$(document).ready(function() {
				
				
				$( "#tgl_pp_pegawai" ).datepicker({
					changeMonth: true,
					changeYear: true,
					dateFormat: "yy-mm-dd",
					yearRange: '1946:2019'
				});
				$("#tgl_pp_pegawai").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});

				$("#tahun_armada, #seat_armada").keydown(function (e) {

					if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||

						(e.keyCode == 65 && e.ctrlKey === true) ||

						(e.keyCode == 67 && e.ctrlKey === true) ||

						(e.keyCode == 88 && e.ctrlKey === true) ||

						(e.keyCode >= 35 && e.keyCode <= 39)) {

						return;
				}

				if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
					e.preventDefault();
				}
			});
				

			});

			$('#id_bu_filter').select2({
				'allowClear': true
			}).on("change", function (e) {
				armadaTable.ajax.reload();
			});

			$( "#tgl_exp_stnk").datepicker({
				changeMonth: true,
				changeYear: true,
				dateFormat: "yy-mm-dd"
			});

			$( "#tgl_exp_stnk" ).inputmask("yyyy-mm-dd",{"placeholder": "yyyy-mm-dd"});

			
			
			
		</script>
	</body>
	</html>
