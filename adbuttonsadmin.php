<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$widget_adbuttons_cfg = array(

'ab_title'				=> '',
'ab_dspcnt'				=> '',
'ab_target' 			=> '',
'ab_adsense' 			=> '',
'ab_adsense_fixed'		=> '',
'ab_adsense_pos'		=> '',
'ab_adsense_pubid'		=> '',
'ab_adsense_channel'	=> '',
'ab_adsense_corners'	=> '',
'ab_adsense_col_border'	=> '',
'ab_adsense_col_title'	=> '',
'ab_adsense_col_bg'		=> '',
'ab_adsense_col_txt'	=> '',
'ab_adsense_col_url'	=> '',
'ab_nocss'				=> '',
'ab_width'				=> '',
'ab_padding'			=> '',
'ab_nofollow'			=> '',
'ab_powered'			=> '',
'ab_yah'				=> '',
'ab_yourad'				=> '',
'ab_yaht'				=> '',
'ab_yahurl'				=> '',
'ab_fix'				=> '',
'ab_count'				=> ''
);

$widget_adbuttons_cfg = ad_buttons_get_config();

$ol_flash = '';

// check if the form has been submitted and validate input
if( $_SERVER['REQUEST_METHOD'] == 'POST' && current_user_can( 'manage_options' )){
	if ( ! isset( $_POST['_abupd'] ) || ! wp_verify_nonce( $_POST['_abupd'], 'update-settings' )) {
		print 'Sorry, your nonce did not verify.';
		exit;
	} else {
	   // process form data
		if (is_numeric ($_POST['ab_dspcnt'])) {
			$widget_adbuttons_cfg['ab_title'] = sanitize_text_field($_POST['ab_title']);
			$widget_adbuttons_cfg['ab_dspcnt'] = (int)$_POST['ab_dspcnt'];
			$widget_adbuttons_cfg['ab_target'] = sanitize_text_field($_POST['ab_target']);
			$widget_adbuttons_cfg['ab_adsense'] = (bool)$_POST['ab_adsense'];
			$widget_adbuttons_cfg['ab_adsense_fixed'] = 1;
			$widget_adbuttons_cfg['ab_adsense_pos'] = (int)$_POST['ab_adsense_pos'];
			if($widget_adbuttons_cfg['ab_adsense_pos'] > $widget_adbuttons_cfg['ab_dspcnt']){
				$widget_adbuttons_cfg['ab_adsense_pos'] = $widget_adbuttons_cfg['ab_dspcnt'];
			}
			$widget_adbuttons_cfg['ab_adsense_pubid'] = sanitize_text_field($_POST['ab_adsense_pubid']);
			$widget_adbuttons_cfg['ab_adsense_channel'] = (int)$_POST['ab_adsense_channel'];
			if (preg_match('/rc:\d{1,2}/', $_POST['ab_adsense_corners']) == 1) $widget_adbuttons_cfg['ab_adsense_corners'] = sanitize_text_field($_POST['ab_adsense_corners']);
			$widget_adbuttons_cfg['ab_adsense_col_border'] = (int)hexdec(trim($_POST['ab_adsense_col_border'], "#"));
			$widget_adbuttons_cfg['ab_adsense_col_title'] = (int)hexdec(trim($_POST['ab_adsense_col_title'], "#"));
			$widget_adbuttons_cfg['ab_adsense_col_bg'] = (int)hexdec(trim($_POST['ab_adsense_col_bg'], "#"));
			$widget_adbuttons_cfg['ab_adsense_col_txt'] = (int)hexdec(trim($_POST['ab_adsense_col_txt'], "#"));
			$widget_adbuttons_cfg['ab_adsense_col_url'] = (int)hexdec(trim($_POST['ab_adsense_col_url'], "#"));
			$widget_adbuttons_cfg['ab_nocss'] = (bool)$_POST['ab_nocss'];
			$widget_adbuttons_cfg['ab_width'] = (int)$_POST['ab_width'];
			$widget_adbuttons_cfg['ab_padding'] = (int)$_POST['ab_padding'];
			$widget_adbuttons_cfg['ab_nofollow'] = (bool)$_POST['ab_nofollow'];
			$widget_adbuttons_cfg['ab_powered'] = (bool)$_POST['ab_powered'];
			$widget_adbuttons_cfg['ab_yah'] = (bool)$_POST['ab_yah'];
			$widget_adbuttons_cfg['ab_yourad'] = (bool)$_POST['ab_yourad'];
			$widget_adbuttons_cfg['ab_yaht'] = sanitize_text_field($_POST['ab_yaht']);
			$widget_adbuttons_cfg['ab_yahurl'] = sanitize_text_field($_POST['ab_yahurl']);	
			$widget_adbuttons_cfg['ab_fix'] = (bool)$_POST['ab_fix'];
			$widget_adbuttons_cfg['ab_count'] = (int)$_POST['ab_count'];					
			update_option('widget_adbuttons_cfg',$widget_adbuttons_cfg);
			$ol_flash = "Your settings have been saved.";
		} else {
			$ab_num_err = 1;
		}
	}
}
?>
<?php if ($ol_flash != '') echo '<div id="message"class="updated fade"><p>' . $ol_flash . '</p></div>'; ?>

<div class="wrap">
<h2>Ad Buttons Settings </h2>

<?php
$widget_adbuttons_cfg = get_option('widget_adbuttons_cfg');
$ab_plugindir = plugins_url( '', __FILE__ );
?>

