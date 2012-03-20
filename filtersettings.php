<?php

/**
 * Settings for filter_freemind
 *
 * @package     filter
 * @subpackage  freemind
 * @copyright   2011 Johannes A. Albert <contact@johannesalbert.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    $filterroot = $CFG->dirroot . '/filter/freemind';

    $browser = get_config('filter_freemind', 'browser');
    if ($browser != 'java' && !file_exists($filterroot . '/browser/visorFreemind.swf')) {
        $settings->add(new admin_setting_heading('filter_freemind/error',
            get_string('error'),
            sprintf(get_string('error_flash', 'filter_freemind'), "{$CFG->dirroot}/filter/freemind/browser/")));
    } elseif ($browser != 'flash' && !file_exists($filterroot . '/browser/visorFreemind.swf')) {
        $settings->add(new admin_setting_heading('filter_freemind/error',
            get_string('error'),
            sprintf(get_string('error_java', 'filter_freemind'), "{$CFG->dirroot}/filter/freemind/browser/")));
    }

    $settings->add(new admin_setting_heading('filter_freemind/general',
        get_string('general', 'filter_freemind'),
        ''));

    $settings->add(new admin_setting_configselect('filter_freemind/browser',
        get_string('browser', 'filter_freemind'),
        '',
        'flash',
        array('flash' => get_string('flash_based', 'filter_freemind'), 'java' => get_string('java_based', 'filter_freemind'), 'auto' => get_string('auto_detect', 'filter_freemind'))));
        
    $settings->add(new admin_setting_configtextarea('filter_freemind/defaults',
        get_string('defaults', 'filter_freemind'),
        '',
        "bgcolor=#FFFFFF\nwidth=100%\nheight=500\nquality=high\nCSSFile={$CFG->httpswwwroot}/filter/freemind/browser/flashfreemind.css\njustMap=true"));

    $settings->add(new admin_setting_heading('filter_freemind/flash',
        get_string('browser_flash', 'filter_freemind'),
        ''));

    $settings->add(new admin_setting_configtext('filter_freemind/url_swfobject',
        get_string('url_swfobject', 'filter_freemind'),
        sprintf(get_string('url_swfobject_desc', 'filter_freemind'), 'http://code.google.com/p/swfobject/', "{$CFG->httpswwwroot}/filter/freemind/swfobject"),
        ''));

    $settings->add(new admin_setting_heading('filter_freemind/java',
        get_string('browser_java', 'filter_freemind'),
        ''));

    $settings->add(new admin_setting_configtext('filter_freemind/url_plugindetect',
        get_string('url_plugindetect', 'filter_freemind'),
        sprintf(get_string('url_plugindetect_desc', 'filter_freemind'), 'http://www.pinlady.net/PluginDetect/index.htm', "{$CFG->httpswwwroot}/filter/freemind/PluginDetect"),
        ''));

}
