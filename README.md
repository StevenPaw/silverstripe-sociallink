# stevenpaw/silverstripe-sociallink

A Silverstripe 6+ module that adds a **Social Media Link** type to [silverstripe/linkfield](https://github.com/silverstripe/silverstripe-linkfield). Editors can pick a social platform from a searchable dropdown and enter the profile URL. Platform icons are self-hosted SVGs (DSGVO-compliant, no external CDN).

## Requirements

- PHP 8.3+
- Silverstripe CMS 6.1+
- silverstripe/linkfield 5+

## Installation

```bash
composer require stevenpaw/silverstripe-sociallink
```

After installing, run a dev/build to create the database table and seed the platform records:

```
/dev/build?flush=1
```

## Usage

### In the CMS

`SocialLink` is automatically available as a link type in any `LinkField`. Editors select a platform from the searchable dropdown and enter the profile URL.

### In templates

Check whether the current link is a SocialLink by testing `$SocialPlatform.exists`. The `SocialPlatform` object provides two helper methods:

**Inline SVG** (recommended — no HTTP request, inherits CSS `color`):

```silverstripe
<a href="$Link" target="_blank">
    <% if $SocialPlatform.exists %>
        <span aria-hidden="true">$SocialPlatform.IconSVG</span>
    <% end_if %>
    $Title
</a>
```

**Public URL** (for use in `<img>` or CSS `background-image`):

```silverstripe
<% if $SocialPlatform.exists %>
    <img src="$SocialPlatform.IconUrl" alt="$SocialPlatform.Title" width="24" height="24">
<% end_if %>
```

**Platform key** (e.g. `instagram`, `youtube`) for custom logic or CSS classes:

```silverstripe
<% if $SocialPlatform.exists %>
    <span class="icon icon--$SocialPlatform.Key">...</span>
<% end_if %>
```

Or in PHP:

```php
$platformKey = $link->getPlatform(); // e.g. 'instagram'
```

### Supported platforms

46 platforms are seeded automatically on `dev/build`:

Apple Music, Atlassian, Bandcamp, Behance, Bluesky, Dailymotion, Deezer, Discord, Dribbble, Facebook, Flickr, GitHub, GitLab, Instagram, KakaoTalk, LINE, LinkedIn, Medium, Microsoft Teams, Mixcloud, Odnoklassniki, Patreon, Pinterest, Reddit, ReverbNation, Signal, Skype, Slack, Snapchat, SoundCloud, Spotify, Steam, Telegram, Tidal, TikTok, Twitch, Vimeo, VK, WeChat, Weibo, WhatsApp, X (Twitter), YouTube, YouTube Music, Zoom

Platforms without a matching FontAwesome 6 Brands icon (Deezer, KakaoTalk, ReverbNation, Signal, Tidal, Zoom) fall back to a generic share icon.

If you need a new public platform, please submit an issue on the repository and I will gladly add it.

## License

BSD 3-Clause
