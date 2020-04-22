

/* script cambio de Div */
  
var cambiar = 
{
   	accion:  function(show){ this.ocultar(); this.mostrar(show); },
   	ocultar: function()
	{ 
		var divs = document.getElementsByTagName('div');
		for(i=1; i<12;i++)
		{ 
			if (document.getElementById("div"+i).style.display == "block")
			{
				document.getElementById("div"+i).style.display = "none";
			}
		} 
	},
   	mostrar: function(num){ document.getElementById("div"+num).style['display'] = "block"; }
};



/*Ocultar Botones*/
$(document).ready(function()
{
	$(".accordion h1:first").addClass("active");
    $(".accordion div:not(:first)").hide();
    $(".accordion h1").click(function()
	{
    	$(this).next("div").slideToggle("fast")
     	.siblings("div:visible").slideUp("fast");
    	$(this).toggleClass("active");
    	$(this).siblings("h1").removeClass("active");
    });
});



/*Manipulación Tabla*/
$(document).ready(function()
{	//<<<<<<<<<<<<<<<---- FUNCION QUE AGREGA FILA---- >>>>>>>>>>>>>
	$(document).on('click','.clsAgregarFila',function()
	{
		var objTabla=$(this).parents().get(3);
		var node= objTabla.childNodes[1].lastChild.cloneNode(true);
 	    var strNueva_Fila='<tr>'+ node.innerHTML +	'</tr>';
	 	   	$(objTabla).find('tbody').append(strNueva_Fila);
			if(!$(objTabla).find('tbody').is(':visible'))
			{
				$(objTabla).find('caption').click();
			}
	});
	
	//<<<<<<<<<<<<<<<---- FUNCION QUE ELIMINA FILA---- >>>>>>>>>>>>>
	$(document).on('click','.clsEliminarFila',function()
	{
		var objCuerpo=$(this).parents().get(2);
		if($(objCuerpo).find('tr').length==1)
		{
			return;
		}
		var objFila=$(this).parents().get(1);
		$(objFila).remove();
	});
	
	//<<<<<<<<<<<<<<<---- FUNCION QUE AGREGA FILA EN LA <<<TABLA_D>>> DEL APARTADO DE CALIDAD DE LOS DATOS/ REFERENCIA DE LOS DATOS ORIGINALES---- >>>>>>>>>>>>>
    $(document).on('click','.clsAgregarFilad',function()
	{
   		var objCuerpo=$(this).parents().get(2);
		var objTabla=$(this).parents().get(3);
		var node= objTabla.childNodes[1].lastChild.cloneNode(true);
 	    var strNueva_Fila='<tr>'+ node.innerHTML + 		'</tr>';
		var pos_letra = strNueva_Fila.indexOf('h_origina') + 9;
		var pos_a = strNueva_Fila.substring( pos_letra, pos_letra + 6).indexOf('"');
	    var letra = strNueva_Fila.substring( pos_letra, pos_letra + pos_a ) ;
		var letra1 = (letra * 1 ) + 1;
        do { 
          		strNueva_Fila = strNueva_Fila.replace('h_origina'+ letra ,'h_origina' + letra1 );
        	} 
		while(strNueva_Fila.indexOf('h_origina' + letra) >= 0);
		
		strNueva_Fila = strNueva_Fila.replace('d_idorigen[]" value="a'+ letra ,'d_idorigen[]" value="a' + letra1 );
  	    $(objTabla).append(strNueva_Fila);
		if(!$(objTabla).find('tbody').is(':visible'))
		{
			$(objTabla).find('caption').click();
		}
	});	
	
	//<<<<<<<<<<<<<<<---- FUNCION QUE AGREGA LA FILA GENERAL EN LA <<<TABLA_T>>> DEL APARTADO DE TAXONOMIA---- >>>>>>>>>>>>>
	$(document).on('click','.clsAgregarFilat',function(){
   		var objCuerpo=$(this).parents().get(2);
		var objTabla=$(this).parents().get(3);
		var node= objTabla.childNodes[1].lastChild.cloneNode(true);
 	    var strNueva_Fila='<tr>'+ node.innerHTML + 		'</tr>';
		   //alert(strNueva_Fila);
		var pos_letra = strNueva_Fila.indexOf('g_titlet') + 8;
		var pos_a = strNueva_Fila.substring( pos_letra, pos_letra + 6).indexOf('"');
	    var letra = strNueva_Fila.substring( pos_letra, pos_letra + pos_a ) ;
		var letra1 = (letra * 1 ) + 1;
		// alert( letra + "....." + letra1);
        do { 
          strNueva_Fila = strNueva_Fila.replace('t' +  letra ,'t'+  letra1 );
        } while(strNueva_Fila.indexOf('t' +  letra) >= 0);
          
		do { 
         strNueva_Fila = strNueva_Fila.replace('value="t'+ letra , 'value="t' + letra1 );
        } while(strNueva_Fila.indexOf('value="t' +  letra) >= 0);
 		 // alert(strNueva_Fila);
  	      $(objTabla).append(strNueva_Fila);
		if(!$(objTabla).find('tbody').is(':visible')){
			$(objTabla).find('caption').click();
		}
	});	
	
	
	//<<<<<<<<<<<<<<<---- FUNCION QUE AGREGA LA FILA GENERAL EN LA <<<TABLA_TC>>> DEL APARTADO DE TAXONOMIA/CITAS---- >>>>>>>>>>>>>
	$(document).on('click','.clsAgregarFilatc',function(){
   		var objCuerpo=$(this).parents().get(2);
		var objTabla=$(this).parents().get(3);
		var node= objTabla.childNodes[1].lastChild.cloneNode(true);
 	    var strNueva_Fila='<tr>'+ node.innerHTML + 		'</tr>';
		//   alert(strNueva_Fila);
		var pos_letra = strNueva_Fila.indexOf('z_origin') + 8;
		var pos_a = strNueva_Fila.substring( pos_letra, pos_letra + 20).indexOf('_');
	    var idt = strNueva_Fila.substring( pos_letra, pos_letra + pos_a); 
		var letra = strNueva_Fila.substring( pos_letra + pos_a + 2, pos_letra + pos_a + 3 ) ;
		var letra1 = (letra * 1 ) + 1;
         // alert(letra1);
        do { 
          strNueva_Fila = strNueva_Fila.replace('z_origin'+ idt + "_a"  +  letra ,'z_origin' + idt + "_a"  +  letra1 );
        } while(strNueva_Fila.indexOf('z_origin' + idt + "_a"  +  letra) >= 0);
          strNueva_Fila = strNueva_Fila.replace('value="a'+ letra , 'value="a' + letra1 );
         // alert(strNueva_Fila);
  	      $(objTabla).append(strNueva_Fila);
		if(!$(objTabla).find('tbody').is(':visible')){
			$(objTabla).find('caption').click();
		}
	});	
	
	
	$(document).on('click','.clsAutor',function(){
 	    var objCuerpo=$(this).parents().get(2);
			if($(objCuerpo).find('tr').length==1){
					return;
			}
		var objFila=$(this).parents().get(1);
		alert($(objFila).value);
		
	});  
	
	$(document).on('click','.clspre_azt',function(){
	   document.getElementsByName('areglo')[0].value = "c";	 
	//	alert(document.getElementsByName("areglo").length) 
	});

	$(document).on('click','.clspre_az',function(){
	    alert(" Prueba de funcion clspre_az")    
	 
	});
	
});