<script>
	var MSIE = navigator.userAgent.indexOf('MSIE')>=0?true:false;
	var navigatorVersion = navigator.appVersion.replace(/.*?MSIE (\d\.\d).*/g,'$1')/1;
	
	var ad_buttons_form_widget_amount_slider_handle = '<?php echo esc_html($ab_plugindir); ?>/slider_handle.gif';
	var slider_handle_image_obj = false;
	var sliderObjectArray = new Array();
	var slider_counter = 0;
	var slideInProgress = false;
	var handle_start_x;
	var event_start_x;
	var currentSliderIndex;
	
	function ad_buttons_form_widget_cancel_event()
	{
		return false;		
	}
	
	function ad_buttons_getImageSliderHeight(){
		if(!slider_handle_image_obj){
			slider_handle_image_obj = new Image();
			slider_handle_image_obj.src = ad_buttons_form_widget_amount_slider_handle;
		}
		if(slider_handle_image_obj.width>0){
			return;
		}else{
			setTimeout('ad_buttons_getImageSliderHeight()',50);
		}
	}
	
	function ad_buttons_positionSliderImage(e,theIndex,inputObj)
	{
		if(this)inputObj = this;
		if(!theIndex)theIndex = inputObj.getAttribute('sliderIndex');
		var handleImg = document.getElementById('slider_handle' + theIndex);
		var ratio = sliderObjectArray[theIndex]['width'] / (sliderObjectArray[theIndex]['max']-sliderObjectArray[theIndex]['min']);
		var currentValue = sliderObjectArray[theIndex]['formTarget'].value-sliderObjectArray[theIndex]['min'];		
		handleImg.style.left = currentValue * ratio + 'px';			
		ad_buttons_setColorByRGB();
	}
	
	function ad_buttons_adjustFormValue(theIndex)
	{
		var handleImg = document.getElementById('slider_handle' + theIndex);	
		var ratio = sliderObjectArray[theIndex]['width'] / (sliderObjectArray[theIndex]['max']-sliderObjectArray[theIndex]['min']);
		var currentPos = handleImg.style.left.replace('px','');
		sliderObjectArray[theIndex]['formTarget'].value = Math.round(currentPos / ratio) + sliderObjectArray[theIndex]['min'];
		
	}
		
	function ad_buttons_initMoveSlider(e)
	{
	
		if(document.all)e = event;	
		slideInProgress = true;
		event_start_x = e.clientX;
		handle_start_x = this.style.left.replace('px','');
		currentSliderIndex = this.id.replace(/[^\d]/g,'');
		return false;
	}
	
	function ad_buttons_startMoveSlider(e)
	{
		if(document.all)e = event;	
		if(!slideInProgress)return;	
		var leftPos = handle_start_x/1 + e.clientX/1 - event_start_x;
		if(leftPos<0)leftPos = 0;
		if(leftPos/1>sliderObjectArray[currentSliderIndex]['width'])leftPos = sliderObjectArray[currentSliderIndex]['width'];
		document.getElementById('slider_handle' + currentSliderIndex).style.left = leftPos + 'px';
		ad_buttons_adjustFormValue(currentSliderIndex);
		if(sliderObjectArray[currentSliderIndex]['onchangeAction']){
			eval(sliderObjectArray[currentSliderIndex]['onchangeAction']);
		}
	}
	
	function ad_buttons_stopMoveSlider()
	{
		slideInProgress = false;
	}
	
	
	function ad_buttons_form_widget_amount_slider(targetElId,formTarget,width,min,max,onchangeAction)
	{
		if(!slider_handle_image_obj){
			ad_buttons_getImageSliderHeight();		
		}
				
		slider_counter = slider_counter +1;
		sliderObjectArray[slider_counter] = new Array();
		sliderObjectArray[slider_counter] = {"width":width - 9,"min":min,"max":max,"formTarget":formTarget,"onchangeAction":onchangeAction};
		
		formTarget.setAttribute('sliderIndex',slider_counter);
		formTarget.onchange = ad_buttons_positionSliderImage;
		var parentObj = document.createElement('DIV');
		parentObj.style.width = width + 'px';
		parentObj.style.height = '12px';	// The height of the image
		parentObj.style.position = 'relative';
		parentObj.id = 'slider_container' + slider_counter;
		document.getElementById(targetElId).appendChild(parentObj);
		
		var obj = document.createElement('DIV');
		obj.className = 'ad_buttons_form_widget_amount_slider';
		obj.innerHTML = '<span></span>';
		obj.style.width = width + 'px';
		obj.id = 'slider_slider' + slider_counter;
		obj.style.position = 'absolute';
		obj.style.bottom = '0px';
		parentObj.appendChild(obj);
		
		var handleImg = document.createElement('IMG');
		handleImg.style.position = 'absolute';
		handleImg.style.left = '0px';
		handleImg.style.zIndex = 5;
		handleImg.src = slider_handle_image_obj.src;
		handleImg.id = 'slider_handle' + slider_counter;
		handleImg.onmousedown = ad_buttons_initMoveSlider;
		if(document.body.onmouseup){
			if(document.body.onmouseup.toString().indexOf('ad_buttons_stopMoveSlider')==-1){
				alert('You allready have an onmouseup event assigned to the body tag');
			}
		}else{
			document.body.onmouseup = ad_buttons_stopMoveSlider;	
			document.body.onmousemove = ad_buttons_startMoveSlider;	
		}
		handleImg.ondragstart = ad_buttons_form_widget_cancel_event;
		parentObj.appendChild(handleImg);
		ad_buttons_positionSliderImage(false,slider_counter);
	}
		

	
	var namedColors = new Array('AliceBlue','AntiqueWhite','Aqua','Aquamarine','Azure','Beige','Bisque','Black','BlanchedAlmond','Blue','BlueViolet','Brown',
	'BurlyWood','CadetBlue','Chartreuse','Chocolate','Coral','CornflowerBlue','Cornsilk','Crimson','Cyan','DarkBlue','DarkCyan','DarkGoldenRod','DarkGray',
	'DarkGreen','DarkKhaki','DarkMagenta','DarkOliveGreen','Darkorange','DarkOrchid','DarkRed','DarkSalmon','DarkSeaGreen','DarkSlateBlue','DarkSlateGray',
	'DarkTurquoise','DarkViolet','DeepPink','DeepSkyBlue','DimGray','DodgerBlue','Feldspar','FireBrick','FloralWhite','ForestGreen','Fuchsia','Gainsboro',
	'GhostWhite','Gold','GoldenRod','Gray','Green','GreenYellow','HoneyDew','HotPink','IndianRed','Indigo','Ivory','Khaki','Lavender','LavenderBlush',
	'LawnGreen','LemonChiffon','LightBlue','LightCoral','LightCyan','LightGoldenRodYellow','LightGrey','LightGreen','LightPink','LightSalmon','LightSeaGreen',
	'LightSkyBlue','LightSlateBlue','LightSlateGray','LightSteelBlue','LightYellow','Lime','LimeGreen','Linen','Magenta','Maroon','MediumAquaMarine',
	'MediumBlue','MediumOrchid','MediumPurple','MediumSeaGreen','MediumSlateBlue','MediumSpringGreen','MediumTurquoise','MediumVioletRed','MidnightBlue',
	'MintCream','MistyRose','Moccasin','NavajoWhite','Navy','OldLace','Olive','OliveDrab','Orange','OrangeRed','Orchid','PaleGoldenRod','PaleGreen',
	'PaleTurquoise','PaleVioletRed','PapayaWhip','PeachPuff','Peru','Pink','Plum','PowderBlue','Purple','Red','RosyBrown','RoyalBlue','SaddleBrown',
	'Salmon','SandyBrown','SeaGreen','SeaShell','Sienna','Silver','SkyBlue','SlateBlue','SlateGray','Snow','SpringGreen','SteelBlue','Tan','Teal','Thistle',
	'Tomato','Turquoise','Violet','VioletRed','Wheat','White','WhiteSmoke','Yellow','YellowGreen');
	
	 var namedColorRGB = new Array('#F0F8FF','#FAEBD7','#00FFFF','#7FFFD4','#F0FFFF','#F5F5DC','#FFE4C4','#000000','#FFEBCD','#0000FF','#8A2BE2','#A52A2A','#DEB887',
	'#5F9EA0','#7FFF00','#D2691E','#FF7F50','#6495ED','#FFF8DC','#DC143C','#00FFFF','#00008B','#008B8B','#B8860B','#A9A9A9','#006400','#BDB76B','#8B008B',
	'#556B2F','#FF8C00','#9932CC','#8B0000','#E9967A','#8FBC8F','#483D8B','#2F4F4F','#00CED1','#9400D3','#FF1493','#00BFFF','#696969','#1E90FF','#D19275',
	'#B22222','#FFFAF0','#228B22','#FF00FF','#DCDCDC','#F8F8FF','#FFD700','#DAA520','#808080','#008000','#ADFF2F','#F0FFF0','#FF69B4','#CD5C5C','#4B0082',
	'#FFFFF0','#F0E68C','#E6E6FA','#FFF0F5','#7CFC00','#FFFACD','#ADD8E6','#F08080','#E0FFFF','#FAFAD2','#D3D3D3','#90EE90','#FFB6C1','#FFA07A','#20B2AA',
	'#87CEFA','#8470FF','#778899','#B0C4DE','#FFFFE0','#00FF00','#32CD32','#FAF0E6','#FF00FF','#800000','#66CDAA','#0000CD','#BA55D3','#9370D8','#3CB371',
	'#7B68EE','#00FA9A','#48D1CC','#C71585','#191970','#F5FFFA','#FFE4E1','#FFE4B5','#FFDEAD','#000080','#FDF5E6','#808000','#6B8E23','#FFA500','#FF4500',
	'#DA70D6','#EEE8AA','#98FB98','#AFEEEE','#D87093','#FFEFD5','#FFDAB9','#CD853F','#FFC0CB','#DDA0DD','#B0E0E6','#800080','#FF0000','#BC8F8F','#4169E1',
	'#8B4513','#FA8072','#F4A460','#2E8B57','#FFF5EE','#A0522D','#C0C0C0','#87CEEB','#6A5ACD','#708090','#FFFAFA','#00FF7F','#4682B4','#D2B48C','#008080',
	'#D8BFD8','#FF6347','#40E0D0','#EE82EE','#D02090','#F5DEB3','#FFFFFF','#F5F5F5','#FFFF00','#9ACD32');	
	
	
	var color_picker_div = false;
	var color_picker_active_tab = false;
	var color_picker_form_field = false;
	var color_picker_active_input = false;

	function ad_buttons_baseConverter (number,ob,nb) {
		number = number + "";
		number = number.toUpperCase();
		var list = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		var dec = 0;
		for (var i = 0; i <=  number.length; i++) {
			dec += (list.indexOf(number.charAt(i))) * (Math.pow(ob , (number.length - i - 1)));
		}
		number = "";
		var magnitude = Math.floor((Math.log(dec))/(Math.log(nb)));
		for (var i = magnitude; i >= 0; i--) {
			var amount = Math.floor(dec/Math.pow(nb,i));
			number = number + list.charAt(amount); 
			dec -= amount*(Math.pow(nb,i));
		}
		if(number.length==0)number=0;
		return number;
	}
	
	function ad_buttons_colorPickerGetTopPos(inputObj)
	{
		
	  var returnValue = inputObj.offsetTop;
	  while((inputObj = inputObj.offsetParent) != null){
	  	returnValue += inputObj.offsetTop;
	  }
	  return returnValue;
	}
	
	function ad_buttons_colorPickerGetLeftPos(inputObj)
	{
	  var returnValue = inputObj.offsetLeft;
	  while((inputObj = inputObj.offsetParent) != null)returnValue += inputObj.offsetLeft;
	  return returnValue;
	}
	
	function ad_buttons_cancelColorPickerEvent()
	{
		return false;
	}
	
	function ad_buttons_showHideColorOptions(e,inputObj) 
	{
		var thisObj = this;
		if(inputObj){
			var parentNode = inputObj.parentNode; 
			thisObj = inputObj;
		}else var parentNode = this.parentNode;
		var activeColorDiv = false;
		var subDiv = parentNode.getElementsByTagName('DIV')[0];
		counter=0;	
		var initZIndex = 10;	
		var contentDiv = document.getElementById('color_picker_content').getElementsByTagName('DIV')[0];
		do{			
			if(subDiv.tagName=='DIV' && subDiv.className!='colorPickerCloseButton'){
				if(subDiv==thisObj){
					thisObj.className='colorPickerTab_active';
					thisObj.style.zIndex = 50;
					var img = thisObj.getElementsByTagName('IMG')[0];
					img.src = "<?php echo esc_html($ab_plugindir); ?>/tab_right_active.gif"
					img.src = img.src.replace(/inactive/,'active');							
					contentDiv.style.display='block';
					activeColorDiv = contentDiv;
				}else{
					subDiv.className = 'colorPickerTab_inactive';	
					var img = subDiv.getElementsByTagName('IMG')[0];
					img.src = "<?php echo esc_html($ab_plugindir); ?>/tab_right_inactive.gif";
					if(activeColorDiv)
						subDiv.style.zIndex = initZIndex - counter;
					else
						subDiv.style.zIndex = counter;
					contentDiv.style.display='none';
				}
				counter++;
			}
			subDiv = subDiv.nextSibling;
			if(contentDiv.nextSibling)contentDiv = contentDiv.nextSibling;
		}while(subDiv);		
		document.getElementById('colorPicker_statusBarTxt').innerHTML = '&nbsp;';
	}
	
	function ad_buttons_createColorPickerTopRow(inputObj){
		var tabs = ['RGB','Named colors','Color slider'];
		var tabWidths = [37,90,70];
		var div = document.createElement('DIV');
		div.className='colorPicker_topRow';
	
		inputObj.appendChild(div);	
		var currentWidth = 0;
		for(var no=0;no<tabs.length;no++){			
			
			var tabDiv = document.createElement('DIV');
			tabDiv.onselectstart = ad_buttons_cancelColorPickerEvent;
			tabDiv.ondragstart = ad_buttons_cancelColorPickerEvent;
			if(no==0){
				suffix = 'active'; 
				color_picker_active_tab = this;
			}else suffix = 'inactive';
			
			tabDiv.id = 'colorPickerTab' + no;
			tabDiv.onclick = ad_buttons_showHideColorOptions;
			if(no==0)tabDiv.style.zIndex = 50; else tabDiv.style.zIndex = 1 + (tabs.length-no);
			tabDiv.style.left = currentWidth + 'px';
			tabDiv.style.position = 'absolute';
			tabDiv.className='colorPickerTab_' + suffix;
			var tabSpan = document.createElement('SPAN');
			tabSpan.innerHTML = tabs[no];
			tabDiv.appendChild(tabSpan);
			var tabImg = document.createElement('IMG');
			tabImg.src = "<?php echo esc_html($ab_plugindir); ?>/tab_right_" + suffix + ".gif";
			tabDiv.appendChild(tabImg);
			div.appendChild(tabDiv);
			if(navigatorVersion<6 && MSIE){	/* Lower IE version fix */
				tabSpan.style.position = 'relative';
				tabImg.style.position = 'relative';
				tabImg.style.left = '-3px';		
				tabDiv.style.cursor = 'hand';	
			}			
			currentWidth = currentWidth + tabWidths[no];
		}
		
		var closeButton = document.createElement('DIV');
		closeButton.className='colorPickerCloseButton';
		closeButton.innerHTML = 'x';
		closeButton.onclick = ad_buttons_closeColorPicker;
		closeButton.onmouseover = ad_buttons_toggleCloseButton;
		closeButton.onmouseout = ad_buttons_toggleOffCloseButton;
		div.appendChild(closeButton);		
	}
	
	function ad_buttons_toggleCloseButton()
	{
		this.style.color='#FFF';
		this.style.backgroundColor = '#317082';	
	}
	
	function ad_buttons_toggleOffCloseButton()
	{
		this.style.color='';
		this.style.backgroundColor = '';					
	}
	
	function ad_buttons_closeColorPicker()
	{		
		color_picker_div.style.display='none';
	}
	
	function ad_buttons_createWebColors(inputObj){
		var webColorDiv = document.createElement('DIV');
		webColorDiv.style.paddingTop = '1px';
		inputObj.appendChild(webColorDiv);
		for(var r=15;r>=0;r-=3){
			for(var g=0;g<=15;g+=3){
				for(var b=0;b<=15;b+=3){
					var red = ad_buttons_baseConverter(r,10,16) + '';
					var green = ad_buttons_baseConverter(g,10,16) + '';
					var blue = ad_buttons_baseConverter(b,10,16) + '';
					var color = '#' + red + red + green + green + blue + blue;
					var div = document.createElement('DIV');
					div.style.backgroundColor=color;
					div.innerHTML = '<span></span>';
					div.className='colorSquare';
					div.title = color;	
					div.onclick = ad_buttons_chooseColor;
					div.setAttribute('rgbColor',color);
					div.onmouseover = ad_buttons_colorPickerShowStatusBarText;
					div.onmouseout = ad_buttons_colorPickerHideStatusBarText;
					webColorDiv.appendChild(div);
				}
			}
		}
	}
		
	function ad_buttons_createNamedColors(inputObj){
		var namedColorDiv = document.createElement('DIV');
		namedColorDiv.style.paddingTop = '1px';
		namedColorDiv.style.display='none';
		inputObj.appendChild(namedColorDiv);
		for(var no=0;no<namedColors.length;no++){
			var color = namedColorRGB[no];
			var div = document.createElement('DIV');
			div.style.backgroundColor=color;
			div.innerHTML = '<span></span>';
			div.className='colorSquare';
			div.title = namedColors[no];	
			div.onclick = ad_buttons_chooseColor;
			div.onmouseover = ad_buttons_colorPickerShowStatusBarText;
			div.onmouseout = ad_buttons_colorPickerHideStatusBarText;
			div.setAttribute('rgbColor',color);
			namedColorDiv.appendChild(div);				
		}		
	}
	
	function ad_buttons_colorPickerHideStatusBarText()
	{
		document.getElementById('colorPicker_statusBarTxt').innerHTML = '&nbsp;';
	}
	
	function ad_buttons_colorPickerShowStatusBarText()
	{
		var txt = this.getAttribute('rgbColor');
		if(this.title.indexOf('#')<0)txt = txt + " (" + this.title + ")";
		document.getElementById('colorPicker_statusBarTxt').innerHTML = txt;	
	}
	
	function ad_buttons_createAllColorDiv(inputObj){
		var allColorDiv = document.createElement('DIV');
		allColorDiv.style.display='none';
		allColorDiv.className = 'js_color_picker_allColorDiv';
		allColorDiv.style.paddingLeft = '3px';
		allColorDiv.style.paddingTop = '5px';
		allColorDiv.style.paddingBottom = '5px';
		inputObj.appendChild(allColorDiv);	
		
		var labelDiv = document.createElement('DIV');
		labelDiv.className='colorSliderLabel';
		labelDiv.innerHTML = 'R';
		allColorDiv.appendChild(labelDiv);	
		
		var innerDiv = document.createElement('DIV');
		innerDiv.className = 'colorSlider';
		innerDiv.id = 'sliderRedColor';		
		allColorDiv.appendChild(innerDiv);		
		
		var innerDivInput = document.createElement('DIV');
		innerDivInput.className='colorInput';
		
		var input = document.createElement('INPUT');
		input.id = 'js_color_picker_red_color';
		input.maxlength = 3;
		input.style.width = '48px';
		input.style.fontSize = '11px';
		input.name = 'redColor';
		input.value = 0;
		
		innerDivInput.appendChild(input);
		allColorDiv.appendChild(innerDivInput);

		var labelDiv = document.createElement('DIV');
		labelDiv.className='colorSliderLabel';
		labelDiv.innerHTML = 'G';
		allColorDiv.appendChild(labelDiv);	
				
		var innerDiv = document.createElement('DIV');
		innerDiv.className = 'colorSlider';
		innerDiv.id = 'sliderGreenColor';		
		allColorDiv.appendChild(innerDiv);		
		
		var innerDivInput = document.createElement('DIV');
		innerDivInput.className='colorInput';
		
		var input = document.createElement('INPUT');
		input.id = 'js_color_picker_green_color';
		input.maxlength = 3;
		input.style.width = '48px';
		input.style.fontSize = '11px';
		input.name = 'GreenColor';
		input.value = 0;
		
		innerDivInput.appendChild(input);
		allColorDiv.appendChild(innerDivInput);
		
		var labelDiv = document.createElement('DIV');
		labelDiv.className='colorSliderLabel';
		labelDiv.innerHTML = 'B';
		allColorDiv.appendChild(labelDiv);			
		var innerDiv = document.createElement('DIV');
		innerDiv.className = 'colorSlider';
		innerDiv.id = 'sliderBlueColor';		
		allColorDiv.appendChild(innerDiv);		
		
		var innerDivInput = document.createElement('DIV');
		innerDivInput.className='colorInput';
		
		var input = document.createElement('INPUT');
		input.id = 'js_color_picker_blue_color';
		input.maxlength = 3;
		input.style.width = '48px';
		input.style.fontSize = '11px';
		input.name = 'BlueColor';
		input.value = 0;
		
		innerDivInput.appendChild(input);
		allColorDiv.appendChild(innerDivInput);

		var colorPreview = document.createElement('DIV');
		colorPreview.className='colorPreviewDiv';
		colorPreview.id = 'colorPreview';
		colorPreview.style.backgroundColor = '#000000';
		colorPreview.innerHTML = '<span></span>';	
		colorPreview.title = 'Click on me to assign color';	
		allColorDiv.appendChild(colorPreview);
		colorPreview.onclick = ad_buttons_chooseColorSlider;
		
		var colorCodeDiv = document.createElement('DIV');
		colorCodeDiv.className='colorCodeDiv';		
		var input = document.createElement('INPUT');
		input.id = 'js_color_picker_color_code';
		
		colorCodeDiv.appendChild(input);
		input.maxLength = 7;
		input.style.fontSize = '11px';
		input.style.width = '48px';		
		input.value = '#000000';
		input.onchange = ad_buttons_setPreviewColorFromTxt;
		input.onblur = ad_buttons_setPreviewColorFromTxt;
		allColorDiv.appendChild(colorCodeDiv);
		
		var clearingDiv = document.createElement('DIV');
		clearingDiv.style.clear = 'both';
		allColorDiv.appendChild(clearingDiv);
				
		ad_buttons_form_widget_amount_slider('sliderRedColor',document.getElementById('js_color_picker_red_color'),170,0,255,"ad_buttons_setColorByRGB()");
		ad_buttons_form_widget_amount_slider('sliderGreenColor',document.getElementById('js_color_picker_green_color'),170,0,255,"ad_buttons_setColorByRGB()");
		ad_buttons_form_widget_amount_slider('sliderBlueColor',document.getElementById('js_color_picker_blue_color'),170,0,255,"ad_buttons_setColorByRGB()");
	}
	
	function ad_buttons_setPreviewColorFromTxt()
	{
		if(this.value.match(/\#[0-9A-F]{6}/g)){
			document.getElementById('colorPreview').style.backgroundColor=this.value;
			var r = this.value.substr(1,2);
			var g = this.value.substr(3,2);
			var b = this.value.substr(5,2);
			document.getElementById('js_color_picker_red_color').value = ad_buttons_baseConverter(r,16,10);
			document.getElementById('js_color_picker_green_color').value = ad_buttons_baseConverter(g,16,10);
			document.getElementById('js_color_picker_blue_color').value = ad_buttons_baseConverter(b,16,10);
			
			ad_buttons_positionSliderImage(false,1,document.getElementById('js_color_picker_red_color'));
			ad_buttons_positionSliderImage(false,2,document.getElementById('js_color_picker_green_color'));
			ad_buttons_positionSliderImage(false,3,document.getElementById('js_color_picker_blue_color'));
		}		
	}
	
	function ad_buttons_chooseColor()
	{
		color_picker_form_field.value = this.getAttribute('rgbColor');
		color_picker_div.style.display='none';
	}
	
	function ad_buttons_createStatusBar(inputObj)
	{
		var div = document.createElement('DIV');
		div.className='colorPicker_statusBar';	
		var innerSpan = document.createElement('SPAN');
		innerSpan.id = 'colorPicker_statusBarTxt';
		div.appendChild(innerSpan);
		inputObj.appendChild(div);
	}
	
	function ad_buttons_chooseColorSlider()
	{
		color_picker_form_field.value = document.getElementById('js_color_picker_color_code').value;
		color_picker_div.style.display='none';		
	}
	
	
	function ad_buttons_showColorPicker(inputObj,formField)
	{
		if(!color_picker_div){
			color_picker_div = document.createElement('DIV');
			color_picker_div.id = 'dhtmlgoodies_colorPicker';
			color_picker_div.style.display='none';
			document.body.appendChild(color_picker_div);
			ad_buttons_createColorPickerTopRow(color_picker_div);			
			var contentDiv = document.createElement('DIV');
			contentDiv.id = 'color_picker_content';
			color_picker_div.appendChild(contentDiv);			
			ad_buttons_createWebColors(contentDiv);
			ad_buttons_createNamedColors(contentDiv);
			ad_buttons_createAllColorDiv(contentDiv);
			ad_buttons_createStatusBar(color_picker_div);			
		}		
		if(color_picker_div.style.display=='none' || color_picker_active_input!=inputObj)color_picker_div.style.display='block'; else color_picker_div.style.display='none';		
		color_picker_div.style.left = ad_buttons_colorPickerGetLeftPos(inputObj) + 'px';
		color_picker_div.style.top = ad_buttons_colorPickerGetTopPos(inputObj) + inputObj.offsetHeight + 2 + 'px';
		color_picker_form_field = formField;
		color_picker_active_input = inputObj;		
	}

	function ad_buttons_setColorByRGB()
	{
		var formObj = document.forms[0];	
		var r = document.getElementById('js_color_picker_red_color').value.replace(/[^\d]/,'');
		var g = document.getElementById('js_color_picker_green_color').value.replace(/[^\d]/,'');
		var b = document.getElementById('js_color_picker_blue_color').value.replace(/[^\d]/,'');		
		if(r/1>255)r=255;
		if(g/1>255)g=255;
		if(b/1>255)b=255;
		r = ad_buttons_baseConverter(r,10,16) + '';
		g = ad_buttons_baseConverter(g,10,16) + '';
		b = ad_buttons_baseConverter(b,10,16) + '';
		if(r.length==1)r = '0' + r;
		if(g.length==1)g = '0' + g;
		if(b.length==1)b = '0' + b;

		document.getElementById('colorPreview').style.backgroundColor = '#' + r + g + b;
		document.getElementById('js_color_picker_color_code').value = '#' + r + g + b;		
	}	

</script> 
<style>
	#dhtmlgoodies_colorPicker{
		position:absolute;
		width:250px;
		padding-bottom:1px;
		background-color:#FFF;
		border:1px solid #317082;
		
		width: 252px;	/* IE 5.x */
		width/* */:/**/250px;	/* Other browsers */
		width: /**/250px;	
	}
	
	#dhtmlgoodies_colorPicker .colorPicker_topRow{
		padding-bottom:1px;
		border-bottom:3px double #317082;
		background-color:#E2EBED;
		padding-left:2px;
		
		width: 250px;	/* IE 5.x */
		width/* */:/**/248px;	/* Other browsers */
		width: /**/248px;	
		
		height: 20px;	/* IE 5.x */
		height/* */:/**/16px;	/* Other browsers */
		height: /**/16px;					
	}
	
	#dhtmlgoodies_colorPicker .colorPicker_statusBar{
		height:13px;
		padding-bottom:2px;
		width:248px;
		border-top:3px double #317082;	
		background-color:#E2EBED;
		padding-left:2px;
		clear:both;
		
		width: 250px;	/* IE 5.x */
		width/* */:/**/248px;	/* Other browsers */
		width: /**/248px;	
		
		height: 18px;	/* IE 5.x */
		height/* */:/**/13px;	/* Other browsers */
		height: /**/13px;							
	}
	
	#dhtmlgoodies_colorPicker .colorSquare{
		margin-left:1px;
		margin-bottom:1px;
		float:left;
		border:1px solid #000;
		cursor:pointer;
		
		width: 12px;	/* IE 5.x */
		width/* */:/**/10px;	/* Other browsers */
		width: /**/10px;	
		
		height: 12px;	/* IE 5.x */
		height/* */:/**/10px;	/* Other browsers */
		height: /**/10px;					
	}
	
	.colorPickerTab_inactive,.colorPickerTab_active{
	
		height:17px;
		padding-left:4px;
		cursor:pointer;					
	}
	
	.colorPickerTab_inactive span{
		background-image:url("<?php echo esc_html($ab_plugindir); ?>/tab_left_inactive.gif");
	}
	
	.colorPickerTab_active span{
		background-image:url("<?php echo esc_html($ab_plugindir); ?>/tab_left_active.gif");
	}
	
	.colorPickerTab_inactive span, .colorPickerTab_active span{
		line-height:16px;
		font-weight:bold;
		font-family:arial;
		font-size:11px;
		padding-top:1px;
		vertical-align:middle;
		background-position:top left;
		background-repeat: no-repeat;	
		float:left;
		padding-left:6px;
		-moz-user-select:no;
	}
	
	.colorPickerTab_inactive img,.colorPickerTab_active img{
		float:left;
	}
	
	.colorPickerCloseButton{
		width:11px;
		height:11px;
		text-align:center;
		line-height:10px;
		border:1px solid #317082;
		position:absolute;
		right:1px;
		font-size:12px;
		font-weight:bold;
		top:1px;
		padding:1px;
		cursor:pointer;	
		
		width: 15px;	/* IE 5.x */
		width/* */:/**/11px;	/* Other browsers */
		width: /**/11px;
		
		height: 15px;	/* IE 5.x */
		height/* */:/**/11px;	/* Other browsers */
		height: /**/11px;			
	}
	
	#colorPicker_statusBarTxt{
		font-size:11px;
		font-family:arial;
		vertical-align:top;
		line-height:13px;
	}
	
	form{
		padding-left:5px;
	}
	
	.ad_buttons_form_widget_amount_slider{
		border-top:1px solid #9d9c99;
		border-left:1px solid #9d9c99;
		border-bottom:1px solid #eee;
		border-right:1px solid #eee;
		background-color:#f0ede0;
		position:absolute;
		bottom:0px;
		
		width: 5px;	/* IE 5.x */
		width/* */:/**/3px;	/* Other browsers */
		width: /**/3px;
		
		height: 5px;	/* IE 5.x */
		height/* */:/**/3px;	/* Other browsers */
		height: /**/3px;
				
	}
	
	.colorSliderLabel{
		width:15px;
		height:20px;
		float:left;
		font-size:11px;
		font-weight:bold;
	}
	
	.colorSlider{
		width:175px;
		height:20px;
		float:left;
	}
	
	.colorInput{
		width:45px;
		height:20px;
		float:left;
	}
	
	.colorPreviewDiv{
		width:186px;
		margin-right:2px;
		margin-top:1px;
		border:1px solid #CCC;
		height:20px;
		float:left;
		cursor:pointer;
		
		width: 188px;	/* IE 5.x */
		width/* */:/**/186px;	/* Other browsers */
		width: /**/186px;
		
		height: 22px;	/* IE 5.x */
		height/* */:/**/20px;	/* Other browsers */
		height: /**/20px;
				

	}
	
	.colorCodeDiv{
		width:50px;
		height:20px;
		float:left;
	}
	
