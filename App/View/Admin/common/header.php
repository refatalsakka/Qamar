<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $title; ?></title>
    <?php  htmlTag('css/libs/bootstrap', 'css') ?>
    <?php  htmlTag('css/libs/fontawesome', 'css') ?>
    <?php  htmlTag('css/users/layout', 'css') ?>
    <?php
        if (! is_array($styles)) {
            htmlTag('css/' . $styles, 'css');
        } else {
            foreach($styles as $style) {
                htmlTag('css/' . $style, 'css');
            }
        }
    ?>
</head>
<body>
    
