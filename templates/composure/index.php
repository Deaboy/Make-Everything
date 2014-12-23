<?php
if (!defined("ENGINE")) die ();
?>
<!DOCTYPE html>
<html>
	<head>
		<!-- Meta information -->
		<meta charset="UTF-8" />
		<meta name="description" content="Personal web site of Daniel "Deaboy" Ernest Andrus II" />
		<meta name="keywords" content="personal, sandbox, blog, code, custom, daniel, dan, deaboy, andrus" />
		<meta name="author" content="Daniel Andrus" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=1"/> <!--320-->

		<!-- Page title -->
		<title>Title</title>

		<!-- Remote CSS links -->
		<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" />
		<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Tangerine:400,700|Open+Sans:300italic,700italic,300,700" />

		<!-- Local CSS links -->
		<link rel="stylesheet" type="text/css" href="<?php echo $templateRoot; ?>css/styles.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo $templateRoot; ?>css/wide.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo $templateRoot; ?>css/medium.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo $templateRoot; ?>css/narrow.css" />
	</head>
	<body>

		<!-- Page wrapper -->
		<div id="page">

			<!-- Page header -->
			<header id="header" role="banner">

				<!-- Page main logo/title -->
				<h1 class="title">Page Title</h1>

				<!-- Page main nav menu -->
				<nav class="main">
					<ul>
						<li><a href="<?php echo $relRoot; ?>" title="Home">Home</a></li>
						<li><a href="<?php echo $relRoot; ?>blog" title="Blog">Blog</a></li>
						<li><a href="<?php echo $relRoot; ?>library" title="Library">Library</a></li>
						<li><a href="<?php echo $relRoot; ?>portfolio" title="Portfolio">Portfolio</a></li>
						<li><a href="<?php echo $relRoot; ?>contact" title="Contact">Contact</a></li>
					</ul>
				</nav>
			</header>

			<!-- Page main content -->
			<main id="content" role="main">

				<?php insertContent(); ?>

			</main>

			<!-- Page footer -->
			<footer id="footer" role="contentinfo">

				<!-- Everybody loves social links. Right? -->
				<nav class="social">
					<ul>
						<li><a href="<?php echo $relRoot; ?>contact/#twitter" title="Twitter"><span class="fa fa-twitter icon"></span>Twitter</a></li>
						<li><a href="<?php echo $relRoot; ?>contact/#facebook" title="Facebook"><span class="fa fa-facebook icon"></span>Facebook</a></li>
						<li><a href="<?php echo $relRoot; ?>contact/#github" title="Github"><span class="fa fa-github icon"></span>GitHub</a></li>
						<li><a href="<?php echo $relRoot; ?>contact/#youtube" title="YouTube"><span class="fa fa-youtube-play icon"></span>YouTube</a></li>
						<li><a href="<?php echo $relRoot; ?>contact/#steam" title="Steam"><span class="fa fa-steam icon"></span>Steam</a></li>
						<li><a href="<?php echo $relRoot; ?>contact/#skype" title="Skype"><span class="fa fa-skype icon"></span>Skype</a></li>
						<li><a href="<?php echo $relRoot; ?>contact/#email" title="Email"><span class="fa fa-envelope icon"></span>Email</a></li>
					</ul>
				</nav>

				<!-- Footer nav menu -->
				<nav class="simple">
					<ul>
						<li><a href="<?php echo $relRoot; ?>" title="Home">Home</a></li>
						<li><a href="<?php echo $relRoot; ?>blog" title="Blog">Blog</a></li>
						<li><a href="<?php echo $relRoot; ?>library" title="Library">Library</a></li>
						<li><a href="<?php echo $relRoot; ?>portfolio" title="Portfolio">Portfolio</a></li>
						<li><a href="<?php echo $relRoot; ?>contact" title="Contact">Contact</a></li>
					</ul>
				</nav>

				<!-- Copyright info -->
				<div class="copyright">
					&copy; 2014-2015 Daniel Andrus
				</div>
			</footer>
		</div>
	</body>
</html>
