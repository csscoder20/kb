(function ($) {
  /*--------------- search js--------*/
  $(".search a").on("click", function () {
    if ($(this).parent().hasClass("open")) {
      $(this).parent().removeClass("open");
    } else {
      $(this).parent().addClass("open");
    }
    return false;
  });

  // === Focus Search Form
  $(document).on("keydown", function (e) {
    if (e.keyCode === 191) {
      e.preventDefault();
      $("input[type=search]").focus();
      return;
    }
  });

  let doc_banner_area = $(".doc_banner_area input[type=search]");
  doc_banner_area.on('focus', function () {
    $("body").addClass("search-focused");
    $(".doc_banner_content").css({"position": "relative", "z-index": "999"});
  });

  doc_banner_area.on('focusout', function () {
    $("body").removeClass("search-focused");
    $(".doc_banner_content").removeAttr('style');
  });

  
  $(".header_search_keyword ul li a").on("click", function (e) {
    e.preventDefault();
    var content = $(this).text();
    $("#searchbox").val(content).focus();
    $(".input-wrapper input")
      .parent()
      .find(".header_search_form_panel")
      .first()
      .slideDown(300);
  });
  $(".input-wrapper input").focusout(function () {
    $(this).parent().find(".header_search_form_panel").first().slideUp(300);
  });
  
})(jQuery);