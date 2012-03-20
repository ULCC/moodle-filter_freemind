<?php

/**
 * FreeMind filter for Moodle 2.x
 *
 * @package     filter
 * @subpackage  freemind
 * @copyright   2011 Johannes Albert <contact@johannesalbert.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @version     2.1.0
 */

defined('MOODLE_INTERNAL') || die();

class filter_freemind extends moodle_text_filter {

    /**
     * @var array global configuration for this filter
     *
     * This might be eventually moved into parent class if we found it
     * useful for other filters, too.
     */
    protected static $globalconfig;

    /**
     * Apply the filter to the text
     *
     * @see filter_manager::apply_filter_chain()
     * @param string $text to be processed by the text
     * @param array $options filter options
     * @return string text after processing
     */
    public function filter($text, array $options = array()) {
        
        if (strpos($text, '.mm') === false) return $text;
        $this->convert_links_to_mindmaps($text);
        return $text;
    }

    ////////////////////////////////////////////////////////////////////////////
    // internal implementation starts here
    ////////////////////////////////////////////////////////////////////////////

    /**
     * Returns the global filter setting
     *
     * If the $name is provided, returns single value. Otherwise returns all
     * global settings in object. Returns null if the named setting is not
     * found.
     *
     * @param mixed $name optional config variable name, defaults to null for all
     * @return string|object|null
     */
    protected function get_global_config($name=null) {
        $this->load_global_config();
        if (is_null($name)) {
            return self::$globalconfig;

        } elseif (array_key_exists($name, self::$globalconfig)) {
            return self::$globalconfig->{$name};

        } else {
            return null;
        }
    }

    /**
     * Makes sure that the global config is loaded in $this->globalconfig
     *
     * @return void
     */
    protected function load_global_config() {
        if (is_null(self::$globalconfig)) {
            self::$globalconfig = get_config('filter_freemind');
        }
    }

    /**
     * Given some text this function converts any URLs it finds into HTML links
     *
     * @param string $text Passed in by reference. The string to be searched for urls.
     */
    protected function convert_links_to_mindmaps(&$text) {

        global $CFG;
        static $freemindInstanceNr;

        // Defaults
        $browser        = $this->get_global_config('browser');
        if (empty($browser)) $browser = 'flash';
        
        $defaults       = $this->get_global_config('defaults');

        $url_filter     = $CFG->httpswwwroot . '/filter/freemind';

        $url_swfobject      = $this->get_global_config('url_swfobject');
        if (empty($url_swfobject)) $url_swfobject = "$url_filter/swfobject";

        $url_plugindetect   = $this->get_global_config('url_plugindetect');
        if (empty($url_plugindetect)) $url_plugindetect = "$url_filter/PluginDetect";
        
        $noflashplugin      = get_string('noflashplugin', 'filter_freemind');
        $nojavaplugin       = get_string('nojavaplugin', 'filter_freemind');

        preg_match_all('/<a .*href="(.+)\.mm( .*)?".*>(.*)<\/a>/isU', $text, $matches);

        if (!isset($freemindInstanceNr)) $freemindInstanceNr = 1;

        for ($i=0; $i<count($matches[0]); $i++, $freemindInstanceNr++) {

            $basename   = $matches[1][$i];
            $basedir    = dirname($basename);
            $argstring  = $matches[2][$i];
            $discarded  = $matches[3][$i];

            $params = $this->getParams($basename, $basedir, $argstring, $defaults);

            $containerId = 'freemind-' . $freemindInstanceNr;
            $mindmap = "\n<br/>
            <div id=\"$containerId\">" . ($browser == 'java'
                ? sprintf($nojavaplugin, "$basename.mm", basename($basename) . '.mm')
                : sprintf($noflashplugin, "$basename.mm", basename($basename) . '.mm')) ."
            </div>
            <script type=\"text/javascript\">
            // <![CDATA[";
            if ($browser == 'flash') {
                $mindmap .= "
                swfobject.embedSWF('$url_filter/browser/visorFreemind.swf', '$containerId', '{$params['width']}', '{$params['height']}', '6.065', 'expressInstall.swf', {$params['flashvars']}, {$params['flashparams']});";
            } elseif ($browser == 'java') {
                $mindmap .= "
                if (PluginDetect.isMinVersion('Java', '1.4', '$url_plugindetect/getJavaInfo2.jar') >= 0) appletobject.embedApplet('$url_filter/browser/freemindbrowser.jar?$freemindInstanceNr', '$containerId', '{$params['width']}', '{$params['height']}', {$params['javaparams']}, {classid:'java:freemind.main.FreeMindApplet.class'});";
            } else {
                $mindmap .= "
                if (swfobject.hasFlashPlayerVersion('6.065')) swfobject.embedSWF('$url_filter/browser/visorFreemind.swf', '$containerId', '{$params['width']}', '{$params['height']}', '6.065', '$url_swfobject/expressInstall.swf', {$params['flashvars']}, {$params['flashparams']});
                else if (PluginDetect.isMinVersion('Java', '1.4', '$url_plugindetect/getJavaInfo2.jar') >= 0) appletobject.embedApplet('$url_filter/browser/freemindbrowser.jar?$freemindInstanceNr', '$containerId', '{$params['width']}', '{$params['height']}', {$params['javaparams']}, {classid:'java:freemind.main.FreeMindApplet.class'});";
            }
            $mindmap .= "
            // ]]>
            </script>\n";

            if ($freemindInstanceNr == 1) {
                if ($browser == 'flash' || $browser == 'auto') $text = '<script type="text/javascript" src="' . $url_swfobject . '/swfobject.js"></script>' . $text;
                if ($browser == 'java' || $browser == 'auto') {
                    $text = '<script type="text/javascript" src="' . $url_plugindetect . '/PluginDetectJava.js"></script>' . $text;
                    $text = '<script type="text/javascript" src="' . $url_filter . '/appletobject/appletobject.js"></script>' . $text;
                }
            }
            $text = str_replace($matches[0][$i], $mindmap, $text);

        }
    }

