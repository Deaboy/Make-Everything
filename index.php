<?php

/*
 * Definition declaring that the engine is indeed the one accessing a file.
 * A simple solution to prevent users from accessing a file directly.
 */
define("ENGINE", 1);

// INITIALIZE SITE ENGINE
require_once "includes/SiteEngine.php";
$engine = new SiteEngine();

// Site paths and such
$relRoot = "/me/";
$absRoot = "localhost/me/";
$pageRoot = "pages/";
$imgRoot = $relRoot."images/";
$templateRoot = $relRoot."templates/composure/";

// Figure out what page to display
$page = isset($_GET["p"]) ? $_GET["p"] : 1;

//$page = $pageRoot.$page.".php";

function insertContent()
{
	global $page;
	global $engine;
	
	if (!isset($page)) return;
	
	echo $engine->getPageContent($page);
}


include "templates/composure/index.php";


?>
