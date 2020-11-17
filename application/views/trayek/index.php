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
					<h1>Trayek</h1>
				</section>
				<section class="invoice">
					<div class="row">
						<div class="col-lg-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									<div class="modal fade" id="addModal"  tabindex="" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
													<h4 class="Form-add-bu" id="addModalLabel">Form Add Trayek</h4>
												</div>
												<div class="modal-body">
													<input type="hidden" id="id_trayek" name="id_trayek" value='' />
													
													<div class="form-group">
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

													<div class="form-group">
														<label>Segment</label>
														<select class="form-control select2 " style="width: 100%;" id="id_segment" name="id_segment">
																<option value="0">--Segment--</option>
																<?php
																foreach ($combobox_segment->result() as $rowmenu) {
																?>
																<option value="<?= $rowmenu->id_segment?>"  ><?= $rowmenu->kd_segment?></option>
																<?php
																}
																?>
														</select>
													</div>

													<div class="form-group">
														<label>Point Awal</label>
														<select class="form-control select2 " style="width: 100%;" id="id_point_awal" name="id_point_awal">
																<option value="0">--Awal--</option>
																<?php
																foreach ($combobox_point->result() as $rowmenu) {
																?>
																<option value="<?= $rowmenu->id_point?>"  ><?= $rowmenu->nm_point?></option>
																<?php
																}
																?>
														</select>
													</div>

													<div class="form-group">
														<label>Point Akhir</label>
														<select class="form-control select2 " style="width: 100%;" id="id_point_akhir" name="id_point_akhir">
																<option value="0">--Akhir--</option>
																<?php
																foreach ($combobox_point->result() as $rowmenu) {
																?>
																<option value="<?= $rowmenu->id_point?>"  ><?= $rowmenu->nm_point?></option>
																<?php
																}
																?>
														</select>
													</div>

													<div class="form-group">
														<label>Harga</label>
														<input type="text" id="harga" name="harga" class="form-control" value='' placeholder="Harga" />
													</div>

													<div class="form-group">
														<label>KM</label>
														<input type="text" id="km_trayek" name="km_trayek" class="form-control" value='' placeholder="KM" />
													</div>

													<div class="form-group">
														<label>Note Trayek</label>
														<input type="text" id="note_trayek" name="note_trayek" class="form-control" value='' placeholder="Note Trayek" />
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
								</div>
								<div class="panel-body">
									<div class="dataTable_wrapper">
										<table class="table table-striped table-bordered table-hover" id="buTable">
											<thead>
												<tr>
													<th>#</th>
													<th>Cabang</th>
													<th>Segment</th>
													<th>Kode</th>
													<th>Awal</th>
													<th>Akhir</th>
													<th>KM</th>
													<th>Note</th>
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
					url: "<?= base_url()?>trayek/ax_data_trayek/",
					type: 'POST'
				},
				columns: 
				[
					{ data: "id_trayek" },
					{ data: "nm_bu" },
					{ data: "kd_segment" },
					{ data: "kd_trayek" },
					{ data: "nm_point_awal" },
					{ data: "nm_point_akhir" },
					{ data: "km_trayek" },
					{ data: "note_trayek" },
					{ data: "active", render: function(data, type, full, meta){
							if(data == 1)
								return "Active";
							else return "Not Active";
						}
					}
				]
			});
		</script>
	</body>
</html>