    protected function getParams($basename, $basedir, $argstring, $defaults)
    {
            $arguments = array();
            $return = array('flashparams' => array(), 'flashvars' => array(), 'javaparams' => array());

            // load defaults
            preg_match_all('/\s*(\S+?)=(\S*+)\s*/is', $defaults, $vars);
            for ($j=0; $j<count($vars[0]); $j++) {
                $arguments[$vars[1][$j]] = $vars[2][$j];
            }
            // load custom arguments
            preg_match_all('/\s*(\S+?)=(\S*+)\s*/is', $argstring, $vars);
            for ($j=0; $j<count($vars[0]); $j++) {
                $arguments[$vars[1][$j]] = $vars[2][$j];
            }

            $flashparams    = array();
            $flashvars      = array();
            $javaparams     = array();

            $width  = '100%';
            $height = '500';

            foreach ($arguments as $k => $v) {
                switch ($k) {
                    case 'width':
                        $width = $v;
                        break;
                    case 'height':
                        $height = $v;
                        break;
                    case 'play':
                    case 'loop':
                    case 'menu':
                    case 'quality':
                    case 'scale':
                    case 'align':
                    case 'salign':
                    case 'wmode':
                    case 'base':
                    case 'devicefont':
                    case 'seamlesstabbing':
                    case 'allowfullscreen':
                    case 'allownetworking':
                    case 'allowscriptaccess':
                    case 'swliveconnect':
                    case 'flashvars':
                        $flashparams[$k] = $v;
                        break;
                    case 'initLoadFile':
                    case 'openUrl':
                    case 'startCollapsedToLevel':
                    case 'mainNodeShape':
                    case 'noEllipseMode':
                    case 'defaultWordWrap':
                    case 'ShotsWidth':
                    case 'genAllShots':
                    case 'unfoldAll':
                    case 'justMap':
                    case 'defaultToolTipWordWrap':
                    case 'offsetX':
                    case 'offsetY':
                    case 'buttonPos':
                    case 'max_alpha_buttons':
                    case 'min_alpha_buttons':
                    case 'scaleTooltips':
                    case 'toolTipsBgColor':
                    case 'baseImagePath':
                    case 'CSSFile':
                        $flashvars[$k] = $v;
                        break;
                    case 'bgcolor':
                    default:
                        $flashparams[$k] = $v;
                        $javaparams[$k] = $v;
                        break;
                }
            }

            $flashvars['initLoadFile']  = $basename . '.mm';
            $flashvars['baseImagePath'] = $basedir . '/';

            $javaparams['browsemode_initial_map']   = $basename . '.mm';
            $javaparams['scriptable']               = 'false';
            $javaparams['modes']                    = 'freemind.modes.browsemode.BrowseMode';
            $javaparams['initial_mode']             = 'Browse';
            $javaparams['selection_method']         = 'selection_method_direct';

            if (isset($flashvars['justmap']) && !isset($javaparams['justmap'])) {
                if ($flashvars['justmap'] == 'true') $javaparams['justmap'] = 'false';
                elseif ($flashvars['justmap'] == 'false') $javaparams['justmap'] = 'true';
            }

            foreach ($flashparams as $k => $v) $return['flashparams'][] = "$k:\"$v\"";
            foreach ($flashvars as $k => $v) $return['flashvars'][] = "$k:\"$v\"";
            foreach ($javaparams as $k => $v) $return['javaparams'][] = "$k:\"$v\"";
            foreach ($return as $k => $v) $return[$k] = '{' . implode(',', $v) . '}';
            $return['width']    = $width;
            $return['height']   = $height;
            return $return;
    }
}
