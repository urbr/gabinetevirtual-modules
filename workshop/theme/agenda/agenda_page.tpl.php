<!-- Include CSS for JQuery Frontier Calendar plugin (Required for calendar plugin) -->
<link rel="stylesheet" type="text/css" href="sites/all/modules/urucumbrasil/modules-gabinetevirtual/workshop/theme/agenda/css/frontierCalendar/jquery-frontier-cal-1.3.2.css" />

<!-- Include CSS for color picker plugin (Not required for calendar plugin. Used for example.) -->
<link rel="stylesheet" type="text/css" href="sites/all/modules/urucumbrasil/modules-gabinetevirtual/workshop/theme/agenda/css/colorpicker/colorpicker.css" />

<!-- Include CSS for JQuery UI (Required for calendar plugin.) -->
<link rel="stylesheet" type="text/css" href="sites/all/modules/urucumbrasil/modules-gabinetevirtual/workshop/theme/agenda/css/jquery-ui/smoothness/jquery-ui-1.8.1.custom.css" />

<!--
Include JQuery Core (Required for calendar plugin)
** This is our IE fix version which enables drag-and-drop to work correctly in IE. See README file in sites/all/modules/workshop/theme/agenda/sites/all/modules/workshop/theme/agenda/js/jquery-core folder. **
-->

<script type="text/javascript" src="sites/all/modules/urucumbrasil/modules-gabinetevirtual/workshop/theme/agenda/js/jquery-core/jquery-1.4.2-ie-fix.min.js"></script>

<!-- Include JQuery UI (Required for calendar plugin.) -->
<script type="text/javascript" src="sites/all/modules/urucumbrasil/modules-gabinetevirtual/workshop/theme/agenda/js/jquery-ui/smoothness/jquery-ui-1.8.1.custom.min.js"></script>

<!-- Include color picker plugin (Not required for calendar plugin. Used for example.) -->
<script type="text/javascript" src="sites/all/modules/urucumbrasil/modules-gabinetevirtual/workshop/theme/agenda/js/colorpicker/colorpicker.js"></script>

<!-- Include jquery tooltip plugin (Not required for calendar plugin. Used for example.) -->
<script type="text/javascript" src="sites/all/modules/urucumbrasil/modules-gabinetevirtual/workshop/theme/agenda/js/jquery-qtip-1.0.0-rc3140944/jquery.qtip-1.0.js"></script>

<!--
	(Required for plugin)
	Dependancies for JQuery Frontier Calendar plugin.
    ** THESE MUST BE INCLUDED BEFORE THE FRONTIER CALENDAR PLUGIN. **
-->
<script type="text/javascript" src="sites/all/modules/urucumbrasil/modules-gabinetevirtual/workshop/theme/agenda/js/lib/jshashtable-2.1.js"></script>

<!-- Include JQuery Frontier Calendar plugin -->
<script type="text/javascript" src="sites/all/modules/urucumbrasil/modules-gabinetevirtual/workshop/theme/agenda/js/frontierCalendar/jquery-frontier-cal-1.3.2.js"></script>


<!-- Some CSS for our example. (Not required for calendar plugin. Used for example.)-->
<style type="text/css" media="screen">
/*
Default font-size on the default ThemeRoller theme is set in ems, and with a value that when combined 
with body { font-size: 62.5%; } will align pixels with ems, so 11px=1.1em, 14px=1.4em. If setting the 
body font-size to 62.5% isn't an option, or not one you want, you can set the font-size in ThemeRoller 
to 1em or set it to px.
http://osdir.com/ml/jquery-ui/2009-04/msg00071.html
*/
body { font-size: 62.5%; }
.shadow {
	-moz-box-shadow: 3px 3px 4px #aaaaaa;
	-webkit-box-shadow: 3px 3px 4px #aaaaaa;
	box-shadow: 3px 3px 4px #aaaaaa;
	/* For IE 8 */
	-ms-filter: "progid:DXImageTransform.Microsoft.Shadow(Strength=4, Direction=135, Color='#aaaaaa')";
	/* For IE 5.5 - 7 */
	filter: progid:DXImageTransform.Microsoft.Shadow(Strength=4, Direction=135, Color='#aaaaaa');
}
</style>

