/* ============================================================
   実績情報「画像を追加」欄 — wp.media で複数画像を選び、
   隠しinputに添付ファイルIDのカンマ区切りで保持する。
   1枚目が並び順の先頭 = モーダルのメイン画像になる。
   ============================================================ */
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".takumi-gallery").forEach(function (root) {
    var input = root.querySelector('input[type="hidden"]');
    var list = root.querySelector(".takumi-gallery__list");
    var addBtn = root.querySelector(".takumi-gallery__add");
    if (!input || !list || !addBtn || typeof wp === "undefined" || !wp.media) {
      return;
    }

    var ids = function () {
      return (input.value || "")
        .split(",")
        .map(function (v) {
          return v.trim();
        })
        .filter(Boolean);
    };

    var setIds = function (arr) {
      input.value = arr.join(",");
    };

    list.addEventListener("click", function (e) {
      var btn = e.target.closest(".takumi-gallery__remove");
      if (!btn) return;
      var item = btn.closest(".takumi-gallery__item");
      var id = item ? item.dataset.id : null;
      if (!id) return;
      setIds(
        ids().filter(function (v) {
          return v !== id;
        })
      );
      item.remove();
    });

    var frame = null;
    addBtn.addEventListener("click", function (e) {
      e.preventDefault();
      if (!frame) {
        frame = wp.media({
          title: "画像を選択",
          button: { text: "この画像を追加" },
          multiple: true,
          library: { type: "image" },
        });
        frame.on("select", function () {
          var current = ids();
          frame
            .state()
            .get("selection")
            .each(function (attachment) {
              var id = String(attachment.id);
              if (current.indexOf(id) !== -1) return;
              current.push(id);

              var thumbUrl =
                (attachment.attributes.sizes &&
                  attachment.attributes.sizes.thumbnail &&
                  attachment.attributes.sizes.thumbnail.url) ||
                attachment.attributes.url;

              var span = document.createElement("span");
              span.className = "takumi-gallery__item";
              span.dataset.id = id;
              span.innerHTML =
                '<img src="' + thumbUrl + '" alt="" width="150" height="150">' +
                '<button type="button" class="takumi-gallery__remove" aria-label="この画像を削除">&times;</button>';
              list.appendChild(span);
            });
          setIds(current);
        });
      }
      frame.open();
    });
  });
});
