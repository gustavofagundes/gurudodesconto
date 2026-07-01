/**
 * GA4 + Meta Pixel — eventos de conversão.
 */
(function () {
  'use strict';

  var cfg = window.guruTracking || {};
  var pixelPage = cfg.pixelPage || {};

  // Persiste UTMs da landing (ex.: Google Ads, Meta Ads) para a sessão.
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

  function fbqTrack(eventName, params) {
    if (!cfg.pixelEnabled || typeof window.fbq !== 'function') {
      return;
    }
    window.fbq('track', eventName, params || {});
  }

  function fbqCustom(eventName, params) {
    if (!cfg.pixelEnabled || typeof window.fbq !== 'function') {
      return;
    }
    window.fbq('trackCustom', eventName, params || {});
  }

  function metaBaseParams(content) {
    return {
      content_name: pixelPage.contentName || document.title || '',
      content_category: pixelPage.contentCategory || cfg.keyword || cfg.campaign || 'site',
      utm_source: storedUtm('utm_source') || cfg.utmSource || '',
      utm_medium: storedUtm('utm_medium') || cfg.utmMedium || '',
      utm_campaign: storedUtm('utm_campaign') || cfg.utmCampaign || cfg.campaign || '',
      utm_content: content || '',
    };
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
      value: pixelPage.value || 1,
    };

    gtagEvent('affiliate_click', payload);
    if (cfg.gaId) {
      gtagEvent('conversion', Object.assign({ send_to: cfg.gaId }, payload));
    }

    var metaParams = Object.assign(metaBaseParams(content), {
      content_type: 'product',
      currency: pixelPage.currency || 'BRL',
      value: pixelPage.value || 1,
    });

    if (pixelPage.contentIds) {
      metaParams.content_ids = pixelPage.contentIds;
    }

    fbqTrack('Lead', metaParams);
    fbqCustom('AffiliateClick', metaParams);
  }

  function trackWhatsappClick(el) {
    var content = el.getAttribute('data-guru-utm-content') || 'whatsapp';
    var groupSlug = el.getAttribute('data-guru-group') || '';
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
    if (cfg.gaId) {
      gtagEvent('conversion', Object.assign({ send_to: cfg.gaId }, payload));
    }

    var metaParams = Object.assign(metaBaseParams(content), {
      content_name: groupSlug ? 'grupo_' + groupSlug : 'whatsapp_' + content.replace(/^whatsapp_/, ''),
      content_category: groupSlug || 'whatsapp',
    });

    fbqTrack('Contact', metaParams);
    fbqCustom('WhatsAppClick', metaParams);
  }

  window.guruTrackWhatsappClick = trackWhatsappClick;

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