</style>

<form method="post">
<?php wp_nonce_field( 'update-settings', '_abupd' ); ?>
<table class="form-table">
<tr>
<th scope="row">Number of Ad Buttons to display in the sidebar widget</th>
<td><input type="text" name="ab_dspcnt" value="<?php echo esc_html($widget_adbuttons_cfg['ab_dspcnt']); ?>" /><input name="ab_title" type="hidden" value="<?php echo esc_html($widget_adbuttons_cfg['ab_title']); ?>"></td>
<td><?php if(isset($ab_num_err)) echo"Please only enter numbers"; ?></td>
</tr>
<tr>
<th scope="row">Ad order</th>
<td>
	<input name="ab_fix" type="checkbox" id="ab_fix" value="1" <?php if($widget_adbuttons_cfg['ab_fix']){echo "checked";} ?> > 
	fixed position <br/>
</td>
<td>
	When checked, the ads will show in a fixed order. When unchecked, the ads will show in random order</td>
</tr>
<tr>
<th scope="row">Target attribute for ad links</th>
<td>
	<input name="ab_target" type="radio" value="bnk" <?php if($widget_adbuttons_cfg['ab_target']=='bnk'){echo"checked";} ?> > 
	target "_blank"<br/>
	<input name="ab_target" type="radio" value="top" <?php if($widget_adbuttons_cfg['ab_target']=='top'){echo"checked";} ?> > 
	target "_top"<br/>
	<input name="ab_target" type="radio" value="non" <?php if($widget_adbuttons_cfg['ab_target']=='non'){echo"checked";} ?> > 
	don't use target attribute
