<?php
if (!defined('ABSPATH'))
	exit;

// Dans votre fichier functions.php ou un fichier de plugin
require_once get_template_directory() . '/class-intranet.php';

// Si vous voulez instancier la classe manuellement plutôt qu'à la fin du fichier de classe
use ThemeIntranet\Intranet;
new Intranet();
