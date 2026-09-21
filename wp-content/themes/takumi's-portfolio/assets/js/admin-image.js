/* ============================================================
   1枚だけの画像欄 — wp.media で選んだ画像を隠しinputに保持する。
   メイン画像(アイキャッチ)欄と、スキルのアイコン画像欄で共通。

   name="_thumbnail_id" の欄は WordPress 本体がアイキャッチとして
   保存するので、外したときは空ではなく "-1" を入れる必要がある。
   その値は data-empty 属性に入れてある。
   ============================================================ */
document.addEventListener("DOMContentLoaded", function () {
  if (typeof wp === "undefined" || !wp.media) {
    return;
  }

  document.querySelectorAll(".takumi-image").forEach(function (root) {
    var input = root.querySelector('input[type="hidden"]');
    var preview = root.querySelector(".takumi-image__preview");
    var pickBtn = root.querySelector(".takumi-image__pick");
    var clearBtn = root.querySelector(".takumi-image__clear");
    if (!input || !preview || !pickBtn) {
      return;
    }

    var emptyValue = input.dataset.empty || "";
    var emptyText =
      (preview.querySelector(".takumi-image__empty") || {}).textContent ||
      "画像は選ばれていません";

    var render = function (url) {
      if (url) {
        preview.innerHTML = '<img src="' + url + '" alt="">';
        pickBtn.textContent = "画像を差し替える";
        if (clearBtn) clearBtn.hidden = false;
      } else {
        preview.innerHTML =
          '<span class="takumi-image__empty">' + emptyText + "</span>";
        pickBtn.textContent = "画像をアップロード / 選択";
        if (clearBtn) clearBtn.hidden = true;
      }
    };

    var frame = null;
    pickBtn.addEventListener("click", function (e) {
      e.preventDefault();
      if (!frame) {
        frame = wp.media({
          title: "画像を選択",
          button: { text: "この画像を使う" },
          multiple: false,
          library: { type: "image" },
        });
        frame.on("select", function () {
          var attachment = frame.state().get("selection").first();
          if (!attachment) return;
          var attrs = attachment.attributes;
          var url =
            (attrs.sizes && attrs.sizes.medium && attrs.sizes.medium.url) ||
            attrs.url;
          input.value = String(attachment.id);
          render(url);
        });
      }
      frame.open();
    });

    if (clearBtn) {
      clearBtn.addEventListener("click", function (e) {
        e.preventDefault();
        input.value = emptyValue;
        render("");
      });
    }
  });
});
