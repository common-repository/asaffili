

jQuery(document).ready(function()
{

jQuery('#button-asaffilipro-actions-import').click(function()
{
	
	var url = jQuery('#button-asaffilipro-actions-import').attr('data-first');
				
	jQuery('#asaffilipro-actions-import-wrapper').html('Import wird gestartet...');
	
	jQuery.post(
		asaffili_ajaxurl.ajaxurl,
		{
			action:'asaffilipro-actions-import-file',
			url:url			
		},
		function(response){jQuery('#asaffilipro-actions-import-wrapper').html(response);}
	);
	
});


jQuery('#button-asaffilipro-actions-import-webgains').click(function()
{
	
	var url = jQuery('#button-asaffilipro-actions-import-webgains').attr('data-first');
				
	jQuery('#asaffilipro-actions-import-webgains-wrapper').html('Import wird gestartet...');
	
	jQuery.post(
		asaffili_ajaxurl.ajaxurl,
		{
			action:'asaffilipro-actions-import-webgains-url',
			url:url			
		},
		function(response){jQuery('#asaffilipro-actions-import-webgains-wrapper').html(response);}
	);
	
});




jQuery('#button-asaffilipro-voucher-import-webgains').click(function()
{
	
	var url = jQuery('#button-asaffilipro-voucher-import-webgains').attr('data-first');
				
	jQuery('#asaffilipro-voucher-import-webgains-wrapper').html('Import wird gestartet...');
	
	jQuery.post(
		asaffili_ajaxurl.ajaxurl,
		{
			action:'asaffilipro-voucher-import-webgains-file',
			url:url			
		},
		function(response){jQuery('#asaffilipro-voucher-import-webgains-wrapper').html(response);}
	);
	
});





jQuery('#button-asaffili-set-new-cat').on("click",function()
{	
	var newcatstr= jQuery('#temp_asaffili_new_cat_str').val();
	var topcatid=jQuery('#temp_asaffili_top_catid').val();
	var url=jQuery('#temp_asaffili_url').val();
	var id= jQuery('#temp_asaffili_import_id').val();
    var importsource = jQuery('#temp_asaffili_importsource').val();
         
    jQuery.ajax({
        type: "POST",
        async: "false",
        url: asaffili_ajaxurl.ajaxurl,
        data: {action:'asaffilinewcatstr',			
			topcatid:topcatid,
			newcatstr:newcatstr,
			importsource:importsource,
			url:url,
			id:id},
        	success: function (data, textStatus, XMLHttpRequest)
        	{
            	//alert("Erfolg"+data);
            	location.href=url;
        	},
        	error: function (XMLHttpRequest, textStatus, errorThrown) {
            	alert("Fehler "+errorThrown);
        	}
       
    });	
});

	
jQuery('#button-asaffili-set-catid').on("click",function()
{
	//var id= jQuery('#button-asaffili-set-import').attr('data-first');
	
	var id= jQuery('#temp_asaffili_import_id').val();
	var newcatid=jQuery('#temp_asaffili_new_catid').val();
	var url=jQuery('#temp_asaffili_url').val();
	
	//alert(id+' '+newcatid);
	
	
	
	jQuery.ajax({
        type: "POST",
        async: "false",
        url: asaffili_ajaxurl.ajaxurl,
        data: {action:'asaffili-set-catid',
				id:id,
				newcatid:newcatid,
				url:url},
        	success: function (data, textStatus, XMLHttpRequest)
        	{
            	//alert("Erfolg"+data);
            	location.href=url;
        	},
        	error: function (XMLHttpRequest, textStatus, errorThrown) {
            	alert("Fehler "+errorThrown);
        	}
        
    
	});
    

});
	

jQuery('#button-asaffili-del-catid').on("click",function()
{	
	var id= jQuery('#temp_asaffili_import_id').val();	
	var url=jQuery('#temp_asaffili_url').val();
	
	
	jQuery.ajax({
        type: "POST",
        async: "false",
        url: asaffili_ajaxurl.ajaxurl,
        data: {action:'asaffili-del-catid',
				id:id,				
				url:url},
        	success: function (data, textStatus, XMLHttpRequest)
        	{
            	//alert("Erfolg"+data);
            	location.href=url;
        	},
        	error: function (XMLHttpRequest, textStatus, errorThrown) {
            	alert("Fehler "+errorThrown);
        	}
	});
});


jQuery('#button-asaffili-noimport-catid').on("click",function()
{	
	var id= jQuery('#temp_asaffili_import_id').val();	
	var url=jQuery('#temp_asaffili_url').val();
	
	jQuery.ajax({
        type: "POST",
        async: "false",
        url: asaffili_ajaxurl.ajaxurl,
        data: {action:'asaffili-noimport-catid',
				id:id,				
				url:url},
        	success: function (data, textStatus, XMLHttpRequest)
        	{
            	//alert("Erfolg"+data);
            	location.href=url;
        	},
        	error: function (XMLHttpRequest, textStatus, errorThrown) {
            	alert("Fehler "+errorThrown);
        	}
	});
});

	
	
	
jQuery('#button-asaffili-read-import').click(function()
{
	var id= jQuery('#button-asaffili-read-import').attr('data-first');
	
	
	jQuery('#asaffili-read-import-wrapper').html('Datei wird gelesen.');
	
	jQuery.post(
		asaffili_ajaxurl.ajaxurl,
		{
			action:'asaffili-read-import',
			id:id
		},
		function(response)
		{
			jQuery('#asaffili-read-import-wrapper').html('<p>Datei gelesen.</p>');
			jQuery('#asaffili-import-ausgabe-fields').html(response);
		}
	);
	
});



jQuery('#button-asaffili-head-print').click(function()
{
	var id= jQuery('#button-asaffili-head-print').attr('data-first');
	
	jQuery.post(
		asaffili_ajaxurl.ajaxurl,
		{
			action:'asaffili-head-print',
			id:id
		},
		function(response)
		{
			jQuery('#asaffili-read-import-wrapper').html('<p>Datei gelesen.</p>');
			jQuery('#asaffili-import-ausgabe-fields').html(response);
		}
	);
	
});



jQuery('#button-asaffili-import-file').click(function()
{
	
	var id= jQuery('#button-asaffili-import-file').attr('data-first');
	jQuery('#asaffili-read-import-wrapper').html('Import wird gestartet...');
	jQuery.post(
		asaffili_ajaxurl.ajaxurl,
		{
			action:'asaffili-import-file',
			id:id
		},
		function(response){jQuery('#asaffili-read-import-wrapper').html(response);}
	);
	
});



jQuery('#button-asaffili-import-file2').click(function()
{
	
	var id= jQuery('#button-asaffili-import-file2').attr('data-first');
	
	var wrapper_ausgabe="";
	var start=1;
	var step=50;
	var result_count=0;
	
	var stat_gesamt=0;
	var stat_insert=0;
	var stat_update=0;
	
	var abbruch=0;
	var max=0;
	
	jQuery('#asaffili-read-import-wrapper').html('Import wird gestartet...');
	
	
	
	jQuery.ajaxSetup({async: false});  

	jQuery.post(
			
			asaffili_ajaxurl.ajaxurl,
			{				
				action:'asaffili-import-file2-setdate',
				id:id
			},
			function(response)
			{
							
				result_count=response;
				
			}
			
	);
		
		
		
	while(abbruch==0 && max<5000)
	{
		max=max+1;
		
		jQuery.ajaxSetup({async: false});  

		jQuery.post(
			
			asaffili_ajaxurl.ajaxurl,
			{				
				action:'asaffili-import-file2',
				id:id,
				start:start,
				step:step
			},
			function(response)
			{
							
				result_count=response;
				
			}
			
		);
		
		
		//alert(result_count);
		jQuery('#asaffili-read-import-wrapper').append(result_count+'<br>');		
   
		start=start+step;
		
  		
  		var pieces = result_count.split(",");
  		
  		var parsed = parseInt(pieces[0]);
  		if (!isNaN(parsed))
  		{
			stat_gesamt=parsed;	
		}
  		var parsed = parseInt(pieces[1]);
  		if(!isNaN(parsed))stat_insert=stat_insert+parsed;
  		var parsed = parseInt(pieces[2]);
  		if(!isNaN(parsed))stat_update=stat_update+parsed;
  
  		if(pieces[4]=='-1')abbruch=1;
  		
		jQuery('#asaffili-read-import-wrapper').html('Eingefuegt: '+stat_insert+'<br>Geändert: '+stat_update+'<br>');
		
	}
	
	
	
	jQuery('#asaffili-read-import-wrapper').append(result_count+'<br>');
	jQuery('#asaffili-read-import-wrapper').append('<br>Import beendet<br>');		
	
});



jQuery('#button-asaffili-stat-delcat').click(function()
{
	
	var id= jQuery('#button-asaffili-stat-delcat').attr('data-first');
	
	jQuery.ajaxSetup({async: false});  

	jQuery.post(
			
	asaffili_ajaxurl.ajaxurl,
	{				
		action:'asaffili-stat-delcat',
		id:id
	},
	function(response)
	{
		result=response;
	}
			
	);
	alert(result+' Datensätze gelöscht');
	
});


jQuery('#button-asaffili-stat-delposts').click(function()
{
	
	var id= jQuery('#button-asaffili-stat-delposts').attr('data-first');
	var url=jQuery('#temp_asaffili_url').val();
	jQuery.ajax({
        type: "POST",
        async: "false",
        url: asaffili_ajaxurl.ajaxurl,
        data: {action:'asaffili-stat-delposts',
				id:id,				
				url:url},
        	success: function (data, textStatus, XMLHttpRequest)
        	{
            	//alert("Erfolg"+data);
            	location.href=url;
        	},
        	error: function (XMLHttpRequest, textStatus, errorThrown) {
            	alert("Fehler "+errorThrown);
        	}
	});
	/*
	jQuery.ajaxSetup({async: false});  

	jQuery.post(
			
	asaffili_ajaxurl.ajaxurl,
	{				
		action:'asaffili-stat-delposts',
		id:id
	},
	function(response)
	{
		result=response;
	}
			
	);
	alert(result+' Datensätze gelöscht');
	*/
});



jQuery('#button-asaffili-stat-delpostsold').click(function()
{
	
	var id= jQuery('#button-asaffili-stat-delpostsold').attr('data-first');
	var url=jQuery('#temp_asaffili_url').val();
	
	/*
	jQuery.ajaxSetup({async: false});  

	jQuery.post(
			
	asaffili_ajaxurl.ajaxurl,
	{				
		action:'asaffili-stat-delpostsold',
		id:id
	},
	function(response)
	{
		
		result=response;
	}
			
	);
	alert(result+' Datensätze gelöscht');
	*/
	
	jQuery.ajax({
        type: "POST",
        async: "false",
        url: asaffili_ajaxurl.ajaxurl,
        data: {action:'asaffili-stat-delpostsold',
				id:id,				
				url:url},
        	success: function (data, textStatus, XMLHttpRequest)
        	{
            	//alert("Erfolg"+data);
            	location.href=url;
        	},
        	error: function (XMLHttpRequest, textStatus, errorThrown) {
            	alert("Fehler "+errorThrown);
        	}
	});
	
	
});


});
