(function () {
  'use strict';

  function syncColor(picker, input) {
    if (!picker || !input) {
      return;
    }

    picker.addEventListener('input', function () {
      input.value = picker.value.replace(/^#/, '');
    });

    input.addEventListener('input', function () {
      var value = input.value.replace(/^#/, '');
      if (/^[0-9A-Fa-f]{6}$/.test(value)) {
        picker.value = '#' + value;
      } else if (/^[0-9A-Fa-f]{3}$/.test(value)) {
        picker.value =
          '#' +
          value
            .split('')
            .map(function (c) {
              return c + c;
            })
            .join('');
      }
    });
  }

  function setDependentVisible(target, visible) {
    if (!target) {
      return;
    }

    target.classList.toggle('is-hidden', !visible);
    if (visible) {
      target.removeAttribute('hidden');
    } else {
      target.setAttribute('hidden', 'hidden');
    }
  }

  function bindDependentToggle(checkbox) {
    if (!checkbox) {
      return;
    }

    var targetId = checkbox.getAttribute('data-aida-toggle');
    if (!targetId) {
      return;
    }

    var target = document.getElementById(targetId);
    if (!target) {
      return;
    }

    var sync = function () {
      setDependentVisible(target, checkbox.checked);
    };

    checkbox.addEventListener('change', sync);
    checkbox.addEventListener('click', sync);
    sync();
  }

  document.querySelectorAll('input[type="color"][data-sync]').forEach(function (picker) {
    var input = document.getElementById(picker.getAttribute('data-sync'));
    syncColor(picker, input);
  });

  document.querySelectorAll('input[data-aida-toggle]').forEach(bindDependentToggle);
})();
