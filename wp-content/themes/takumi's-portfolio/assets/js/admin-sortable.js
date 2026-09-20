/* ============================================================
   スキル一覧(管理画面)の並び替え —
   行の左端のハンドルを掴んで動かすと、その順番を menu_order として保存する。
   保存は Ajax。ページを離れなくても順番が確定する。
   ============================================================ */
document.addEventListener("DOMContentLoaded", function () {
  var settings = window.takumiSortable;
  var list = document.getElementById("the-list");
  if (!settings || !list) {
    return;
  }

  var dragging = null;
  var notice = document.createElement("p");
  notice.className = "takumi-order-notice";
  var table = list.closest("table");
  if (table && table.parentNode) {
    table.parentNode.insertBefore(notice, table.nextSibling);
  }

  var say = function (text, state) {
    notice.textContent = text;
    notice.setAttribute("data-state", state || "ok");
  };

  // ハンドル以外から掴めてしまうと、テキスト選択のたびに行が動いてしまう。
  list.addEventListener("mousedown", function (e) {
    var handle = e.target.closest(".takumi-drag");
    var row = e.target.closest("tr");
    if (!row) {
      return;
    }
    row.draggable = !!handle;
  });

  list.addEventListener("dragstart", function (e) {
    dragging = e.target.closest("tr");
    if (!dragging) {
      return;
    }
    dragging.classList.add("takumi-dragging");
    e.dataTransfer.effectAllowed = "move";
    // Firefox はデータを入れないとドラッグが始まらない
    e.dataTransfer.setData("text/plain", dragging.id || "row");
  });

  list.addEventListener("dragover", function (e) {
    if (!dragging) {
      return;
    }
    e.preventDefault();
    e.dataTransfer.dropEffect = "move";
    var over = e.target.closest("tr");
    if (!over || over === dragging || over.parentNode !== list) {
      return;
    }
    list.querySelectorAll(".takumi-drop-target").forEach(function (tr) {
      tr.classList.remove("takumi-drop-target");
    });
    var rect = over.getBoundingClientRect();
    var after = e.clientY > rect.top + rect.height / 2;
    over.classList.add("takumi-drop-target");
    list.insertBefore(dragging, after ? over.nextSibling : over);
  });

  var finish = function () {
    if (!dragging) {
      return;
    }
    dragging.classList.remove("takumi-dragging");
    dragging = null;
    list.querySelectorAll(".takumi-drop-target").forEach(function (tr) {
      tr.classList.remove("takumi-drop-target");
    });
    save();
  };

  list.addEventListener("drop", function (e) {
    e.preventDefault();
    finish();
  });
  list.addEventListener("dragend", finish);

  var save = function () {
    var ids = Array.prototype.map
      .call(list.querySelectorAll("tr[id^='post-']"), function (row) {
        return row.id.replace("post-", "");
      })
      .filter(Boolean);
    if (!ids.length) {
      return;
    }

    // 画面上の番号を先に振り直す(保存を待たずに結果が見えるように)
    list.querySelectorAll(".takumi-order-number").forEach(function (el, i) {
      el.textContent = i + 1;
    });

    say(settings.savingText);
    var body = new URLSearchParams();
    body.append("action", "takumi_save_order");
    body.append("nonce", settings.nonce);
    body.append("post_type", settings.postType);
    ids.forEach(function (id) {
      body.append("ids[]", id);
    });

    fetch(settings.ajaxUrl, {
      method: "POST",
      credentials: "same-origin",
      body: body,
    })
      .then(function (res) {
        return res.json();
      })
      .then(function (json) {
        say(json && json.success ? settings.savedText : settings.errorText, json && json.success ? "ok" : "error");
      })
      .catch(function () {
        say(settings.errorText, "error");
      });
  };
});
