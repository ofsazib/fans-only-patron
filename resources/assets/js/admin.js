function htmlbodyHeightUpdate() {
  var height3 = $(window).height();
  var height1 = $(".nav").height() + 50;

  var height2 = $(".main").height();

  if (height2 > height3) {
    $("html").height(Math.max(height1, height3, height2) + 10);
    $("body").height(Math.max(height1, height3, height2) + 10);
  } else {
    $("html").height(Math.max(height1, height3, height2));
    $("body").height(Math.max(height1, height3, height2));
  }
}

$(document).ready(function () {
  htmlbodyHeightUpdate();

  $(window).resize(function () {
    htmlbodyHeightUpdate();
  });

  $(window).scroll(function () {
    height2 = $(".main").height();
    htmlbodyHeightUpdate();
  });

  $(".js-example-basic-multiple").select2({
    multiple: true,
  });

  $(".sortableUI tbody").sortable({
    update: function () {
      var order = $(".sortableUI tbody").sortable("toArray");
      $.get("/admin/navigation-ajax-sort", { navi_order: order }, function (r) {
        $(".order-result").show();
      });
    },
  });
  $(".sortableUI").disableSelection();
  $(".dataTable").dataTable();
  $(".select2").select2();
});
