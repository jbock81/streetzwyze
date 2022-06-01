/**
 * Created by intelWorx joseph@intelworx.com
 * Copyright 2015 CashVault.ng
 */
(function (exports) {
  'use script';

  var DEFAULTS = {
    height: 700,
    width: 450
  };

  var SCRIPT_BASE = (function () {
    var scripts = document.getElementsByTagName('script'),
      myUrl = scripts[scripts.length - 1].getAttribute('src'),
      path = myUrl.substr(0, myUrl.lastIndexOf('/') + 1) + '../',
      a = document.createElement('a');
    a.href = path;
    console.log('Auto Detected BASE URL', a.href);
    return a.href;
  }());

  var CashVaultPlugin = function (scriptBaseUrl) {
    var that = this;
    scriptBaseUrl = scriptBaseUrl || SCRIPT_BASE;


    this.displayPopup = function (reservationId, extraData, options) {
      var paymentUrl = that.getUrl(reservationId, extraData);
      options = options || {};
      //detect if mobile,
      if (this.isMobile.any) {
        window.location.href = paymentUrl;
        return; //halt execution
      }

      var popuId = 'CashVaultPlugin-PopupWrapper', parentDiv;
      //build background
      parentDiv = document.getElementById(popuId);

      if (parentDiv) {
        parentDiv.innerHTML = '';
        parentDiv.style.display = 'block';
      } else {
        parentDiv = document.createElement('div');
        parentDiv.id = popuId;
      }

      var params = {
        zIndex: 9999,
        topBarHeight: 0
      };


      var bgUrl = scriptBaseUrl + "static/img/loading-bg.gif";
      var barTemplate = "<button id='CashVaultPlugin-closeBtn' type='button' style='background: none; border: none; font-size: 20px; font-weight: bold; width: 24px; height: 24px; line-height: 20px; background: grey; color: #fff; border-radius: 50%; position: absolute; top: 8px; right: 8px;'>&times;</button>",
        divTemplate = '<div style="position: fixed; z-index: ' + params.zIndex + '; background: #333; top: 0; left: 0; opacity: 0.2; width: 100%; height: 100%;" class="CashVaultPlugin-overlay"></div>';
      divTemplate += '<div id="CashVaultPlugin-container" style="position: absolute; ' +
        'z-index: ' + (params.zIndex + 10) + '; max-width: 95%; background: top center no-repeat url(' + bgUrl + ');">' +
        barTemplate +
        '<div id="CashVaultPlugin-frameWrapper" style="background: transparent; padding: 0; box-sizing: content-box;"> <iframe style="border: none;width: 100%; height:100%;" src="' + paymentUrl + '"  frameborder="0"></iframe></div>' +
        '</div>';

      function divSizing(prefHeight, prefWidth) {
        window.scroll(0, 0);
        var winWidth = 0.95 * window.innerWidth,
          winHeight = 0.99 * window.innerHeight,
          height = prefHeight || DEFAULTS.height, //Math.min(winHeight, prefHeight || DEFAULTS.height),
          width = Math.min(winWidth, prefWidth || DEFAULTS.width),
          left = (width === winWidth) ? 0 : ((winWidth - width) / 2),
          top = (height >= winHeight) ? 0 : ((winHeight - height) / 2);
        var container = document.getElementById('CashVaultPlugin-container');
        container.style.height = height + 'px';
        container.style.width = width + 'px';
        container.style.top = top + 'px';
        container.style.left = left + 'px';

        var frameWrapper = document.getElementById('CashVaultPlugin-frameWrapper');
        frameWrapper.style.height = (height - params.topBarHeight) + 'px';
      }

      parentDiv.innerHTML = divTemplate;
      document.body.appendChild(parentDiv);
      divSizing();


      if (options.onShow && typeof options.onShow === 'function') {
        options.onShow();
      }

      //for previously added
      window.removeEventListener('resize', divSizing);
      window.addEventListener('resize', divSizing);
      //close handler
      document.getElementById('CashVaultPlugin-closeBtn').addEventListener('click', function () {
        parentDiv.style.display = 'none';
        if (options.onClose && typeof options.onClose === 'function') {
          options.onClose();
        }
        window.removeEventListener('resize', divSizing);
        //return false;
      });

      window.addEventListener('message', function (e) {
        if (SCRIPT_BASE.indexOf(e.origin) === 0) {
          switch (e.data.type) {
            case 'size':
              var container = document.getElementById('CashVaultPlugin-container');
              var height = e.data.height, width = e.data.width;
              if (height) {
                container.style.height = (height + params.topBarHeight) + 'px';
              }

              if (width) {
                container.style.width = width + 'px';
              }

              var frameWrapper = document.getElementById('CashVaultPlugin-frameWrapper');
              frameWrapper.style.height = (height) + 'px';
              divSizing(height + params.topBarHeight, width);
              break;
          }
        }
      });

    };

    this.getUrl = function (reservationId, extraData) {
      if (!reservationId) {
        throw 'Reservation ID must be provided for plugin to work';
      }
      extraData = extraData || {};
      var queryParams = [], paramName;
      queryParams.push('rid=' + encodeURIComponent(reservationId));
      for (var i in extraData) {

        if (extraData.hasOwnProperty(i) && ['rid', 'reservationId', 'autoShow'].indexOf(i) < 0) {
          paramName = i.match(/^customer/) ? (i.substr(0, 1).toUpperCase() + i.substr(1)) : i;
          queryParams.push(encodeURIComponent(paramName) + '=' + encodeURIComponent(extraData[i]));
        }
      }

      return scriptBaseUrl + 'pay?' + queryParams.join('&');
    };

  };


  /**
   * isMobile.js v0.3.9
   *
   * A simple library to detect Apple phones and tablets,
   * Android phones and tablets, other mobile devices (like blackberry, mini-opera and windows phone),
   * and any kind of seven inch device, via user agent sniffing.
   *
   * @author: Kai Mallea (kmallea@gmail.com)
   *
   * @license: http://creativecommons.org/publicdomain/zero/1.0/
   */
  (function (global) {

    var apple_phone = /iPhone/i,
      apple_ipod = /iPod/i,
      apple_tablet = /iPad/i,
      android_phone = /(?=.*\bAndroid\b)(?=.*\bMobile\b)/i, // Match 'Android' AND 'Mobile'
      android_tablet = /Android/i,
      amazon_phone = /(?=.*\bAndroid\b)(?=.*\bSD4930UR\b)/i,
      amazon_tablet = /(?=.*\bAndroid\b)(?=.*\b(?:KFOT|KFTT|KFJWI|KFJWA|KFSOWI|KFTHWI|KFTHWA|KFAPWI|KFAPWA|KFARWI|KFASWI|KFSAWI|KFSAWA)\b)/i,
      windows_phone = /IEMobile/i,
      windows_tablet = /(?=.*\bWindows\b)(?=.*\bARM\b)/i, // Match 'Windows' AND 'ARM'
      other_blackberry = /BlackBerry/i,
      other_blackberry_10 = /BB10/i,
      other_opera = /Opera Mini/i,
      other_chrome = /(CriOS|Chrome)(?=.*\bMobile\b)/i,
      other_firefox = /(?=.*\bFirefox\b)(?=.*\bMobile\b)/i, // Match 'Firefox' AND 'Mobile'
      seven_inch = new RegExp(
        '(?:' +         // Non-capturing group

        'Nexus 7' +     // Nexus 7

        '|' +           // OR

        'BNTV250' +     // B&N Nook Tablet 7 inch

        '|' +           // OR

        'Kindle Fire' + // Kindle Fire

        '|' +           // OR

        'Silk' +        // Kindle Fire, Silk Accelerated

        '|' +           // OR

        'GT-P1000' +    // Galaxy Tab 7 inch

        ')',            // End non-capturing group

        'i');           // Case-insensitive matching

    var match = function (regex, userAgent) {
      return regex.test(userAgent);
    };

    var IsMobileClass = function (userAgent) {
      var ua = userAgent || navigator.userAgent;
      // Facebook mobile app's integrated browser adds a bunch of strings that
      // match everything. Strip it out if it exists.
      var tmp = ua.split('[FBAN');
      if (typeof tmp[1] !== 'undefined') {
        ua = tmp[0];
      }

      this.apple = {
        phone: match(apple_phone, ua),
        ipod: match(apple_ipod, ua),
        tablet: !match(apple_phone, ua) && match(apple_tablet, ua),
        device: match(apple_phone, ua) || match(apple_ipod, ua) || match(apple_tablet, ua)
      };
      this.amazon = {
        phone: match(amazon_phone, ua),
        tablet: !match(amazon_phone, ua) && match(amazon_tablet, ua),
        device: match(amazon_phone, ua) || match(amazon_tablet, ua)
      };
      this.android = {
        phone: match(amazon_phone, ua) || match(android_phone, ua),
        tablet: !match(amazon_phone, ua) && !match(android_phone, ua) && (match(amazon_tablet, ua) || match(android_tablet, ua)),
        device: match(amazon_phone, ua) || match(amazon_tablet, ua) || match(android_phone, ua) || match(android_tablet, ua)
      };
      this.windows = {
        phone: match(windows_phone, ua),
        tablet: match(windows_tablet, ua),
        device: match(windows_phone, ua) || match(windows_tablet, ua)
      };
      this.other = {
        blackberry: match(other_blackberry, ua),
        blackberry10: match(other_blackberry_10, ua),
        opera: match(other_opera, ua),
        firefox: match(other_firefox, ua),
        chrome: match(other_chrome, ua),
        device: match(other_blackberry, ua) || match(other_blackberry_10, ua) || match(other_opera, ua) || match(other_firefox, ua) || match(other_chrome, ua)
      };
      this.seven_inch = match(seven_inch, ua);
      this.any = this.apple.device || this.android.device || this.windows.device || this.other.device || this.seven_inch;
      // excludes 'other' devices and ipods, targeting touchscreen phones
      this.phone = this.apple.phone || this.android.phone || this.windows.phone;
      // excludes 7 inch devices, classifying as phone or tablet is left to the user
      this.tablet = this.apple.tablet || this.android.tablet || this.windows.tablet;

      if (typeof window === 'undefined') {
        return this;
      }
    };

    var instantiate = function () {
      var IM = new IsMobileClass();
      IM.Class = IsMobileClass;
      return IM;
    };

    if (typeof module != 'undefined' && module.exports && typeof window === 'undefined') {
      //node
      module.exports = IsMobileClass;
    } else if (typeof module != 'undefined' && module.exports && typeof window !== 'undefined') {
      //browserify
      module.exports = instantiate();
    } else if (typeof define === 'function' && define.amd) {
      //AMD
      define('isMobile', [], global.isMobile = instantiate());
    } else {
      global.isMobile = instantiate();
    }

  })(CashVaultPlugin.prototype);

  //auto initialize

  window.addEventListener('load', function () {
    console.log('Searching for reservation buttons');
    var clickables = document.querySelectorAll('[data-reservation-id]');
    if (clickables.length < 1) {
      console.log('No reservation button found.');
      return;
    }

    var plugin = new CashVaultPlugin();
    var displayPopup = function (el) {
      var data = el.dataset;
      plugin.displayPopup(data.reservationId, data, {
        onShow: function () {
          console.log('Payment launched for reservation: ' + data.reservationId);
        },
        onClose: function () {
          console.log('Payment closed for reservation: ' + data.reservationId);
        }
      });
    };

    var hasAutoShown = false, btn, autoShow;
    for (var i = 0; i < clickables.length; i++) {
      btn = clickables[i], autoShow = btn.dataset.autoShow;
      if (autoShow && autoShow.toLowerCase() === 'true' && !hasAutoShown) {
        displayPopup(btn);
        hasAutoShown = true;
      }
      //whether auto-shown or not, add click listener
      btn.addEventListener('click', function () {
        displayPopup(this);
      });
    }

  });
  exports.CashVaultPlugin = CashVaultPlugin;
})(this);