</td>
<td>
	open links in new tab/window<br/>
	open links in current window<br/>
	Strict DOCTYPE compatibility
</td>
</tr>
<tr>
<th scope="row">Target attribute for ad links</th>
<td>
	<input name="ab_nofollow" type="checkbox" id="ab_nofollow" value="1" <?php if($widget_adbuttons_cfg['ab_nofollow']){echo"checked";} ?> > 
	use nofollow links <br/>
</td>
<td>
	When checked, this ads the rel=&quot;nofollow&quot; attribute to the Ad Buttons links</td>
</tr>
<tr>
<th scope="row">Link love</th>
<td>
	<input name="ab_powered" type="checkbox" id="ab_powered" value="1" <?php if($widget_adbuttons_cfg['ab_powered']){echo"checked";} ?> > 
	show 'powered by Ad Buttons' link <br/>
</td>
<td> Show some link love to the author of this plugin. If you need to hide the link, please considerer making a <a href="http://blogio.net/blog/donate/" target="_blank">small donation</a> to the Ad Buttons project . </td>
</tr>



<tr>
<th scope="row">Stats</th>
<td>
	<input name="ab_count" type="checkbox" id="ab_count" value="1" <?php if($widget_adbuttons_cfg['ab_count']){echo"checked";} ?> > 
	count views and clicks of logged in users<br/>
