<?php
        $twitter = "/monitor/search/twitter/".$terms;
        $lastfm  = "http://news.google.com/news?hl=pt-BR&prmdo=1&q=".$terms."&ie=UTF-8&output=rss&cf=all&scoring=n&pz=1&cf=all&num=100";

        #drupal_add_js(drupal_get_path('module', 'workshop').'/theme/js/jquery.min.js');
        #drupal_add_js('var $jq = $.noConflict(true);', array('type' => 'inline', 'scope' => 'header'));
        drupal_add_js(drupal_get_path('module', 'workshop').'/theme/js/so.so.social.feed.js');
        drupal_add_js(drupal_get_path('module', 'workshop').'/theme/js/so.so.social.init.js');
?>
<script>
                sss.TWITTER_RSS = "<?php print $twitter; ?>";
                sss.LASTFM_RSS = "<?php print $lastfm; ?>";
                sss.FACEBOOK_RSS = '';
                sss.FLICKR_RSS = '';
                sss.DELICIOUS_RSS = '';
                sss.TUMBLR_RSS = '';
                sss.WORDPRESS_RSS = '';
                sss.POSTEROUS_RSS = '';
                sss.LIMIT = 110;

                $(function() {
                        $("#blogs").soSoSocial();
                });
</script>
<div id="box_container">
		<h2>Monitoramento das Redes Sociais</h2>

	<div id="box_content">		

	<div id="lc">
		<h3>Nas redes</h3>
		<div id="blogs" class="activityFeed">
			<img src="/themes/workshop/images/ajax-loader.gif" width="32" height="32" alt="Carregando" class="loader">
	    </div>
	</div>
	<div id="rc">
		<h3>No Twitter</h3>
		<div id="twitter" class="activityFeedTwitter"></div>
	</div>
	<div class="clear"></div>
	
	</div>
	<div><p>Outra pesquisa:
		<form method="post" action="?q=monitor/search">
			<input type="text" name="terms" value="<?php print $orig; ?>"> 
			<input type="submit" value="Pesquisar"> 
		</form></p>
	</dvi>
</div>