/*Scripts Arbol*/
function collapseAll(){
			$('#tt').tree('collapseAll');
		}
function expandAll(){
			$('#tt').tree('expandAll');
		}
function expandTo(idv){
			var node = $('#tt').tree('find',idv);
			$('#tt').tree('expandTo', node.target).tree('select', node.target);
			document.getElementsByName("c_clasif_ruta")[0].value =  getDirPath();
		}	
function getSelected(){
			var node = $('#tt').tree('getSelected');
			if (node){
			 var s = node.id;
			 document.getElementsByName("c_clasificacion")[0].value = s;
			 document.getElementsByName("c_clasif_ruta")[0].value =  getDirPath();
		  }
		}		 
function getDirPath(){
    var nodo = $('#tt').tree('getSelected');
    var path;
   
     while(undefined!=nodo){
        if(undefined!=nodo.id){
          path+="@"+nodo.text;
        }
        var nodeP=$('#tt').tree('getParent',nodo.target);
          nodo=nodeP;
      }
      var va=path.split('@');
          path="";
      for(var i=va.length-1;i>=0;i--){
          path+="\\"+va[i];
      }
      path=path.replace('@','');
      path=path.replace('\\undefined','');
	  path=path.replace('\\undefined','');
	  path=path.replace('\\Acervo','Acervo');
	  path=path.replace('<a href="#" style="text-decoration:none;color:black;" ondblclick="getSelected()">','')
 	  path=path.replace('</a>','')
     return path; 
   }