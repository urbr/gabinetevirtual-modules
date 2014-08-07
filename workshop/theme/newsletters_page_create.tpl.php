

		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script src="https://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#drag .sortable-list').sortable({
		connectWith: '#drag .sortable-list',
		update: function(event, ui)
		{
			$('#news_items_field').val('');	
			jQuery('.news_items').children().each(function(i) {
				var li = jQuery(this);
				var content = $('#news_items_field').val();
				$('#news_items_field').val(content + ' ' +  li.attr('id'));
			});
		}

	});
});
</script>


</head>

<style>
/* Sortable items */

.sortable-list {
	list-style: none;
	margin: 0;
	min-height: 60px;
	border:1px solid #E8E8E8;
	padding: 10px;
	background-color: #EEE; /* para browsers sem suporte a CSS 3 */ 
}


.sortable-item {
	background: #FFF;
	color:#666;
	-moz-border-radius:8px;-khtml-border-radius:8px;-webkit-border-radius:8px;border-radius:8px;
	border: 1px solid #666;
	cursor: move;
	-moz-box-shadow: 1px 1px 2px #CCC;
	-webkit-box-shadow: 1px 1px 2px #CCC;
	box-shadow: 1px 1px 2px #CCC;
	font-size:13px;
	line-height:22px;
	display: block;
	margin-bottom: 10px;
	padding: 20px 10px;
	text-align: center;
}

.sortable-item:HOVER{
	background:#FFF;
	border: 1px solid #437EA1;
	color:#437EA1;
}

.setas{
	background:url("/sites/all/themes/urbr/gabinetevirtual/images/setas.png") no-repeat;
	height:71px;
	display:block;
	margin-left:305px;
	text-indent:-5000px;
	overflow:hidden;
	position:absolute;
	top:155px;
	width:28px;
}

.column .item_date{
	background:url("/sites/all/themes/urbr/gabinetevirtual/images/ic_date.gif") no-repeat 88px 7px;
	padding:0 0 0 15px;
	color:437EA1;
	font-size:10px;
	display:block;
	font-weight:bold;
}

/* Disabled list */

#drag .sortable-list {background-color: #F4F4F4; -moz-border-radius:8px;-khtml-border-radius:8px;-webkit-border-radius:8px;border-radius:8px; margin-bottom:10px; width:270px;}
#drag .sortable-item {cursor: move;}
.left {float: left;}
/* Columns */

.column {
	margin-left: 2%;
	width: 44%;
}

.column P{
	font-weight:bold;
	display:block;
	text-align:center;
	padding-top:20px;
	width:290px;
}

.column.first {margin-left: 0;}
.news_items {min-height:3000px;}
</style>

<div id="drag">
	<div class="column left first">
		
		
		<span class="setas">nav</span>

		<p>Matérias a serem publicadas:</p>
		<ul class="sortable-list news_items">
		</ul>
		<div id="form">
			<form action="?q=newsletter/edit" method="POST">
				<input type="hidden" id="news_items_field" name="news_items">
				<input type="submit" value="Gerar o boletim" class="button">
			</form>
		</div>

	</div>


	<div class="column left">
		<p>Opções de matérias:</p>
		<ul class="sortable-list">
			<?php
			if(isset($newsletters)){
				foreach ($newsletters as $news) {
					print "<li class='sortable-item' id='".$news["nid"]."'>
							  <span class='item_date'>".date("d/m/Y", $news["created"])."</span>
							  ".$news["title"]."
						   </li>";
				}
			}
			?>
		</ul>

	</div>
</div>
