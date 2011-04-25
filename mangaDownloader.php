<?php
class mangaDownloader_ax
{	
	/**
	 * ANIMEXTREMIST VERSION
	 * Le pasas una URL con la siguiente forma: http://www.animextremist.com/mangas-online/air/capitulo-1/airtv1.html
	 * La clase descarga el capitulo automaticamente en la carpeta mangas/air-capitulo-1
	 * @param string $manga_url
	 */
	
	function download($manga_url)
	{
		$html = file_get_contents($manga_url);
	    
	    /* a new dom object*/
	    $dom = new domDocument;
	    /* load the html into the object*/
	    @$dom->loadHTML($html);
	
	    /* discard white space*/
	    $dom->preserveWhiteSpace = false;
	    
		/* PRIMERO SACAMOS LA SERIE Y EL NUMERO DE CAPITULO */
		
	    $str1 = urlParameters(2, $manga_url);
    	$str2 = urlParameters(3, $manga_url);
	 	$serie = urlParameters(4, $manga_url);
    	$capi = urlParameters(5, $manga_url);
		
		/* LUEGO GUARDAMOS LA URL DE LA PRIMERA IMAGEN */
	    
	    $links = $dom->getElementsByTagName('img');
	    $i = 0;
		foreach ($links as $link)
		{
	    	if ($i == 0) 
	    	{
	    		//guardamos la url de la imagen
			    $imageurl = $link->getAttribute('src');
	    		break;
	    	}
			$i++;
		}
		//reformateamos la string pa tener la plantilla
		
		$str1 = urlParameters(2, $imageurl);
    	$str2 = urlParameters(3, $imageurl);
		$rest = substr(urlParameters(6, $imageurl), 0, -4);
		/* AQUI YA TENEMOS LA URL DONDE ESTAN LAS IMAGENES: http://img2.submanga.com/pages/107/1074632bf/ 
		 * EMPEZAMOS UN BUCLE QUE GUARDE LAS IMAGENES HASTA QUE DE ERROR  */  
		
		//OWNED :)
		
		$error = 0;
		for($i=0;$error == 0;$i++)
		{
			if ($i==0) 
			{
				$url = "http://".$str1."/".$str2."/".$serie."/".$capi."/".$rest.".jpg";
				echo $url."<br>";
				if (saveImg($url, $i.".jpg", $serie."-".$capi) == 0) $error = 1;
				set_time_limit(20);
			}
			else 
			{
				$url = "http://".$str1."/".$str2."/".$serie."/".$capi."/".$rest.$i.".jpg";
				if (saveImg($url, $i.".jpg", $serie."-".$capi) == 0) $error = 1;
				set_time_limit(20);
			}
			
		}
	}
	
	/**
	 * Envia un manga en compreso en Zip a la lista de usuarios proporcionada
	 * @param String $file
	 * @param Array String $list
	 */
	
	function enviarManga($file, $list)
	{
		$file = "mangas/$file";
		//comprimiremos la carpeta de un
		
		echo $file;
		
		//enviaremos la carpeta a los usuarios de la lista
		
		correo($file, $list);
	}
	
}

class mangaDownloader_sm
{	
	/**
	 * SUBMANGAVERSION
	 * Le pasas una URL con la siguiente forma: http://submanga.com/Naruto/536/107463 es necesario el ultimo numero despues del capitulo 107463
	 * La clase descarga el capitulo automaticamente en la carpeta mangas/Naruto536
	 * @param string $manga_url
	 */
	
	function download($manga_url)
	{
		/* parametizamos la url y sacamos todos los datos necesarios */
		
    	$serie = urlParameters(3, $manga_url);
    	$capi = urlParameters(4, $manga_url);
		
		/* HASTA AQUI SE CONSIGUE LA URL DEL ULTIMO CAPITULO: http://submanga.com/c/107463 */
		$html = file_get_contents($manga_url);
	    
	    /* a new dom object*/
	    $dom = new domDocument;
	    /* load the html into the object*/
	    @$dom->loadHTML($html);
	
	    /* discard white space*/
	    $dom->preserveWhiteSpace = false;
	    
		/*Intentamos conseguir el id de la carpeta */
	    $inputs = $dom->getElementsByTagName('input');
		foreach($inputs as $element)
		{
			if ($element->getAttribute('name') == "id")
		   		$finalurl = $element->getAttribute('value');
		}

		/* HASTA AQUI SE CONSIGUE LA URL CON ID DEL CAPITULO: http://submanga.com/c/107463 */
		$url_mod = "http://submanga.com/c/".$finalurl;
		
		$html = file_get_contents($url_mod);
	    
	    /* a new dom object*/
	    $dom = new domDocument;
	    /* load the html into the object*/
	    @$dom->loadHTML($html);
	
	    /* discard white space*/
	    $dom->preserveWhiteSpace = false;
		
		/*Luego sacamos el primer link... */   
	    $links = $dom->getElementsByTagName('img');
	    $i = 0;
		foreach ($links as $link)
		{
	    	if ($i == 2) 
	    	{
	    		//guardamos el tercer enlace que es el de la imagen
			    $imageurl = $link->getAttribute('src');
	    		break;
	    	}
			$i++;
		}
		/* AQUI YA TENEMOS LA URL DONDE ESTAN LAS IMAGENES: http://img2.submanga.com/pages/107/1074632bf/ 
		 * EMPEZAMOS UN BUCLE QUE GUARDE LAS IMAGENES HASTA QUE DE ERROR   */
		
		$error = 0;
		for($i=1;$error == 0;$i++)
		{
			$url = urlParameters(0, $imageurl)."/".urlParameters(1, $imageurl)."/".urlParameters(2, $imageurl)."/".urlParameters(3, $imageurl)."/".urlParameters(4, $imageurl)."/".urlParameters(5, $imageurl)."/".$i.".jpg";
			if (saveImg($url, $i.".jpg", $serie.$capi) == 0) $error = 1;
			set_time_limit(20);
		}
	}
	
