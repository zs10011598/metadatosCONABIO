<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"> 
<html lang="es">
  <head>
    <title>Inicio</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <style>
	
html, body,div,h1,form,td,input {
  margin:0;
  padding:0;
  font:normal 13px arial,tahoma,verdana;
}
body {
  background:url(img/fondo.png) repeat-x;
}
body > div {
  border: 1px solid #c0c0c0;
  border-radius: 8px;
  box-shadow: 4px 4px 0.5em #d3d3d3;
  width:700px;
  margin:20px auto 0 auto;
  background:#ffffff;
}

#hd {
  padding:10px 12px;
  border-bottom: 1px solid #c0c0c0;
}
#cn { 	margin:10px; 	}

#hd > h1 {
	text-align:center;
	font-size:1.7em;
	color:#6E6E6E;
}
#cn > h1 {
  font-size:1.5em;
  text-align:center;
  font-weight:bold;
}


.cn{
	display:block;
	margin:auto;
	margin-bottom:5px;
	margin-top:5px;
	padding:5px 20px;
}

.bttnIni{
	display:block;
  	margin-bottom:15px;
 	width:183px;
	height:30px;
}
.datos{
position:initial;
width:260px;
height:30px;
margin-left:28%;
}

.txtUser{
position:relative;
}

input{
   border: 1px solid #393939;
   border-radius: 5px 5px 5px 5px;
   padding: 5px;
   width:172px;
   height:12px;
   font-size: 100%;
}

.error {
	position:relative;
	width:135px;
	margin: 3px 0 2px 105px;
	background: rgba(221, 75, 57, 0.85) url(img/lightbulb.png) no-repeat 1px 6px;
	background: rgba(221, 75, 57, 0.85) url(img/lightbulb.png) no-repeat 1px 6px;
	background-size: 20px;
	border-radius: 5px;
	padding: 8px 25px;
	font-size: 12px;
	color: #eee;
	text-align: center;
}

.highlight {
	border: 2px solid #FF1E00;
}


	</style>
	<script src="jquery/jquery-1.7.1.min.js"></script>
	<script>
    $(document).ready(function(){	
	$('.error').hide();
	/*$( "#iniciar" ).click(function() {	
		validaCampos();	
	});	*/
	
});

function validaCampos() {
	
		usuario =  $("input[name = usuario]");
		password = $("input[name = password]");
		
		
		var validaDatos = [	[usuario 		, "Usuario incorrecto"	] ,
							[password 		, "Password incorrecto"	] ];
						
		for (i = 0 ; i <= validaDatos.length ; i++){
			if (typeof(validaDatos[i]) !== 'undefined'){				
				if (validaDatos[i][0].val() == '') {
						createAlert (validaDatos[i][0] , validaDatos[i][1]);
						break;
					}
				else{
						removeAlert (validaDatos[i][0]);
					}
			}
		}
		
		userVal = $("input[name = usuario]").val();
		pwrdVal = $("input[name = password]").val();
		
		if(userVal !=='' & pwrdVal !=='')
		{
			ajax(userVal, pwrdVal , "index");
		}		
	
}

function ajax(userVal, pwrdVal , caso)
{
	var dataString = {var1 : userVal , var2 : pwrdVal, var3: caso}
		
		$.ajax({
		data: dataString,
		url: 'php/funciones.php', 
		type: "GET",
		dataType : "json",
		}).done(function(result) {
			if( result.success ) {
			
				$(location).attr('href','Menu.php'); 
			}
			else
			{
				$('.error').replaceWith('<div class="error">'+result.message+'</div>');
				$('.error').fadeIn('slow');
				$(".error").fadeOut(8000);
			}
		});
}


function createAlert(campo, txt) {
	campo.addClass('highlight');
	$('.error').replaceWith('<div class="error">'+txt+'</div>');
	$('.error').fadeIn('slow');
	$(".error").fadeOut(8000);
	$(campo).focus();
	return false;
}

function removeAlert(campo) {
	campo.removeClass('highlight');
	$('.error').hide();
}    
    </script>
  </head>
  <body>
    <div>
      <div id="hd">
		<h1>Dirección General de Geomática</h1>
		<h1>Subcoordinación de Sistemas de Información Geográfica</h1>
      </div>
      <div id="cn" class="cn">  
	  	<h1>Sistema de Captura de Metadatos</h1>
        <img class="cn" src ="img/logo.jpg"/>	
	    <div class="cn">
        	<button id="btn-login" disabled="true" onclick="login()" class="bttnIni cn">Iniciar Sesi&oacute;n</button>

        	<div id="gated-content" style="display: none;">
		    	<form action="Menu.php" method="POST">
		    		<p style="text-align: center;">
			    		Bienvenido <span id="ipt-user-profile"></span>
				    </p>
				    <input type="text" id="token" name="token" style="display: none;">
				    <input type="text" id="user" name="user" style="display: none;">
				    <input type="submit" name="enviar" class="bttnIni cn" value="Entrar">
		    	</form>
			</div>

        	<button id="btn-logout" style="display: none;" onclick="logout()" class="bttnIni cn">Salir</button>
	    </div>
      </div>
    </div>

    <script src="https://cdn.auth0.com/js/auth0-spa-js/1.2/auth0-spa-js.production.js"></script>
  	<script src="public/js/app.js"></script>

  </body>
</html>
