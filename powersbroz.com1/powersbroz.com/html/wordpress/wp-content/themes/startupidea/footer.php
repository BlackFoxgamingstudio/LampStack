<!-- start footer -->
    <?php global $themeum_options; ?>
    <footer id="footer">
        <div class="container">
            <div class="row">
                <?php dynamic_sidebar('bottom'); ?>
            </div> <!-- end row -->
            <?php if (isset($themeum_options['copyright-en']) && $themeum_options['copyright-en']){?>
                <div class="row copyright text-center">
                    <div class="col-sm-12">
                        <?php if(isset($themeum_options['copyright-text'])) echo balanceTags($themeum_options['copyright-text']); ?><!--p>All rights reserved <strong>Startup Idea </strong>2014</p-->
                    </div>
                </div>
            <?php } ?>
        </div> <!-- end container -->
    </footer>
</div> <!-- #page -->
<?php wp_footer(); ?>
</body>
</html>