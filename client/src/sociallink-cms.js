/**
 * Replaces the generic social-link icon on each link item with the
 * platform-specific icon by reading the description text rendered by
 * SocialLink::getDescription() ("PlatformTitle: URL").
 *
 * Loads after the CMS page and uses MutationObserver to handle
 * React-rendered link items that appear after initial page load.
 */

// Must match SocialLink::PLATFORMS (key => title) — reversed here for lookup
const PLATFORM_BY_TITLE = {
  'Instagram':        'instagram',
  'Facebook':         'facebook',
  'X (Twitter)':      'x',
  'YouTube':          'youtube',
  'YouTube Music':    'youtube-music',
  'TikTok':           'tiktok',
  'LinkedIn':         'linkedin',
  'Spotify':          'spotify',
  'Apple Music':      'apple-music',
  'SoundCloud':       'soundcloud',
  'Bandcamp':         'bandcamp',
  'Twitch':           'twitch',
  'Discord':          'discord',
  'Pinterest':        'pinterest',
  'Snapchat':         'snapchat',
  'Reddit':           'reddit',
  'Vimeo':            'vimeo',
  'Medium':           'medium',
  'Patreon':          'patreon',
  'GitHub':           'github',
  'GitLab':           'gitlab',
  'Dribbble':         'dribbble',
  'Behance':          'behance',
  'Flickr':           'flickr',
  'VK':               'vk',
  'Odnoklassniki':    'ok',
  'Weibo':            'weibo',
  'Tidal':            'tidal',
  'Deezer':           'deezer',
  'Mixcloud':         'mixcloud',
  'ReverbNation':     'reverbnation',
  'Telegram':         'telegram',
  'WhatsApp':         'whatsapp',
  'LINE':             'line',
  'Signal':           'signal',
  'KakaoTalk':        'kakao',
  'WeChat':           'wechat',
  'Atlassian':        'atlassian',
  'Slack':            'slack',
  'Microsoft Teams':  'teams',
  'Zoom':             'zoom',
  'Skype':            'skype',
  'Dailymotion':      'dailymotion',
  'Bluesky':          'bluesky',
  'Steam':            'steam',
};

function applyPlatformIcon(linkEl) {
  // Only act on Social Media Link items
  const typeEl = linkEl.querySelector('.link-picker__type');
  if (!typeEl || !typeEl.textContent.includes('Social Media Link')) return;

  const urlEl = linkEl.querySelector('.link-picker__url');
  if (!urlEl) return;

  // Description format: "PlatformTitle: https://..."
  const desc = urlEl.textContent.trim();
  const colonIdx = desc.indexOf(':');
  if (colonIdx < 0) return;

  const platformTitle = desc.substring(0, colonIdx).trim();
  const platformKey = PLATFORM_BY_TITLE[platformTitle];
  if (!platformKey) return;

  // The icon span is the first child of the edit button
  const iconEl = linkEl.querySelector('.link-picker__button > span[aria-hidden]');
  if (iconEl) {
    iconEl.className = `font-icon-social-link-${platformKey}`;
  }
}

function scanLinkItems(root) {
  root.querySelectorAll('.link-picker__link').forEach(applyPlatformIcon);
}

// Observe DOM mutations to catch React-rendered link items
const observer = new MutationObserver(function (mutations) {
  const processed = new Set();

  for (const mutation of mutations) {
    // Walk up from changed node to find the containing link item
    let el = mutation.target;
    while (el && el !== document.body) {
      if (el.classList && el.classList.contains('link-picker__link')) {
        if (!processed.has(el)) {
          processed.add(el);
          applyPlatformIcon(el);
        }
        break;
      }
      el = el.parentElement;
    }

    // Also scan any newly added subtrees
    for (const node of mutation.addedNodes) {
      if (!(node instanceof Element)) continue;
      if (node.classList.contains('link-picker__link') && !processed.has(node)) {
        processed.add(node);
        applyPlatformIcon(node);
      }
      node.querySelectorAll('.link-picker__link').forEach(function (n) {
        if (!processed.has(n)) {
          processed.add(n);
          applyPlatformIcon(n);
        }
      });
    }
  }
});

observer.observe(document.body, { childList: true, subtree: true });

// Initial scan for items already in the DOM when the script loads
scanLinkItems(document.body);
