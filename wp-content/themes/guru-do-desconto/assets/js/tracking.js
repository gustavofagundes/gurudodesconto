/**
 * GA4 + Meta Pixel — eventos de conversão.
 */
(function () {
  'use strict';

  var cfg = window.guruTracking || {};

  // Persiste UTMs da landing (ex.: Google Ads) para a sessão.
  try {
    var params = new URLSearchParams(window.location.search);
    ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content'].forEach(function (key) {
      var val = params.get(key);
      if (val) {
        sessionStorage.setItem('guru_' + key, val);
      }
    });
  } catch (e) {
    /* ignore */
  }

  function storedUtm(key) {
    try {
      return sessionStorage.getItem('guru_' + key) || '';
    } catch (e) {
      return '';
    }
  }

  function gtagEvent(name, params) {
    if (typeof window.gtag !== 'function') {
      return;
    }
    window.gtag('event', name, params);
  }

  function fbqEvent(name, params) {
    if (typeof window.fbq !== 'function') {
      return;
    }
    window.fbq('track', name, params || {});
  }

  function trackAffiliateClick(el) {
    var content = el.getAttribute('data-guru-utm-content') || 'affiliate';
    var label = (el.textContent || '').trim().slice(0, 80);
    var payload = {
      event_category: 'conversion',
      event_label: content,
      link_text: label,
      campaign: cfg.campaign || storedUtm('utm_campaign'),
      keyword: cfg.keyword || '',
      utm_source: storedUtm('utm_source') || cfg.utmSource || '',
      utm_medium: storedUtm('utm_medium') || cfg.utmMedium || '',
      utm_campaign: storedUtm('utm_campaign') || cfg.utmCampaign || cfg.campaign || '',
      utm_content: content,
      value: 1,
    };

    gtagEvent('affiliate_click', payload);
    gtagEvent('conversion', Object.assign({ send_to: cfg.gaId }, payload));
    fbqEvent('Lead', { content_name: 'affiliate_click', content_category: content });
  }

  function trackWhatsappClick(el) {
    var content = el.getAttribute('data-guru-utm-content') || 'whatsapp';
    var payload = {
      event_category: 'conversion',
      event_label: content,
      campaign: cfg.campaign || 'grupo_promocoes',
      utm_source: storedUtm('utm_source') || cfg.utmSource || '',
      utm_medium: storedUtm('utm_medium') || cfg.utmMedium || '',
      utm_campaign: storedUtm('utm_campaign') || cfg.utmCampaign || '',
      utm_content: content,
      value: 1,
    };

    gtagEvent('whatsapp_click', payload);
    gtagEvent('conversion', Object.assign({ send_to: cfg.gaId }, payload));
    fbqEvent('Contact', { content_name: 'whatsapp_click', content_category: content });
  }

  document.addEventListener(
    'click',
    function (event) {
      var el = event.target.closest('a');
      if (!el) {
        return;
      }

      var track = el.getAttribute('data-guru-track');
      if (track === 'affiliate' || el.classList.contains('btn-affiliate')) {
        trackAffiliateClick(el);
        return;
      }

      if (
        track === 'whatsapp' ||
        el.classList.contains('btn-whatsapp') ||
        el.classList.contains('floating-whatsapp')
      ) {
        trackWhatsappClick(el);
      }
    },
    true
  );
})();
