(function ($) {
  "use strict";

  $(document).ready(function () {
    $(
      "." +
        window.wcg.name +
        " a.btn-rate, ." +
        window.wcg.name +
        " a.btn-remind, ." +
        window.wcg.name +
        " a.btn-cancel"
    ).click(function (e) {
      e.preventDefault();

      $.post({
        url: window.wcg.ajax_url,
        data: {
          _ajax_nonce: $(this).data("nonce"),
          action: window.wcg.name + "-" + $(this).data("action"),
        },
        async: false,
        success: function (response) {
          if (response.redirect) {
            window.open(response.redirect, "_blank").focus();
          }
          $("." + window.wcg.name + " .notice-dismiss").click();
        },
      });
    });
  });
})(jQuery);
