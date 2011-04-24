<?php
include 'mangaDownloader.php';

//catálogo http://www.animextremist.com/mangas.htm XD

$obj = new mangaDownloader_ax();
$obj->download("http://www.animextremist.com/mangas-online/air/capitulo-2/airtv.html");

//$obj = new mangaDownloader_sm();
//$obj->last("http://submanga.com/Naruto");
//$obj->download("http://submanga.com/Naruto/450/32698");

?>