(function ($) {
  "use strict";

  $(document).ready(function () {
    $(
      "." +
      window.wooCustomGateway.name +
      " a.btn-rate, ." +
      window.wooCustomGateway.name +
      " a.btn-remind, ." +
      window.wooCustomGateway.name +
      " a.btn-cancel"
    ).click(function (e) {
      e.preventDefault();

      $.post({
        url: window.wooCustomGateway.ajax_url,
        data: {
          _ajax_nonce: $(this).data("nonce"),
          action: window.wooCustomGateway.name + "-" + $(this).data("action"),
        },
        async: false,
        success: function (response) {
          if (response.redirect) {
            window.open(response.redirect, "_blank").focus();
          }
          $("." + window.wooCustomGateway.name + " .notice-dismiss").click();
        },
      });
    });
  });
})(jQuery);