</td>
<td> When unchecked, ad views and clicks of logged in users will not be recorded in the Stats. </td>
</tr>



<tr>
<td colspan="3"><hr></td>
</tr>
<tr>
<th scope="row">Show 'Your Ad Here' button</th>
<td><input name="ab_yah" type="checkbox" id="ab_yah" value="1" <?php if($widget_adbuttons_cfg['ab_yah']){echo"checked";} ?> ></td>
<td>When checked, this shows a button linking to your advertizing details page </td>
</tr>
<tr>
                       			 <th scope="row">'Your Ad Here' page<br>
                       			   or<br>
                       			   'Your Ad Here' url </th>
                        		 <td>
								<input name="ab_yaht" type="radio" value="pag" <?php if($widget_adbuttons_cfg['ab_yaht']=='pag'){echo"checked";} ?> >
								<?php 
								$args = array(
									'depth'            => 0,
									'child_of'         => 0,
									'selected'         => $widget_adbuttons_cfg['ab_yourad'],
									'echo'             => 1,
									'name'             => 'ab_yourad'
								 	);
								wp_dropdown_pages($args); ?><br/>
								<input name="ab_yaht" type="radio" value="url" <?php if($widget_adbuttons_cfg['ab_yaht']=='url'){echo"checked";} ?> >
								<input name="ab_yahurl" type="text" value="<?php if($widget_adbuttons_cfg['ab_yahurl']){echo $widget_adbuttons_cfg['ab_yahurl'];}else{echo"http://";} ?>" size="25" maxlength="200">

						
