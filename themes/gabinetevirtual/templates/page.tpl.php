<?php

/**
* @file
* Bartik's theme implementation to display a single Drupal page.
*
* The doctype, html, head and body tags are not in this template. Instead they
* can be found in the html.tpl.php template normally located in the
* modules/system directory.
*
* Available variables:
*
* General utility variables:
* - $base_path: The base URL path of the Drupal installation. At the very
*   least, this will always default to /.
* - $directory: The directory the template is located in, e.g. modules/system
*   or themes/bartik.
* - $is_front: TRUE if the current page is the front page.
* - $logged_in: TRUE if the user is registered and signed in.
* - $is_admin: TRUE if the user has permission to access administration pages.
*
* Site identity:
* - $front_page: The URL of the front page. Use this instead of $base_path,
*   when linking to the front page. This includes the language domain or
*   prefix.
* - $logo: The path to the logo image, as defined in theme configuration.
* - $site_name: The name of the site, empty when display has been disabled
*   in theme settings.
* - $site_slogan: The slogan of the site, empty when display has been disabled
*   in theme settings.
* - $hide_site_name: TRUE if the site name has been toggled off on the theme
*   settings page. If hidden, the "element-invisible" class is added to make
*   the site name visually hidden, but still accessible.
* - $hide_site_slogan: TRUE if the site slogan has been toggled off on the
*   theme settings page. If hidden, the "element-invisible" class is added to
*   make the site slogan visually hidden, but still accessible.
*
* Navigation:
* - $main_menu (array): An array containing the Main menu links for the
*   site, if they have been configured.
* - $secondary_menu (array): An array containing the Secondary menu links for
*   the site, if they have been configured.
* - $breadcrumb: The breadcrumb trail for the current page.
*
* Page content (in order of occurrence in the default page.tpl.php):
* - $title_prefix (array): An array containing additional output populated by
*   modules, intended to be displayed in front of the main title tag that
*   appears in the template.
* - $title: The page title, for use in the actual HTML content.
* - $title_suffix (array): An array containing additional output populated by
*   modules, intended to be displayed after the main title tag that appears in
*   the template.
* - $messages: HTML for status and error messages. Should be displayed
*   prominently.
* - $tabs (array): Tabs linking to any sub-pages beneath the current page
*   (e.g., the view and edit tabs when displaying a node).
* - $action_links (array): Actions local to the page, such as 'Add menu' on the
*   menu administration interface.
* - $feed_icons: A string of all feed icons for the current page.
* - $node: The node object, if there is an automatically-loaded node
*   associated with the page, and the node ID is the second argument
*   in the page's path (e.g. node/12345 and node/12345/revisions, but not
*   comment/reply/12345).
*
* Regions:
* - $page['header']: Items for the header region.
* - $page['featured']: Items for the featured region.
* - $page['highlighted']: Items for the highlighted content region.
* - $page['help']: Dynamic help text, mostly for admin pages.
* - $page['content']: The main content of the current page.
* - $page['sidebar_first']: Items for the first sidebar.
* - $page['triptych_first']: Items for the first triptych.
* - $page['triptych_middle']: Items for the middle triptych.
* - $page['triptych_last']: Items for the last triptych.
* - $page['footer_firstcolumn']: Items for the first footer column.
* - $page['footer_secondcolumn']: Items for the second footer column.
* - $page['footer_thirdcolumn']: Items for the third footer column.
* - $page['footer_fourthcolumn']: Items for the fourth footer column.
* - $page['footer']: Items for the footer region.
*
* @see template_preprocess()
* @see template_preprocess_page()
* @see template_process()
* @see bartik_process_page()
* @see html.tpl.php
*/
?>

  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="robots" content="All" />
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="generator" lang="en" content="OpenACS version 5.5.1" />
  <meta name="author" content="Urucum Brasil" />
  <link rel="stylesheet" href="http://www.urucumbrasil.com.br/resources/ajaxhelper/jquery/chat.css" type="text/css" media="all">
  <link rel="stylesheet" href="http://www.urucumbrasil.com.br/resources/acs-templating/lists.css" type="text/css" media="all">
  <link rel="stylesheet" href="http://www.urucumbrasil.com.br/resources/acs-templating/forms.css" type="text/css" media="all">
  <link rel="alternate" href="http://www.urucumbrasil.com.br/urucumti/gabinete/noticias/rss/" title="Urucum Brasil" type="application/rss+xml">
  <link rel="stylesheet" href="http://www.urucumbrasil.com.br/resources/acs-subsite/default-master.css" type="text/css" media="all">
  <link rel="stylesheet" href="http://www.urucumbrasil.com.br/resources/acs-templating/fontsize/font-size.css" type="text/css" media="all">
  <link rel="stylesheet" href="http://www.urucumbrasil.com.br/resources/pro-gabinete/css/home.css" type="text/css" media="all">
  <link rel="stylesheet" href="http://bolsafamilia.urucumbrasil.com.br/themes/bolsa/css/homegabinete.css" type="text/css" media="all">

