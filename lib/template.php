<?
class Template extends Smarty {

  function Template() {
    global $config;

    $this->Smarty();

    $this->template_dir = BASE_PATH . 'templates/' . $config['active_template'] . '/';
    $this->compile_dir  = BASE_PATH . 'templates_c/';
    $this->config_dir   = '';
    $this->cache_dir    = '';

    $this->caching = false;
    $this->assign('url_base', $config['website_url']);
    $this->assign('tpl_base', 'templates/' . $config['active_template']);
  }
}
?>