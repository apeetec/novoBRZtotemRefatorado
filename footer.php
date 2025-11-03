</main>
<footer hidden>
    <?php wp_footer(); ?>
      <!-- ////////////////////// FIM PARTE #3 ////////////////////// -->
      <!-- Materialize Scripts -->
      <script src="<?php bloginfo('template_url'); ?>/js/materialize.js"></script>
      <script src="<?php bloginfo('template_url'); ?>/js/jquery.magnify.js"></script>
      <script src="<?php bloginfo('template_url'); ?>/js/jquery.magnify-mobile.js"></script>
      <script>
        $(document).ready(function() {
          $('.zoom').magnify();
        });
      </script>
      <!-- inicializações -->
      <script src="<?php bloginfo('template_url'); ?>/js/init.js"></script>
      <script src="<?php bloginfo('template_url'); ?>/js/script.js"></script>
      <!-- Script Slick carousel -->
      <script src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
      <script src="https://d3js.org/d3.v7.min.js"></script>
      <script src="<?php bloginfo('template_url'); ?>/js/fslightbox.js"></script>
       <script>
          var modalImagens = document.getElementById('imagens');
          fsLightboxInstances["gallery"].props.onOpen = function () {
            modalImagens.hidePopover();
            console.log("🔥 Lightbox aberta!");
                
          }       
          fsLightboxInstances["gallery"].props.onClose = function () {
            modalImagens.showPopover();
            console.log("🔥 Lightbox fechada!");     
          }       

        fsLightboxInstances["gallery"].props.onOpen = function () {
            modalImagens.hidePopover();
            console.log("🔥 Lightbox aberta!");
                
        }       
        fsLightboxInstances["gallery"].props.onClose = function () {
          modalImagens.showPopover();
	        console.log("🔥 Lightbox fechada!");     
        }
      </script>
  </footer>
  </body>
</html>