
      <!-- FOOTER -->
      <footer>
        <div class="container">
            
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-3 col-sm-6">
                        <?php
                        if (function_exists('dynamic_sidebar')) {
                            dynamic_sidebar("footer-one");
                        } ?>                    
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <?php
                        if (function_exists('dynamic_sidebar')) {
                            dynamic_sidebar("footer-two");
                         } ?>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <?php
                        if (function_exists('dynamic_sidebar')) {
                            dynamic_sidebar("footer-three");
                        } ?>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <?php
                        if (function_exists('dynamic_sidebar')) {
                            dynamic_sidebar("footer-four");
                        } ?>
                           
                    </div>
                </div>
            </div>  
            
            <div class="clearfix spacer"></div>

            <p class="pull-right"><a href="#">Back to top</a></p>
            <p><?php 
                $info = get_field('footer_info', 'option');                 
                $info = str_replace(array('{Y}', '{SITENAME}'), array(date('Y'), get_bloginfo('name')) , $info );
                echo $info ? $info : 'Copyright &copy; '.date('Y').' '. get_bloginfo('name').'. All Rights Reserved';
            ?>
            </p>   
        </div><!-- /.container -->       
      </footer>    

  <?php wp_footer(); ?>

  </body>
</html>

