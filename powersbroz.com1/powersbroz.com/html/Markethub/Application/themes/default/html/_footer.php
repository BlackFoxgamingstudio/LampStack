
</div>
<!--[if (lt IE 9) & (!IEMobile)]>
<script src="<?php echo $C->SITE_URL ?>themes/default/js/selectivizr-min.js"></script>
<![endif]-->

	<?php
        // Important - do not remove this:
        if( $C->DEBUG_MODE ) { $this->load_template('footer_debuginfo.php'); }
    ?>
    
    <?php
        @include( $C->INCPATH.'../themes/include_in_footer.php' );
    ?> 
</body>
</html>