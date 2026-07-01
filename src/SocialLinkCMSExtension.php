<?php declare(strict_types=1);

namespace Stevenpaw\SocialLink;

use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Core\Extension;
use SilverStripe\Core\Manifest\ModuleLoader;
use SilverStripe\View\Requirements;

/**
 * Injects the social-link icon CSS and JS into every CMS page.
 * The CSS defines per-platform SVG icon classes (.font-icon-social-link-*).
 * The JS uses a MutationObserver to apply the correct class to each link item.
 */
class SocialLinkCMSExtension extends Extension
{
    public function init(): void
    {
        $module = ModuleLoader::inst()->getModule('stevenpaw/silverstripe-sociallink');
        if (!$module) {
            return;
        }

        $css = $module->getResource('client/dist/sociallink-cms.css');
        $js  = $module->getResource('client/dist/sociallink-cms.js');

        if ($css->exists()) {
            Requirements::css($css->getURL());
        }

        if ($js->exists()) {
            Requirements::javascript($js->getURL());
        }
    }
}
