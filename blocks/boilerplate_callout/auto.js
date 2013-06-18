var BoilerplateCallout ={
	toggleLinkElements: function(){
		$('#link-elements').toggle();
	},
	toggleUrlType: function(type){
		if (type == "manual"){
			$('#link-url-wrap').css('display', 'block');
			$('#link-cid-wrap').css('display', 'none');
		} else  if (type == "sitemap"){
			$('#link-url-wrap').css('display', 'none');
			$('#link-cid-wrap').css('display', 'block');
		} else if (type == "popup"){
			$('#link-url-wrap').css('display', 'none');
			$('#link-cid-wrap').css('display', 'none');
		}
	}
}