<script type="text/javascript">
	function show_hide_event(element_id,check) {
		if ($(check).is(':checked')) {
			$(element_id).show();
		} else {
			$(element_id).hide();
		}
	}
	function jqCheckAll() {
		$("INPUT[@name=check_calendars][type='checkbox']").attr('checked', true);
		$('.events').show();
	}
	function jqUncheckAll() {
		$("INPUT[@name=check_calendars][type='checkbox']").attr('checked', false);
		$('.events').hide();
	}
</script>

<script type="text/javascript">
$(document).ready(function(){	

	 var clickDate = "";
	 var clickAgendaItem = "";

	 var jfcalplugin = $("#mycal").jFrontierCal({
	 	date: new Date(),
	 	dayClickCallback: myDayClickHandler,
	 	agendaClickCallback: myAgendaClickHandler,
	 	agendaDropCallback: myAgendaDropHandler,
	 	agendaMouseoverCallback: myAgendaMouseoverHandler,
	 	applyAgendaTooltipCallback: myApplyTooltip,
	 	agendaDragStartCallback : myAgendaDragStart,
	 	agendaDragStopCallback : myAgendaDragStop,
	 	dragAndDropEnabled: true
	 }).data("plugin");

	/**
	 * Do something when dragging starts on agenda div
	 */
	 function myAgendaDragStart(eventObj,divElm,agendaItem){
		// destroy our qtip tooltip
		if(divElm.data("qtip")){
			divElm.qtip("destroy");
		}								
	};
	
	/**
	 * Do something when dragging stops on agenda div
	 */
	 function myAgendaDragStop(eventObj,divElm,agendaItem){
		//alert("drag stop");
	};
	
	/**
	 * Custom tooltip - use any tooltip library you want to display the agenda data.
	 * for this example we use qTip - http://craigsworks.com/projects/qtip/
	 *
	 * @param divElm - jquery object for agenda div element
	 * @param agendaItem - javascript object containing agenda data.
	 */
	 function myApplyTooltip(divElm,agendaItem){

		// Destroy currrent tooltip if present
		if(divElm.data("qtip")){
			divElm.qtip("destroy");
		}
		
		var displayData = "";
		
		var title = agendaItem.title;
		var startDate = agendaItem.startDate;
		var endDate = agendaItem.endDate;
		var allDay = agendaItem.allDay;
		var data = agendaItem.data;
		
		displayData += "<br><b>" + title+ "</b><br><br>";
		if(allDay){
			displayData += "(Dia inteiro)<br><br>";
		}else{
			displayData += "<b>Início:</b> " + startDate.toLocaleString() + "<br>" + "<b>Fim:</b> " + endDate.toLocaleString() + "<br><br>";
		}
		for (var propertyName in data) {
			displayData += "<b>" + propertyName + ":</b> " + data[propertyName] + "<br>"
		}
		// use the user specified colors from the agenda item.
		var backgroundColor = agendaItem.displayProp.backgroundColor;
		var foregroundColor = agendaItem.displayProp.foregroundColor;
		var myStyle = {
			border: {
				width: 5,
				radius: 10
			},
			padding: 10, 
			textAlign: "left",
			tip: true,
			name: "dark" // other style properties are inherited from dark theme		
		};
		if(backgroundColor != null && backgroundColor != ""){
			myStyle["backgroundColor"] = backgroundColor;
		}
		if(foregroundColor != null && foregroundColor != ""){
			myStyle["color"] = foregroundColor;
		}
		// apply tooltip
		divElm.qtip({
			content: displayData,
			position: {
				corner: {
					tooltip: "bottomMiddle",
					target: "topMiddle"			
				},
				adjust: { 
					mouse: true,
					x: 0,
					y: -15
				},
				target: "mouse"
			},
			show: { 
				when: { 
					event: 'mouseover'
				}
			},
			style: myStyle
		});

	};

	/**
	 * Make the day cells roughly 3/4th as tall as they are wide. this makes our calendar wider than it is tall. 
	 */
	 jfcalplugin.setAspectRatio("#mycal",0.75);

	/**
	 * Called when user clicks day cell
	 * use reference to plugin object to add agenda item
	 */
	 function myDayClickHandler(eventObj){
		// Get the Date of the day that was clicked from the event object
		var date = eventObj.data.calDayDate;
		// store date in our global js variable for access later
		clickDate = date.getFullYear() + "-" + (date.getMonth()+1) + "-" + date.getDate();
		// open our add event dialog
		$('#add-event-form').dialog('open');
	};
	
	/**
	 * Called when user clicks and agenda item
	 * use reference to plugin object to edit agenda item
	 */
	 function myAgendaClickHandler(eventObj){
		// Get ID of the agenda item from the event object
		var agendaId = eventObj.data.agendaId;		
		// pull agenda item from calendar
		var agendaItem = jfcalplugin.getAgendaItemById("#mycal",agendaId);
		clickAgendaItem = agendaItem;
		$("#display-event-form").dialog('open');
	};
	
	/**
	 * Called when user drops an agenda item into a day cell.
	 */
	 function myAgendaDropHandler(eventObj){
		// Get ID of the agenda item from the event object
		var agendaId = eventObj.data.agendaId;
		var date = eventObj.data.calDayDate;
		var agendaItem = jfcalplugin.getAgendaItemById("#mycal",agendaId);		
		var id = agendaItem.data.Identificador;
		var start_date = agendaItem.startDate.toString();
		var end_date = agendaItem.endDate.toString();

		$.ajax({
            url: '?q=agenda/update',
            type: 'POST',
            dataType: 'json',
			data: {
		        id: id,
		        start_date: start_date,
		        end_date: end_date,
		        field: 'date'
			},
            async: false,
            success: function(json) {
				if (json.response != 0) {
					alert("Dia alterado com sucesso!");
				} else {
					alert("Ocorreu um erro ao atualizar esse evento, por favor tente novamente!");
				}
			}
		});
	};
	
	/**
	 * Called when a user mouses over an agenda item	
	 */
	 function myAgendaMouseoverHandler(eventObj){
	 	var agendaId = eventObj.data.agendaId;
	 	var agendaItem = jfcalplugin.getAgendaItemById("#mycal",agendaId);
		//alert("You moused over agenda item " + agendaItem.title + " at location (X=" + eventObj.pageX + ", Y=" + eventObj.pageY + ")");
	};
	/**
	 * Initialize jquery ui datepicker. set date format to yyyy-mm-dd for easy parsing
	 */
	 $("#dateSelect").datepicker({
	 	showOtherMonths: true,
	 	selectOtherMonths: true,
	 	changeMonth: true,
	 	changeYear: true,
	 	showButtonPanel: true,
	 	dateFormat: 'yy-mm-dd'
	 });

	/**
	 * Set datepicker to current date
	 */
	 $("#dateSelect").datepicker('setDate', new Date());
	/**
	 * Use reference to plugin object to a specific year/month
	 */
	 $("#dateSelect").bind('change', function() {
	 	var selectedDate = $("#dateSelect").val();
	 	var dtArray = selectedDate.split("-");
	 	var year = dtArray[0];
		// jquery datepicker months start at 1 (1=January)		
		var month = dtArray[1];
		// strip any preceeding 0's		
		month = month.replace(/^[0]+/g,"")		
		var day = dtArray[2];
		// plugin uses 0-based months so we subtrac 1
		jfcalplugin.showMonth("#mycal",year,parseInt(month-1).toString());
	});	
	/**
	 * Initialize previous month button
	 */
	 $("#BtnPreviousMonth").button();
	 $("#BtnPreviousMonth").click(function() {
	 	jfcalplugin.showPreviousMonth("#mycal");
		// update the jqeury datepicker value
		var calDate = jfcalplugin.getCurrentDate("#mycal"); // returns Date object
		var cyear = calDate.getFullYear();
		// Date month 0-based (0=January)
		var cmonth = calDate.getMonth();
		var cday = calDate.getDate();
		// jquery datepicker month starts at 1 (1=January) so we add 1
		$("#dateSelect").datepicker("setDate",cyear+"-"+(cmonth+1)+"-"+cday);
		var data = new Array("Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro");
		document.getElementById("boxMonth").innerHTML = "<h3>" + data[cmonth] + " - " + cyear + "</h3>";
		hideEvents();
		return false;
	});
	/**
	 * Initialize next month button
	 */
	 $("#BtnNextMonth").button();
	 $("#BtnNextMonth").click(function() {
	 	jfcalplugin.showNextMonth("#mycal");
		// update the jqeury datepicker value
		var calDate = jfcalplugin.getCurrentDate("#mycal"); // returns Date object
		var cyear = calDate.getFullYear();
		// Date month 0-based (0=January)
		var cmonth = calDate.getMonth();
		var cday = calDate.getDate();
		// jquery datepicker month starts at 1 (1=January) so we add 1
		$("#dateSelect").datepicker("setDate",cyear+"-"+(cmonth+1)+"-"+cday);
		var data = new Array("Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro");
		document.getElementById("boxMonth").innerHTML = "<h3>" + data[cmonth] + " - " + cyear + "</h3>";
		hideEvents();
		return false;
	});

	/**
	 * Initialize delete all agenda items button
	 */
	 $("#BtnDeleteAll").button();
	 $("#BtnDeleteAll").click(function() {	
	 	jfcalplugin.deleteAllAgendaItems("#mycal");	
	 	return false;
	 });		

	/**
	 * Initialize iCal test button
	 */
	 $("#BtnICalTest").button();
	 $("#BtnICalTest").click(function() {
		// Please note that in Google Chrome this will not work with a local file. Chrome prevents AJAX calls
		// from reading local files on disk.		
		jfcalplugin.loadICalSource("#mycal",$("#iCalSource").val(),"html");	
		return false;
	});	

	 $("#add-event-form").dialog({
	 	autoOpen: false,
	 	height: 400,
	 	width: 400,
	 	modal: true,
	 	buttons: {
	 		'Criar evento': function() {

	 			var what = jQuery.trim($("#what").val());

	 			if(what == ""){
	 				alert("Por favor, adicione um título ao seu evento.");
	 			}else{

	 				var startDate = $("#startDate").val();
	 				var startDtArray = startDate.split("-");
	 				var startYear = startDtArray[0];
					// jquery datepicker months start at 1 (1=January)		
					var startMonth = startDtArray[1];		
					var startDay = startDtArray[2];
					// strip any preceeding 0's		
					startMonth = startMonth.replace(/^[0]+/g,"");
					startDay = startDay.replace(/^[0]+/g,"");
					var startHour = jQuery.trim($("#startHour").val());
					var startMin = jQuery.trim($("#startMin").val());
					var startMeridiem = jQuery.trim($("#startMeridiem").val());
					startHour = parseInt(startHour.replace(/^[0]+/g,""));
					if(startMin == "0" || startMin == "00"){
						startMin = 0;
					}else{
						startMin = parseInt(startMin.replace(/^[0]+/g,""));
					}
					if(startMeridiem == "AM" && startHour == 12){
						startHour = 0;
					}else if(startMeridiem == "PM" && startHour < 12){
						startHour = parseInt(startHour) + 12;
					}

					var endDate = $("#endDate").val();
					var endDtArray = endDate.split("-");
					var endYear = endDtArray[0];
					// jquery datepicker months start at 1 (1=January)		
					var endMonth = endDtArray[1];		
					var endDay = endDtArray[2];
					// strip any preceeding 0's		
					endMonth = endMonth.replace(/^[0]+/g,"");

					endDay = endDay.replace(/^[0]+/g,"");
					var endHour = jQuery.trim($("#endHour").val());
					var endMin = jQuery.trim($("#endMin").val());
					var endMeridiem = jQuery.trim($("#endMeridiem").val());
					endHour = parseInt(endHour.replace(/^[0]+/g,""));
					if(endMin == "0" || endMin == "00"){
						endMin = 0;
					}else{
						endMin = parseInt(endMin.replace(/^[0]+/g,""));
					}
					if(endMeridiem == "AM" && endHour == 12){
						endHour = 0;
					}else if(endMeridiem == "PM" && endHour < 12){
						endHour = parseInt(endHour) + 12;
					}

					// Dates use integers
					var startDateObj = new Date(parseInt(startYear),parseInt(startMonth)-1,parseInt(startDay),startHour,startMin,0,0);
					var endDateObj = new Date(parseInt(endYear),parseInt(endMonth)-1,parseInt(endDay),endHour,endMin,0,0);

					$.ajax({
                        url: '?q=agenda/save',
                        type: 'POST',
                        dataType: 'json',
						data: {
					        title: what,
					        start_date: startDateObj.getTime()/1000,
					        end_date: endDateObj.getTime()/1000,
					        event_color: $("#colorBackground").val(),
					        text_color: $("#colorForeground").val(),
						},
                        async: false,
                        success: function(json) {
							if (json.response != 0) {
								jfcalplugin.addAgendaItem(
									"#mycal",
									what,
									startDateObj,
									endDateObj,
									false,
									{
										Identificador: json.response
									},
									{
										backgroundColor: $("#colorBackground").val(),
										foregroundColor: $("#colorForeground").val()
									}
								);
							} else {
								alert("Ocorreu um erro ao adicionar esse evento, por favor tente novamente!");
							}
						}
					});

					$(this).dialog('close');

				}
				
			},
			Cancelar: function() {
				$(this).dialog('close');
			}
		},
		open: function(event, ui){
			// initialize start date picker
			$("#startDate").datepicker({
				showOtherMonths: true,
				selectOtherMonths: true,
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true,
				dateFormat: 'yy-mm-dd'
			});
			// initialize end date picker
			$("#endDate").datepicker({
				showOtherMonths: true,
				selectOtherMonths: true,
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true,
				dateFormat: 'yy-mm-dd'
			});
			// initialize with the date that was clicked
			$("#startDate").val(clickDate);
			$("#endDate").val(clickDate);
			// initialize color pickers
			$("#colorSelectorBackground").ColorPicker({
				color: "#008714",
				onShow: function (colpkr) {
					$(colpkr).css("z-index","10000");
					$(colpkr).fadeIn(500);
					return false;
				},
				onHide: function (colpkr) {
					$(colpkr).fadeOut(500);
					return false;
				},
				onChange: function (hsb, hex, rgb) {
					$("#colorSelectorBackground div").css("backgroundColor", "#" + hex);
					$("#colorBackground").val("#" + hex);
				}
			});
			//$("#colorBackground").val("#1040b0");		
			$("#colorSelectorForeground").ColorPicker({
				color: "#ffffff",
				onShow: function (colpkr) {
					$(colpkr).css("z-index","10000");
					$(colpkr).fadeIn(500);
					return false;
				},
				onHide: function (colpkr) {
					$(colpkr).fadeOut(500);
					return false;
				},
				onChange: function (hsb, hex, rgb) {
					$("#colorSelectorForeground div").css("backgroundColor", "#" + hex);
					$("#colorForeground").val("#" + hex);
				}
			});
			//$("#colorForeground").val("#ffffff");				
			// put focus on first form input element
			$("#what").focus();
		},
		close: function() {
			// reset form elements when we close so they are fresh when the dialog is opened again.
			$("#startDate").datepicker("destroy");
			$("#endDate").datepicker("destroy");
			$("#startDate").val("");
			$("#endDate").val("");
			$("#startHour option:eq(0)").attr("selected", "selected");
			$("#startMin option:eq(0)").attr("selected", "selected");
			$("#startMeridiem option:eq(0)").attr("selected", "selected");
			$("#endHour option:eq(0)").attr("selected", "selected");
			$("#endMin option:eq(0)").attr("selected", "selected");
			$("#endMeridiem option:eq(0)").attr("selected", "selected");			
			$("#what").val("");
			//$("#colorBackground").val("#1040b0");
			//$("#colorForeground").val("#ffffff");
		}
	});

	/**
	 * Initialize display event form.
	 */
	 $("#display-event-form").dialog({
	 	autoOpen: false,
	 	height: 400,
	 	width: 400,
	 	modal: true,
	 	buttons: {		
	 		Cancel: function() {
	 			$(this).dialog('close');
	 		},
	 		'Editar': function() {
	 			if(clickAgendaItem != null){
					var agendaItem = jfcalplugin.getAgendaItemById("#mycal",clickAgendaItem.agendaId);		
		 			$(this).dialog('close');
		 			window.location = "?q=agenda/update/form/"+agendaItem.data.Identificador;
	 			}
	 		},
	 		'Deletar': function() {
	 			if(confirm("Você tem certeza que deseja deletar esse evento?")){
	 				if(clickAgendaItem != null){

						var agendaItem = jfcalplugin.getAgendaItemById("#mycal",clickAgendaItem.agendaId);		
						var id = agendaItem.data.Identificador;

						$.ajax({
				            url: '?q=agenda/delete',
				            type: 'POST',
				            dataType: 'json',
							data: {
						        id: id
							},
				            async: false,
				            success: function(json) {
								if (json.response != 0) {
									jfcalplugin.deleteAgendaItemById("#mycal",clickAgendaItem.agendaId);
								} else {
									alert("Ocorreu um erro ao deletar esse evento, por favor tente novamente!");
								}
							}
						});
					}
					$(this).dialog('close');
				}
			}			
		},
		open: function(event, ui){
			if(clickAgendaItem != null){
				var title = clickAgendaItem.title;
				var startDate = clickAgendaItem.startDate;
				var endDate = clickAgendaItem.endDate;
				var allDay = clickAgendaItem.allDay;
				var data = clickAgendaItem.data;
				// in our example add agenda modal form we put some fake data in the agenda data. we can retrieve it here.
				$("#display-event-form").append(
					"<br><b>" + title+ "</b><br><br>"		
					);				
				if(allDay){
					$("#display-event-form").append(
						"(Evento de dia inteiro)<br><br>"				
						);				
				}else{
					$("#display-event-form").append(
						"<b>Início:</b> " + startDate.toLocaleString() + "<br>" +
						"<b>Fim:</b> " + endDate.toLocaleString() + "<br><br>"				
						);				
				}
				for (var propertyName in data) {
					$("#display-event-form").append("<b>" + propertyName + ":</b> " + data[propertyName] + "<br>");
				}			
			}		
		},
		close: function() {
			// clear agenda data
			$("#display-event-form").html("");
		}
	});	 

	/**
	 * Initialize our tabs
	 */
	 $("#tabs").tabs({
		/*
		 * Our calendar is initialized in a closed tab so we need to resize it when the example tab opens.
		 */
		 show: function(event, ui){
		 	if(ui.index == 1){
		 		jfcalplugin.doResize("#mycal");
		 	}
		 }	
	 });

	 <?php foreach ($events as $ev) : ?>
	    jfcalplugin.addAgendaItem(
		 	"#mycal",
		 	"<?php print $ev->title; ?>",
		 	new Date(<?php print $ev->start_date; ?>*1000),
		 	new Date(<?php print $ev->end_date; ?>*1000),
		 	false,
		 	{
		 		Autor: "<?php print $ev->fname; ?>",
		 		Identificador: <?php print $ev->id; ?>
		 	},
		 	{
		 		backgroundColor: "<?php print $ev->event_color; ?>",
		 		foregroundColor: "<?php print $ev->text_color; ?>",
		 	}
	 	);
	 <?php  endforeach; ?>
 	 $(".events").hide();

	 function hideEvents(){
	 	$(".events").hide();
	 }

	});
