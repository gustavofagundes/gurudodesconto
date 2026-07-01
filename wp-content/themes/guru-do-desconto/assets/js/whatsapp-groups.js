/**
 * Seleção múltipla de grupos WhatsApp — abre uma aba por grupo.
 */
(function () {
  'use strict';

  var picker = document.querySelector('[data-whatsapp-picker]');
  if (!picker) {
    return;
  }

  var cfg = window.guruWhatsappPicker || {};
  var strings = cfg.strings || {};

  var checkboxes = picker.querySelectorAll('[data-wa-group]');
  var bulkBar = picker.querySelector('[data-wa-bulk-bar]');
  var bulkBtn = picker.querySelector('[data-wa-bulk-join]');
  var countEl = picker.querySelector('[data-wa-count]');
  var selectAllBtn = picker.querySelector('[data-wa-select-all]');
  var clearAllBtn = picker.querySelector('[data-wa-clear-all]');
  var fallback = picker.querySelector('[data-wa-fallback]');
  var fallbackList = picker.querySelector('[data-wa-fallback-links]');

  function selectedBoxes() {
    return Array.prototype.filter.call(checkboxes, function (cb) {
      return cb.checked;
    });
  }

  function updateUI() {
    var selected = selectedBoxes();
    var count = selected.length;

    checkboxes.forEach(function (cb) {
      var card = cb.closest('[data-wa-card]');
      if (card) {
        card.classList.toggle('whatsapp-group-card--selected', cb.checked);
      }
    });

    if (countEl) {
      countEl.textContent =
        count === 0
          ? strings.noneSelected || 'Nenhum grupo selecionado'
          : count === 1
            ? strings.oneSelected || '1 grupo selecionado'
            : (strings.manySelected || '%d grupos selecionados').replace('%d', String(count));
    }

    if (bulkBar) {
      bulkBar.hidden = count === 0;
    }

    if (bulkBtn) {
      bulkBtn.disabled = count === 0;
      bulkBtn.textContent =
        count === 1
          ? strings.joinOne || 'Entrar em 1 grupo no WhatsApp'
          : (strings.joinMany || 'Entrar em %d grupos no WhatsApp').replace('%d', String(count));
    }

    if (selectAllBtn) {
      selectAllBtn.disabled = count === checkboxes.length;
    }

    if (clearAllBtn) {
      clearAllBtn.disabled = count === 0;
    }
  }

  function trackGroup(cb) {
    if (typeof window.guruTrackWhatsappClick !== 'function') {
      return;
    }
    var el = document.createElement('a');
    el.setAttribute('data-guru-utm-content', cb.getAttribute('data-utm-content') || 'whatsapp_bulk');
    el.setAttribute('data-guru-group', cb.getAttribute('data-slug') || '');
    window.guruTrackWhatsappClick(el);
  }

  function showFallback(items) {
    if (!fallback || !fallbackList || !items.length) {
      return;
    }

    fallbackList.innerHTML = '';
    items.forEach(function (item) {
      var li = document.createElement('li');
      var link = document.createElement('a');
      link.href = item.url;
      link.target = '_blank';
      link.rel = 'noopener';
      link.className = 'btn btn-whatsapp btn-sm';
      link.textContent = item.name;
      link.setAttribute('data-guru-track', 'whatsapp');
      link.setAttribute('data-guru-utm-content', item.utm);
      link.setAttribute('data-guru-group', item.slug);
      li.appendChild(link);
      fallbackList.appendChild(li);
    });

    fallback.hidden = false;
    fallback.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }

  function openSelectedGroups() {
    var selected = selectedBoxes();
    if (!selected.length) {
      return;
    }

    if (fallback) {
      fallback.hidden = true;
    }
    if (fallbackList) {
      fallbackList.innerHTML = '';
    }

    var blocked = [];

    selected.forEach(function (cb) {
      var url = cb.getAttribute('data-url');
      var name = cb.getAttribute('data-name') || '';
      var slug = cb.getAttribute('data-slug') || '';
      var utm = cb.getAttribute('data-utm-content') || 'whatsapp_bulk';

      if (!url) {
        return;
      }

      trackGroup(cb);

      var win = window.open(url, '_blank', 'noopener,noreferrer');
      if (!win) {
        blocked.push({ url: url, name: name, slug: slug, utm: utm });
      }
    });

    if (blocked.length) {
      showFallback(blocked);
    }
  }

  checkboxes.forEach(function (cb) {
    cb.addEventListener('change', updateUI);
    cb.addEventListener('click', function (event) {
      event.stopPropagation();
    });
  });

  if (selectAllBtn) {
    selectAllBtn.addEventListener('click', function () {
      checkboxes.forEach(function (cb) {
        cb.checked = true;
      });
      updateUI();
    });
  }

  if (clearAllBtn) {
    clearAllBtn.addEventListener('click', function () {
      checkboxes.forEach(function (cb) {
        cb.checked = false;
      });
      updateUI();
    });
  }

  if (bulkBtn) {
    bulkBtn.addEventListener('click', openSelectedGroups);
  }

  updateUI();
})();