/<!--\[if lte IE 6\]>
<style type="text/css">
<script src="http://www.urucumbrasil.com.br/resources/pro-gabinetetheme/js/DD_belatedPNG.js" type="text/javascript"></script>
</style>  
<!\[endif\]-->

<div id="site">
  <div id="topo">
    <div class="menu">
      <div class="span-24">
        <div id="nav">
            <?php print theme('links__system_main_menu', array(
              'links' => $main_menu,
              'attributes' => array(
                'id' => 'main-menu-links',
                'class' => array('links', 'clearfix'),
              ),
              'heading' => array(
                'text' => t('Menu inicial'),
                'level' => 'h2',
                'class' => array('element-invisible'),
              ),
            )); ?>
        </div>
      </div>
    </div>
  </div>

     <div id="header">
        <?php if(isset($img_header) && !empty($img_header)): ?>
            <?php print "<img src=\"$img_header\">"; ?>
        <?php endif; ?>
     </div>

  <div id="content">

    <!-- menu esquerdo -->

    <div id="user_information">
      <div class="user_photo">
        <a href="http://www.urucumbrasil.com.br/urucumti/gabinete/user/portrait/upload?return_url=http://www.urucumbrasil.com.br/urucumti/gabinete/arquivos/" target="_blank"><img class="portrait" src="http://www.urucumbrasil.com.br/resources/acs-subsite/no_picture.jpg"> </a>
        <div class="user_status">
          <span>
            <b>
              <?php if (isset($name) && !empty($name)): ?>
                <?php print $name; ?>
              <?php endif; ?>
            </b>
          </span> 
          <img class="online" src="http://www.urucumbrasil.com.br/resources/acs-subsite/online.png">
        </div>

      </div>
      <div id="user_menu">
        <ul>

          <li>
            <?php if (isset($config) && !empty($config)): ?>
              <?php print $config; ?>
            <?php endif; ?>
          </li>
          <li>
            <?php if (isset($pass) && !empty($pass)): ?>
              <?php print $pass; ?>
            <?php endif; ?>
          </li>
        </ul>
      </div>
      <div id="users_chat">
        <h3>Membros</h3>

          <?php if (isset($online) && !empty($online)): ?>
            <?php print $online; ?>
          <?php endif; ?>

            <ul style="padding-top:8px;">
              <li style="float:left;margin-right:8px; float:left;"><a href="http://www.urucumbrasil.com.br/urucumti/gabinete/g/member-invite?return_url=http://www.urucumbrasil.com.br/urucumti/gabinete/arquivos/" class="button" style="border:none;">Convidar</a></li>
              <li><a href="http://www.urucumbrasil.com.br/urucumti/gabinete/members" class="button" style="border:none;">Membros</a></li>

              <div class="box-webconferencia">
                <p><strong>Webconferência</strong> com membros do gabinete.</p>
                <p><a href="http://li172-158.members.linode.com/dimdim/html/envcheck/connect.action?action=host&amp;email=fellipe.vasconcelos@urucumbrasil.com.br&amp;confKey=A86CB678AD0FACA0&amp;displayName=Fellipe&amp;confName=Conferência com Membros do Gabinete&amp;lobby=false&amp;networkProfile=2&amp;meetingHours=5&amp;meetingMinutes=0&amp;maxParticipants=100&amp;presenterAV=av&amp;maxAttendeeMikes=3&amp;returnUrl=http://www.urucumbrasil.com.br/urucumti/gabinete/arquivos/&amp;whiteboardEnabled=true&amp;screenShareEnabled=true"><img src="http://www.urucumbrasil.com.br/resources/pro-gabinete/images/bt_iniciar.png" /></a></p>
              </div>
            </ul>

          </div>

        </div>

  <?php if(arg(0) == "agenda"): ?>

  <div id="cont_itens_gabinete_interno">
    <div class="box_item_principal"><a href="<?php print url('workshop'); ?>"><img src="http://bolsafamilia.urucumbrasil.com.br/themes/bolsa/img/agenda/bg_box_m.png" /></a></div>
    <div class="box_item_menor box_margin">
      <h2>Gerador de boletim</h2>
        <img src="http://bolsafamilia.urucumbrasil.com.br/themes/bolsa/img/agenda/thumb_gerador_boletim.png" />
        <p><a href="<?php print url('newsletter'); ?>">Produza com simples clic boletins personalizados e distribua para públicos especificamente selecionados</a></p>
    </div>
    <div class="box_item_menor">
      <h2>Monitor</h2>
        <img src="http://bolsafamilia.urucumbrasil.com.br/themes/bolsa/img/agenda/thumb_monitor.png" />
        <p><a href="<?php print url('monitor'); ?>">Acompanhe em tempo real os conteúdos de sites de notícias, blogs, twiters, facebook e demais mídias sociais</a></p>
        </div>
    <div class="box_item_menor box_margin">
      <h2>Arquivos</h2>
        <img src="http://bolsafamilia.urucumbrasil.com.br/themes/bolsa/img/agenda/thumb_doc_compartilhados.png" />
        <p><a>Armazene e organize seus documentos compartilhados deixando-os disponíveis a qualquer tempo e local</a></p>
        </div>
    <div class="box_item_menor">
      <h2>Banco de imagens</h2>
        <img src="http://bolsafamilia.urucumbrasil.com.br/themes/bolsa/img/agenda/thumb_banco_imagem.png" />
        <p><a>Faça uploads de suas imagens e as envie para publicação na rede, imprensa ou banco de imagens</a></p>
        </div>
    <div class="box_item_menor box_margin">
      <h2>Contato</h2>
        <img src="http://bolsafamilia.urucumbrasil.com.br/themes/bolsa/img/agenda/thumb_contato.png" />
        <p><a href="<?php print url('newsletter/email'); ?>">Gerencie sua base de contatos organizando-os por região, estrato profissional, área de interesse e outras categorias personalizadas</a></p>
        </div>
    <div class="box_item_menor">
      <h2>Agendar Web Conferência Pública</h2>
        <img src="http://bolsafamilia.urucumbrasil.com.br/themes/bolsa/img/agenda/thumb_conferencia.png" />
        <p><a>Crie Web conferencias para conversar diretamente com seu público, com apoio de áudio, vídeo, chat e apresentações online</a></p>
        </div>

    <?php endif; ?>

  </div>
        <div id="boxes">
          <?php if ($messages): ?>
            <div id="messages">
              <div class="section clearfix">
                <?php print $messages; ?>
              </div>
            </div>
          <?php endif; ?>
          <?php print render($page['content']); ?> 
          
        </div>
        
      

    </div>

  </div>

  <div id="base">
  </div>

</div>

<!-- Rodapé -->
<div id="footer"> 
  <p>Produzido por: <a href="#"><img src="http://www.urucumbrasil.com.br/resources/pro-gabinete/images/logo_urucum.png" /></a></p>
</div>

<!-- fecha tudo -->

<script type="text/javascript">
<!--
_uacct = "UA-2291113-2";
urchinTracker();
-->
</script>
<noscript><p>Javascript Habilite esse recurso para website</p></noscript>