</script>

<div class="col_calendar">
	<div class="portlet-header">
	    <div class="portlet-title">
	      <h3 id="calendar_portal_portlet">Calendário</h3>
	    </div>
	 </div>
	<div class="color_666">
		<a href="#calendar" onclick="javascript:jqCheckAll()">Marcar Todos</a> | <a href="#calendar" onclick="javascript:jqUncheckAll();">Limpar</a><?php if(user_access('administer_workshop')){ print ' | <a href="/agenda/aprovar">Aprovar agendas públicas</a>'; } ?>
	</div>
	<br/>
	<?php
		$users = db_query("SELECT DISTINCT(fname) as name FROM agenda");
		foreach ($users as $user)
		{
			$randomColor = dechex(rand(9999,10000000));
			$rgb = rand(0,200) . ',' . rand(0,200) . ',' . rand(0,200);
			echo '<div class=" user" style="background-color:rgb('.$rgb.');">
					  <input name="check_calendars" onclick="javascript:show_hide_event('.$user->name.',this);" type="checkbox">
					  '.$user->name.'
					  <br>
				  </div>';
		}
	?>
</div>
</p>
<div  class="ui-widget-header ui-corner-all" style="padding:3px; vertical-align: middle; white-space:nowrap; overflow: hidden;">
	<button id="BtnPreviousMonth">Mês Anterior</button>
	<button id="BtnNextMonth">Próximo Mês</button>
	&nbsp;
	Data: <input type="text" id="dateSelect" size="7"/>
	&nbsp;
	<!--<button id="BtnDeleteAll">Deletar Tudo</button>-->
	<button id="BtnICalTest">iCal Teste</button>
	<input type="text" id="iCalSource" size="20" value="sites/all/modules/urucumbrasil/modules-gabinetevirtual/workshop/theme/agenda/extra/fifa-world-cup-2010.ics"/>
