<?php
	$twitter = "http://search.twitter.com/search.rss?q=".$terms."&rpp=50&lang=pt";
	$lastfm  = "http://news.google.com/news?hl=pt-BR&prmdo=1&q=".$terms."&ie=UTF-8&output=rss&cf=all&scoring=n&pz=1&cf=all&num=100";
?>
<link rel="stylesheet" href="http://www.urucumbrasil.com.br/resources/pro-community/css/monitor.css" type="text/css" media="all">
<script type="text/javascript" src="http://www.urucumbrasil.com.br/resources/ajaxhelper/jquery/jquery.min.js"></script>
<script type="text/javascript" src="http://www.urucumbrasil.com.br/resources/pro-community/js/so.so.social.feed.js"></script>
<script type="text/javascript" src="http://www.urucumbrasil.com.br/resources/pro-community/js/so.so.social.init.js"></script>

<script type="text/javascript">

	sss.TWITTER_RSS = "<?php print $twitter; ?>";
	sss.LASTFM_RSS = "<?php print $lastfm; ?>";
	sss.FACEBOOK_RSS = '';
	sss.FLICKR_RSS = '';
	sss.DELICIOUS_RSS = '';
	sss.TUMBLR_RSS = '';
	sss.WORDPRESS_RSS = '';
	sss.POSTEROUS_RSS = '';
	sss.LIMIT = 110;

	$(document).ready(function() {
		$("#blogs").soSoSocial();
	});
</script>

<div id="box_container">
		<h2>Monitoramento das Redes Sociais</h2>

	<div id="box_content">		

	<div id="lc">
		<h3>Nas redes</h3>
		<div id="blogs" class="activityFeed">
			<img src="http://www.urucumbrasil.com.br/resources/pro-community/images/ajax-loader.gif" width="32" height="32" alt="Carregando" class="loader">
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