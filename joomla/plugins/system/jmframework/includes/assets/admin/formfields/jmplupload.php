<?php

/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Form Field class for the Joomla Platform.
 * Provides spacer markup to be used in form layouts.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldJmplupload extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  11.1
     */
    protected $type = 'Jmplupload';

    /**
     * Method to get the field input markup for a spacer.
     * The spacer does not have accept input.
     *
     * @return  string  The field input markup.
     *
     * @since   11.1
     */
    protected function getInput()
    {
    	JHtml::_('jquery.framework');
    	JHtml::_('script', 'system/html5fallback.js', false, true);
    	
    	$path = JUri::root().'plugins/system/jmframework/includes/assets/admin/formfields/jmplupload';
    	
    	$document = JFactory::getDocument();
    	$document->addScript($path.'/js/plupload.full.min.js');
    	
    	$browse_button = (!empty($this->element['browse_button'])) ? JText::_($this->element['browse_button']) : JText::_('PLG_SYSTEM_JMFRAMEWORK_PLUPLOAD_BROWSE');
    	$upload_button = (!empty($this->element['upload_button'])) ? JText::_($this->element['upload_button']) : JText::_('PLG_SYSTEM_JMFRAMEWORK_PLUPLOAD_UPLOAD');
    	
    	$browse_button_id 	= $this->id.'_browse';
    	$upload_button_id 	= $this->id.'_upload';
    	$container_id 		= $this->id.'_container';
    	$filelist_id 		= $this->id.'_files';
    	$console_id 		= $this->id.'_console';
    	
    	$flash_url = $path.'/js/Moxie.swf';
    	$silverlight_url = $path.'/js/Moxie.xap';
    	
    	$extensions = (!empty($this->element['extensions'])) ? $this->element['extensions'] : 'json,svg,eot,woff,ttf,otf,zip,jpg,jpeg,png,css';
    	
    	$uri = JUri::getInstance();

    	$myuri = new JUri($uri->toString());
    	$myuri->setVar('jmajax', 'plupload');
    	$myuri->setVar('jmtask', (string)$this->element['task']);
    	$myuri->setVar('jmpluploadid', $this->id);
    	
    	$url = $myuri->toString();
    	
    	//JURI::reset();
    	
    	$js ='
    			jQuery(document).ready(function(){
    				var JMPLUpload_'.$this->id.' = new plupload.Uploader({
						runtimes : \'html5,flash,silverlight,html4\',
						browse_button : \''.$browse_button_id.'\', 
						container: \''.$container_id.'\',
						url : \''.$url.'\',
						flash_swf_url : \''.$flash_url.'\',
						silverlight_xap_url : \''.$silverlight_url.'\',
						
						filters : {
							max_file_size : \'10mb\',
							mime_types: [
								{title : "Allowed files", extensions : "'.$extensions.'"}
							]
						},
					
						init: {
							PostInit: function() {
								document.getElementById(\''.$filelist_id.'\').innerHTML = \'\';
					
								document.getElementById(\''.$upload_button_id.'\').onclick = function() {
									JMPLUpload_'.$this->id.'.start();
									return false;
								};
							},
					
							FilesAdded: function(up, files) {
								plupload.each(files, function(file) {
									document.getElementById(\''.$filelist_id.'\').innerHTML += \'<div id="\' + file.id + \'">\' + file.name + \' (\' + plupload.formatSize(file.size) + \') <b></b></div>\';
								});
							},
					
							UploadProgress: function(up, file) {
								document.getElementById(file.id).getElementsByTagName(\'b\')[0].innerHTML = \'<span>\' + file.percent + "%</span>";
							},
							
							Error: function(up, err) {
								document.getElementById(\''.$console_id.'\').innerHTML += "\nError #" + err.code + ": " + err.message;
							},
										
							UploadComplete: function(up, file, undef) {
								jQuery(document).trigger("jmplupload_'.$this->id.'", [up, file, undef]);
							}
						}
					});
					
					JMPLUpload_'.$this->id.'.init();
    			});
    			';
    	
    	$document->addScriptDeclaration($js);
    	
    	$html = '
    			<div id="'.$container_id.'">
	    			<span id="'.$browse_button_id.'" class="button btn">'.$browse_button.'</span>
					<span id="'.$upload_button_id.'" class="button btn">'.$upload_button.'</span>
				</div>
				<div>
					<p id="'.$console_id.'"></p>
					<p id="'.$filelist_id.'">Your browser doesn\'t have Flash, Silverlight or HTML5 support.</p>
				</div>
    	';
    	
    	return $html;
    }
}
