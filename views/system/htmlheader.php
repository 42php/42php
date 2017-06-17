<!DOCTYPE html>
<html lang="<?= \Core\Conf::get('lang') ?>">
<head>
    <meta charset="utf-8" />
    <meta name="robots" content="<?= \Core\Conf::get('page.robots') ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= \Core\Conf::get('page.title') ?></title>
    <meta name="description" content="<?= str_replace('"', '&quot;', \Core\Conf::get('page.description')) ?>" />
    <meta name="keywords" content="<?= str_replace('"', '&quot;', \Core\Conf::get('page.keywords')) ?>" />

    <link rel="shortcut icon" href="<?= \Core\Conf::get('page.favicon', '/favicon.ico') ?>" type="image/x-icon">
    <link rel="icon" href="<?= \Core\Conf::get('page.favicon', '/favicon.ico') ?>" type="image/x-icon">

    <?php foreach (\Core\Conf::get('page.meta', []) as $line) { ?>
        <?= $line ?>
    <?php } ?>

    <!-- Facebook share -->
    <meta property="og:title" content="<?= str_replace('"', '&quot;', \Core\Conf::get('page.title')) ?>">
    <?php if (\Core\Conf::get('page.share.image', '') != '') { ?>
        <meta property="og:image" content="<?= str_replace('"', '&quot;', \Core\Conf::get('page.share.image')) ?>">
    <?php } ?>
    <meta property="og:description" content="<?= str_replace('"', '&quot;', \Core\Conf::get('page.description')) ?>">

    <!-- Twitter share -->
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="<?= \Core\Conf::get('page.share.twitter.username') ?>" />
    <meta name="twitter:title" content="<?= str_replace('"', '&quot;', \Core\Conf::get('page.title')) ?>" />
    <meta name="twitter:description" content="<?= str_replace('"', '&quot;', \Core\Conf::get('page.description')) ?>" />
    <?php if (\Core\Conf::get('page.share.image', '') != '') { ?>
        <meta name="twitter:image" content="<?= str_replace('"', '&quot;', \Core\Conf::get('page.share.image')) ?>" />
    <?php } ?>

    <?php
    // alternate links for multilingual
    if (\Core\Conf::get('route') !== false) {
        foreach (\Core\i18n::$__acceptedLanguages as $l) {
            $url = \Core\Argv::createUrl(\Core\Conf::get('route.name'), \Core\Conf::get('route.params'), $l);
            echo '<link rel="alternate" hreflang="'.$l.'" href="http://'.$_SERVER['HTTP_HOST'].$url.'" />'."\n";
        }
    }
    ?>

    <?php foreach (\Core\Conf::get('page.css', []) as $file) { ?>
        <link rel="stylesheet" type="text/css" href="<?=$file ?>" />
    <?php } ?>
</head>
<body>