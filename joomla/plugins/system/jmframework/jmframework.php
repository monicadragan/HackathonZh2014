<?php
/**
 * @version $Id: jmframework.php 34 2014-04-09 11:43:22Z michal $
 * @package JMFramework
 * @copyright Copyright (C) 2012 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Michal Olczyk - michal.olczyk@design-joomla.eu
 *
 * JMFramework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * JMFramework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with JMFramework. If not, see <http://www.gnu.org/licenses/>.
 *
 */

defined('_JEXEC') or die('Restricted access');

class plgSystemJMFramework extends JPlugin
{
    private $template;
    
    public function __construct(&$subject, $config = array()) {
        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }
        parent::__construct($subject, $config);
    }
    
    /**
     * 
     * Enter description here ...
     * @param JForm $form
     * @param unknown $data
     */
    
    function onContentPrepareForm($form, $data)
    {
        $app = JFactory::getApplication();
        $doc = JFactory::getDocument();
        $this->template = $this->getTemplateName();
        
        if ($this->template && ( ($app->isAdmin() && $form->getName() == 'com_templates.style') || ($app->isSite() && ($form->getName() == 'com_config.templates' || $form->getName() == 'com_templates.style')) )) {
            jimport('joomla.filesystem.path');
            //JForm::addFormPath( dirname(__FILE__) . DS. 'includes' . DS .'assets' . DS . 'admin' . DS . 'params');
            $plg_file = JPath::find(dirname(__FILE__) . DS. 'includes' . DS .'assets' . DS . 'admin' . DS . 'params', 'template.xml');
            $tpl_file = JPath::find(JPATH_ROOT . DS. 'templates' . DS . $this->template, 'templateDetails.xml');
            
            if (!$plg_file) {
                return false;
            }
            if ($tpl_file) {
                $form->loadFile($plg_file, false, '//form');
                $form->loadFile($tpl_file, false, '//config');
            } else {
                $form->loadFile($plg_file, false, '//form');
            }
            
            if ($app->isSite()) {
                $jmstorage_fields = $form->getFieldset('jmstorage');
                foreach ($jmstorage_fields as $name => $field){
                    $form->removeField($name, 'params');
                }
                $form->removeField('config', 'params');
                
                $jmlayoutbuilder_fields = $form->getFieldset('jmlayoutbuilder');
                foreach ($jmlayoutbuilder_fields as $name => $field){
                	$form->removeField($name, 'params');
                }
                $form->removeField('layout', 'params');
            }
            
            if ($app->isAdmin()) {
            	$doc->addStyleDeclaration('#jm-ef3plugin-info, .jm-row > .jm-notice {display: none !important}');
            }
            
        }
    }
    
    function onAfterRoute(){
        $app = JFactory::getApplication();
        
        $template = $this->getTemplateName();
        if ($template) {
            define('JMF_FRAMEWORK_PATH', dirname(__FILE__));
            define('JMF_FRAMEWORK_URL', JURI::root(true).'/plugins/system/jmframework');
            
            define('JMF_TPL', $template);
            define('JMF_TPL_PATH', JPATH_ROOT.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.$template);
            define('JMF_TPL_URL', JURI::root(true). '/templates/' . $template);
            
            define('JMF_EXEC', 'JMF');
            define('JMF_ASSETS', JURI::root(true).'/plugins/system/jmframework/includes/assets/admin/');
            
            $this->loadLanguage();
            
            $this->template = $template;
            
            if ($app->isSite()) {
                require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'template.php';
                include_once JMF_TPL_PATH.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'jm_template.php';
                $className = false;
                if (class_exists('JMTemplate')) {
                	$className = 'JMTemplate';
                } else if (class_exists('JMTemplate'.ucfirst(str_replace('-', '', JMF_TPL)))) {
                	$className = 'JMTemplate'.ucfirst(str_replace('-', '', JMF_TPL));
                }
                
                $lang = JFactory::getLanguage();
            
                $lang->load('tpl_'.$this->template, JPATH_ADMINISTRATOR, 'en-GB', false, true)
                ||  $lang->load('tpl_'.$this->template, JMF_TPL_PATH, 'en-GB', false, true);
                
                $lang->load('tpl_'.$this->template, JPATH_ADMINISTRATOR, null, true, true)
                ||  $lang->load('tpl_'.$this->template, JMF_TPL_PATH, null, true, true);
                
                if ($className !== false) {
                	$doc = JFactory::getDocument();
                	if ($doc instanceof JDocumentHTML) {
                		$jmf = new $className($doc);
                		$jmf->ajax(); // check for ajax requests
                	}
                }
            } else {
            	require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'template.php';
            	require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'admin.php';
            	$doc = JFactory::getDocument();
                $jmf = new JMFAdminTemplate($doc);
                $jmf->ajax(); // check for ajax requests 
            }
        }
    }
    
    function onAfterRender() {
        $app = JFactory::getApplication();
        if ($app->isAdmin() && $this->template) {
            $this->loadLanguage('tpl_'.$this->template, JPATH_ROOT);
        }
    }
        
    function onBeforeRender(){
        $app = JFactory::getApplication();
        $template = $this->getTemplateName();
        if ($template && ($app->isAdmin() || ($app->input->get('option') == 'com_config' && $app->input->get('view') == 'templates' ) )) {
            
        	$document = JFactory::getDocument();
            
            if ($app->isAdmin()) {
                $document->addStyleSheet(JMF_ASSETS . 'css/admin.css');
            }
            $document->addScript(JMF_ASSETS . 'js/jmoptiongroups.js');
            $document->addScript(JMF_ASSETS . 'js/jmspacer.js');
            //$document->addScript(JMF_TPL_ASSETS . 'js/jmconfig.js');
            $document->addScript(JMF_ASSETS . 'js/jscolor.js');
            $document->addScript(JMF_ASSETS . 'js/misc.js');
            
            //$document->addScript('http://code.jquery.com/jquery-latest.js');
        }
		
    }

	function onBeforeCompileHead(){
		
		$app = JFactory::getApplication();
		if (empty($this->template) || $app->isAdmin()) {
			return true;
		}
		
		$params = $app->getTemplate(true)->params;
		
		if($params->get('devmode',0) || JDEBUG || $app->input->get('option')=='com_config') { // don't compress css/js in development mode or joomla debug mode
			return true;
		}		
		
		if (JFolder::exists(JMF_TPL_PATH.DIRECTORY_SEPARATOR.'cache') == false) {
			if (!JFolder::create(JMF_TPL_PATH.DIRECTORY_SEPARATOR.'cache')) {
				if (JDEBUG) {
					throw new Exception(JText::_('PLG_SYSTEM_JMFRAMEWORK_CACHE_FOLDER_NOT_ACCESSIBLE'));	
				} else {
					return false;
				}
			}
		}
		
		$document = JFactory::getDocument();
		
		$cssCompress = $params->get('cssCompress','0')=='1' ? true : false;
		$jsCompress = $params->get('jsCompress','0')=='1' ? true : false;

		if($cssCompress) {
			
			$styles = $document->_styleSheets;
			$compress = array();
			$mtime = 0;
			
			foreach($styles as $url => $style) {
				
				// get css path
				$path = $this->getPath($url);
				if(!$path || !JFile::exists($path)) continue;		
				
				// get max modification time
				$ftime = filemtime($path);
				if($ftime > $mtime) $mtime = $ftime;
				
				$compress[$url] = $path;
			}
			
			$key = md5(serialize($compress));
			
			$stylepath = JPath::clean(JMF_TPL_PATH.'/cache/jmf_'.$key.'.css');
			$cachetime = JFile::exists($stylepath) ? filemtime($stylepath) : 0;
			$styleurl  = JMF_TPL_URL.'/cache/jmf_'.$key.'.css?t='.$cachetime;
			
			if(!JFile::exists($stylepath) || $mtime > $cachetime) {
				// compress and merge css files only if one of the files was modified
				require_once JPath::clean(JMF_FRAMEWORK_PATH.'/includes/libraries/minify/CSSmin.php');
				$cssmin = new CSSmin();
				$css = array();
				$css[] = "/* Joomla-Monster Framework minify css";
				$css[] = " * --------------------------------------------------";
				$css[] = " */";
				
				foreach($compress as $url => $path) {
					$src = JFile::read($path);
					$src = $this->updateUrls($src, dirname($url));
					$css[] = "\n/* --------------------------------------------------";
					$css[] = " * ".$url;
					$css[] = " * -------------------------------------------------- */";
					$css[] = $cssmin->run($src);
				}
				
				JFile::write($stylepath, implode("\n", $css));
			}
			
			if(JFile::exists($stylepath)) {
				
				$newstyles = array();
				
				$newstyles[$styleurl] = array('mime' => 'text/css', 'media' => null, 'attribs' => array());
				
				foreach ($styles as $url => $data) {
					if(!array_key_exists($url, $compress)) $newstyles[$url] = $data;
				}
				
				$document->_styleSheets = $newstyles;
			}
			
		}
		
		if($jsCompress) {
				
			$scripts = $document->_scripts;
			$compress = array();
			$mtime = 0;
			
			foreach($scripts as $url => $script) {
				
				// get css path
				$path = $this->getPath($url);
				if(!$path || !JFile::exists($path)) continue;		
				
				// get max modification time
				$ftime = filemtime($path);
				if($ftime > $mtime) $mtime = $ftime;
				
				$compress[$url] = $path;
			}
			
			$key = md5(serialize($compress));
			
			$scriptpath = JPath::clean(JMF_TPL_PATH.'/cache/jmf_'.$key.'.js');
			$cachetime = JFile::exists($scriptpath) ? filemtime($scriptpath) : 0;
			$scripturl  = JMF_TPL_URL.'/cache/jmf_'.$key.'.js?t='.$cachetime;
			
			if(!JFile::exists($scriptpath) || $mtime > $cachetime) {
				// compress and merge js files only if one of the files was modified
				require_once JPath::clean(JMF_FRAMEWORK_PATH.'/includes/libraries/minify/JSMin.php');
				
				$js = array();
				$js[] = "/* Joomla-Monster Framework minify js";
				$js[] = " * --------------------------------------------------";
				$js[] = " */";
				
				foreach($compress as $url => $path) {
					$src = JFile::read($path);
					$js[] = "\n/* --------------------------------------------------";
					$js[] = " * ".$url;
					$js[] = " * -------------------------------------------------- */";
					$js[] = JSMin::minify($src).";";
				}
				
				JFile::write($scriptpath, implode("\n", $js));
			}
			
			if(JFile::exists($scriptpath)) {
				
				$newscripts = array();
				
				$newscripts[$scripturl] = array('mime' => 'text/javascript', 'defer' => false, 'async' => false);
				
				foreach ($scripts as $url => $data) {
					if(!array_key_exists($url, $compress)) $newscripts[$url] = $data;
				}
				
				$document->_scripts = $newscripts;
			}
			
		}
		
	}

	function updateUrls($src, $url){
		
		$app = JFactory::getApplication();
		
		// make sure url is root relative or absolute
		$url = ($url[0] === '/' || strpos($url, '://') !== false) ? $url : JURI::root(true) . '/' . $url;
		
		// replace image urls
		preg_match_all('/url\\(\\s*([^\\)\\s]+)\\s*\\)/', $src, $matches, PREG_SET_ORDER);
		
		foreach($matches as $match) {
			
			$uri = $match[1];
			
			if($uri[0] === "'" || $uri[0] === '"') {
				$uri = substr($uri, 1, strlen($uri) - 2);
			} 
			
			if ($uri[0] !== '/' && strpos($uri, '://') === false && strpos($uri, 'data:') !== 0) {
				
				$uri = $url . '/' . $uri;
				// replace the url
				$src = str_replace($match[0], "url('$uri')", $src);
			}
		}
		
		// replace imported stylesheet urls
		preg_match_all('/@import\\s+[\'"](.*?)[\'"]/', $src, $matches, PREG_SET_ORDER);
		
		foreach($matches as $match) {
			
			$uri = $match[1];
			
			if($uri[0] === "'" || $uri[0] === '"') {
				$uri = substr($uri, 1, strlen($uri) - 2);
			} 
			
			if ($uri[0] !== '/' && strpos($uri, '://') === false && strpos($uri, 'data:') !== 0) {
				
				$uri = $url . '/' . $uri;
				// replace the url
				$src = str_replace($match[0], "@import '$uri'", $src);
			}
		}
		
		return $src;
	}

	function getPath($url) {
		
		$app = JFactory::getApplication();
		$params = $app->getTemplate(true)->params;
		$skips = explode("\n", $params->get('skipCompress'));
		
		foreach($skips as $skip) {
			//$this->debug("URL: ".$url."\nSKIP: ".$skip."\nCMP: ".(strstr($url, trim($skip))!==false ? 'TRUE':'FALSE'));
			if(strstr($url, trim($skip))!==false) return false;
		}
		
		if(substr($url, 0, 2) === '//'){
			$url = JURI::getInstance()->getScheme() . ':' . $url; 
		}
		
		if (preg_match('/^https?\:/', $url)) {
			if (strpos($url, JURI::base()) === false){
				// external css
				return false;
			}
			$path = JPath::clean(JPATH_ROOT . '/' . substr($url, strlen(JURI::base())));
		} else {
			$path = JPath::clean(JPATH_ROOT . '/' . (JURI::root(true) && strpos($url, JURI::root(true)) === 0 ? substr($url, strlen(JURI::root(true))) : $url));
		}
		
		return is_file($path) ? $path : false;
	}
	
    function getTemplateName() {
        $app = JFactory::getApplication();
        $template = false;
        if ($app->isSite()) {
            $template = $app->getTemplate(null);
        } else {
            $option = $app->input->get('option', null, 'string');
            $view = $app->input->get('view', null, 'string');
            $task = $app->input->get('task', '', 'string');
            $controller = current(explode('.',$task));
            $id = $app->input->get('id', null, 'int');
            if ($option == 'com_templates' && ($view == 'style' || $controller == 'style' || $task == 'apply' || $task == 'save' || $task == 'save2copy') && $id > 0) {
                $db = JFactory::getDbo();
                
                $query = $db->getQuery(true);
                
                $query->select('template');
                $query->from('#__template_styles');
                $query->where('id='.$id);
                
                $db->setQuery($query);
                $template = $db->loadResult();
            }
        }
        
        if ($template) {
            jimport('joomla.filesystem.file');
            $path = JPATH_ROOT.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.$template.DIRECTORY_SEPARATOR.'templateDetails.xml';
            if (JFile::exists($path)) {
                $xml = JInstaller::parseXMLInstallFile($path);
                if (array_key_exists('group', $xml)){
                    if ($xml['group'] == 'jmf') {
                        return $template;
                    }   
                }
            }
        }
        
        return false;
    }

	public static function debug($data, $exit = false, $type = 'warning'){
	
		$app = JFactory::getApplication();
		if($exit) {
			echo "JMF DEBUG:";
			echo  "<pre>".print_r($data,true)."</pre>";
			$app->close();
		} else {
			$app->enqueueMessage("<pre>JMF DEBUG:\n".print_r($data,true)."</pre>", $type);
		}
	}
}
