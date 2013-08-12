<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style type="text/css">

  BODY{
    font-family: 'Lucida Sans Unicode','Lucida Grande',arial;
  }

  .button {
    -moz-border-radius:5px 5px 5px 5px;
    background:url("http://www.urucumbrasil.com.br/resources/pro-gabinete/images/bg_button_g.png") repeat-x scroll 0 0 transparent;
    color:#FFFFFF !important;
    display:inline-block;
    font-size:16px;
    margin-bottom:20px;
    padding:12px 20px 10px 16px;
    cursor:pointer;
    text-decoration:none !important;
    border:none;
  }

  .button:hover {
    color:#CCC !important;
  }

  .lista-btns{
    padding-left:263px;
  }

   td img {
     display: block;
   }

  </style>
</head>
<body bgcolor="#eeeeee" style="margin:0; padding:0;">
<table width="350" align="center">
 <tr>
   <td>

    <div style="background:#eee; padding:10px 0;">

      <p align="center" style="font-family:'Lucida Sans Unicode', 'Lucida Grande', arial;"><font color="#666666" size="2">Caso n&atilde;o esteja conseguindo visualizar esse email</font> <a href="http://localhost/drupal/?q=newsletter/item/<?php print $news_id; ?>"><font color="#666666" size="2">clique aqui.</font></a></p>

      <table width="778px" border="0" bgcolor="#FFFFFF" style="border-collapse:collapse; margin:10px auto; font-family:'Lucida Sans Unicode', 'Lucida Grande', arial; -moz-border-radius-bottomright:12px; -moz-border-radius-bottomleft:12px; -moz-border-radius-topright:12px;  -moz-border-radius-topleft:12px;">
          <td><img alt="Layout" src="http://www.urucumbrasil.com.br/resources/newsletter/default/images/spacer.gif" alt="" width="20" height="1" /></td>
          <td valign="top"><table width="100%" height="130" border="0" style="border-collapse:collapse;">

            <tr>
              <td valign="top" style="padding-top:20px;"><img alt="Layout" src="http://www.urucumbrasil.com.br/resources/newsletter/bolsafamilia/images/cabecalho_news.png" />
              </tr>
            </table></td>
            <td>&nbsp;</td>
          </tr>

          <tr>
            <td>&nbsp;</td>
            <td colspan="2"><table width="100%" border="0" style="border-collapse:collapse;">
              <tr>
                <td valign="top">
                  <?php 
                    $i = 1;
                    $length = count($nodes);
                    $others = array();
                    foreach ($nodes as $node) {
                       if($i < 5){
                           @$file = (array)file_load(node_load($node->nid)->field_imagem['und'][0]['fid']);
                           if($i < 3){
                              print '<table width="100%" border="0" cellpadding="3" style="border-collapse:collapse; border-top:1px solid #E0E8ED;">';
                              print '  <tr>';
                              print '   <td colspan="2" style="padding-top:20px;">';
                              print '      <h2><a href="http://'.$_SERVER['SERVER_NAME'].'/node/'.$node->nid.'" style="text-decoration:none;">';
                              print '      <font size="5" color="#003D6F">'.$node->title.'</font></a></h2>';

                           } else {
                              $style = ($i%2!=0) ? 'float:left' : 'float:right';
                              print '<table border="0" width="45%" style="'.$style.';" cellpadding="3">';
                              print '<tr>';
                              print '  <td colspan="3" valign="top">';
                              print '    <h2><a href="http://'.$_SERVER['SERVER_NAME'].'/node/'.$node->nid.'" style="text-decoration:none;">';
                              print '    <font size="3" color="#003D6F">'.$node->title.'</font></a></h2>';
                           }
                           if($length == 1){
                              print '<div style="font-size:14px; padding-top:10px;">';
                              print     @theme('image_style', array('style_name' => 'large', 'path' => file_build_uri($file['filename']),'getsize' => TRUE, 'attributes' => array('class' => 'thumb', 'width' => '190', 'height' => '150', 'style' => 'width:190px;float:left; border:0; margin:0 15px 15px 0;')));
                              print '   <p style="font-family: arial, helvetica, freesans, sans-serif; font-size: 1.26em; margin: 0px; outline: 0px; padding: 0px 0px 1.5em; color: #333333; line-height: 1.45em;">';
                              print       $node->content;
                              print '   </p>';
                              print '<div>';
                           } 
                           print '      </td>
                                      </tr>
                                      <tr>';
                           if($i < 3 && $length != 1)
                              @print '   <td valign="top"><a href="http://'.$_SERVER['SERVER_NAME'].'/node/'.$node->nid.'">'.theme('image_style', array('style_name' => 'large', 'path' => file_build_uri($file['filename']),'getsize' => TRUE, 'attributes' => array('class' => 'thumb', 'width' => '298', 'height' => '150'))).'</a></td>';
                           elseif($i >= 3 && $length != 1)
                              @print '   <td valign="top"><a href="http://'.$_SERVER['SERVER_NAME'].'/node/'.$node->nid.'">'.theme('image_style', array('style_name' => 'square_thumbnail', 'path' => file_build_uri($file['filename']),'getsize' => TRUE, 'attributes' => array('class' => 'thumb', 'width' => '120', 'height' => '120', 'style' => 'width:120px;height:120px;'))).'</a></td>';
                           $font = ($i < 3) ? 3 : 2;
                           if($length != 1)
                              print '    <td valign="top"><a href="http://'.$_SERVER['SERVER_NAME'].'/node/'.$node->nid.'" style="text-decoration:none;"><font size="'.$font.'" color="#484848">'.$node->content.'</font></a></td>';
                           print '    </tr>
                                    </table>';
                       } else {
                           $others[$i][] = $node->nid; 
                           $others[$i][] = $node->title;
                           $others[$i][] = $node->content;
                       }
                       $i++;
                    }
                  ?>
             </td>

             <td valign="top" width="231" border="0" style="border-collapse:collapse;">
              <table border="0" style="border-collapse:collapse; -moz-border-radius-bottomleft:10px; -moz-border-radius-topleft:10px;" height="100%">
                <tr>
                  <td><img alt="Layout" src="http://www.urucumbrasil.com.br/resources/newsletter/default/images/spacer.gif" alt="" width="20" height="23" /></td>
                  <td bgcolor="#EDEDED" id="colAux"><img alt="Layout" src="http://www.urucumbrasil.com.br/resources/newsletter/default/images/spacer.gif" alt="" width="20" height="23" /></td>
                  <td width="170" bgcolor="#EDEDED">

                  <?php
                    if(true){
                       print '<table>
                                <tr>
                                  <td colspan="2" style="padding-top:10px;"><h2><font size="2" color="#000000">OUTROS DESTAQUES</font></h2></td>
                                </tr>
                              </table>';
                       foreach ($others as $key => $value) {
                          @$file = (array)file_load(node_load($others[$key][0])->field_imagem['und'][0]['fid']);
                          print '<table cellpadding="2">
                                    <tr>
                                      <td valign="top"><a href="http://'.$_SERVER['SERVER_NAME'].'/node/'.$others[$key][0].'">'.@theme('image_style', array('style_name' => 'square_thumbnail', 'path' => file_build_uri($file['filename']),
                                        'getsize' => TRUE, 'attributes' => array('class' => 'thumb', 'width' => '78', 'height' => '78', 'style' => 'width:78px;height:78px'))).'</td>
                                      <td valign="top"><a href="http://'.$_SERVER['SERVER_NAME'].'/node/'.$others[$key][0].'" style="text-decoration:none;"><font size="1" color="#484848">'.$others[$key][1].'</font></a></td>
                                    </tr>
                                 </table>
                                 <img alt="Layout" src="http://www.urucumbrasil.com.br/resources/newsletter/default/images/divisor_col_aux.gif" />';
                       }
                    }
                  ?>

                  <table width="100%" style="margin-top:20px;">
                    <tr>
                      <td width="90"><h3 style="margin:0; padding:0;"><font size="1" color="#000000">Bras&iacute;lia</font></h3>
                        <p style="margin:0; padding:0;"><font size="2"><span style="font-size:10px;">61</span> 3215 5269</font></p>
                        <h3 style="margin:15px 0 0 0; padding:0;"><font size="1" color="#000000">Porto Alegre</font></h3>
                        <p style="margin:0; padding:0;"><font size="2"><span style="font-size:10px;">51</span> 3023 3922</font></p>

                        <p><a href="http://www.urucumbrasil.com.br"><img alt="Layout" src="http://www.urucumbrasil.com.br/resources/newsletter/bolsafamilia/images/bt_site.gif" border="0" /></a></p></td>
                        <td width="34"><!-- Redes Sociais -->

                          <p style="margin:0 0 12px 0; padding:0;"><a href="#"><img alt="Layout" src="http://www.urucumbrasil.com.br/resources/newsletter/default/images/ic_twitter.gif" alt="Twitter" border="none;" /></a></p>
                          <p style="margin:0 0 12px 0; padding:0;"><a href="#"><img alt="Layout" src="http://www.urucumbrasil.com.br/resources/newsletter/default/images/ic_facebook.gif" alt="Facebook" border="none;" /></a></p>
                          <p style="margin:0 0 12px 0; padding:0;"><a href="#"><img alt="Layout" src="http://www.urucumbrasil.com.br/resources/newsletter/default/images/ic_orkut.gif" alt="Orkut" border="none;" /></a></p></td>
                        </tr>
                      </table>

                      <!-- FIM COLUNA AUXILIAR --></td>
                      <td bgcolor="#EDEDED"><img alt="Layout" src="http://www.urucumbrasil.com.br/resources/newsletter/default/images/spacer.gif" alt="" width="20" height="23" /></td>

                    </tr>
                  </table></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td style="  border-top: 1px solid #CCCCCC;margin-top: 20px;" valign="bottom"><p style="float:left;"><font size="1">Modelo de Informativo</font></p>

                <p style="padding-left: 319px;"><font size="1">A mensagem foi enviada para 1change_email1. Se você não deseja mais receber os e-mails do Modelo, use este <a href="1change_href1" style="color:#468BCA;">link</a> para cancelar sua inscrição.</font></p></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><img alt="Layout" src="http://www.urucumbrasil.com.br/resources/newsletter/default/images/spacer.gif" alt="" width="20" height="15" /></td>
                <td>&nbsp;</td>
                <td><img alt="Layout" src="http://www.urucumbrasil.com.br/resources/newsletter/default/images/spacer.gif" alt="" width="20" height="15" /></td>

      </td>
    </tr>
  </table>
        <?php
          if(!$mail && user_access('administer_workshop')){
            print '<div class="lista-btns">
                      <a href="?q=/newsletter/edit/item/'.$news_id.'" class="button">Editar</a>
                      <a href="?q=/newsletter/send/'.$news_id.'" class="button">Enviar</a>
                      <a href="?q=/newsletter" class="button">Salvar</a>
                   </div>';
          }
        ?>  
</body>
</html>
