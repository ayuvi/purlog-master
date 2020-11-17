<!DOCTYPE html>
<html>
<head>
	<?= $this->load->view('head'); ?>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
</head>
<body class="sidebar-mini wysihtml5-supported <?= $this->config->item('color')?>">
	<div class="wrapper">
		<?= $this->load->view('nav'); ?>
		<?= $this->load->view('menu_groups'); ?>
		<div class="content-wrapper">
			<section class="content-header">
				<h1>Reports - BBM Rekanan</h1>
			</section>
			<section class="invoice">
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">

								<div class="row">
									<div class="form-group col-md-12">
										<label class="control-label col-md-2">Select</label>
										<div class="col-md-10">
											<select data-placeholder="Pilih Perusahaan" name="display" id="display" class="form-control" onchange="set_display()">
												<option value="0" disabled selected>- Pilih Reports-</option>
												<option value="1">BULAN</option>
												<option value="2">PERIODE</option>
											</select>
										</div>
									</div>
								</div>
							</div>

							<div class="panel-body" id="div_bulan">
								<form id="form" class="form-horizontal">
									<input type="hidden" id="id_data" name="id_data">
									<div class="form-body">
										<div class="form-group">
											<label class="control-label col-md-2">Tahun</label>
											<div class="col-md-9">
												<input name="tahun" id="tahun" placeholder="Tahun" class="form-control" type="text" value="<?= date('Y')?>">
												<span class="help-block"></span>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-2">Bulan</label>
											<div class="col-md-9">
												<select data-placeholder="Pilih Bulan" name="bulan" id="bulan" class="form-control select2">
													<option value="0">~ Pilih Bulan</option>
													<option value="1" >1. Januari</option>
													<option value="2" >2. Februari</option>
													<option value="3" >3. Maret</option>
													<option value="4" >4. April</option>
													<option value="5" >5. Mei</option>
													<option value="6" >6. Juni</option>
													<option value="7" >7. Juli</option>
													<option value="8" >8. Agustus</option>
													<option value="9" >9. September</option>
													<option value="10" >10. Oktober</option>
													<option value="11" >11. Nopember</option>
													<option value="12" >12. Desember</option>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-2">Supplier</label>
											<div class="col-md-9">
												<select data-placeholder="Pilih Supplier" name="supplier" id="supplier" class="form-control select2">
													<option value="0">~ Pilih Supplier</option>
													<?php 
													foreach ($combobox_supplier as $row) { ?>
														<option value="<?=$row->id_supplier;?>"><?=$row->nm_supplier;?></option>
													<?php } ?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-2">Cetak</label>
											<div class="col-md-9">
												<input type="text" class="form-control pull-right" id="tanggal_cetak" name="tanggal_cetak" value="<?= date('Y-m-d')?>">
												<span class="help-block"></span>
											</div>
										</div>
									</div>
								</form>
								<hr>
								<div class="form-body">
									<center>
										<div class="form-group">
											<div class="col-md-3">
											</div>
											<div class="col-md-3">
												<select data-placeholder="Pilih Jenis Print" name="jenis_print" id="jenis_print" class="form-control">
													<option value="1" >1. HTML</option>
													<option value="2" >2. PDF</option>
													<option value="3" >3. Excell</option>
												</select>
											</div>
											<div class="col-md-3">
												<button type="button" class="btn btn-success" onclick="printCetakKredit(1)"><i class="fa fa-print"></i> Cetak Kredit</button>
												<button type="button" class="btn btn-success" onclick="printCetak()"><i class="fa fa-print"></i> Cetak</button>
											</div>
										</div>
									</center>
								</div>
							</div>

							<div class="panel-body" id="div_periode">
								<form id="form" class="form-horizontal">
									<input type="hidden" id="id_data" name="id_data">
									<div class="form-body">
										<div class="form-group">
											<label class="control-label col-md-2">Periode</label>
											<div class="col-md-3">
												<input type="text" class="form-control pull-right" id="periode_1" name="periode_1" value="<?= date('Y-m-d')?>">
											</div>
											<label class="control-label col-md-1">S/D</label>
											<div class="col-md-3">
												<input type="text" class="form-control pull-right" id="periode_2" name="periode_2" value="<?= date('Y-m-d')?>">
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">Supplier</label>
										<div class="col-md-7">
											<select data-placeholder="Pilih Supplier" name="supplier_2" id="supplier_2" class="form-control select2">
												<option value="0" disabled selected>~ Pilih Supplier</option>
												<?php 
												foreach ($combobox_supplier as $row) { ?>
													<option value="<?=$row->id_supplier;?>"><?=$row->nm_supplier;?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">Cetak</label>
										<div class="col-md-7">
											<input type="text" class="form-control pull-right" id="tanggal_cetak_2" name="tanggal_cetak_2" value="<?= date('Y-m-d')?>">
											<span class="help-block"></span>
										</div>
									</div>
								</form>
								<hr>
								<div class="form-body">
									<center>
										<div class="form-group">
											<div class="col-md-3">
											</div>
											<div class="col-md-3">
												<select data-placeholder="Pilih Jenis Print" name="jenis_print_2" id="jenis_print_2" class="form-control">
													<option value="1" >1. HTML</option>
													<option value="2" >2. PDF</option>
													<option value="3" >3. Excell</option>
												</select>
											</div>
											<div class="col-md-3">
												<button type="button" class="btn btn-success" onclick="printCetakKredit(2)"><i class="fa fa-print"></i> Cetak Kredit</button>
												<button type="button" class="btn btn-success" onclick="printCetak2()"><i class="fa fa-print"></i> Cetak</button>
											</div>
										</div>
									</center>
								</div>
							</div>

						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
	<?= $this->load->view('basic_js'); ?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
	<script type="text/javascript">
		function set_display() {
			var x = $('#display').val();
			if (x==1) {
				document.getElementById('div_bulan').style.display = 'block';
				document.getElementById('div_periode').style.display = 'none';
			} else {
				document.getElementById('div_bulan').style.display = 'none';
				document.getElementById('div_periode').style.display = 'block';
			}
		}

		$('#tahun').datepicker({
			minViewMode: 'years',
			autoclose: true,
			format: 'yyyy',
			orientation: "bottom auto"
		});

		$(document).ready(function() {
			document.getElementById('div_bulan').style.display = 'none';
			document.getElementById('div_periode').style.display = 'none';

			$('#tanggal_cetak').datepicker({
				autoclose: true,
				format: "yyyy-mm-dd",
				todayHighlight: true,
				todayBtn: true,
				todayHighlight: true,  
			});
			$('#periode_1').datepicker({
				autoclose: true,
				format: "yyyy-mm-dd",
				todayHighlight: true,
				todayBtn: true,
				todayHighlight: true,  
			});
			$('#periode_2').datepicker({
				autoclose: true,
				format: "yyyy-mm-dd",
				todayHighlight: true,
				todayBtn: true,
				todayHighlight: true,  
			});
			$('#tanggal_cetak_2').datepicker({
				autoclose: true,
				format: "yyyy-mm-dd",
				todayHighlight: true,
				todayBtn: true,
				todayHighlight: true,  
			});

			$( "#tanggal_cetak" ).inputmask("yyyy-mm-dd",{"placeholder": "yyyy-mm-dd"});
			$( "#periode_1" ).inputmask("yyyy-mm-dd",{"placeholder": "yyyy-mm-dd"});
			$( "#periode_2" ).inputmask("yyyy-mm-dd",{"placeholder": "yyyy-mm-dd"});
			$( "#tanggal_cetak_2" ).inputmask("yyyy-mm-dd",{"placeholder": "yyyy-mm-dd"});
		});

		function printCetakKredit(st) {
			var tahun 			= $('#tahun').val();
			var bulan 			= $('#bulan').val();
			var supplier 		= $('#supplier').val();
			var tanggal_cetak 	= $('#tanggal_cetak').val();
			var format 	= $('#jenis_print').val();

			var periode_1 		= $('#periode_1').val();
			var periode_2 		= $('#periode_2').val();
			var supplier_2 		= $('#supplier_2').val();
			var tanggal_cetak_2 = $('#tanggal_cetak_2').val();
			var format_2 		= $('#jenis_print_2').val();

			if (st==1) {

				if ($('#tahun').val()!='' && $('#bulan').val()!='0' && $('#supplier').val()!='0' && $('#tanggal_cetak').val()!='') {
					var url     		= "<?= base_url() ?>reports_print/bbm_rekanan_kredit/";
					window.open(url+"?tahun="+tahun+"&bulan="+bulan+"&supplier="+supplier+"&tanggal_cetak="+tanggal_cetak+"&format="+format, '_blank');
					window.focus();
				}else{
					alertify.alert("Warning", "Isi Semua data.. Data tidak boleh ada yang kosong");
				}

			}else {

				if ($('#periode_1').val()!='' && $('#periode_2').val()!='' && $('#supplier_2').val()!='0' && $('#tanggal_cetak').val()!='') {
					var url     		= "<?= base_url() ?>reports_print/bbm_rekanan_kredit/";
					window.open(url+"?periode_1="+periode_1+"&periode_2="+periode_2+"&supplier_2="+supplier_2+"&tanggal_cetak_2="+tanggal_cetak_2+"&format_2="+format_2, '_blank');
					window.focus();
				}else{
					alertify.alert("Warning", "Isi Semua data.. Data tidak boleh ada yang kosong");
				}

			}
		}
	</script>

</body>
</html>
