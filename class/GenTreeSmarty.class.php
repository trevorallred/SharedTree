<?
$smarty_class = $config["SMARTY_LIB"] . "Smarty.class.php";

if (!file_exists($smarty_class))
	die("Failed to load Smarty libraries ($smarty_class). Please update your local_config.php file with a valid Smarty library");

require_once($smarty_class);

class GenTreeSmarty extends Smarty {
    function GenTreeSmarty() {
	parent::__construct();

        global $config;
	$this->setTemplateDir($config['BASE_DIR'] . '/templates');
	$this->setCompileDir($config['BASE_DIR'] . '/inc/smarty/templates_c');
	$this->setConfigDir($config['BASE_DIR'] . '/inc/smarty/configs');
	$this->setCacheDir($config['BASE_DIR'] . '/inc/smarty/cache');

	//$this->caching = Smarty::CACHING_LIFETIME_CURRENT;
	$this->assign('app_name', 'SharedTree');
	// print_pre($this->cache_dir);
	/*

	//if (!file_exists($this->plugins_dir)) {
	//	die("Smarty plugins dir doesn't exist in $this->plugins_dir");
	//}
	if (!file_exists($this->compile_dir))
		die("Smarty templates_c directory doesn't exist. You must create it in BASE_DIR/inc/smarty/templates_c");
	if (!is_writable($this->compile_dir))
		die("Smarty templates_c directory is not writable");
	if (!file_exists($this->config_dir))
		die("Smarty configs directory doesn't exist. You must create it in BASE_DIR/inc/smarty/configs");
	if (!file_exists($this->cache_dir))
		die("Smarty cache_dir directory doesn't exist. You must create it in BASE_DIR/inc/smarty/cache");
	if (!is_writable($this->cache_dir))
		die("Smarty cache_dir directory is not writable");
*/
    }
}

?>
