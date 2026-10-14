# Aida Chatbox

Official WordPress plugin for [Aida](https://aidasales.ir) — the AI sales assistant. Embed and configure the website chatbox from the WordPress admin (no manual script-tag edits).

| | |
| --- | --- |
| **Author** | [Aida Sales](https://aidasales.ir) |
| **License** | [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html) |
| **Requires** | WordPress 5.8+, PHP 7.4+ |
| **Stable** | 1.0.1 |

> `readme.txt` in this repo is the [WordPress.org plugin readme](https://developer.wordpress.org/plugins/wordpress-org/how-your-readme-txt-works/) format. This `README.md` is for GitHub.

## Features

- API key from your Aida account
- Position (left / right) and default open or closed state
- Distance from bottom and side (desktop & mobile)
- Auto-open delay and “open once”
- Thinking / processing UI
- Brand colors
- FAQ suggestion questions (up to 3)
- Collect visitor name and phone before chat
- English and Persian (فارسی) admin, RTL-ready

## Installation

1. Upload the `aida-chatbox` folder to `/wp-content/plugins/`, or install the ZIP via **Plugins → Add New**.
2. Activate **Aida Chatbox**.
3. Open **Aida Chatbox** in the admin menu.
4. Paste your API key from [app.aidasales.ir](https://app.aidasales.ir) → Channel Settings and save.
5. Remove any old hard-coded Aida `<script>` tag from your theme or page builder.

## About Aida

Aida is a SaaS AI sales assistant (website, Instagram, Telegram, CRM). This plugin is a connector to that service — similar to analytics or live-chat plugins.

- Chatbox script URL is a **fixed** official endpoint (`cdn.aidasales.ir`)
- The plugin does **not** download or execute arbitrary remote code
- Chat replies and leads are handled by Aida’s servers using your API key

**Links:** [Product](https://aidasales.ir) · [Live demos](https://aidasales.ir/i) · [App](https://app.aidasales.ir)

## FAQ

**Do I need an Aida account?**  
Yes. Create one at [aidasales.ir](https://aidasales.ir), get your website chatbox API key, then paste it into this plugin.

**Do I still paste the script tag from the Aida app?**  
No. Enter the API key and options here. The plugin loads the chatbox for you.

**Will settings update with WP Rocket or another cache plugin?**  
Yes. Settings are loaded from WordPress on each page view (not frozen in cached HTML). Saving also purges common page caches.

**Is the chatbox free?**  
The WordPress plugin is free (GPLv2). Aida’s AI service is a separate subscription — see [aidasales.ir](https://aidasales.ir).

## Changelog

### 1.0.1

- Fix: show seconds and “Open once” when auto-open is enabled; same for the visitor-info form text.

### 1.0.0

- Initial release: admin settings, live config loader, cache-friendly updates, English / Persian UI, Aida branding.

## Privacy

When the chatbox is enabled and an API key is set, the visitor browser loads scripts from Aida’s CDN and may send chat messages and optional contact fields to Aida’s servers. See Aida’s terms and privacy policy on [aidasales.ir](https://aidasales.ir).
