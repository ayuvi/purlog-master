<!DOCTYPE html>
<html>
<head>
	<?= $this->load->view('head'); ?>
	<link rel="stylesheet" href="<?= base_url()?>assets/TreeGrid/css/jquery.treegrid.css">
</head>
<body class="sidebar-mini wysihtml5-supported <?= $this->config->item('color')?>">
	<div class="wrapper">
		<?= $this->load->view('nav'); ?>
		<?= $this->load->view('menu_groups'); ?>
		<div class="content-wrapper">
			<section class="content-header">
				<h1>COA</h1>
			</section>
			<section class="invoice">
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<button class="btn btn-primary" onclick="addDataLevel_1(0)">
									<i class='fa fa-plus'></i> Add Parent COA
								</button>
							</div>
							<div class="panel-body">
								<div class="row" id="tampil">

								</div>

							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>

	<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="Form-add-bu" id="addModalLabel">Form Add</h4>
				</div>
				<div class="modal-body">
					<input type="hidden" id="id_coa" name="id_coa" value='' />
					<input type="hidden" id="level" name="level" value='' />
					<input type="hidden" id="parent_id" name="parent_id" value='' />

					<div class="form-group">
						<label>Kode</label>
						<input type="text" id="kd_coa" name="kd_coa" class="form-control" placeholder="Kode">
					</div>
					<div class="form-group">
						<label>Name</label>
						<input type="text" id="nm_coa" name="nm_coa" class="form-control" placeholder="Name">
					</div>
					<div class="form-group">
						<label>Satuan</label>
						<select class="form-control" id="satuan" name="satuan">
							<option value="000">1. 000</option>
							<option value="hari">2. hari</option>
							<option value="km 000">3. km 000</option>
							<option value="orang">4. orang</option>
							<option value="rit">5. rit</option>
							<option value="Rp (000)">6. Rp (000)</option>
							<option value="unit">7. unit</option>
						</select>
					</div>
					<div class="form-group">
						<label>Kelompok</label>
						<select class="form-control" id="kelompok" name="kelompok">
							<option value="1" <?php echo set_select('myselect', '1', TRUE); ?>>1. Produksi</option>
							<option value="2" <?php echo set_select('myselect', '2'); ?>>2. Pendapatan</option>
							<option value="3" <?php echo set_select('myselect', '3'); ?>>3. Beban</option>
						</select>
					</div>
					<div class="form-group">
						<label>Active</label>
						<select class="form-control" id="active" name="active">
							<option value="1" <?php echo set_select('myselect', '1', TRUE); ?> >Active</option>
							<option value="0" <?php echo set_select('myselect', '0'); ?> >Not Active</option>
						</select>
					</div>
					<div class="form-group">
						<label>Type</label>
						<select class="form-control" id="type" name="type">
							<option value="1" <?php echo set_select('myselect', '1', TRUE); ?> >Bulanan</option>
							<option value="2" <?php echo set_select('myselect', '2'); ?> >Tahunan</option>
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

	
	<?= $this->load->view('basic_js'); ?>
	<script type="text/javascript" src="<?= base_url()?>assets/TreeGrid/js/jquery.treegrid.js">"></script>
	<script type='text/javascript'>
		$(document).ready(function() {
			tampil();
			// var table_2 = $('#tableCoa').dataTable({
			// 	"lengthMenu": [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
			// 	"aoColumnDefs": [{"bSortable": false, "aTargets": [0]}]
			// });

			// $('.dataTables_filter input').addClass('form-control').attr('placeholder', 'Search');
		});
		
		function tampil() {
			var url = "<?php echo site_url('coa/cetakCoa')?>";
			$.ajax({
				url : url,
				type: "POST",
				async: false,
				dataType: "JSON",
				success: function(data)
				{
					$('#tampil').html(data.detail);
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					alert('Error get data!');
				}
			});
			$('.tree').treegrid();
		}

		$('#btnSave').on('click', function () {
			if($('#nm_coa').val() == '')
			{
				alertify.alert("Warning", "Please fill coa Name.");
			}
			else
			{
				var url = '<?=base_url()?>coa/ax_set_data';
				var data = {
					id_coa: $('#id_coa').val(),
					kd_coa: $('#kd_coa').val(),
					nm_coa: $('#nm_coa').val(),
					satuan: $('#satuan').val(),
					kelompok: $('#kelompok').val(),
					active: $('#active').val(),
					type: $('#type').val(),
					level: $('#level').val(),
					parent_id: $('#parent_id').val(),
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
						// window.location="<?php echo base_url();?>coa"; 
						tampil();
					}
				});
			}
		});

		function addDataLevel_1(id_coa)
		{
			if(id_coa == 0) {
				$('#addModalLabel').html('Add COA Level ke 1');
				$('#id_coa').val('');
				$('#level').val('1');
				$('#parent_id').val('0');
				$('#kd_coa').val('');
				$('#nm_coa').val('');
				$('#satuan').val('000');
				$('#active').val('1');
				$('#kelompok').val('1');
				$('#type').val('1');
				$('#addModal').modal('show');
			}else{
				var url = '<?=base_url()?>coa/ax_get_data_by_id';
				var data = {
					id_coa: id_coa
				};
				$.ajax({
					url: url,
					method: 'POST',
					data: data
				}).done(function(data, textStatus, jqXHR) {
					var data = JSON.parse(data);
					$('#addModalLabel').html('Edit COA Level ke 1');
					$('#id_coa').val(data['id_coa']);
					$('#level').val('1');
					$('#parent_id').val('0');
					$('#kd_coa').val(data['kd_coa']);
					$('#nm_coa').val(data['nm_coa']);
					$('#satuan').val(data['satuan']);
					$('#active').val(data['active']);
					$('#kelompok').val(data['kelompok']);
					$('#type').val(data['type']);
					$('#addModal').modal('show');
				});
			}
		}

		function addDataNextLevel(id_coa,level)
		{
			$('#addModalLabel').html('Add COA Level ke '+level);
			$('#id_coa').val('');
			$('#level').val(level);
			$('#parent_id').val(id_coa);
			$('#kd_coa').val('');
			$('#nm_coa').val('');
			$('#satuan').val('000');
			$('#active').val('1');
			$('#kelompok').val('1');
			$('#type').val('1');
			$('#addModal').modal('show');
		}

		function DeleteData(id_coa)
		{
			alertify.confirm(
				'Confirmation', 
				'Are you sure you want to delete this data?', 
				function(){
					var url = '<?=base_url()?>coa/ax_unset_data';
					var data = {
						id_coa: id_coa
					};
					$.ajax({
						url: url,
						method: 'POST',
						data: data
					}).done(function(data, textStatus, jqXHR) {
						var data = JSON.parse(data);
						alertify.error('Data deleted.');
						tampil();
					});
				},
				function(){ }
				);
		}
	</script>
</body>
</html>
