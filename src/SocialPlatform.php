<?php declare(strict_types=1);

namespace Stevenpaw\SocialLink;

use SilverStripe\Control\Director;
use SilverStripe\Core\Manifest\ModuleLoader;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\View\Parsers\HTMLValue;

/**
 * Represents a social media platform.
 * Records are seeded automatically via requireDefaultRecords() on dev/build.
 *
 * @property string $Key
 * @property string $Title
 */
class SocialPlatform extends DataObject
{
    private static string $table_name = 'Stevenpaw_SocialPlatform';

    private static array $db = [
        'Key'   => 'Varchar(50)',
        'Title' => 'Varchar(100)',
    ];

    private static array $default_sort = ['Title' => 'ASC'];

    public function requireDefaultRecords(): void
    {
        parent::requireDefaultRecords();

        foreach (SocialLink::PLATFORMS as $key => $label) {
            if (static::get()->filter('Key', $key)->exists()) {
                continue;
            }
            $platform = static::create();
            $platform->Key = $key;
            $platform->Title = $label;
            $platform->write();
            DB::alteration_message("Created social platform: {$label}", 'created');
        }
    }

    /**
     * Returns the public URL to this platform's SVG icon (after dev/build).
     * Usage in templates: $Platform.IconUrl
     */
    public function getIconUrl(): string
    {
        $key = $this->Key ?? '';
        $module = ModuleLoader::inst()->getModule('stevenpaw/silverstripe-sociallink');
        if (!$module) {
            return '';
        }

        $resource = $module->getResource("client/dist/icons/{$key}.svg");
        return $resource->exists() ? Director::absoluteURL($resource->getURL()) : '';
    }

    /**
     * Returns the raw inline SVG markup for this platform's icon.
     * Usage in templates: $Platform.IconSVG
     * Usage in PHP: echo $platform->getIconSVG();
     */
    public function getIconSVG(): DBHTMLText
    {
        $key = $this->Key ?? '';
        $base = Director::baseFolder() . '/vendor/stevenpaw/silverstripe-sociallink/client/dist/icons/';

        $file = file_exists($base . $key . '.svg')
            ? $base . $key . '.svg'
            : $base . '_fallback.svg';

        $svg = file_exists($file) ? file_get_contents($file) : '';

        return DBHTMLText::create()->setValue($svg);
    }
}