</div>

<br>
<div align="center">
	<?php
		$month = array("Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro");
	?>
	<div id="boxMonth"><?php print "<h3>".$month[date("n")-1]." - ".date("Y")."</h3>"; ?></div>
</div>
<div id="mycal"></div>

</div>

<!-- debugging-->
<div id="calDebug"></div>

<!-- Add event modal form -->
<style type="text/css">
//label, input.text, select { display:block; }
fieldset { padding:0; border:0; margin-top:25px; }
.ui-dialog .ui-state-error { padding: .3em; }
.validateTips { border: 1px solid transparent; padding: 0.3em; }
</style>
<div id="add-event-form" title="Adicionar um novo evento">
	<p class="validateTips">Todos os campos são obrigatórios.</p>
	<form>
		<fieldset>
			<label for="name">Título do evento</label>
			<input type="text" name="what" id="what" class="text ui-widget-content ui-corner-all" style="margin-bottom:12px; width:95%; padding: .4em;"/>
			<table style="width:100%; padding:5px;">
				<tr>
					<td>
						<label>Data Inicial</label>
						<input type="text" name="startDate" id="startDate" value="" class="text ui-widget-content ui-corner-all" style="margin-bottom:12px; width:95%; padding: .4em;"/>				
					</td>
					<td>&nbsp;</td>
					<td>
						<label>Hora</label>
						<select id="startHour" class="text ui-widget-content ui-corner-all" style="margin-bottom:12px; width:95%; padding: .4em;">
							<option value="12" SELECTED>12</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
						</select>				
						<td>
							<td>
								<label>Minuto</label>
								<select id="startMin" class="text ui-widget-content ui-corner-all" style="margin-bottom:12px; width:95%; padding: .4em;">
									<option value="00" SELECTED>00</option>
									<option value="10">10</option>
									<option value="20">20</option>
									<option value="30">30</option>
									<option value="40">40</option>
									<option value="50">50</option>
								</select>				
								<td>
									<td>
										<label>AM/PM</label>
										<select id="startMeridiem" class="text ui-widget-content ui-corner-all" style="margin-bottom:12px; width:95%; padding: .4em;">
											<option value="AM" SELECTED>AM</option>
											<option value="PM">PM</option>
										</select>				
									</td>
								</tr>
								<tr>
									<td>
										<label>Data Final</label>
										<input type="text" name="endDate" id="endDate" value="" class="text ui-widget-content ui-corner-all" style="margin-bottom:12px; width:95%; padding: .4em;"/>				
									</td>
									<td>&nbsp;</td>
									<td>
										<label>Hora</label>
										<select id="endHour" class="text ui-widget-content ui-corner-all" style="margin-bottom:12px; width:95%; padding: .4em;">
											<option value="12" SELECTED>12</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10">10</option>
											<option value="11">11</option>
										</select>				
										<td>
											<td>
												<label>Minuto</label>
												<select id="endMin" class="text ui-widget-content ui-corner-all" style="margin-bottom:12px; width:95%; padding: .4em;">
													<option value="00" SELECTED>00</option>
													<option value="10">10</option>
													<option value="20">20</option>
													<option value="30">30</option>
													<option value="40">40</option>
													<option value="50">50</option>
												</select>				
												<td>
													<td>
														<label>AM/PM</label>
														<select id="endMeridiem" class="text ui-widget-content ui-corner-all" style="margin-bottom:12px; width:95%; padding: .4em;">
															<option value="AM" SELECTED>AM</option>
															<option value="PM">PM</option>
														</select>				
													</td>				
												</tr>			
											</table>
											<table>
												<tr>
													<td>
														<label>Color de fundo&nbsp;&nbsp;</label>
													</td>
													<td>
														<div id="colorSelectorBackground"><div style="background-color: #008714; width:30px; height:30px; border: 2px solid #000000;"></div></div>
														<input type="hidden" id="colorBackground" value="#008714">
													</td>
													<td>&nbsp;&nbsp;&nbsp;</td>
													<td>
														<label>Color do texto&nbsp;&nbsp;</label>
													</td>
													<td>
														<div id="colorSelectorForeground"><div style="background-color: #ffffff; width:30px; height:30px; border: 2px solid #000000;"></div></div>
														<input type="hidden" id="colorForeground" value="#ffffff">
													</td>						
												</tr>				
											</table>
										</fieldset>
									</form>
								</div>

								<div id="display-event-form" title="Informações do evento">

								</div>	

								<p>&nbsp;</p>
							</div>
						</div>
