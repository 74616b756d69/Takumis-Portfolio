/**
 * WORK RECORDS — 行クリックで詳細をモーダル表示する。
 *
 * 各行は実績の単体ページへの <a>。JS が動いていればその遷移を止めて
 * モーダルを開き、動いていなければ通常のリンクとして単体ページへ飛ぶ。
 */
(function () {
  "use strict";

  var list = document.querySelector(".records-list");
  var dialog = document.getElementById("record-dialog");
  if (!list || !dialog || typeof dialog.showModal !== "function") return;

  // 閉じたあとフォーカスを戻す先。document.activeElement ではなく行そのものを
  // 覚える（Safari はリンクをマウスクリックしてもフォーカスを乗せないため）。
  var lastFocused = null;

  function open(record) {
    var detail = record.parentNode.querySelector(".record-detail");
    if (!detail) return false;

    dialog.innerHTML =
      '<div class="record-visual">' +
        '<button class="rv-close" type="button" aria-label="閉じる">' +
          '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" ' +
          'stroke-width="2" stroke-linecap="round" aria-hidden="true">' +
          '<path d="M18 6 6 18M6 6l12 12"/></svg>' +
        "</button>" +
        detail.innerHTML +
      "</div>";

    lastFocused = record;
    dialog.showModal();
    document.body.classList.add("is-dialog-open");
    dialog.querySelector(".rv-close").focus();
    return true;
  }

  list.addEventListener("click", function (e) {
    // 新しいタブで開こうとしている場合は邪魔しない
    if (e.metaKey || e.ctrlKey || e.shiftKey || e.button !== 0) return;

    var record = e.target.closest(".record");
    if (!record) return;

    if (open(record)) e.preventDefault();
  });

  dialog.addEventListener("click", function (e) {
    // 背景（dialog 自身）のクリック、または閉じるボタンで閉じる
    if (e.target === dialog || e.target.closest(".rv-close")) dialog.close();
  });

  // Esc は <dialog> が標準で拾う。閉じたらフォーカスを元の行に戻す
  dialog.addEventListener("close", function () {
    document.body.classList.remove("is-dialog-open");
    if (lastFocused) lastFocused.focus();
  });

  // モーダルを開いたときだけ存在させる（初期表示の DOM を軽く保つ）
  list.setAttribute("data-enhanced", "true");
})();
