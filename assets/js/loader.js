/**
 * Aida Chatbox front-end loader.
 *
 * Fetches live settings from an uncached WordPress REST endpoint, then injects
 * the CDN chatbox script with the correct data-* attributes. This avoids
 * stale config frozen inside page-cache HTML (WP Rocket, etc.).
 */
(function () {
  'use strict';

  if (window.__AIDA_CHATBOX_WP_LOADER__) {
    return;
  }
  window.__AIDA_CHATBOX_WP_LOADER__ = true;

  var boot = window.aidaChatboxBoot || {};
  var configUrl = boot.configUrl;

  if (!configUrl) {
    return;
  }

  function appendVersion(url, version) {
    if (!version) {
      return url;
    }
    var sep = url.indexOf('?') === -1 ? '?' : '&';
    return url + sep + 'aida_v=' + encodeURIComponent(String(version));
  }

  function injectScript(config) {
    if (!config || !config.enabled || !config.scriptUrl) {
      return;
    }

    var attrs = config.attributes || {};
    if (!attrs['data-aida-api-key']) {
      return;
    }

    var existing = document.querySelector('script[data-aida-wp-loader="1"]');
    if (existing) {
      return;
    }

    var script = document.createElement('script');
    script.src = appendVersion(config.scriptUrl, config.version);
    script.async = true;
    script.setAttribute('data-aida-wp-loader', '1');
    script.setAttribute('data-no-optimize', '1');
    script.setAttribute('data-cfasync', 'false');

    Object.keys(attrs).forEach(function (name) {
      if (attrs[name] !== undefined && attrs[name] !== null && attrs[name] !== '') {
        script.setAttribute(name, String(attrs[name]));
      }
    });

    (document.body || document.documentElement).appendChild(script);
  }

  function loadConfig() {
    var url = appendVersion(configUrl, boot.version);
    var separator = url.indexOf('?') === -1 ? '?' : '&';
    url += separator + '_=' + Date.now();

    fetch(url, {
      method: 'GET',
      credentials: 'same-origin',
      cache: 'no-store',
      headers: {
        Accept: 'application/json',
      },
    })
      .then(function (response) {
        if (!response.ok) {
          throw new Error('Aida Chatbox config request failed');
        }
        return response.json();
      })
      .then(injectScript)
      .catch(function () {
        /* Fail silently on the public site. */
      });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadConfig);
  } else {
    loadConfig();
  }
})();
