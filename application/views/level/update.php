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
          <h1>
            Level
          </h1>
        </section>

        <section class="invoice">
            
        <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        
                        <div class="panel-heading">
                            Form Update
                        </div>
                        
                        <div class="panel-body">
                                            <?php                                                        
                                            foreach ($listlevelselect->result() as $rows) {
                                            ?>
                                            <?php echo validation_errors(); ?>

                                            <?php echo form_open('level/Update'); ?>
                                            
                                            <div class="form-group">
                                           
                                            <input type="hidden" name="id_level" class="form-control" placeholder="Level ID" value="<?= $rows->id_level?>" readonly>
                                            </div>

                                            <div class="form-group">
                                            <label>Level Name</label>
                                            <input type="text" name="nm_level" class="form-control" placeholder="Level Name" value="<?= $rows->nm_level?>">
                                            </div>                                            

                                            <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control" name="active">
                                            <option value="1" <?php if ($rows->active == 1){echo set_select('myselect', '1',TRUE);} ?> >Active</option>
                                            <option value="0" <?php if ($rows->active == 0){echo set_select('myselect', '0',TRUE);} ?> >Deactive</option>                                            
                                            </select> 
                                            </div>

                                            <div class="form-group">
                                            <input type="submit" value="Save" class="btn btn-primary">
                                            <a href="<?=base_url();?>level"><input type="button" value="Cancel" class="btn btn-default"></a>
                                            </div>
                                            <?php echo form_close(); ?>
                                            <? } ?>  
                        </div>
                        
                    </div>
                    
                </div>
                
                </div>
            
        </section>
        
      </div>
      
    </div>

    <?= $this->load->view('basic_js'); ?>
    
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
                "scrollX": true
        });
    });
    </script>
  </body>
</html>
