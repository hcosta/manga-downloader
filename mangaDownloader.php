<?php

class mangaDownloader_ms
{	
	/**
	 * MANGASTREAM VERSION
	 * Le pasas una URL con la siguiente forma: http://submanga.com/Naruto/536/107463 es necesario el ultimo numero despues del capitulo 107463
	 * La clase descarga el capitulo automáticamente en la carpeta mangas/Naruto536
	 * @param string $manga_url
	 */
}

class mangaDownloader_sm
{	
	/**
	 * SUBMANGAVERSION
	 * Le pasas una URL con la siguiente forma: http://submanga.com/Naruto/536/107463 es necesario el ultimo numero despues del capitulo 107463
	 * La clase descarga el capitulo automáticamente en la carpeta mangas/Naruto536
	 * @param string $manga_url
	 */
	function download($manga_url)
	{
		/* parametizamos la url y sacamos todos los datos necesarios */
		
    	$serie = urlParameters(3, $manga_url);
    	$capi = urlParameters(4, $manga_url);
		$url_mod = "http://submanga.com/c/".urlParameters(5, $manga_url);
		
		/* HASTA AQUI SE CONSIGUE LA URL DEL ULTIMO CAPITULO: http://submanga.com/c/107463 */
		$html = file_get_contents($url_mod);
	    
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
		
		$error = 0;
		for($i=1;$error == 0;$i++)
		{
			$url = urlParameters(0, $imageurl)."/".urlParameters(1, $imageurl)."/".urlParameters(2, $imageurl)."/".urlParameters(3, $imageurl)."/".urlParameters(4, $imageurl)."/".urlParameters(5, $imageurl)."/".$i.".jpg";
			if (saveImg($url, $i.".jpg", $serie.$capi) == 0) $error = 1;
			set_time_limit(20);
		}
	}
	
	/**
	 * 
	 * Le pasas una URL con la siguiente forma: http://submanga.com/Naruto
	 * la clase descarga el último capitulo automáticamente
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
		
		$error = 0;
		for($i=1;$error == 0;$i++)
		{
			$url = urlParameters(0, $imageurl)."/".urlParameters(1, $imageurl)."/".urlParameters(2, $imageurl)."/".urlParameters(3, $imageurl)."/".urlParameters(4, $imageurl)."/".urlParameters(5, $imageurl)."/".$i.".jpg";
			if (saveImg($url, $i.".jpg", $serie.$capi) == 0) $error = 1;
			set_time_limit(20);
		}
	}
}


/**
 * 
 * Guarda una imagen. Le pasas url, nombre y directorio. Devuelve false si no lo consigue o true
 * @param $string $imageurl
 * @param $string $name
 * @param $string $loc
 */
function saveImg($imageurl, $name, $loc)
{
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
			return 1;
		}
	}
	else 
	{
		echo $loc." ".$name." error!<br>";
		return 0;
	}
}

/**
 * 
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
?>
