        <?php  htmlTag('js/libs/bootstrap', 'js') ?>
        <?php  htmlTag('js/libs/jquery', 'js') ?>
        <?php
            if (! is_array($scripts)) {
                htmlTag('js/' . _e($scripts), 'js');
            } else {
            foreach($scripts as $script) {
                htmlTag('js/' . _e($script), 'js');
                }
            }
        ?>
    </body>
</html>