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
            <!-- <img src="<?= base_url()?>assets/img/b1.jpeg" class="img" alt="" width="300">
            <img src="<?= base_url()?>assets/img/b2.jpeg" class="img" alt="" width="300">
            <img src="<?= base_url()?>assets/img/b3.jpeg" class="img" alt="" width="300"> -->
          </h1>
        </section>

       
		  </div>
    </div>

			<?= $this->load->view('basic_js'); ?>
      <script type='text/javascript'>
      
    </script>
	</body>
</html>
