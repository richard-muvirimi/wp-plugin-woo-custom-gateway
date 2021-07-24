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
      console.log(window.wcg.name + "-" + $(this).data("action"));
      $.post({
        url: window.wcg.ajax_url,
        data: {
          _ajax_nonce: $(this).data("nonce"),
          action: window.wcg.name + "-" + $(this).data("action"),
        },
        success: function (response) {
          console.log(response);
          if (response.redirect) {
            window.location.assign(response.redirect);
          }
          $("." + window.wcg.name + " .notice-dismiss").click();
        },
      });
    });
  });
})(jQuery);
