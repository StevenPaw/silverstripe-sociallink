<?php declare(strict_types=1);

namespace Stevenpaw\SocialLink;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\SearchableDropdownField;
use SilverStripe\Forms\UrlField;
use SilverStripe\Forms\Validation\CompositeValidator;
use SilverStripe\Forms\Validation\RequiredFieldsValidator;
use SilverStripe\LinkField\Models\Link;

/**
 * A link to a social media profile or page.
 *
 * @property string $SocialUrl
 * @property int    $SocialPlatformID
 * @method   SocialPlatform SocialPlatform()
 */
class SocialLink extends Link
{
    private static string $table_name = 'SocialLink';

    private static array $db = [
        'SocialUrl' => 'Varchar',
    ];

    private static array $has_one = [
        'SocialPlatform' => SocialPlatform::class,
    ];

    private static int $menu_priority = 15;

    private static string $icon = 'font-icon-share';

    public const PLATFORMS = [
        'instagram'    => 'Instagram',
        'facebook'     => 'Facebook',
        'x'            => 'X (Twitter)',
        'youtube'      => 'YouTube',
        'youtube-music' => 'YouTube Music',
        'tiktok'       => 'TikTok',
        'linkedin'     => 'LinkedIn',
        'spotify'      => 'Spotify',
        'apple-music'  => 'Apple Music',
        'soundcloud'   => 'SoundCloud',
        'bandcamp'     => 'Bandcamp',
        'twitch'       => 'Twitch',
        'discord'      => 'Discord',
        'pinterest'    => 'Pinterest',
        'snapchat'     => 'Snapchat',
        'reddit'       => 'Reddit',
        'vimeo'        => 'Vimeo',
        'medium'       => 'Medium',
        'patreon'      => 'Patreon',
        'github'       => 'GitHub',
        'gitlab'       => 'GitLab',
        'dribbble'     => 'Dribbble',
        'behance'      => 'Behance',
        'flickr'       => 'Flickr',
        'vk'           => 'VK',
        'ok'           => 'Odnoklassniki',
        'weibo'        => 'Weibo',
        'tidal'        => 'Tidal',
        'deezer'       => 'Deezer',
        'mixcloud'     => 'Mixcloud',
        'reverbnation' => 'ReverbNation',
        'telegram'     => 'Telegram',
        'whatsapp'     => 'WhatsApp',
        'line'         => 'LINE',
        'signal'       => 'Signal',
        'kakao'        => 'KakaoTalk',
        'wechat'       => 'WeChat',
        'atlassian'    => 'Atlassian',
        'slack'        => 'Slack',
        'teams'        => 'Microsoft Teams',
        'zoom'         => 'Zoom',
        'skype'        => 'Skype',
        'dailymotion'  => 'Dailymotion',
        'bluesky'      => 'Bluesky',
        'steam'        => 'Steam',
    ];

    /**
     * Returns the platform key (e.g. 'instagram') for use in templates and frontend logic.
     */
    public function getPlatform(): string
    {
        return $this->SocialPlatform()->Key ?? '';
    }

    public function getCMSFields(): FieldList
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $fields->replaceField('SocialUrl', UrlField::create('SocialUrl', 'URL')
                ->setAllowedProtocols(['http', 'https']));

            $fields->replaceField('SocialPlatformID', SearchableDropdownField::create(
                'SocialPlatformID',
                'Plattform',
                SocialPlatform::get()
            )->setEmptyString('— Plattform wählen —'));
        });

        return parent::getCMSFields();
    }

    public function getCMSCompositeValidator(): CompositeValidator
    {
        $validator = parent::getCMSCompositeValidator();
        $validator->addValidator(RequiredFieldsValidator::create(['SocialUrl', 'SocialPlatformID']));
        return $validator;
    }

    public function getDescription(): string
    {
        $platform = $this->SocialPlatform();
        $label = $platform->exists() ? $platform->Title : '';
        return $label ? "{$label}: {$this->SocialUrl}" : ($this->SocialUrl ?? '');
    }

    public function getURL(): string
    {
        $this->beforeExtending('updateURL', function (string &$url) {
            $url = $this->SocialUrl ?: '';
        });
        return parent::getURL();
    }

    public function getMenuTitle(): string
    {
        return _t(__CLASS__ . '.LINKLABEL', 'Social Media Link');
    }
}
