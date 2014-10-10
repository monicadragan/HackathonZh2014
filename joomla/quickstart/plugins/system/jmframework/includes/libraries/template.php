<?php
/**
 * @version $Id: template.php 35 2014-04-10 10:19:00Z michal $
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

abstract class JMFTemplate {
    
    protected $maxgrid  = 12;
    protected $spanX    = '/(\s*)span(\d+)(\s*)/';
    protected $dscreen  = 'default';
    protected $screens  = array('default', 'wide', 'normal', 'xtablet', 'tablet', 'mobile');
    protected $maxcol   = array('default' => 6, 'wide' => 6, 'normal' => 6, 'xtablet' => 4, 'tablet' => 2, 'mobile' => 2);
    protected $minspan  = array('default' => 2, 'wide' => 2, 'normal' => 2, 'xtablet' => 3, 'tablet' => 6, 'mobile' => 6);  
    
    /**
     * layout config
     * @var JRegistry
     */
    protected $layoutconfig = null;
    
    /**
     * included blocks
     */
    protected $blocks = null;
    
    /**
     * @var JDocument
     */
    public $document;
    
    /**
     * @var JRegistry
     */
    public $params;
    
    /**
     * 
     * Browser type - handler by Mobile_Detect class [desktop|phone|tablet]
     * @var string
     */
    public $browser_type;
    
    /**
     * @var bool
     */
    public static $less_js_included = false;
    
    function __construct(JDocument &$document) {
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
        
        $app = JFactory::getApplication();
        
        $this->params = new JRegistry;
        
        $tpl_vars = get_object_vars($document);
        
        foreach ($tpl_vars as $name => $value) {
            if (!empty($value)) {
                if (is_object($value)) {
                    $this->$name = clone $value;
                }
                else {
                    $this->$name = $value;
                }
            }
        }
        
        $this->document = $document;
        
        $this->setup();
        
        // get layout config
        $layout = $this->params->get('layout', 'default');
        if($app->isAdmin()) {
            $layout = $app->input->getCmd('jmlayout', $layout);
        }
        $layout_path = JPath::clean(JPATH_ROOT . '/templates/' . JMF_TPL . '/assets/layout/' . $layout . '.json');
        if (JFile::exists($layout_path)) {
            $this->layoutconfig = new JRegistry;
            $this->layoutconfig->loadString(JFile::read($layout_path));
        }
        
        $this->postSetup();
    }

    protected function setup() {
        $app = JFactory::getApplication();
        $tplarray = $this->params->toArray();

        // loading joomla core features
        JHTML::_('behavior.modal');
        JHTML::_('behavior.tooltip');
        JHtml::_('jquery.ui', array('core', 'sortable'));
        
        // add layout responsiveness script
        $this->addScript(JMF_FRAMEWORK_URL.'/includes/assets/template/js/layout.js');
        //$this->debug(JMF_FRAMEWORK_URL.'/assets/template/js/layout.js');
        
        // determine the direction
        if ($app->input->get('direction') == 'rtl'){
            setcookie("jmfdirection", "rtl");
            $direction = 'rtl';
        } else if ($app->input->get('direction') == 'ltr') {
            setcookie("jmfdirection", "ltr");
            $direction = 'ltr';
        } else {
            if (isset($_COOKIE['jmfdirection'])) {
                $direction = $_COOKIE['jmfdirection'];
            } else {
                $direction = $app->input->get('jmfdirection', $this->document->direction);
            }
        }
        
        $this->direction = $this->params->set('direction', $direction);
        
        // handle JM Option Groups
        foreach ($tplarray as $param => $value) {
            if (is_string($value) && strstr($value,';')) {
                $parts = explode(';', $value);
                if(is_numeric($parts[0])) $this->params->set($param, $parts[0]); // only numeric to avoid cutting the text options containg semicolons 
            }
        }
        
        if (!class_exists('Mobile_Detect')) {
            require_once JMF_FRAMEWORK_PATH.DIRECTORY_SEPARATOR. 'includes' .DIRECTORY_SEPARATOR. 'libraries' .DIRECTORY_SEPARATOR. 'Mobile_Detect' .DIRECTORY_SEPARATOR. 'Mobile_Detect.php';  
        }
        $detect = new Mobile_Detect;
        $this->browser_type = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'desktop');
        
        $themer_mode = ($this->params->get('themermode', false) == '1') ? true : false;
        
        // make sure that custom fonts' CSS files have been generated
        $font_path = JMF_TPL_PATH.DIRECTORY_SEPARATOR.'fonts';
        
        if (is_dir($font_path)) {
            $font_folders = JFolder::folders($font_path);
            if (is_array($font_folders) && !empty($font_folders)) {
                foreach ($font_folders as $font) {
                    if ($this->generateFontCss($font, $font_path.DIRECTORY_SEPARATOR.$font) && $themer_mode) {
                        $this->addStyleSheet(JMF_TPL_URL.'/fonts/'.$font.'/font.css');
                    }
                }
            }
        }
    }
    
    abstract function postSetUp ();
    
    public function cacheStyleSheet($generator) {
        if (JFolder::exists(JMF_TPL_PATH.DIRECTORY_SEPARATOR.'cache') == false) {
            if (!JFolder::create(JMF_TPL_PATH.DIRECTORY_SEPARATOR.'cache')) {
                if (JDEBUG) {
                    throw new Exception(JText::_('PLG_SYSTEM_JMFRAMEWORK_CACHE_FOLDER_NOT_ACCESSIBLE'));    
                } else {
                    return false;
                }
            }
        }
        
        $tplParamsHash = md5($this->params->toString());
        
        // file name
        $css = current(explode('.', $generator)).'_'.$tplParamsHash.'.css';
        
        // CSS path
        //$cssPath = JPATH_ROOT.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'tpl-'.$this->template.DIRECTORY_SEPARATOR.$css;
        $cssPath = JMF_TPL_PATH.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$css;
        
        // CSS URL
        //$cssURL = JURI::base().'cache/tpl-'.$this->template.'/'.$css;
        $cssURL = JMF_TPL_URL.'/cache/'.$css;
        
        // CSS generator
        $cssGenerator = JMF_TPL_PATH.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.$generator;
        
        if (!JFile::exists($cssGenerator)) {
            if (JDEBUG) {
                throw new Exception(JText::sprintf('PLG_SYSTEM_JMFRAMEWORK_MISSING_CSS_GENERATOR', $generator));    
            } else {
                return false;
            }
        }
        
        if (!JFile::exists($cssPath) || $this->params->get('devmode', false) == true) {
            if (JFile::exists($cssPath)) {
                JFile::delete($cssPath);
            }
            // if there's nothing in cache, let's cache the css.    
            ob_start();
            // PHP file which uses template parameters to generate CSS content
            include($cssGenerator);
            
            $cssContent = ob_get_contents();
            
            ob_end_clean();
            
            if ($cssContent) {
                if (!JFile::write($cssPath, $cssContent)) {
                    if (JDEBUG) {
                        throw new Exception(JText::_('PLG_SYSTEM_JMFRAMEWORK_CACHE_FOLDER_NOT_ACCESSIBLE'));    
                    } else {
                        return false;
                    }
                }
            }
        }

        // if CSS exists return its URL
        if (JFile::exists($cssPath)) {
            return $cssURL;
        }
        return false;
    }
    
    public function checkModules($condition) {
        
        $operators = '(,|\+|\-|\*|\/|==|\!=|\<\>|\<|\>|\<=|\>=|and|or|xor)';
        $words = preg_split('# ' . $operators . ' #', $condition, null, PREG_SPLIT_DELIM_CAPTURE);
        for ($i = 0, $n = count($words); $i < $n; $i += 2) {
            // odd parts (modules)
            //$name = strtolower($words[$i]); // why to lower?
            $words[$i] = $this->getPosition($words[$i]);
        }
        
        $newcond = '';
        foreach ($words as $word) {
            $newcond = ' ' . $word; // merge positions with operators
        }
        
        return $this->countModules(trim($newcond)); // pass condition to JDocument function
    }
    
    public function countModules($condition) {
        return $this->document->countModules($condition);
    }
    
    public function getPosition($name)
    {
        $position = $this->getLayoutConfig($name, $name);
        if(!is_string($position)) $position = (is_array($position) ? $position['position'] : (isset($position->position) ? $position->position : $position));
        
        return $position;
    }
    
    public function countMenuChildren()
    {
        return $this->document->countMenuChildren();
    }
    
    public function addStyleSheet($path, $type = 'text/css', $media = null, $attribs = array()) {
        return $this->document->addStyleSheet($path, 'text/css', $media, $attribs);
    }
    
    public function addCompiledStyleSheet($path, $useVars = true, $themer = false) {
        if ($themer) {
            //if (false){
            $this->attachThemeCustomiser($useVars);
            if ($lessPath = $this->getLessUrl($path)){
                return $this->document->addHeadLink($lessPath, 'stylesheet/less');
            }
        } else {
            $path = $this->lessToCss($path, $useVars);
            if ($path) {
                return $this->document->addStyleSheet($path);
            }
        }
    }
    
    public function addStyleDeclaration($content, $type = 'text/css'){
        return $this->document->addStyleDeclaration($content, $type);
    }
    
    public function addScript($url, $type = "text/javascript", $defer = false, $async = false)
    {
        return $this->document->addScript($url, $type, $defer, $async);
    }

    public function addScriptDeclaration($content, $type = 'text/javascript')
    {
        return $this->document->addScriptDeclaration($content, $type);
    }
    
    public function addGeneratedFont($font) {
        $developer_mode = ($this->params->get('devmode', false) == '1') ? true : false;
        $themer_mode = ($this->params->get('themermode', false) == '1') ? true : false;
        
        $font_dir = JMF_TPL_PATH.DIRECTORY_SEPARATOR.'fonts'.DIRECTORY_SEPARATOR.$font;
        $font_css_path = $font_dir.DIRECTORY_SEPARATOR.'font.css';
        $font_css_url = JMF_TPL_URL.'/fonts/'.$font.'/font.css';
        
        if (JFolder::exists($font_dir) == false) {
            return false;
        }
        
        if (JFile::exists($font_css_path)) {
            if ($developer_mode) {
                JFile::delete($font_css_path);
            } else {
                return $this->addStyleSheet($font_css_url);
            }
        }

        if ($this->generateFontCss($font, $font_dir)) {
            return $this->addStyleSheet($font_css_url);
        }
        
        return false;
        
    }
        
    public function generateFontCss($font, $font_dir){
        if (JFolder::exists($font_dir) == false) {
            return false;
        }
        
        $font_types = array('eot','woff','ttf','svg');
        $font_files = array();
        foreach($font_types as $type) {
            if (JFile::exists($font_dir.DIRECTORY_SEPARATOR.$font.'.'.$type)) {
                $font_files[$type] = true;
            }
        }
        
        if (empty($font_files)) {
            return false;
        }
        
        $css_contents = array();
        $css_contents[] = '@font-face {';
        $css_contents[] = 'font-family: \''.$font.'\';';
        if (isset($font_files['eot'])) {
            $css_contents[] = 'src: url(\''.$font.'.eot\');';
        }
        $src = array();
        foreach($font_files as $k=>$v) {
            if ($k == 'eot') {
                $src[] = 'url(\''.$font.'.eot?#iefix\') format(\'embedded-opentype\')';
            } else if ($k == 'woff') {
                $src[] = 'url(\''.$font.'.woff\') format(\'woff\')';
            } else if ($k == 'ttf') {
                $src[] = 'url(\''.$font.'.ttf\') format(\'truetype\')';
            } else if ($k == 'svg') {
                $src[] = 'url(\''.$font.'.svg#'.$font.'\') format(\'svg\')';
            }
        }
        if (!empty($src)) {
            $css_contents[] = 'src: '.implode(",\n", $src).';';
        }
        $css_contents[] = 'font-weight: normal;';
        $css_contents[] = 'font-style: normal;';
        $css_contents[] = '}';
        
        $css_content = implode("\n", $css_contents);
        
        return JFile::write($font_dir.DIRECTORY_SEPARATOR.'font.css', $css_content);
        
    }

    public function renderBlock($block_name, $is_scheme = false) {
        $block_name = ($is_scheme) ? $block_name : 'blocks/'.$block_name;
        $layout_file = JPath::clean(JMF_TPL_PATH.'/tpl/'.$block_name.'.php');      
        if (JFile::exists($layout_file)) {
            include($layout_file);
        } else {
            throw new Exception(JText::_('PLG_SYSTEM_JMFRAMEWORK_MISSING_BLOCK_FILE').': '.$block_name, 400);
        }
    }
    
    public function renderScheme($scheme_name) {
        return $this->renderBlock($scheme_name, true);
    }
    
    public function renderModules($position, $chrome = 'none', $grid_layout = 12) {
        if (!$position) return false;
        jimport( 'joomla.cms.module.helper' );
        $renderer = JFactory::getDocument()->loadRenderer('module');
    
        $app = JFactory::getApplication();
        $frontediting = $app->getCfg('frontediting', 1);
    
        $version = new JVersion;
    
        $user = JFactory::getUser();
        $canEdit = $user->id && $frontediting && !($app->isAdmin() && $frontediting < 2) && $user->authorise('core.edit', 'com_modules');
        $correctVersion = (bool)version_compare($version->getShortVersion(), '3.2.0', '>=');
        $menusEditing = ($frontediting == 2) && $user->authorise('core.edit', 'com_menus');
    
        $bootstrap_row_counter = $grid_layout;
        
        $posname = $this->getPosition($position);
        
        $html = '';
        if ($modules = JModuleHelper::getModules( $posname )) {
            $attribs['style'] = $chrome;
    
            //$html .= '<div class="'.$position.' count_'.count($modules).' '.$this->getClass($position).'">';
    
            $count = count($modules);
    
            for ($i = 0; $i < $count; $i++) {
                $module_params = new JRegistry;
                $module_params->loadString($modules[$i]->params);
                $bootstrap_size = (int)$module_params->get('bootstrap_size', 0);
                $span_size = ($bootstrap_size == 0) ? $grid_layout : $bootstrap_size;
    
                $module_content = $renderer->render($modules[$i], $attribs, null);
    
                $module_html  = '<div class="span'.$bootstrap_size.'">';
                $module_html .= $module_content;
                $module_html .= '</div>';
    
                if ($correctVersion && $app->isSite() && $canEdit && trim($module_content) != '' && $user->authorise('core.edit', 'com_modules.module.' . $modules[$i]->id))
                {
                    $displayData = array('moduleHtml' => &$module_html, 'module' => $modules[$i], 'position' => $posname, 'menusediting' => $menusEditing);
                    JLayoutHelper::render('joomla.edit.frontediting_modules', $displayData);
                }
    
                if ($bootstrap_row_counter == $grid_layout) {
                    $html .= '<div class="row-fluid">';
                }
    
                $html .= $module_html;
                $bootstrap_row_counter -= $span_size;
                if ($i < $count-1 && $bootstrap_row_counter > 0) {
                    $next_module_params = new JRegistry;
                    $next_module_params->loadString($modules[$i+1]->params);
                    $next_bootstrap_size = (int)$next_module_params->get('bootstrap_size', '0');
                    $next_span_size = ($next_bootstrap_size == 0) ? $grid_layout : $next_bootstrap_size;
    
                    if ((int)($bootstrap_row_counter - $next_span_size) < 0) {
                        $bootstrap_row_counter -= $next_span_size;
                        $html .= '</div>';
                    }
                } else {
                    $html .= '</div>';
                }
                $bootstrap_row_counter;
                if ($bootstrap_row_counter <= 0){
                    $bootstrap_row_counter = $grid_layout;
                }
    
            }
            //$html .= '</div>';
        }
        return $html;
    }
    
    public function renderFlexiblock($name, $chrome = 'none', $cols = 4, $grid_layout = 12) {
        
        $dscreen  = $this->dscreen;
        $defpos = array();
        for($i = 1; $i <= $cols; $i++) $defpos[] = $name.'-'.$i;
        $poss   = $defpos;
        $vars   = array();
    
        $splparams = array();
        for ($i = 1; $i <= $this->maxgrid; $i++) {
            $param = $this->getLayoutConfig('column' . $i . '#' . $name);
            if (empty($param)) {
                break;
            } else {
                $splparams[] = $param;
            }
        }
    
        //we have configuration in setting file
        if (!empty($splparams)) {
            $poss = array();
            foreach ($splparams as $idx => $splparam) {
                $param = (object)$splparam;
                $poss[] = isset($param->position) ? $param->position : $defpos[$idx];
            }
    
            $cols = count($poss);
        }
    
        // check if there's any modules
        if (!$this->countModules(implode(' or ', $poss))) {
            return;
        }
    
        //empty - so we will use default configuration
        if (empty($splparams)) {
            //generate a optimize default width
            $default = $this->genWidth($dscreen, $cols);
    
            foreach ($poss as $i => $pos) {
                //is there any configuration param
                $var = isset($vars[$pos]) ? $vars[$pos] : '';
    
                $param = new stdClass;
                $param->position = $pos;
    
                $param->$dscreen = ($var && isset($var[$dscreen])) ? $var[$dscreen] : 'span' . $default[$i];
                if ($var) {
                    foreach($this->screens as $screen){
                        if (isset($var[$screen])) {
                            $param->$screen = $var[$screen];
                        }
                    }
                        
                }
    
                $splparams[$i] = $param;
            }
        }
    
        //build data
        //$responsive = $this->getParam('responsive', 1);
        $datas    = array();
        foreach ($splparams as $splparam) {
            $param = (object)$splparam;
    
            $data = '';
                
            //if($responsive){
                    
                foreach($this->screens as $screen){
                        
                    if(isset($param->$screen)){
                        
                        if(strpos(' ' . $param->$screen . ' ', ' hidden ') !== false){
                            $param->$screen = str_replace(' hidden ', ' hidden-' . $screen . ' ', ' ' . $param->$screen . ' ');
                        }
    
                        $data .= ' data-' . $screen . '="' . $param->$screen . '"';
                    }
                }
            //} 
    
            $datas[] = $data;
        }
        
        $html = '<div class="row-fluid jm-flexiblock jm-'.$name.'">';
        foreach($splparams as $i => $splparam) {
            $param = (object)$splparam;
            
            $html.= '<div class="'.$param->default.'" '.$datas[$i].'>';
            if($this->countModules($param->position)) {
                $html.= $this->renderModules($param->position, $chrome, $grid_layout);
            } else {
                $html.= '<!-- empty module position -->';
            }
            $html.= '</div>';
        }
        $html.= '</div>';
        
        return $html;
    }
    
    public function countFlexiblock($name, $cols = 4)
    {
        $poss = array();
    
        for ($i = 1; $i <= $this->maxgrid; $i++) {
            $param = $this->getLayoutConfig('column' . $i . '#' . $name);
            if (empty($param)) {
                break;
            } else {
                $param = (object)$param;
                $poss[] = isset($param->position) ? $param->position : '';
            }
        }
    
        if (empty($poss)) {
            for($i = 1; $i <= $cols; $i++) $poss[] = $name.'-'.$i;
        }
    
        return $this->countModules(implode(' or ', $poss));
    }
    
    function fitWidth($numpos)
    {
        $result = array();
        $avg = floor($this->maxgrid / $numpos);
        $sum = 0;
    
        for ($i = 0; $i < $numpos - 1; $i++) {
            $result[] = $avg;
            $sum += $avg;
        }
    
        $result[] = $this->maxgrid - $sum;
    
        return $result;
    }
    
    function genWidth($layout, $numpos)
    {
        $cminspan = $this->minspan[$layout];
        $total = $cminspan * $numpos;
    
        if ($total < $this->maxgrid) {
            return $this->fitWidth($numpos);
        } else {
            $result = array();
            $rows = ceil($total / $this->maxgrid);
            $cols = ceil($numpos / $rows);
    
            for ($i = 0; $i < $rows - 1; $i++) {
                $result = array_merge($result, $this->fitWidth($cols));
                $numpos -= $cols;
            }
    
            $result = array_merge($result, $this->fitWidth($numpos));
        }
    
        return $result;
    }
    
    public function getClass($name, $cls = array())
    {
        $data = '';
        $param = $this->getLayoutConfig($name, '');
        
        if (empty($param)) {
            if (is_string($cls)) {
                $data = ' ' . $cls;
            } else if (is_array($cls)) {
                $param = (object)$cls;
            }
        }
    
        if (!empty($param)) {
    
            foreach ($this->maxcol as $screen => $span) {
                //convert hidden class
                if(!empty($param->$screen) && strpos(' ' . $param->$screen . ' ', ' hidden ') !== false){
                    $param->$screen = str_replace(' hidden ', ' hidden-' . $screen . ' ', ' ' . $param->$screen . ' ');
                }
    
                if(!empty($param->$screen)){
                    $data .= ' data-' . $screen . '="' . trim($param->$screen) . '"';
                }
            }
                
            $dscreen = $this->dscreen;
            if(!empty($data)){
                $data = (isset($param->$dscreen) ? ' ' . $param->$dscreen : '') . ' jm-responsive"' . substr($data, 0, strrpos($data, '"'));
            }
        }
        
        return $data;
    }
    
    public function getBlocks($default) {
        
        if(is_null($this->blocks)) { 
        
            $this->blocks = array();
            $dblocks = is_array($default) ? $default : explode(',', $default);
            
            $path = JPath::clean(JMF_TPL_PATH . '/tpl/blocks');
            $files = JFolder::files($path, '.php');
            
            foreach($files as $file) {
                
                $block_name = trim(JFile::stripExt($file));      
                $param = $this->getLayoutConfig('block#'.$block_name);
                
                if(!empty($param)) $this->blocks[$param->ordering] = $block_name;
                
            }
            
            if(!empty($this->blocks)){
                // sort blocks by ordering key
                ksort($this->blocks);
            } else {
                $this->blocks = $dblocks;
            }
            
        }
        return $this->blocks;
    }
    
    public function getLayoutConfig($name, $default = null)
    {
        return isset($this->layoutconfig) ? $this->layoutconfig->get($name, $default) : $default;
    }
    
    protected function lessToCss($lessPath, $useVars = true) {
        if (class_exists('lessc') == false) {
            require_once JMF_FRAMEWORK_PATH.DIRECTORY_SEPARATOR. 'includes' .DIRECTORY_SEPARATOR. 'libraries' .DIRECTORY_SEPARATOR. 'less' .DIRECTORY_SEPARATOR. 'lessc.inc.0.4.0.php'; 
        }
        
        $developer_mode = ($this->params->get('devmode', false) == '1') ? true : false;
        
        $filename = JFile::stripExt(JFile::getName($lessPath));
        $style_id = @JFactory::getApplication()->getTemplate(true)->id;
        $css_suffix = '';
        if ($style_id > 0) {
            $css_suffix = '.'.$style_id;
        }

        $cssPath = JMF_TPL_PATH . DIRECTORY_SEPARATOR. 'css' .DIRECTORY_SEPARATOR. $filename.$css_suffix. '.css';
        
        if (!JFile::exists($lessPath)) {
            $lessPath = JMF_TPL_PATH . DIRECTORY_SEPARATOR. 'less' .DIRECTORY_SEPARATOR. $filename. '.less';
        }
        
        if (JFile::exists($lessPath) && JFile::exists($cssPath)) {
            $lessTime = filemtime($lessPath);
            $cssTime = filemtime($cssPath);
            if ($lessTime <= $cssTime && $developer_mode == false) {
                return JMF_TPL_URL. '/css/' . $filename.$css_suffix.'.css';
            }
        }
        
        if (JFile::exists($cssPath)) {
            JFile::delete($cssPath);
        }
        try {
            $less = new lessc();
            
            if ($useVars) {
                $variables = $this->params->get('jm_bootstrap_variables', array());
                if (!empty($variables)) {
                    $less->setVariables($variables);
                }
            }
            
            $less->checkedCompile($lessPath, $cssPath);
            //lessc::ccompile($lessPath, $cssPath);
        }
        catch (exception $e) {
            throw new Exception(JText::sprintf('PLG_SYSTEM_JMFRAMEWORK_LESS_ERROR', $e->getMessage()));
        }
        
        return JMF_TPL_URL. '/css/' . $filename.$css_suffix.'.css';
    }
    
    protected function getLessUrl($lessPath) {
        $filename = JFile::stripExt(JFile::getName($lessPath));
        if (!JFile::exists($lessPath)) {
            $lessPath = JMF_TPL_PATH . DIRECTORY_SEPARATOR. 'less' .DIRECTORY_SEPARATOR. $filename. '.less';
        }
        if (JFile::exists($lessPath)) {
            $lessPath = str_replace(JPATH_ROOT, '', realpath($lessPath));
            $lessPath = str_replace(DIRECTORY_SEPARATOR, '/', $lessPath);
            if(substr($lessPath, 0, 1) == '/') {
                $lessPath = substr($lessPath, 1);
            }
            return JURI::root(false) . $lessPath;
        }
        return false;
    }

    protected function attachThemeCustomiser($useVars = true){
        if (self::$less_js_included == false) {
            
            //JHTML::_('behavior.framework', true);
            JHtml::_('jquery.ui', array('core', 'sortable'));
            
            if(!defined('JMF_TPL_ASSETS')){
                define('JMF_TPL_ASSETS', JURI::root(false).'plugins/system/jmframework/includes/assets/admin/');
            }
            
            $app = JFactory::getApplication();
            $jconf  = JFactory::getConfig();
            $cookie_path = ($jconf->get('cookie_path') == '') ? JUri::base(true) : $jconf->get('cookie_path');
            $cookie_domain = ($jconf->get('cookie_domain') == '') ? $_SERVER['HTTP_HOST'] : $jconf->get('cookie_domain');

            $global_vars = array();
            
            // taking LESS initial variables generated by the template (based on parameters)
            if ($useVars) {
                $params = $this->document->params->toArray();

                $variables = $this->params->get('jm_bootstrap_variables', array());
                
                $variables = array_merge($params, $variables);

                if (!empty($variables)){
                    foreach($variables as $k=>$v) {
                        $global_vars['@'.$k] = $v;
                    }
                }
            }
            
            // Making sure that variables don't start with @. 
            // Less.js doesn't want @ before variable name, whicle LessC PHP compiler requires it.
            $form_vars = array();
            foreach ($global_vars as $k=>$v) {
                $form_vars[str_replace('@','',$k)] = $v;
            }
            
            // Including and merging variables stored in a Cookie by Theme Customiser, which override default params.
            $ts_cookie = JFactory::getApplication()->input->cookie->get('JMTH_TIMESTAMP_'.$this->template, 0);
            if ((int)$ts_cookie != -1) {
                //$form_cookie_vars = JFactory::getApplication()->input->cookie->get('JM_form_vars_'.$this->template, false, 'raw');
                $form_cookie_vars = JFactory::getApplication()->getUserState($this->template.'.themer.state', false);
                if ($form_cookie_vars) {
                    $cashed_vars = json_decode($form_cookie_vars, true);
                    foreach ($cashed_vars as $k=>$v) {
                        if (preg_replace('#[^0-9a-z]#i', '', $v) != '') {
                            $form_vars[$k] = $v;
                        }
                    }
                }    
            }
            JFactory::getApplication()->input->cookie->set('JMTH_TIMESTAMP_'.$this->template, time(), 0, $cookie_path);
            
            // Saving all set of variables into Cookie. Just to be sure they won't get lost somewhere.
            //JFactory::getApplication()->input->cookie->set('JM_form_vars_'.$this->template, json_encode($form_vars), 0, $cookie_path);
            JFactory::getApplication()->setUserState($this->template.'.themer.state', json_encode($form_vars));
            
            // All LESS vars that go directly in LESS object start with 'JM'. We don't need to pass any other variables.
            $less_vars = array();
            foreach($form_vars as $k=>$v) {
                if (substr($k, 0, 2) == 'JM') {
                    $less_vars[$k] = $v;
                }
            }
            
            $script_init = '
                    less = {
                    env: "development",
                    mode: "browser",
                    async: false,
                    fileAsync: false,
                    poll: 1000,
                    functions: {},
                    dumpLineNumbers: "mediaquery",
                    relativeUrls: false,
                    rootpath: "'.JMF_TPL_URL.'/less/",
                    globalVars: '.json_encode($less_vars).'
            };';
            
            
            // Must use addCustomTag() instead of addScript(), because LESS's init script has to go before LESS library.
            $this->document->addCustomTag('<script type="text/javascript">'.$script_init.'</script>');
            $this->document->addCustomTag('<script type="text/javascript" src="'. JMF_FRAMEWORK_URL.'/includes/assets/template/themecustomiser/less-1.7.0.js' .'"></script>');
            $this->document->addCustomTag('<script type="text/javascript" src="'. JMF_FRAMEWORK_URL.'/includes/assets/template/themecustomiser/jmthemecustomiser.jquery.js' .'"></script>');
            
            $language = array(
                    'LANG_PLEASE_WAIT' => JText::_('PLG_SYSTEM_JMFRAMEWORK_THEMER_WAIT'),
                    'LANG_PLEASE_WAIT_APPLYING' => JText::_('PLG_SYSTEM_JMFRAMEWORK_THEMER_WAIT_APPLYING'),
                    'LANG_PLEASE_WAIT_SAVING' => JText::_('PLG_SYSTEM_JMFRAMEWORK_THEMER_WAIT_SAVING'),
                    'LANG_PLEASE_WAIT_RELOADING' => JText::_('PLG_SYSTEM_JMFRAMEWORK_THEMER_WAIT_RELOADING'),
                    'LANG_ERROR_FORBIDDEN' => JText::_('PLG_SYSTEM_JMFRAMEWORK_THEME_LOGIN_ERROR'),
                    'LANG_ERROR_UNAUTHORISED' => JText::_('PLG_SYSTEM_JMFRAMEWORK_THEME_ACCESS_ERROR'),
                    'LANG_ERROR_BAD_REQUEST' => JText::_('PLG_SYSTEM_JMFRAMEWORK_THEME_BAD_REQUEST_ERROR')
            );
            
            // extending JMThemeCustomiser with some variables and initialising Theme Customiser
            $script_interface = "
                        jQuery.extend(JMThemeCustomiser, {
                            url: '" . JFactory::getURI()->toString() . "',
                            lang: ".json_encode($language).",
                            lessVars: ".json_encode($form_vars).",
                            cookie: {path: '".$cookie_path."', domain: '".$cookie_domain."'},
                            styleId : ".(int)$app->getTemplate('template')->id.",
                            login_form : ".(int)$this->document->params->get('themerlogin', 0)."
                        });

                        JMThemeCustomiser.init(\"".$this->template."\");
                                    
                        jQuery(document).ready(function(){
                            JMThemeCustomiser.render();
                            jQuery(document).trigger('JMFrameworkInit');
                        });
                    ";
            $this->document->addCustomTag('<script type="text/javascript">'.$script_interface.'</script>');
            
            // Adding all scripts manually
            $this->document->addStyleSheet(JMF_FRAMEWORK_URL.'/includes/assets/template/themecustomiser/jmthemecustomiser.css');
            $this->document->addStyleSheet(JURI::root(false).'plugins/system/jmframework/includes/assets/admin/formfields/jmiriscolor/iris.min.css');
            $this->document->addScript(JURI::root(false).'plugins/system/jmframework/includes/assets/admin/js/jquery/jquery.ui.draggable.js');
            $this->document->addScript(JURI::root(false).'plugins/system/jmframework/includes/assets/admin/js/jquery/jquery.ui.slider.js');
            $this->document->addScript(JURI::root(false).'plugins/system/jmframework/includes/assets/admin/js/jquery/jquery.ui.accordion.js');
            $this->document->addScript(JURI::root(false).'plugins/system/jmframework/includes/assets/admin/formfields/jmiriscolor/iris.js');
            $this->document->addScript(JURI::root(false).'plugins/system/jmframework/includes/assets/admin/js/jmoptiongroups.js');
            $this->document->addScript(JURI::root(false).'plugins/system/jmframework/includes/assets/admin/js/jmgooglefont.js');
            
            $this->document->addScriptDeclaration("
                        jQuery(document).on('JMFrameworkInit', function(){
                            jQuery('.jmirispicker').each(function() {
                                jQuery(this).iris({
                                    hide: true,
                                    palettes: true
                                });
                            });
                            jQuery(document).on('click',function(event){
                                jQuery('.jmirispicker').each(function() {
                                    if (event.target != this && typeof jQuery(this).iris != 'undefined') {
                                        jQuery(this).iris('hide');
                                    }
                                });
                            });
                            
                            var JMThemerGoogleFonts = new JMGoogleFontHelper('.google-font-url').initialise();
                            
                        });
                ");

            self::$less_js_included = true;
        }
    }
    
    public function displayComponent(){
    
        $app = JFactory::getApplication();
    
        $display = !in_array($app->input->get('Itemid'), (array)$this->params->get('DisableComponentDisplay', array()));
    
        if(!$display) { // check if current view is the same as menu item
                
            $menu = $app->getMenu();
                
            // Get active menu
            $active = $menu->getActive();
            // if no active menu then try to get menu item by current Itemid
            if(!$active) $active = $menu->getItem($app->input->get('Itemid'));
                
            // no active menu item
            if(!$active) $display = true;
            // compare current option and view with real menu item query
            else if($active->query['option'] != $app->input->get('option') || $active->query['view'] != $app->input->get('view','')) $display = true;
        }
    
        return $display;
    }
    
    public function getColumnClasses($s, $c, $l, $r) {
        
        $class = array();
        
        switch($s) {
            case 'lcr':
                $class['content'] = "span$c offset$l";
                $class['left'] = "span$l offset-".($c+$l);
                $class['right'] = "span$r";
                break;
            case 'clr':
            case 'c':
                $class['content'] = "span$c";
                $class['left'] = "span$l";
                $class['right'] = "span$r";
                break;
            case 'crl':
                $class['content'] = "span$c";
                $class['left'] = "span$l offset$r";
                $class['right'] = "span$r offset-".($l+$r);
                break;
            case 'rcl':
                $class['content'] = "span$c offset$r";
                $class['left'] = "span$l";
                $class['right'] = "span$r offset-".($c+$l+$r);
                break;
            case 'lrc':
                $class['content'] = "span$c offset".($l+$r);
                $class['left'] = "span$l offset-".($c+$l+$r);
                $class['right'] = "span$r offset-".($c+$r);
                break;
            case 'rlc':
                $class['content'] = "span$c offset".($l+$r);
                $class['left'] = "span$l offset-".($c+$l);
                $class['right'] = "span$r offset-".($c+$l+$r);
                break;
            case 'cl':
            case 'cr':
                $class['content'] = "span$c";
                $class['left'] = "span$l";
                $class['right'] = "span$l";
                break;
            case 'lc':
            case 'rc':
                $class['content'] = "span$c offset$l";
                $class['left'] = "span$l offset-".($c+$l);
                $class['right'] = "span$l offset-".($c+$l);
                break;
            default:
                $class['content'] = "span".($s == 'tablet' || $s == 'mobile' ? floor(100 * $c / 12) : $c);
                $class['left'] = "span".($s == 'tablet' || $s == 'mobile' ? floor(100 * $l / 12) : $l);
                $first = false;
                if($c+$l > 12) {
                    $class['left'] .= ' first-span';
                    $first = true;
                }
                $class['right'] = "span".($s == 'tablet' || $s == 'mobile' ? floor(100 * $r / 12) : $r);
                if($first && $l+$r > 12) {
                    $class['right'] .= ' first-span';
                } else if(!$first && $c+$l+$r > 12) {
                    $class['right'].= ' first-span';
                }
                break;
        }
        
        return $class;
    }
    
    public function ajax(){
    
        $app = JFactory::getApplication();
    
        $jmajax = $app->input->getCmd('jmajax');
        $task = $app->input->getCmd('jmtask');
    
        if($jmajax == 'themer') { // Layout builder tasks
                
            // clear the buffer from any output
            if (!count(array_diff(ob_list_handlers(),array('default output handler')))) {
                ob_clean();
            } 
                
            switch($task) {
                case 'display':
                    echo $this->renderThemer();
                    break;
                case 'save':
                    echo $this->saveThemerConfig(false);
                    break;
                case 'save_file':
                    echo $this->saveThemerConfig(true);
                    break;
                case 'get_state':
                    echo $this->getThemerState();
                    break;
                case 'set_state':
                    echo $this->setThemerState();
                    break;
    
                default: echo self::renderAlert(JText::_('PLG_SYSTEM_JMFRAMEWORK_UNKNOWN_TASK'));
            }
    
            // close application
            $app->close();
        }
    
    }
    
    public function saveThemerConfig($save_to_file = true){
        $app = JFactory::getApplication();
        $input = $app->input;
        $db = JFactory::getDbo();
        $user   = JFactory::getUser();
        $result = new JObject;
        $actions = JAccess::getActionsFromFile(JPATH_ADMINISTRATOR . '/components/com_templates/access.xml', "/access/section[@name='component']/");
        foreach ($actions as $action) {
            $result->set($action->name, $user->authorise($action->name, 'com_templates'));
        }
        
        $hasAccess = false;
        $isLoggedIn = $user->guest ? false : true;
        
        if ($result->get('core.edit')) {
            $hasAccess = true;
        }
        
        if (!$isLoggedIn) {
            $msg = JText::_('PLG_SYSTEM_JMFRAMEWORK_THEME_LOGIN_ERROR');
            throw new Exception($msg, 403);
            return false;
        } else if (!$hasAccess) {
            $msg = JText::_('PLG_SYSTEM_JMFRAMEWORK_THEME_ACCESS_ERROR');
            throw new Exception($msg, 401);
            return false;
        }
        
        $style_id = $input->getInt('jmstyleid', 0);
        if (!$style_id) {
            $msg = JText::_('PLG_SYSTEM_JMFRAMEWORK_THEME_BAD_REQUEST_ERROR');
            throw new Exception($msg, 400);
            return false;
        }
        
        $data = $input->get('jmvars', array(), 'array');
        
        if (empty($data)) {
            return false;
        }
        
        $db->setQuery('SELECT params FROM #__template_styles WHERE id='.(int)$style_id.' LIMIT 1');
        $params = $db->loadResult();
        $params = (!empty($params)) ? json_decode($params, true) : false;
        
        if (empty($params)) {
            return false;
        }
        
        foreach($data as $k=>$v) {
            if (is_scalar($v) /*&& array_key_exists($k, $params)*/) {
                $params[$k] = $v;
            }
        }
        
        $this->purgeStyleSheets($style_id);
        
        if ($save_to_file) {
            $path = JMF_TPL_PATH . DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'config';
            
            if (JFolder::exists($path) == false) {
                JFolder::create($path);
            }
            
            $base_name = 'custom_style';
            if (!empty($params['templateStyle'])) {
                $base_name .= '_'.$params['templateStyle'];
            } else {
                $base_name .= '_0';
            }
            
            $iterator = 0;
            $file_name = $base_name.'.cfg.json';
            
            while(JFile::exists($path.DIRECTORY_SEPARATOR.$file_name)) {
                $iterator++;
                $suffix = '_'.$iterator;
                $file_name = $base_name.$suffix.'.cfg.json';
            }
            
            $params = json_encode($params);
            
            if(JFile::write($path.DIRECTORY_SEPARATOR.$file_name, $params)) {
                return JText::sprintf('PLG_SYSTEM_JMFRAMEWORK_THEME_SETTINGS_SAVED_TO_FILE', $file_name);
            } else {
                return JText::_('PLG_SYSTEM_JMFRAMEWORK_THEME_SETTINGS_SAVING_ERROR');
            }
        } else {
            $params = json_encode($params);
            $db->setQuery('UPDATE #__template_styles SET params='.$db->quote($params).' WHERE id='.(int)$style_id);
            if ($db->query() == false) {
                return $db->getErrorMsg();
            }
            
            if (defined('JMF_TPL')) {
                // dump CSS sheets which were made from LESS files
                $suffix = ($style_id > 0) ? '.'.$style_id : '';
                
                foreach ($less_files as $less) {
                    $name = JFile::stripExt($less);
                    /*if (in_array($name.'.css', $css_files)) {
                     JFile::delete(JPath::clean(JPATH_ROOT.'/templates/'.JMF_TPL.'/css/').$name.'.css');
                    }*/
                    if (in_array($name.$suffix.'.css', $css_files)) {
                        JFile::delete(JPath::clean(JPATH_ROOT.'/templates/'.JMF_TPL.'/css/').$name.$suffix.'.css');
                    }
                }
                
            }
            
            return JText::_('PLG_SYSTEM_JMFRAMEWORK_THEME_SETTINGS_SAVED_TO_DB');
        }
    }
    
    public function renderThemer() {
        
        $form = new JForm('jmframework.themecustomiser');
        
        jimport('joomla.filesystem.path');
        $plg_file = JPath::find(JMF_FRAMEWORK_PATH . DS. 'includes' . DS .'assets' . DS . 'admin' . DS . 'params', 'template.xml');
        $tpl_file = JPath::find(JPATH_ROOT . DS. 'templates' . DS . JMF_TPL, 'templateDetails.xml');
        
        if (!$tpl_file && !$plg_file) {
            return false;
        }
        
        if ($plg_file){
            $form->loadFile($plg_file, false, '//form');
        }
        
        if ($tpl_file){
            $form->loadFile($tpl_file, false, '//config');
        }
        
        $fieldSets = $form->getFieldsets('themecustomiser');
        if (empty($fieldSets)){
            return false;
        }
        
        $path = JPath::clean(JMF_FRAMEWORK_PATH.'/includes/assets/admin/layouts/themecustomiser.php');
        
        ob_start();
        if (JFile::exists($path)) {
            include($path);
        } else {
            throw new Exception('Missing file: '.$layoutbuilder_path, 500);
        }
        $html = ob_get_contents();
        ob_end_clean();
        
        return $html;
    }

    public function getThemerState() {
        $app = JFactory::getApplication();
        return $app->getUserState(JMF_TPL.'.themer.state', '');
    }
    
    public function setThemerState() {
        $app = JFactory::getApplication();
        $data = $app->input->get('jmvars', null, 'raw');
        /*if (!$data) {
            return '';
        }*/
        $app->setUserState(JMF_TPL.'.themer.state', $data);
        
        return $data;
    }
    
    public static function purgeStyleSheets($style_id = 0) {
        $css_files = JFolder::files(JPath::clean(JPATH_ROOT.'/templates/'.JMF_TPL.'/css'), '.css');
        $less_files = JFolder::files(JPath::clean(JPATH_ROOT.'/templates/'.JMF_TPL.'/less'), '.less');
        
        if (is_array($less_files)) {
            $suffix = ($style_id > 0) ? '.'.$style_id : '';
            foreach ($less_files as $less) {
                $name = JFile::stripExt($less);
                /*if (in_array($name.'.css', $css_files)) {
                 JFile::delete(JPath::clean(JPATH_ROOT.'/templates/'.JMF_TPL.'/css/').$name.'.css');
                }*/
                if (in_array($name.$suffix.'.css', $css_files)) {
                    JFile::delete(JPath::clean(JPATH_ROOT.'/templates/'.JMF_TPL.'/css/').$name.$suffix.'.css');
                }
            }
        }
        
        return true;
    }
    
    public static function renderAlert($msg) {
        return $msg;
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