</td>
<td>The page on your site that contains details about advertizing on your website. This page should contain contact details, website statistics, advertizing plans, etc. </td>
   	  </tr> 
<tr>

<td colspan="3"><hr></td>
</tr>
<tr>
<th scope="row">AdSense</th>
<td><input name="ab_adsense" type="checkbox" id="ab_adsense" value="1" <?php if($widget_adbuttons_cfg['ab_adsense']){echo"checked";} ?> >
enable AdSense</td>
<td>this adds one 125 x 125 AdSense ad to your rotating ad pool</td>
</tr>
<tr>
<th scope="row">AdSense fixed position</th>
<td><input name="ab_adsense_fixed" type="hidden" id="ab_adsense_fixed" value="1">
<select name="ab_adsense_pos">
	<?php $counter = 1;
	while($counter <= (int)$widget_adbuttons_cfg['ab_dspcnt']){
		echo "<option value=\"$counter\"";
		if((int)$widget_adbuttons_cfg['ab_adsense_pos']==$counter){echo" selected";}
		echo ">$counter</option>";
		$counter = $counter + 1;
	} ?>
</select></td>
<td>show the adsense ad always in this ad position </td>
</tr>
<tr>
<th scope="row">Ad client</th>
<td><input name="ab_adsense_pubid" type="text" value="<?php if($widget_adbuttons_cfg['ab_adsense_pubid']){echo esc_html($widget_adbuttons_cfg['ab_adsense_pubid']);}else{echo"pub-";} ?>" size="25" maxlength="25"></td>
<td>your AdSense Publisher ID (pub-xxxxxxxxxxxxxxxx)</td>
</tr>
<tr>
<th scope="row">Ad channel </th>
<td><input name="ab_adsense_channel" type="text" value="<?php if($widget_adbuttons_cfg['ab_adsense_channel']){echo esc_html($widget_adbuttons_cfg['ab_adsense_channel']);} ?>" size="25" maxlength="25"></td>
<td>optional ad channel (needs to be created in your AdSense account first) </td>
</tr>
<tr>
<th scope="row">AdSense corner style</th>
<td><select name="ab_adsense_corners" size="1">
  <option value="rc:0" <?php if($widget_adbuttons_cfg['ab_adsense_corners']=='rc:0'){echo" selected";} ?>>Square</option>
  <option value="rc:6" <?php if($widget_adbuttons_cfg['ab_adsense_corners']=='rc:6'){echo" selected";} ?>>Slightly rounded</option>
  <option value="rc:10" <?php if($widget_adbuttons_cfg['ab_adsense_corners']=='rc:10'){echo" selected";} ?>>Very rounded</option>