	/**
	 * Le pasas una URL con la siguiente forma: http://submanga.com/Naruto
	 * la clase descarga el ultimo capitulo automaticamente
	 * @param string $manga_url
	 */
	
	function last($manga_url)
	{
	    $html = file_get_contents($manga_url);
	    $dom = new domDocument;
	    @$dom->loadHTML($html);
	    $dom->preserveWhiteSpace = false;
	    $tables = $dom->getElementsByTagName('table');
	    $rows = $tables->item(0)->getElementsByTagName('tr');
	    $i = 0;
	    foreach ($rows as $row)
	    {
	    	//solo queremos el primero, que es el ultimo capitulo :]
	    	if ($i == 2) break;
			$i++;
	        /** buscamos los enlaces dentro de la fila ***/	
	        $cols = $row->getElementsByTagName('a');
		    foreach ($cols as $link)
		    {
		    	$serie = urlParameters(3, $link->getAttribute('href'));
		    	$capi = urlParameters(4, $link->getAttribute('href'));
			    $urlmanga = "http://submanga.com/c/".urlParameters(5, $link->getAttribute('href'));
			    break;
			};
	    } 
	    /* HASTA AQUI SE CONSIGUE LA URL DEL ULTIMO CAPITULO: http://submanga.com/c/107463 */
		$html = file_get_contents($urlmanga);
	    
	    /* a new dom object*/
	    $dom = new domDocument;
	    /* load the html into the object*/
	    @$dom->loadHTML($html);
	
	    /* discard white space*/
	    $dom->preserveWhiteSpace = false;
	
	    $links = $dom->getElementsByTagName('img');
	    $i = 0;
		foreach ($links as $link)
		{
	    	if ($i == 2) 
	    	{
	    		//guardamos el tercer enlace que es el de la imagen
			    $imageurl = $link->getAttribute('src');
	    		break;
	    	}
			$i++;
		}
		/* AQUI YA TENEMOS LA URL DONDE ESTAN LAS IMAGENES: http://img2.submanga.com/pages/107/1074632bf/ 
		 * EMPEZAMOS UN BUCLE QUE GUARDE LAS IMAGENES HASTA QUE DE ERROR    */
		
		//OWNED :)
		
		$error = 0;
		for($i=1;$error == 0;$i++)
		{
			$url = urlParameters(0, $imageurl)."/".urlParameters(1, $imageurl)."/".urlParameters(2, $imageurl)."/".urlParameters(3, $imageurl)."/".urlParameters(4, $imageurl)."/".urlParameters(5, $imageurl)."/".$i.".jpg";
			if (saveImg($url, $i.".jpg", $serie.$capi) == 0) $error = 1;
			set_time_limit(20);
		}
		
		return $serie.$capi;
	}
}


/**
 * Guarda una imagen. Le pasas url, nombre y directorio. Devuelve false si no lo consigue o true
 * @param $string $imageurl
 * @param $string $name
 * @param $string $loc
 */

function saveImg($imageurl, $name, $loc)
{	
	if (!file_exists("mangas")) mkdir("mangas");
	
	if (!file_exists("mangas/$loc")) mkdir("mangas/$loc");
		
	$image = @file_get_contents($imageurl);
	
	if ($image)
	{
		if (!file_exists("mangas/$loc/$name"))
		{
			$fp = fopen("mangas/$loc/$name", 'a');
			if($fp) 
			{
			    fwrite($fp, $image);
			    fclose($fp);
			}
			echo $loc." ".$name." guardada!<br>";
			return 1;
		}
		else 
		{
			echo $loc." ".$name." ya existe!<br>";
			return 0;
		}
	}
	else 
	{
		echo $loc." ".$name." error!<br>";
		return 0;
	}
}

/**
 * Parametiza una url
 * @param $string $segment
 * @param $string $web
 */

function urlParameters($segment, $web)
{
	 $navString = $web; // Agafa la URL
	 $parts = explode('/', $navString); // La parteix per "/"
	 return $parts[$segment];
}

/**
 * Envia un correo con archivo adjunto
 * @param String $file
 * @param String $user
 */

function correo($file, $user)
{
	/*
	$semilla = md5(date('r', time()));
	$para = $user;
	$asunto = "Toma manga $file";
	$headers = "From:robot-de-esos-que-mandan-cosas-lol@robot.com\r\nReply-To: robot-de-esos-que-mandan-cosas-lol@robot.com";
	$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$semilla."\"";
	$adjunto= chunk_split(base64_encode(file_get_contents($file)));
	$correo = "
	--PHP-mixed-$semilla;
	Content-Type: multipart/alternative; boundary='PHP-alt-$semilla'
	--PHP-alt-$semilla
	Content-Type: text/plain; charset='iso-8859-1'
	Content-Transfer-Encoding: 7bit
	
	Nuestro correo en version de texto plano
	
	--PHP-alt-$semilla
	Content-Type: text/html; charset='iso-8859-1'
	Content-Transfer-Encoding: 7bit
	
	<h2>Contenido HTML!</h2>
	<p>Aqui ponemos nuestra version <b>HTML</b> de nuestro correo.</p>
	
	--PHP-alt-$semilla--
	
	--PHP-mixed-$semilla
	Content-Type: application/zip; name=adjunto.zip
	Content-Transfer-Encoding: base64
	Content-Disposition: attachment 
	
	$adjunto
	--PHP-mixed-$semilla--";
	echo @mail($para, $asunto, $correo, $headers);*/
}
?>