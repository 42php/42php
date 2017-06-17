<?php foreach (\Core\Conf::get('page.js', []) as $script) { ?>
    <script type="text/javascript" src="<?=$script ?>"></script>
<?php } ?>
<?php $mod = \Core\Conf::get('route.name', ''); $a = \Core\Conf::get('page.googleAnalytics', ''); if ($a != '') { ?>
    <script type="text/javascript">
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
        ga('create', '<?=$a ?>', 'auto');
        ga('require', 'displayfeatures');
        ga('send', 'pageview');
    </script>
<?php } ?>
<?php foreach (\Core\Conf::get('page.bottom', []) as $script) { ?>
    <?=$script ?>
<?php } ?>
</body>
</html>