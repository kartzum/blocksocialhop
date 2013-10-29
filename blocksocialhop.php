<?php
if (!defined('_CAN_LOAD_FILES_'))
	exit;
	
class BlockSocialHop extends Module
{
	private $elements;
	
	public function __construct()
	{			
		$this->name = 'blocksocialhop';
		$this->tab = 'front_office_features';		
		$this->author = 'kartzum';
		$this->version = '0.1.0';
		$this->displayName = 'Block Social Hop';

		parent::__construct();

		$this->displayName = $this->l('Social networking block');
		$this->description = $this->l('Allows you to add information about your brand\'s social networking sites.');
		
		$this->elements = array(
				'blocksocialhop_facebook' => array(
						'url' => 'facebook_url',
						'l_configuration_label' => 'Facebook URL: ',						
						'img_path' => 'facebook.png',
						'l_title' => 'Facebook',
					),
				'blocksocialhop_twitter' => array(
						'url' => 'twitter_url',
						'l_configuration_label' => 'Twitter URL: ',						
						'img_path' => 'twitter.png',
						'l_title' => 'Twitter',
					),				
				'blocksocialhop_gplus' => array(
						'url' => 'gplus_url',
						'l_configuration_label' => 'G+ URL: ',						
						'img_path' => 'gplus.png',
						'l_title' => 'G+',
				),
				'blocksocialhop_vk' => array(
						'url' => 'vk_url',
						'l_configuration_label' => 'VK URL: ',						
						'img_path' => 'vk.png',
						'l_title' => 'VK',
				),
				'blocksocialhop_rss' => array(
						'url' => 'rss_url',
						'l_configuration_label' => 'RSS URL: ',						
						'img_path' => 'rss.png',
						'l_title' => 'RSS',
				),
		);
	}
	
	public function install()
	{
		$installElement = FALSE;
		foreach ($this->elements as $key => $data) {
			$installElement = Configuration::updateValue($key, '');
			if($installElement == FALSE) {
				break;
			}
		}
		
		return (parent::install() 
				&& $installElement 				 				
				&& $this->registerHook('displayHeader') 
				&& $this->registerHook('displayFooter')
		);
	}
	
	public function uninstall()
	{	
		$uninstallElement = FALSE;
		foreach ($this->elements as $key => $data) {
			$uninstallElement = Configuration::deleteByName($key);
			if($uninstallElement == FALSE) {
				break;
			}
		}
					
		return (parent::uninstall() && $uninstallElement);
	}
	
	public function getContent()
	{
		$output = '';
		
		if (isset($_POST['submitModule']))
		{	
			foreach ($this->elements as $key => $data) {
				Configuration::updateValue($key, (($_POST[$data['url']] != '') ? $_POST[$data['url']]: ''));
			}
							
			$this->_clearCache('blocksocialhop.tpl');
			$output = '<div class="conf confirm">'.$this->l('Configuration updated').'</div>';
		}
		
		$content = '';
		foreach ($this->elements as $key => $data) {
			$content = $content.'
					<label for="'.$data['url'].'">'.$this->l($data['l_configuration_label']).'</label>
					<input type="text" id="'.$data['url'].'" name="'.$data['url'].'" value="'.Tools::safeOutput((Configuration::get($key) != "") ? Configuration::get($key) : "").'" />
					<div class="clear">&nbsp;</div>';
		}
		
		return '
		<h2>'.$this->displayName.'</h2>
		'.$output.'
		<form action="'.Tools::htmlentitiesutf8($_SERVER['REQUEST_URI']).'" method="post">
			<fieldset class="width2">'.$content.'										
				<br /><center><input type="submit" name="submitModule" value="'.$this->l('Update settings').'" class="button" /></center>
			</fieldset>
		</form>';		
	}
	
	public function hookDisplayHeader()
	{
		$this->context->controller->addCSS(($this->_path).'blocksocialhop.css', 'all');
	}
		
	public function hookDisplayFooter()
	{
		if (!$this->isCached('blocksocialhop.tpl', $this->getCacheId())) {
			$elements = array();
			foreach ($this->elements as $key => $data) {
				$elements[$key] = array();
				$elements[$key]['url'] = Configuration::get($key);
				$elements[$key]['img'] = ($this->_path).'img/'.$data['img_path'];
				$elements[$key]['title'] = $this->l($data['l_title']);
			}
			$this->smarty->assign(array('elements' => $elements));			
		}

		return $this->display(__FILE__, 'blocksocialhop.tpl', $this->getCacheId());
	}
}
?>
