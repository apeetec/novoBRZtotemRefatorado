<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Gabriel Batista">
    <!-- Descrição -->
    <meta name="description" content="<?php echo get_bloginfo('description'); ?>">
    <!-- Titulo -->
    <title><?php if(is_home()) { echo get_bloginfo('name') . ' | ' . get_bloginfo('description'); } else { echo get_the_title() . ' | ' . get_bloginfo('name'); } ?></title>
    <?php header('Set-Cookie: cross-site-cookie=name; SameSite=None; Secure'); ?>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php bloginfo('template_url'); ?>/img/favicon.png">
    <!-- CSS Gabriel -->
    <link href="<?php bloginfo('template_url'); ?>/style.css" rel="stylesheet">
    <!-- Materialize -->
    <link href="<?php bloginfo('template_url'); ?>/css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    <!-- Icons -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- CSS de CDNs externas -->
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/lightgallery@2.0.0-beta.3/css/lightgallery.css'>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/lightgallery@2.0.0-beta.3/css/lg-zoom.css'>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/justifiedGallery@3.8.1/dist/css/justifiedGallery.css'>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/lightgallery@2.0.0-beta.3/css/lg-thumbnail.css'>
    <!-- ////////////////////// FIM PARTE #2/3 ////////////////////// -->
    <!-- SLICK SLIDE 1/3 -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css">
    <!-- Magnify -->
    <link href="<?php bloginfo('template_url'); ?>/css/magnify.css" rel="stylesheet">
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>
    <nav role="navigation" hidden class="">
      <div class="nav-wrapper container">
        <a href="<?php echo get_site_url(); ?>" class="brand-logo">Logo</a>
        <!-- Menu desktop -->
        <ul class="right hide-on-med-and-down">
          <li><a href="">Navbar Link</a></li>
        </ul>
        <!-- Menu mobile -->
        <ul id="nav-mobile" class="sidenav">
          <li><a href="#">Navbar Link</a></li>
          <li><a href="#" id="fechar">Fechar</a></li>
        </ul>
        <a href="#" data-target="nav-mobile" class="sidenav-trigger"
          ><i class="material-icons menu">menu</i></a
        >
      </div>
    </nav>
    <main>