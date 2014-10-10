<?php

/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

defined('_JEXEC') or die;

class JMTemplate extends JMFTemplate {
	public function postSetUp() {
		// get columns classes
		$s = $this->getLayoutConfig('#scheme','lcr');
		$l = $this->params->get('columnLeftWidth', '3');
		$r = $this->params->get('columnRightWidth', '3');
				
		if ((!$this->checkModules('left-column')) && (!$this->checkModules('right-column'))) {
            $c = 12;
			$s = str_replace(array('l','r'), '', $s);
        } else if (($this->checkModules('left-column')) && (!$this->checkModules('right-column'))) {
            $c = 12 - $l;
			$s = str_replace(array('r'), '', $s);
        } else if ((!$this->checkModules('left-column')) && ($this->checkModules('right-column'))) {
            $c = 12 - $r;
			$l = $r; // potrzebne, żeby później nie dublować wyliczeń
			$s = str_replace(array('l'), '', $s);
        } else {
            $c = 12 - $l - $r;
        }
		
		// get classes for columns		
		$class = $this->getColumnClasses($s, $c, $l, $r);
		
		$this->params->set('class', $class);	
		
		$bootstrap_vars = array();
		
		/* Template Layout */
		
		$templatefluidwidth = $this->params->get('JMfluidGridContainerLg', '1170px');
		$bootstrap_vars['JMfluidGridContainerLg'] = $templatefluidwidth;
		
		$gutterwidth = $this->params->get('JMbaseSpace', '30px');
		$bootstrap_vars['JMbaseSpace'] = $gutterwidth;
		
		
        /* Font Modifications */
        
        //body
        
        $bodyfontsize = (int)$this->params->get('JMbaseFontSize', '13');
		$bootstrap_vars['JMbaseFontSize'] = $bodyfontsize.'px';
		
        $bodyfonttype = $this->params->get('bodyFontType', '0');
        $bodyfontfamily = $this->params->get('bodyFontFamily', 'Arial, Helvetica, sans-serif'); 
        $bodycustomfont = $this->params->get('bodyCustomFont', 'Tahoma');  
        $bodygooglewebfontfamily = $this->params->get('bodyGoogleWebFontFamily');
        $generatedwebfontfamily = $this->params->get('bodyGeneratedWebFont');

        switch($bodyfonttype) {
            case "0" : {
                $bootstrap_vars['JMbaseFontFamily'] = $bodyfontfamily;
                break;    
            }
            case "1" :{
                $bootstrap_vars['JMbaseFontFamily'] = $bodycustomfont;
                break;
            }
        	case "2" :{
                $bootstrap_vars['JMbaseFontFamily'] = $bodygooglewebfontfamily;
                break;
            }
            case "3" :{
            	$bootstrap_vars['JMbaseFontFamily'] = $generatedwebfontfamily;
            	break;
            }
            default: {
                $bootstrap_vars['JMbaseFontFamily'] = 'Tahoma';
                break;
            }
       	}
	   
	   	//module title
	   	
	 	$headingsfontsize = (int)$this->params->get('JMmoduleTitleFontSize', '18');
		$bootstrap_vars['JMmoduleTitleFontSize'] = $headingsfontsize.'px';
		
		$headingsfonttype = $this->params->get('headingsFontType', '0');
		$headingsfontfamily = $this->params->get('headingsFontFamily', 'Arial, Helvetica, sans-serif'); 
		$headingscustomfont = $this->params->get('headingsCustomFont', 'Tahoma');   
		$headingsgooglewebfontfamily = $this->params->get('headingsGoogleWebFontFamily');
		$headingsgeneratedwebfontfamily = $this->params->get('headingsGeneratedWebFont');
		
        switch($headingsfonttype) {
            case "0" : {
                $bootstrap_vars['JMmoduleTitleFontFamily'] = $headingsfontfamily;
                break;    
            }
            case "1" :{
                $bootstrap_vars['JMmoduleTitleFontFamily'] = $headingscustomfont;
                break;
            }
            case "2" :{
                $bootstrap_vars['JMmoduleTitleFontFamily'] = $headingsgooglewebfontfamily;
                break;
            }
            case "3" :{
            	$bootstrap_vars['JMmoduleTitleFontFamily'] = $headingsgeneratedwebfontfamily;
            	break;
            }
            default: {
                $bootstrap_vars['JMmoduleTitleFontFamily'] = 'Tahoma';
                break;
            }
       	}
		
		//top menu horizontal
		
		$djmenufontsize = (int)$this->params->get('JMtopmenuFontSize', '16');
		$bootstrap_vars['JMtopmenuFontSize'] = $djmenufontsize.'px';
		
		$djmenufonttype = $this->params->get('djmenuFontType', '0');
		$djmenufontfamily = $this->params->get('djmenuFontFamily', 'Arial, Helvetica, sans-serif');
		$djmenucustomfont = $this->params->get('djmenuCustomFont', 'Tahoma');  
		$djmenugooglewebfontfamily = $this->params->get('djmenuGoogleWebFontFamily');
		$djmenugeneratedwebfontfamily = $this->params->get('djmenuGeneratedWebFont');
		
        switch($djmenufonttype) {
            case "0" : {
                $bootstrap_vars['JMtopmenuFontFamily'] = $djmenufontfamily;
                break;    
            }
            case "1" :{
                $bootstrap_vars['JMtopmenuFontFamily'] = $djmenucustomfont;
                break;
            }
            case "2" :{
                $bootstrap_vars['JMtopmenuFontFamily'] = $djmenugooglewebfontfamily;
                break;
            }
            case "3" :{
            	$bootstrap_vars['JMtopmenuFontFamily'] = $djmenugeneratedwebfontfamily;
            	break;
            }
            default: {
                $bootstrap_vars['JMtopmenuFontFamily'] = 'Tahoma';
                break;
            }
       	}

		//article title
		
		$articlesfontsize = (int)$this->params->get('JMarticleTitleFontSize', '18');
		$bootstrap_vars['JMarticleTitleFontSize'] = $articlesfontsize.'px';
		
		$articlesfonttype = $this->params->get('articlesFontType', '0');
		$articlesfontfamily = $this->params->get('articlesFontFamily', 'Arial, Helvetica, sans-serif');
		$articlescustomfont = $this->params->get('articlesCustomFont', 'Tahoma'); 
		$articlesgooglewebfontfamily = $this->params->get('articlesGoogleWebFontFamily');
		$articlesgeneratedfontfamily = $this->params->get('articlesGeneratedWebFont');
		
        switch($articlesfonttype) {
            case "0" : {
                $bootstrap_vars['JMarticleTitleFontFamily'] = $articlesfontfamily;
                break;    
            }
            case "1" :{
                $bootstrap_vars['JMarticleTitleFontFamily'] = $articlescustomfont;
                break;
            }
            case "2" :{
                $bootstrap_vars['JMarticleTitleFontFamily'] = $articlesgooglewebfontfamily;
                break;
            }
            case "3" :{
            	$bootstrap_vars['JMarticleTitleFontFamily'] = $articlesgeneratedfontfamily;
            	break;
            }
            default: {
                $bootstrap_vars['JMarticleTitleFontFamily'] = 'Tahoma';
                break;
            }
       	}
		
	    /* Color Modifications */
	    
	    //scheme color
        $colorversion = $this->params->get('JMcolorVersion', '#017eba'); 
		$bootstrap_vars['JMcolorVersion'] = $colorversion;
	    
	    //global
        $pagebackground = $this->params->get('JMpageBackground', '#f4f4f4'); 
		$bootstrap_vars['JMpageBackground'] = $pagebackground;
		
        $bodyfontcolor = $this->params->get('JMbaseFontColor', '#898989'); 
		$bootstrap_vars['JMbaseFontColor'] = $bodyfontcolor;
		
        $componentbackground = $this->params->get('JMcomponentBackground', '#ffffff'); 
		$bootstrap_vars['JMcomponentBackground'] = $componentbackground;

        $componentborder = $this->params->get('JMcomponentBorder', '#e7e7e7'); 
		$bootstrap_vars['JMcomponentBorder'] = $componentborder;

        $articlefontcolor = $this->params->get('JMarticleFontColor', '#2f2f2f'); 
		$bootstrap_vars['JMarticleFontColor'] = $articlefontcolor;
		
		//topbar
        $topbarbackground = $this->params->get('JMtopbarBackground', '#ffffff'); 
		$bootstrap_vars['JMtopbarBackground'] = $topbarbackground;
		
        $topbarborder = $this->params->get('JMtopbarBorder', '#e7e7e7'); 
		$bootstrap_vars['JMtopbarBorder'] = $topbarborder;
		
        $topbarfontcolor = $this->params->get('JMtopbarFontColor', '#898989'); 
		$bootstrap_vars['JMtopbarFontColor'] = $topbarfontcolor;
		
		//menubar
        $topmenubackground = $this->params->get('JMtopmenuBackground', '#ffffff'); 
		$bootstrap_vars['JMtopmenuBackground'] = $topmenubackground;
		
        $topmenuborder = $this->params->get('JMtopmenuBorder', '#e7e7e7'); 
		$bootstrap_vars['JMtopmenuBorder'] = $topmenuborder;
		
        $topmenufontcolor = $this->params->get('JMtopmenuFontColor', '#017eba'); 
		$bootstrap_vars['JMtopmenuFontColor'] = $topmenufontcolor;
		
		//modules
        $modulebackground = $this->params->get('JMmoduleBackground', '#ffffff'); 
		$bootstrap_vars['JMmoduleBackground'] = $modulebackground;

        $moduleborder = $this->params->get('JMmoduleBorder', '#e7e7e7'); 
		$bootstrap_vars['JMmoduleBorder'] = $moduleborder;
		
        $modulefontcolor = $this->params->get('JMmoduleFontColor', '#898989'); 
		$bootstrap_vars['JMmoduleFontColor'] = $modulefontcolor;
		
        $colorbox1background = $this->params->get('JMcolorBox1Background', '#017eba'); 
		$bootstrap_vars['JMcolorBox1Background'] = $colorbox1background;
		
        $colorbox2background = $this->params->get('JMcolorBox2Background', '#a0b046'); 
		$bootstrap_vars['JMcolorBox2Background'] = $colorbox2background;
		
        $colorbox3background = $this->params->get('JMcolorBox3Background', '#f78145'); 
		$bootstrap_vars['JMcolorBox3Background'] = $colorbox3background;
		
		//footer
        $footerbackground = $this->params->get('JMfooterBackground', '#e7e7e7'); 
		$bootstrap_vars['JMfooterBackground'] = $footerbackground;
		
        $footerborder = $this->params->get('JMfooterBorder', '#dedede'); 
		$bootstrap_vars['JMfooterBorder'] = $footerborder;
		
        $footerfontcolor = $this->params->get('JMfooterFontColor', '#898989'); 
		$bootstrap_vars['JMfooterFontColor'] = $footerfontcolor;
		
       	$this->params->set('jm_bootstrap_variables', $bootstrap_vars);
	
    }
}