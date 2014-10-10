<?php

/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.joomla-monster.com/license.html Joomla-Monster Proprietary Use License
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

//advanced selectors font parameters
$advancedfontsize = $this->params->get('advancedFontSize', '18');
$advancedfonttype = $this->params->get('advancedFontType', '0');
$advancedfontfamily = $this->params->get('advancedFontFamily', 'Arial, Helvetica, sans-serif');
$advancedcustomfont = $this->params->get('advancedCustomFont', 'Tahoma');   
$advancedgooglewebfonturl = htmlspecialchars($this->params->get('advancedGoogleWebFontUrl'));
$advancedgooglewebfontfamily = $this->params->get('advancedGoogleWebFontFamily');
$advancedselectors = $this->params->get('advancedSelectors');

if($advancedselectors != '') {
    echo $advancedselectors; ?> {
    <?php 
    switch($advancedfonttype) {
        case "0":
            echo "font-family: ".$advancedfontfamily.";";
        break;
        case "1": 
            echo "font-family: ".$advancedcustomfont.";";
        break;
        case "2":       
            echo "font-family: ".$advancedgooglewebfontfamily.";";
        break;
        default: 
            echo "font-family: Tahoma;";
    }
    ?>
    font-size: <?php echo $advancedfontsize; ?>;
}
<?php } ?>