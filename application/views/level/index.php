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
					<h1>Level</h1>
				</section>				
				<section class="invoice">
					<div class="row">
						<div class="col-lg-12">
							<div class="panel panel-default">
								<div class="panel-heading">								
									<button class="btn btn-primary " data-toggle="modal" data-target="#myModal">
										Create Level
									</button>								
									<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
													<h4 class="Form-add-level" id="myModalLabel">Form Add</h4>
												</div>
												<div class="modal-body">
													<?php echo validation_errors(); ?>
													<?php echo form_open('level/insert'); ?>                     
													<div class="form-group">
														<label>Level</label>
														<input type="text" name="nm_level" class="form-control" placeholder="Level Name">
													</div>
													<div class="form-group">
														<label>Status</label>
														<select class="form-control" name="active">
															<option value="1" <?php echo set_select('myselect', '1', TRUE); ?> >Active</option>
															<option value="0" <?php echo set_select('myselect', '0'); ?> >Deactive</option>
														</select> 
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
													<input type="submit" value="Save" class="btn btn-primary">                                     
													<?php echo form_close(); ?>
												</div>
											</div>										
										</div>									
									</div>								
								</div>							
								<div class="panel-body">
									<div class="dataTable_wrapper">
										<table class="table table-striped table-bordered table-hover" id="dataTables-example">
											<thead>
												<tr>
													<th>Options</th>
													<th>Level</th>
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
			function konfirmasi(data){
				var x = confirm('Are you sure?');
				if(x){
					location.href='<?= base_url()?>level/delete/'+data;
				}else{
					
				}
			}
			
			$(document).ready(function() {
				$('#dataTables-example').DataTable({
					"ordering" : false,
					"scrollX": true,
					"processing": true,
					"serverSide": true,
					ajax: {
						url: "<?= base_url() ?>level/ax_data_level/",
						type: 'POST'
					},
					columns: [
						{ 
							data: "id_level", render: function(data, type, full, meta)
							{
								return'<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button><ul class="dropdown-menu"><li><a href="level/formupdate/'+data+'"><i class="fa fa-pencil"></i> Edit</a></li><li><a href="level/formgroupakses/'+data+'"><i class="fa fa-list"></i> Group Akses</a></li><li><a href="level/formdetailakses/'+data+'"><i class="fa fa-search"></i> Detail Akses</a></li><li><a type="button" onClick="konfirmasi('+data+')"><i class="fa fa-trash"></i> Delete</a></li></ul></div>';																										
							}
						},
						
						{ data: "nm_level" },
						{ data: "active", render: function(data, type, full, meta)
							{
								if(data==1)
									return "active";
								else 
									return "deactive";
							}
						},
					],
				});
			});
		</script>
	</body>
</html>
