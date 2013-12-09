function iColorShow(id,id2){
	var eICP=$("#"+id2).position();
	var picker = $("#iColorPicker")
	picker.css({'top':eICP.top+($("#"+id).outerHeight())+"px",'left':(eICP.left)+"px",'position':'absolute'}).fadeIn("fast");
	picker_bg = $("#iColorPickerBg")
	picker_bg.css({'position':'fixed','top':0,'left':0,'width':'100%','height':'100%'}).fadeIn("fast");
	var def=$("#"+id).val();
	$('#colorPreview span').text(def);
	$('#colorPreview').css('background',def);$('#color').val(def);
	var hxs=$('#iColorPicker');
	for(i=0;i<hxs.length;i++){
		var tbl=document.getElementById('hexSection'+i);
		var tblChilds=tbl.childNodes;
		for(j=0;j<tblChilds.length;j++){
				var tblCells=tblChilds[j].childNodes;
				for(k=0;k<tblCells.length;k++){
					$(tblChilds[j].childNodes[k]).unbind().mouseover(function(a){
						var aaa="#"+$(this).attr('hx');
						$('#colorPreview').css('background',aaa);
						$('#colorPreview span').text(aaa)}).mouseover(function(){
							var aaa="#"+$(this).attr('hx');
							$("#"+id).val(aaa).css("background",aaa);
							$("#"+id).val(aaa).attr("value",aaa);
							$("#"+id).trigger("change");
							}).click(function(){
							$("#iColorPickerBg").hide();
							$("#iColorPicker").fadeOut("fast");
							$(this)})}}}
}
