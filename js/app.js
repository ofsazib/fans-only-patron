// redirect user when trying to do actions and he's not logged in
window.livewire.on("requires-login", function () {
  window.location.href = loginURI;
});

// listen to livewire swal messages
window.livewire.on("swal", function (response) {
  swal({
    title: response.title,
    text: response.message,
    icon: response.type,
  });
});

// listen to livewire confirmation requests
window.livewire.on("swal-confirm", function (response) {
  swal({
    title: response.title,
    text: response.message,
    icon: "warning",
    buttons: true,
    dangerMode: true,
    buttons: [textNo, textYes],
  }).then((willDelete) => {
    if (willDelete) {
      window.livewire.emit(response.emitEvent, response.parameter);
    } else {
      return;
    }
  });
});

// listen to livewire growl messages
window.livewire.on("growl", function (response) {
  $.growl({ title: response.message, message: "" });
});


$(function () {
  $("body").on("click", '[data-toggle="lightbox"]', function (event) {
    event.preventDefault();
    $(this).ekkoLightbox();
  });

  $("body").on("click", ".sendTip", function (e) {
    e.preventDefault();

    var postId = $(this).data("post");

    // show leave tip
    var leaveTipFrm = $("div.leave-tip[data-post=" + postId + "]");
    leaveTipFrm.toggleClass("d-none");
  });

  // tip button
  $("body").on("click", ".submitTipBtn", function (e) {
    e.preventDefault();

    var postId = $(this).data("id");
    var gateway = $(this).data("gateway");

    var $form = $('form[name="tipFrm-' + postId + '"]');

    console.log($form);

    // append gateway
    $form.append($('<input type="hidden" name="gateway" />').val(gateway));

    // submit the form
    $form.submit();
  });

  // delete comment
  $("body").on("click", ".delete-comment", function (e) {
    e.preventDefault();

    var commentId = $(this).data("id");
    var postId = $(this).data("post");

    swal({
      title: confirmationTitle,
      text: confirmationMessage,
      icon: "warning",
      buttons: true,
      dangerMode: true,
      buttons: [textNo, textYes],
    }).then((willDelete) => {
      if (willDelete) {
        $.get(
          deleteCommentURI + "/" + commentId,
          { _token: $("meta[name=token]").attr("content") },
          function (resp) {
            if (resp.message == "deleted") {
              // increment comments count
              var commentsCount = $(
                "span.post-comments-count[data-id=" + postId + "]"
              );
              var newCommentsCount = parseInt(commentsCount.html()) - 1;

              commentsCount.html(newCommentsCount);

              $('.singleComment[data-id="' + commentId + '"]').addClass(
                "d-none"
              );
              $.growl({ title: successfullyRemovedComment, message: "" });
            }
          }
        );
      } else {
        return;
      }
    });
  });

  // clipboard
  var clipboard = new ClipboardJS(".copyLink");

  clipboard.on("success", function (e) {
    $.growl({ title: successfullyCopiedLink, message: "" });
  });

  $("body").on("newPostAdded", function (event, postId) {
    $.get(loadPostById + "/" + postId, function (resp) {
      $("#createPostFrm").after(resp);
    });
  });

  // tooltip
  $('[data-toggle="tooltip"]').tooltip();

  // toggle like
  function toggleLike(postId) {
    var resp = $.post(
      likeURI + "/" + postId,
      { _token: $('meta[name="_token"]').attr("content") },
      function (resp) {
        return resp;
      }
    );

    return resp;
  }

  // love posts
  $("body").on("click", ".lovePost", function () {
    // get post id
    var postId = $(this).data("id");
    var currentPostLikes = $("span.post-likes-count[data-id=" + postId + "]");
    var intLikes = parseInt(currentPostLikes.html());

    var result = toggleLike(postId);

    if (result) {
      $(this).addClass("d-none");
      $("a.unlovePost[data-id=" + postId + "]").removeClass("d-none");
      currentPostLikes.html(intLikes + 1);
    } else {
      $.growl({ title: result.responseText, message: "" });
    }
  });

  // unlove posts
  $("body").on("click", ".unlovePost", function () {
    // get post id
    postId = $(this).data("id");
    var currentPostLikes = $("span.post-likes-count[data-id=" + postId + "]");
    var intLikes = parseInt(currentPostLikes.html());

    $(this).addClass("d-none");
    $("a.lovePost[data-id=" + postId + "]").removeClass("d-none");
    currentPostLikes.html(intLikes - 1);

    toggleLike(postId);
  });

  // load post comments
  $("body").on("click", ".loadComments", function () {
    $(this).removeClass("loadComments").addClass("hideComments");

    // get post id
    postId = $(this).data("id");

    $(".appendComments[data-id=" + postId + "]").removeClass("d-none");
    $("div.leave-comment[data-id=" + postId + "]").removeClass("d-none");
    $(".loadMoreComments[data-id=" + postId + "]").removeClass("d-none");

    $.get(commentsURI + "/" + postId, function (resp) {
      $(".appendComments[data-id=" + postId + "]").html(resp.view);
      $(".post-" + postId + "-lastId").html(resp.lastId);
    });
  });

  // hide comments
  $("body").on("click", ".hideComments", function () {
    // add loadComments class back to be able to load again if clicked.
    $(this).removeClass("hideComments").addClass("loadComments");

    // hide comments
    $(".appendComments[data-id=" + postId + "]").addClass("d-none");

    // hide comment form
    $("div.leave-comment[data-id=" + postId + "]").addClass("d-none");

    // clear comments list
    $(".appendComments[data-id=" + postId + "]").html("");

    // hide load more comments
    $(".loadMoreComments[data-id=" + postId + "]").addClass("d-none");
    $(".noMoreComments[data-id=" + postId + "]").addClass("d-none");
  });

  // load more poost comments
  $("body").on("click", ".loadMoreComments", function () {
    // get post id
    postId = $(this).data("id");
    lastId = $(".post-" + postId + "-lastId").html();

    var commentsDiv = $(".appendComments[data-id=" + postId + "]");

    $.get(commentsURI + "/" + postId + "/" + lastId, function (resp) {
      if (resp.lastId != "0") {
        commentsDiv.append(resp.view);
        $(".post-" + postId + "-lastId").html(resp.lastId);
      } else {
        $(".loadMoreComments[data-id=" + postId + "]").addClass("d-none");
        $(".noMoreComments[data-id=" + postId + "]").removeClass("d-none");
      }
    });
  });

  // leave comment
  $("body").on("keypress", ".leave-comment-inp", function (e) {
    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      var commentTxt = $(this);
      var commentText = $.trim(commentTxt.val());
      var postId = $(this).data("id");

      if (commentText.length < 2) {
        $.growl.error({ title: pleaseWriteSomething, message: "" });
      } else {
        $.post(
          postCommentURI + "/" + postId,
          {
            message: commentText,
            _token: $("meta[name=_token]").attr("content"),
          },
          function (resp) {
            if (resp.message == "posted") {
              var commentId = resp.id;

              // increment comments count
              var commentsCount = $(
                "span.post-comments-count[data-id=" + postId + "]"
              );
              var newCommentsCount = parseInt(commentsCount.html()) + 1;

              commentsCount.html(newCommentsCount);

              $.get(
                loadCommentByIdURI + "/" + commentId + "/" + postId,
                function (resp) {
                  commentTxt.after(resp);
                  commentTxt.val("");
                }
              );
            } else {
              $.growl.error({ title: resp.message, message: "" });
            }
          }
        );
      }
    }
  });

  // edit comment
  $("body").on("click", ".edit-comment", function (e) {
    e.preventDefault();

    var commentId = $(this).data("id");
    var postId = $(this).data("post");
    var commentContent = $("div.comment-content[data-id=" + commentId + "]");
    var commentForm = $("div.comment-form[data-id=" + commentId + "]");

    commentContent.addClass("d-none");
    commentForm.removeClass("d-none");

    $.get(
      editCommentsURI + "/" + commentId,
      { _token: $("meta[name=token]").attr("content") },
      function (resp) {
        commentForm.html(resp);
      }
    );
  });

  $("body").on("keypress", ".update-comment-inp", function (e) {
    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      var commentTxt = $(this);
      var commentText = $.trim(commentTxt.val());
      var commentId = $(this).data("id");

      if (commentText.length < 2) {
        $.growl.error({ title: pleaseWriteSomething, message: "" });
      } else {
        $.post(
          updateCommentsURI,
          {
            id: commentId,
            comment: commentText,
            _token: $("meta[name=_token]").attr("content"),
          },
          function (resp) {
            if (resp.updated) {
              $("div.comment-content[data-id=" + commentId + "]").html(
                resp.comment.comment
              );
              $("div.comment-content[data-id=" + commentId + "]").removeClass(
                "d-none"
              );
              $("div.comment-form[data-id=" + commentId + "]").addClass(
                "d-none"
              );
            } else {
              $.growl.error({ title: resp.message, message: "" });
            }
          }
        );
      }
    }
  });

  // image upload btn
  $(".imageUploadLink").click(function () {
    $("input[name=imageUpload]").trigger("click");
  });

  $("input[name=imageUpload]").on("change", function () {
    var val = $(this).val();
    $(".uploadName").text(val);
  });

  // video upload btn
  $(".videoUploadLink").click(function () {
    $("input[name=videoUpload]").trigger("click");
  });

  $("input[name=videoUpload]").on("change", function () {
    var val = $(this).val();
    $(".uploadName").text(val);
  });

  // image upload btn
  $(".audioUploadLink").click(function () {
    $("input[name=audioUpload]").trigger("click");
  });

  $("input[name=audioUpload]").on("change", function () {
    var val = $(this).val();
    $(".uploadName").text(val);
  });

  // post new media: lock type changing
  $(".togglePostType").on("click", function () {
    var switchTo = $(this).data("switch-to");

    if ("paid" == switchTo) {
      $("a[data-switch-to=free]").removeClass("d-none");
      $("a[data-switch-to=paid]").addClass("d-none");
    } else {
      $("a[data-switch-to=paid]").removeClass("d-none");
      $("a[data-switch-to=free]").addClass("d-none");
    }

    $(".postType").val(switchTo);
  });

  // delete post
  $("body").on("click", ".delete-post", function (e) {
    e.preventDefault();

    var postId = $(this).data("id");
    var delUri = $(this).attr("href");
    var postCard = $('.card[data-post-id="' + postId + '"');

    swal({
      title: confirmationTitle,
      text: confirmationMessage,
      icon: "warning",
      buttons: true,
      dangerMode: true,
      buttons: [cancelButton, confirmButton],
    }).then((willDelete) => {
      if (willDelete) {
        $.get(delUri, function (resp) {
          $.growl({ title: successfullyRemovedPost, message: "" });
          postCard.hide("slow");
        });
      }
    });

    return false;
  });

  // add new post
  $(".btnCreatePost").on("click", function (ev) {
    ev.preventDefault();

    // set link to this button
    var thisBtn = $(".btnCreatePost");

    // make button disabled
    thisBtn.attr({ disabled: "true" });

    // set link to button icon
    var thisIcon = thisBtn.find("svg");

    // add loading spinner
    var spinnerClass = "fas fa-spinner fa-spin";
    thisIcon.addClass(spinnerClass);

    // set link to textarea
    var textContentEl = $("#createPost");
    var textContent = $("#createPost").val();

    // show progress bar
    var progressDiv = $("#progress");
    progressDiv.removeClass("d-none");

    var progressBar = $(".progress-bar");
    var percentage = $(".percent");
    var percentTxt = "0%";

    // check for empty description
    if (textContent.length < 1) {
      // focus on the textarea
      textContentEl.focus();

      // remove disabled attribute
      thisBtn.removeAttr("disabled");

      // remove spinner
      thisIcon.removeClass(spinnerClass);

      progressDiv.addClass("d-none");

      swal({
        text: pleaseWriteSomething,
        icon: "error",
      });

      return false;
    }

    $("#createPostFrm")
      .ajaxForm({
        dataType: "json",

        error: function (xhrResp, statusText, responseText, jsonResp) {
          thisBtn.removeAttr("disabled");

          if (xhrResp.statusText != "OK") {
            if (typeof xhrResp.responseJSON.errors != "undefined") {
              var errMsg = xhrResp.responseJSON.errors;
            } else if (typeof xhrResp.responseJSON.message != "undefined") {
              var errMsg = xhrResp.responseJSON.message;
            } else {
              var errMsg = "Unknown server error";
            }

            console.log(errMsg);

            swal({
              title: errMsg,
              text: errMsg,
              icon: "error",
            });
          }

          // remove spinner
          thisIcon.removeClass(spinnerClass);
          progressDiv.addClass("d-none");
        },

        beforeSend: function () {
          // reset progress bar to 0%
          progressBar.width(percentTxt);
          percentage.html(percentTxt);
        },

        uploadProgress: function (ev, pos, total, progress) {
          // set progress including '%' sign
          var progressTxt = progress + "%";

          // update progress bar percentage
          progressBar.width(progressTxt);
          percentage.html(progressTxt);
        },
        complete: function (resp) {
          var resp = resp.responseJSON;

          progressBar.width("0%");
          percentage.html("0%");
          progressDiv.addClass("d-none");

          $(".btnCreatePost").find("svg").removeClass("fas fa-spinner fa-spin");
          $(".btnCreatePost").find("i").addClass("far fa-paper-plane");
          $(".btnCreatePost").attr("disabled", false);

          if (resp.result != false) {
            var post = resp.post;

            document.location.href = "/feed";

            // $("body").trigger("newPostAdded", [post]);

            // $("#createPost").val("");
            // $(".uploadName").text("");

            // $("input[name=imageUpload]").val("");
            // $("input[name=videoUpload]").val("");
            // $("input[name=audioUpload]").val("");

            // $(".MultiFile-wrap").html("");
          } else {
            var allErrors = "";
            var index = "";

            for (index in resp.errors) {
              allErrors = allErrors + "." + resp.errors[index];
            }

            swal({
              icon: "error",
              text: allErrors,
              title: "",
            });
          }
        },
      })
      .submit();

    return false;
  });
});