</select></td>
<td>AdSense corner style </td>
</tr>
<tr>
<th scope="row">AdSense colors</th>
<td>
<table>
	<tr>
		<td>
		border color
		</td>
		<td>
		<input name="ab_adsense_col_border" type="text" id="ab_adsense_col_border" value="#<?php echo esc_html(dechex($widget_adbuttons_cfg['ab_adsense_col_border'])); ?>" size="7" maxlength="7">
		</td>
		<td>
		<input type="button" value="Color picker" onclick="ad_buttons_showColorPicker(this,document.forms[0].ab_adsense_col_border)">
		</td>
	</tr>
	<tr>
		<td>
		title color
		</td>
		<td>
		<input name="ab_adsense_col_title" type="text" id="ab_adsense_col_title" value="#<?php echo esc_html(dechex($widget_adbuttons_cfg['ab_adsense_col_title'])); ?>" size="7" maxlength="7">
		</td>
		<td>
		<input type="button" value="Color picker" onclick="ad_buttons_showColorPicker(this,document.forms[0].ab_adsense_col_title)">
		</td>
	</tr>
	<tr>
		<td>
		background color 
		</td>
		<td>
		<input name="ab_adsense_col_bg" type="text" id="ab_adsense_col_bg" value="#<?php echo esc_html(dechex($widget_adbuttons_cfg['ab_adsense_col_bg'])); ?>" size="7" maxlength="7">
		</td>
		<td>
		<input type="button" value="Color picker" onclick="ad_buttons_showColorPicker(this,document.forms[0].ab_adsense_col_bg)">
		</td>
	</tr>
	<tr>
		<td>
		text color
		</td>
		<td>
		<input name="ab_adsense_col_txt" type="text" id="ab_adsense_col_txt" value="#<?php echo esc_html(dechex($widget_adbuttons_cfg['ab_adsense_col_txt'])); ?>" size="7" maxlength="7">
		</td>
		<td>
		<input type="button" value="Color picker" onclick="ad_buttons_showColorPicker(this,document.forms[0].ab_adsense_col_txt)">
		</td>
	</tr>
	<tr>
		<td>
		link color
		</td>
		<td>
		<input name="ab_adsense_col_url" type="text" id="ab_adsense_col_url" value="#<?php echo esc_html(dechex($widget_adbuttons_cfg['ab_adsense_col_url'])); ?>" size="7" maxlength="7">
		</td>
		<td>
		<input type="button" value="Color picker" onclick="ad_buttons_showColorPicker(this,document.forms[0].ab_adsense_col_url)">
		</td>
	</tr>
