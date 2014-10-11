<?php
/**
 * @version $Id: jmlayout.php 32 2014-03-25 11:49:38Z michal $
 * @package JMFramework
 * @copyright Copyright (C) 2012 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Szymon Woronowski - szymon.woronowski@design-joomla.eu
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

defined('JPATH_PLATFORM') or die;

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class JFormFieldJmlayout extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  11.1
     */
    protected $type = 'Jmlayout';
    protected static $loaded = false;

    
    protected function getInput()
    {
        if (!self::$loaded && defined('JMF_EXEC')) {
            
        	self::$loaded = true;
        	$app = JFactory::getApplication();
            $doc = JFactory::getDocument();
			
            JHtml::_('jquery.ui', array('core', 'sortable'));
            $doc->addStyleSheet(JMF_ASSETS . 'css/layout.css');
            $doc->addScript(JMF_ASSETS . 'js/jmlayout.js');
            
            JFactory::getDocument()->addScriptDeclaration ("
				jQuery.extend(JMLayoutBuilder, {
            		url: '" . JFactory::getURI()->toString() . "',
        			field: '".$this->id."',
        			lang: ".$this->addLanguage()."
        		});
				jQuery(document).ready(function() {
					jQuery(document.body).addClass('jmframework');
        			jQuery('.jm_layoutbuilder_build a').click(function (e) {
						e.preventDefault();
						jQuery(this).tab('show');
					});
        		});
			");
            
            $options = $this->getOptions();
            $loadOptions = JHtml::_('select.genericlist', $options, $this->name, 'onchange="JMLayoutBuilder.loadLayout()"', 'value', 'text', $this->value, $this->id);
            
            $layoutbuilder_path = JPath::clean(JMF_FRAMEWORK_PATH.'/includes/assets/admin/layouts/layoutbuilder.php');
            
            ob_start();
			if (JFile::exists($layoutbuilder_path)) {
				include($layoutbuilder_path);
			} else {
				throw new Exception('Missing file: '.$layoutbuilder_path, 500);
			}
			$html = ob_get_contents();
			ob_end_clean();
			
			return $html;
        }
    }
    
    private function addLanguage(){

    	$langs = array(
			'PLG_SYSTEM_JMFRAMEWORK_EMPTY_POSITION' => JText::_('PLG_SYSTEM_JMFRAMEWORK_EMPTY_POSITION'),
			'PLG_SYSTEM_JMFRAMEWORK_SELECT_MODULE_POSITION' => JText::_('PLG_SYSTEM_JMFRAMEWORK_SELECT_MODULE_POSITION'),
			'PLG_SYSTEM_JMFRAMEWORK_EDIT_MODULE_POSITION' => JText::_('PLG_SYSTEM_JMFRAMEWORK_EDIT_MODULE_POSITION'),
			'PLG_SYSTEM_JMFRAMEWORK_ELEMENT_WIDTH' => JText::_('PLG_SYSTEM_JMFRAMEWORK_ELEMENT_WIDTH'),
			'PLG_SYSTEM_JMFRAMEWORK_MODULE_POSITION_NAME' => JText::_('PLG_SYSTEM_JMFRAMEWORK_MODULE_POSITION_NAME'),
			'PLG_SYSTEM_JMFRAMEWORK_ELEMENT_DRAG_TO_RESIZE' => JText::_('PLG_SYSTEM_JMFRAMEWORK_ELEMENT_DRAG_TO_RESIZE'),
			'PLG_SYSTEM_JMFRAMEWORK_HIDE_POSITION' => JText::_('PLG_SYSTEM_JMFRAMEWORK_HIDE_POSITION'),
			'PLG_SYSTEM_JMFRAMEWORK_SHOW_POSITION' => JText::_('PLG_SYSTEM_JMFRAMEWORK_SHOW_POSITION'),
			'PLG_SYSTEM_JMFRAMEWORK_HIDDEN_POSITION_DESC' => JText::_('PLG_SYSTEM_JMFRAMEWORK_HIDDEN_POSITION_DESC'),
			'PLG_SYSTEM_JMFRAMEWORK_CONFIRM_COPY_LAYOUT' => JText::_('PLG_SYSTEM_JMFRAMEWORK_CONFIRM_COPY_LAYOUT'),
			'PLG_SYSTEM_JMFRAMEWORK_CONFIRM_DELETE_LAYOUT' => JText::_('PLG_SYSTEM_JMFRAMEWORK_CONFIRM_DELETE_LAYOUT'),
			'PLG_SYSTEM_JMFRAMEWORK_CORRECT_LAYOUT_NAME' => JText::_('PLG_SYSTEM_JMFRAMEWORK_CORRECT_LAYOUT_NAME'),
			'PLG_SYSTEM_JMFRAMEWORK_UNKNOWN_WIDTH' => JText::_('PLG_SYSTEM_JMFRAMEWORK_UNKNOWN_WIDTH'),
			'PLG_SYSTEM_JMFRAMEWORK_CHANGE_POSITOIN_NUMBER' => JText::_('PLG_SYSTEM_JMFRAMEWORK_CHANGE_POSITOIN_NUMBER'),
    		'PLG_SYSTEM_JMFRAMEWORK_CANT_LOAD_LAYOUT' => JText::_('PLG_SYSTEM_JMFRAMEWORK_CANT_LOAD_LAYOUT'),
    		'PLG_SYSTEM_JMFRAMEWORK_DRAG_TO_RESIZE' => JText::_('PLG_SYSTEM_JMFRAMEWORK_DRAG_TO_RESIZE'),
    		'PLG_SYSTEM_JMFRAMEWORK_MODULES_CHROME' => JText::_('PLG_SYSTEM_JMFRAMEWORK_MODULES_CHROME'),
    		'PLG_SYSTEM_JMFRAMEWORK_SORT_BLOCKS' => JText::_('PLG_SYSTEM_JMFRAMEWORK_SORT_BLOCKS'),
    		'PLG_SYSTEM_JMFRAMEWORK_HIDE_BLOCK' => JText::_('PLG_SYSTEM_JMFRAMEWORK_HIDE_BLOCK'),
    		'PLG_SYSTEM_JMFRAMEWORK_SHOW_BLOCK' => JText::_('PLG_SYSTEM_JMFRAMEWORK_SHOW_BLOCK'),
    		'PLG_SYSTEM_JMFRAMEWORK_SORT_MAIN_COLUMNS' => JText::_('PLG_SYSTEM_JMFRAMEWORK_SORT_MAIN_COLUMNS')
    	);
    	
    	return json_encode($langs);
    }
    
    protected function getLabel(){
    	return '<label id="' . $this->id . '-lbl"">' . JText::_('PLG_SYSTEM_JMFRAMEWORK_LAYOUTBUILDER_INFO') . '</label>';
    }
    
    protected function getOptions() {
        $options = array();
        if (defined('JMF_TPL_PATH')) {
	        $path = JMF_TPL_PATH.DIRECTORY_SEPARATOR.'tpl';
	
	        $files = JFolder::files($path, '.php');
	        
	        if (is_array($files)) {
	            foreach($files as $file) {
	            	$options[] = JHtml::_('select.option', JFile::stripExt($file), JFile::stripExt($file));
	            }
	        }
        }

        return $options;
    }
    
    private function debug($msg, $type = 'message') {
    
    	$app = JFactory::getApplication();
    	$app->enqueueMessage("<pre>".print_r($msg, true)."</pre>", $type);
    
    }
}
?>