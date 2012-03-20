<?php

/**
 * Strings for filter_freemind
 *
 * @package    filter
 * @subpackage freemind
 * @copyright  2011 Johannes A. Albert <contact@johannesalbert.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['filtername']       = 'FreeMind';

$string['general']          = 'General';

$string['browser']          = 'Browser';
$string['browser_flash']    = 'Flash-based browser';
$string['browser_java']     = 'Java-based browser';

$string['defaults']         = 'Default parameters';

$string['url_swfobject']    = 'URL to swfobject';
$string['url_plugindetect'] = 'URL to PluginDetect';

$string['flash_based']      = 'Flash-based';
$string['java_based']       = 'Java-based';
$string['auto_detect']      = 'Auto-detect (experimental)';

$string['noflashplugin']    = 'Please install "<a href="http://www.adobe.com/go/getflashplayer">Adobe Flash Player</a>" in order to view "<a href="%s">%s</a>" inside your Browser. <a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a>';
$string['nojavaplugin']     = 'Please install "<a href="http://www.java.com/download/java/">Java Runtime Environment</a>" in order to view "<a href="%s">%s</a>" inside your Browser.';

$string['error_flash']      = '<strong>visorFreemind.swf</strong> (<a href="http://freemind.sourceforge.net/wiki/index.php/Flash_browser" target="_blank">Info &amp; Download</a>) must be contained in <br /><em>%s</em>';
$string['error_java']       = '<strong>appletobject.js</strong> (comes with this filter package)</li> and <strong>freemindbrowser.jar</strong> (<a href="http://freemind.sourceforge.net/wiki/index.php/Asked_Questions#Mind_maps_on_web_pages_with_FreeMind.27s_applet" target="_blank">Info</a> | <a href="http://sourceforge.net/projects/freemind/files/freemind-browser/" target="_blank">Download</a>) must be contained in <br /><em>%s</em>';

$string['url_swfobject_desc']       = 'Base URL to <strong>swfobject.js</strong> + <strong>expressInstall.swf</strong> (<a href="%s" target="_blank">Info &amp; Download</a>).<br />Leave empty to use default: <em>%s</em>';
$string['url_plugindetect_desc']    = 'Base URL to <strong>PluginDetectJava.js</strong> + <strong>getJavaInfo3.jar</strong> (<a href="%s" target="_blank">Info &amp; Download</a>).<br />Leave empty to use default: <em>%s</em>';


