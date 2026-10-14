=== Aida Chatbox ===
Contributors: mohsenadc
Tags: chatbot, ai chat, live chat, customer support, sales
Requires at least: 5.8
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Official WordPress plugin for Aida — the AI sales assistant. Embed and configure the website chatbox from the admin.

== Description ==

**This is the official Aida Chatbox plugin**, published by [Aida Sales](https://aidasales.ir) (the makers of Aida).

**Aida** ([aidasales.ir](https://aidasales.ir)) is an AI sales assistant that talks to your customers wherever they are — website, Instagram, Telegram, and CRM — 24/7. Aida answers questions, understands needs, persuades toward purchase, and records leads in your CRM so your sales team can focus on high-value conversations.

Try live demos by industry (health, real estate, online store, education) at [aidasales.ir/i](https://aidasales.ir/i).

= What this plugin does =

This plugin embeds the **Aida website chatbox** on your WordPress site and lets you manage all widget options from **Aida Chatbox** in the admin:

* API key from your Aida account
* Position (left / right) and default open or closed state
* Distance from bottom and side (desktop & mobile) so the button clears navbars and scrollbars
* Auto-open delay and “open once”
* Thinking / processing UI
* Brand colors
* FAQ suggestion questions (up to 3)
* Collect visitor name and phone before chat

You no longer need to copy a long script tag from the Aida app after every change.

= About Aida (the service) =

Aida is a **Software as a Service** (Guideline 6). This plugin is a connector to that service — similar to analytics or live-chat SaaS plugins.

* The chatbox script URL is a **fixed official endpoint** (`cdn.aidasales.ir`). Site owners cannot enter an arbitrary remote script URL.
* The plugin does **not** download or execute arbitrary code, PHP, or user-supplied scripts.
* Chat replies and leads are handled by Aida’s servers using your API key.
* A free trial may exist on the Aida website; **this plugin does not** lock, gate, or time-limit any of its own code (Guidelines 5–6).

Learn more:

* Product & pricing: [https://aidasales.ir](https://aidasales.ir)
* Live demos: [https://aidasales.ir/i](https://aidasales.ir/i)
* App / channel settings: [https://app.aidasales.ir](https://app.aidasales.ir)

= Bilingual admin =

English and Persian (فارسی). The UI follows your WordPress language and is RTL-ready for Persian.

= Privacy =

When the chatbox is enabled and an API key is set, the visitor browser loads scripts from Aida’s CDN and may send chat messages and optional contact fields to Aida’s servers so the assistant can reply. By configuring your API key you connect the site to the Aida service. See Aida’s own terms and privacy policy on [aidasales.ir](https://aidasales.ir).

== Installation ==

1. Upload the `aida-chatbox` folder to `/wp-content/plugins/`, or install the ZIP via **Plugins → Add New**.
2. Activate **Aida Chatbox**.
3. Open **Aida Chatbox** in the admin menu.
4. Paste your API key from [app.aidasales.ir](https://app.aidasales.ir) → Channel Settings and save.
5. Remove any old hard-coded Aida `<script>` tag from your theme or page builder.

== Frequently Asked Questions ==

= Do I need an Aida account? =

Yes. Create an account at [aidasales.ir](https://aidasales.ir), get your website chatbox API key from the app, then paste it into this plugin.

= Do I still paste the script tag from the Aida app? =

No. Enter the API key and options here. The plugin loads the chatbox for you.

= Will settings update if I use WP Rocket or another cache plugin? =

Yes. Settings are loaded from WordPress on each page view (not frozen inside cached HTML). Saving also purges common page caches.

= Is the chatbox free? =

The WordPress plugin is free (GPLv2). Aida’s AI service is a separate subscription; see plans and trial options on [aidasales.ir](https://aidasales.ir).

= Where can I see Aida in action? =

Visit [aidasales.ir/i](https://aidasales.ir/i) for live demos.

== Changelog ==

= 1.0.1 =
* Fix: show seconds and “Open once” when auto-open is enabled; same for the visitor-info form text.

= 1.0.0 =
* Initial release: admin settings, live config loader, cache-friendly updates, English / Persian UI, Aida branding.

== Upgrade Notice ==

= 1.0.1 =
Fixes missing auto-open options (seconds / open once) in the settings screen.

= 1.0.0 =
Initial public release.
