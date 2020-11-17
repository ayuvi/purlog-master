
    <script src="<?= base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="<?= base_url();?>assets/plugins/select2/select2.min.js"></script>
    <script src="<?= base_url();?>assets/js/jquery.timepicker.js"></script>
    <script src="<?= base_url()?>assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?= base_url()?>assets/bootstrap/src/bootstrap-input-spinner.js"></script>

    <script src="<?= base_url()?>assets/plugins/fastclick/fastclick.min.js"></script>

    <script src="<?= base_url()?>assets/dist/js/app.min.js"></script>
    
    <script src="<?= base_url()?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url()?>assets/plugins/datatables/dataTables.bootstrap.min.js"></script>
    
    <script src="<?=base_url();?>assets/js/highcharts.js"></script>
    <script src="<?=base_url();?>assets/js/highcharts-3d.js"></script>
    <script src="<?=base_url();?>assets/js/exporting.js"></script>
    <script src="<?=base_url();?>assets/js/export-data.js"></script>

    <script src="<?= base_url()?>assets/plugins/chartjs/Chart.min.js"></script>
    <script src="<?= base_url()?>assets/plugins/chartjs/Chart.PieceLabel.min.js"></script>
    
    <script src="<?=base_url();?>assets/js/jquery-ui.js"></script>
    
    <script src="<?=base_url();?>assets/plugins/multiple-email/multiple-emails.js"></script>
    
    <script> $(".select2").select2(); 
    $(".loader").hide();
    </script>
    
    <script src="<?=base_url();?>assets/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="<?=base_url();?>assets/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="<?=base_url();?>assets/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    
    <script src="<?=base_url();?>assets/plugins/alertifyjs/alertify.min.js"></script>
    <script src="<?=base_url();?>assets/js/jquery.number.min.js"></script>
    <script src="<?=base_url();?>assets/plugins/touchspin/jquery.bootstrap-touchspin.js"></script>

    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>

    <script type="text/javascript">
        
        function Left(str, n){
        var iLen = String(str).length - n;

        return String(str).substring(iLen, 0);
        }

        function Right(str, n){
            if (n <= 0)
               return "";
            else if (n > String(str).length)
               return str;
            else {
               var iLen = String(str).length;
               return String(str).substring(iLen, iLen - n);
            }
        }
    </script>
    <!--Start of Tawk.to Script-->
<script type="text/javascript">
// var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
// (function(){
// var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
// s1.async=true;
// s1.src='https://embed.tawk.to/5c3b2d14361b3372892fe0a0/default';
// s1.charset='UTF-8';
// s1.setAttribute('crossorigin','*');
// s0.parentNode.insertBefore(s1,s0);
// })();
function angka(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }

</script>
<!--End of Tawk.to Script-->
    