</table></td>
<td>use hex color codes with a lenght of 7 characters <br/> 
(i.e. #FFFFFF = white, #000000 = black, #0000FF = blue)<br/>
preview of your current ad settings:<br/>  
<script><!--
google_adtest = "on";
google_ad_client = "ca-google-asfe";
google_ad_width = 125;
google_ad_height = 125;
google_ad_format = "125x125_as";
google_ad_type = "text";
google_ad_channel = "";
google_color_border = "<?php echo esc_html(dechex($widget_adbuttons_cfg['ab_adsense_col_border'])); ?>";
google_color_bg = "<?php echo esc_html(dechex($widget_adbuttons_cfg['ab_adsense_col_bg'])); ?>";
google_color_link = "<?php echo esc_html(dechex($widget_adbuttons_cfg['ab_adsense_col_title'])); ?>";
google_color_text = "<?php echo esc_html(dechex($widget_adbuttons_cfg['ab_adsense_col_txt'])); ?>";
google_color_url = "<?php echo esc_html(dechex($widget_adbuttons_cfg['ab_adsense_col_url'])); ?>";
google_ui_features = "<?php echo esc_html($widget_adbuttons_cfg['ab_adsense_corners']); ?>";
//-->
</script>
<script src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br/><strong>please remember not to click on your own AdSense ads, this will get you banned from AdSense!
</strong></td>
</tr>
<tr>
<td colspan="3"><hr></td>
</tr>
<tr >
<th scope="row">Disable css styling</th>
<td><input name="ab_nocss" type="checkbox" id="ab_nocss" value="1" <?php if($widget_adbuttons_cfg['ab_nocss']){echo"checked";} ?> >
disable css </td>
<td>check to disable below parameters for layout control. With some wordpress themes the layout breaks when using css styling on the ads </td>
</tr>
<tr>
<th scope="row">Ad block width </th>
<td><input name="ab_width" type="text" value="<?php if($widget_adbuttons_cfg['ab_width']){echo esc_html($widget_adbuttons_cfg['ab_width']);} ?>" size="4" maxlength="4"></td>
<td>width of your sidebar </td>
</tr>
<tr>
<th scope="row">Ad padding </th>
<td><input name="ab_padding" type="text" value="<?php if($widget_adbuttons_cfg['ab_padding']){echo esc_html($widget_adbuttons_cfg['ab_padding']);} ?>" size="4" maxlength="4"></td>
<td>size of the padding arround your ads </td>
</tr>
<tr>
<td colspan="3">
preview:<br/>

<style>
#ab_adblock
{
width: <?php echo esc_html($widget_adbuttons_cfg['ab_width']); ?>px;
border:1px solid #ccc;
padding:<?php echo esc_html($widget_adbuttons_cfg['ab_padding']); ?>px;
}
#ab_adblock img
{
float: left;
padding:<?php echo esc_html($widget_adbuttons_cfg['ab_padding']); ?>px;
}
#ab_adsense
{
float: left;
padding:<?php echo esc_html($widget_adbuttons_cfg['ab_padding']); ?>px;
}
#ab_clear
{
clear: both;
}
</style>
<div id="ab_adblock"><?php 
if($widget_adbuttons_cfg['ab_adsense']){
	$count = 1;
}else{
	$count = 0;
}

while($count < $widget_adbuttons_cfg['ab_dspcnt']){
	if($widget_adbuttons_cfg['ab_adsense']){
		if($widget_adbuttons_cfg['ab_adsense_pos']==$count){
			echo '<div id="ab_adsense"><script><!--
					google_adtest = "on";
					google_ad_client = "ca-google-asfe";
					google_ad_width = 125;
					google_ad_height = 125;
					google_ad_format = "125x125_as";
					google_ad_type = "text";
					google_ad_channel = "";
					google_color_border = "'.esc_html(dechex($widget_adbuttons_cfg['ab_adsense_col_border'])).'";
					google_color_bg = "'.esc_html(dechex($widget_adbuttons_cfg['ab_adsense_col_bg'])).'";
					google_color_link = "'.esc_html(dechex($widget_adbuttons_cfg['ab_adsense_col_title'])).'";
					google_color_text = "'.esc_html(dechex($widget_adbuttons_cfg['ab_adsense_col_txt'])).'";
					google_color_url = "'.esc_html(dechex($widget_adbuttons_cfg['ab_adsense_col_url'])).'";
					google_ui_features = "'.esc_html(dechex($widget_adbuttons_cfg['ab_adsense_corners'])).'";
					//-->
					</script>
					<script src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
					</script></div>'; 
		}
	}	
	echo'<img src="'.esc_html($ab_plugindir).'/ad_button.jpg" alt="ad button">';
	$count = $count + 1;
}

if($widget_adbuttons_cfg['ab_adsense']){
	if($widget_adbuttons_cfg['ab_adsense_pos']==$count){
		echo '<div id="ab_adsense"><script><!--
				google_adtest = "on";
				google_ad_client = "ca-google-asfe";
				google_ad_width = 125;
				google_ad_height = 125;
				google_ad_format = "125x125_as";
				google_ad_type = "text";
				google_ad_channel = "";
				google_color_border = "'.esc_html(dechex($widget_adbuttons_cfg['ab_adsense_col_border'])).'";
				google_color_bg = "'.esc_html(dechex($widget_adbuttons_cfg['ab_adsense_col_bg'])).'";
				google_color_link = "'.esc_html(dechex($widget_adbuttons_cfg['ab_adsense_col_title'])).'";
				google_color_text = "'.esc_html(dechex($widget_adbuttons_cfg['ab_adsense_col_txt'])).'";
				google_color_url = "'.esc_html(dechex($widget_adbuttons_cfg['ab_adsense_col_url'])).'";
				google_ui_features = "'.esc_html(dechex($widget_adbuttons_cfg['ab_adsense_corners'])).'";
				//-->
				</script>
				<script src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
				</script></div>'; 
	}
}
	?>
	<div id="ab_clear"></div>
	</div>
<p class="submit"><input type="submit" name="Submit" class="button button-primary" value="<?php _e('Save Changes') ?>" /></p>
</td></tr>
</table>
</form>

</div>
