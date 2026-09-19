/* ============================================================
   Works — 作品データ(単一ソース)と描画・フィルタ・モーダル
   ============================================================ */

const WORKS = [
  {
    id: "ylmemoria",
    title: "YL MEMORIA",
    meta: "文化祭・チーム制作・2024",
    year: 2024,
    tech: ["html/css", "js"],
    type: ["front", "design"],
    role: "チームリーダー・フロントコーディング・デザイン",
    description:
      "文化祭の展示作品として「花詞データベース」を制作しました。約50種類の花について、花言葉だけでなく学名・由来・原産地などを自ら調査し、HTMLで整理しました。さらにJavaScriptを用いて「季節」「色」「神話由来」といったテーマごとに絞り込みができる仕組みを実装し、来場者が目的や興味に合わせて花を探せるようにしました。当時はデータベースの知識がなかったため、すべて手入力でデータを構築しましたが、情報の分類方法や検索軸を工夫することで「情報を活用しやすい形に整える」ことを意識しました。この経験は、のちのデータベース学習やUI設計の理解につながっています。(サイト公開なし)",
    thumb: "img/YLMEMORIA/YL MEMORIA.png",
    images: ["img/YLMEMORIA/YL MEMORIA.png", "img/YLMEMORIA/Plumeria.png", "img/YLMEMORIA/list.png"],
    url: "",
    github: "https://github.com/74616b756d69/YLMEMORIA",
    icons: ["html", "css", "js"],
    category: "personal",
  },
  {
    id: "wakashachiya",
    title: "若鯱家 Webサイト",
    meta: "産学連携・チーム制作・2024",
    year: 2024,
    tech: ["html/css", "js"],
    type: ["front"],
    role: "チームリーダー・フロントコーディング",
    description:
      "株式会社若鯱家様との産学連携プロジェクトで、チーム制作としてWebサイトのリニューアルを担当しました。既存サイトは主に既存顧客向けの内容で、若年層の集客が課題でした。そこでチームでは、若年層を対象にアンケートを実施し、求められる情報や興味を引くアプローチを整理。その結果をもとに、デザイン・情報設計・機能面を考慮したWebサイトを制作しました。(Web公開なし)",
    thumb: "img/wakasyatiya/若鯱家-top.png",
    images: ["img/wakasyatiya/若鯱家-top.png", "img/若鯱家会社概要.png"],
    url: "",
    github: "https://github.com/74616b756d69/Wakasyatiya-TeamH",
    icons: ["html", "css", "js"],
    category: "company",
  },
  {
    id: "hikariwo",
    title: "HiKaRiWo",
    meta: "産学連携・チームリーダー・2025",
    year: 2025,
    tech: ["html/css", "js", "python"],
    type: ["front", "back"],
    role: "チームリーダー・要件定義・実装指揮",
    description:
      "企業様との産学連携プロジェクトでチームリーダーを担当したWebシステム開発です。要件定義・設計・実装・リリースまでのフルサイクルを指揮し、Django と Docker を用いてWebシステムを構築しました。企画からリリースまでを一貫して経験したことで、チーム開発の進行管理と技術選定の判断力を養いました。",
    thumb: "img/hikariwo/HiKaRiWo_LP.png",
    images: ["img/hikariwo/HiKaRiWo_LP.png"],
    url: "",
    github: "https://github.com/74616b756d69/TECJUM-teamE_hikariwo",
    icons: ["html", "css", "js", "python", "django", "docker"],
    category: "company",
  },
  {
    id: "client-k",
    title: "有限会社K様 Webサイト制作",
    meta: "実案件・チーム制作・2025",
    year: 2025,
    tech: ["html/css", "js", "php"],
    type: ["front", "back"],
    role: "フロント・バックエンドコーディング",
    description:
      "実案件として受注したコーポレートサイト制作です。WordPress をベースに、フロントエンドからバックエンドまでのコーディングを担当しています。(現在制作中)",
    thumb: "img/icon/logo_img.png",
    images: ["img/icon/logo_img.png"],
    url: "",
    github: "",
    icons: ["html", "css", "js", "php", "wordpress"],
    category: "company",
  },
  {
    id: "portfolio",
    title: "Takumi's Portfolio",
    meta: "個人制作・2025",
    year: 2025,
    tech: ["html/css", "js"],
    type: ["front", "design"],
    role: "デザイン・コーディング全て",
    description:
      "本ポートフォリオサイトです。デザインからコーディングまですべて自身で制作しました。Three.js によるインタラクティブな3D演出や、GSAP を用いたスクロールアニメーションを取り入れ、WordPress テーマ化にも対応しています。",
    thumb: "img/img/portfolio.png",
    images: ["img/img/portfolio.png"],
    url: "https://takumisportfolio.main.jp",
    github: "https://github.com/74616b756d69/Takumis-portfolio",
    icons: ["html", "css", "js", "threejs", "wordpress"],
    category: "personal",
  },
  {
    id: "lapesca",
    title: "La Pesca様 Webサイト",
    meta: "掲載のみ承認案件・2025",
    year: 2025,
    tech: ["html/css", "js"],
    type: ["front", "design"],
    role: "コーディング",
    description:
      "クライアント案件として制作に参加したWebサイトです。掲載のみ承認いただいている案件のため、詳細は面談時にご紹介できます。",
    thumb: "img/Lapesca/Lapeseca_top.png",
    images: ["img/Lapesca/Lapeseca_top.png"],
    url: "",
    github: "",
    icons: ["html", "css", "js"],
    category: "company",
  },
  {
    id: "todo",
    title: "ToDoリスト",
    meta: "個人制作・2025",
    year: 2025,
    tech: ["html/css", "js", "php"],
    type: ["front", "back", "design"],
    role: "設計・実装すべて",
    description:
      "Laravel で制作したタスク管理アプリです。ユーザー認証・CRUD・フォームバリデーションを実装し、バックエンド開発の基礎を体系的に習得しました。",
    thumb: "img/TODO/todo-top.jpeg",
    images: ["img/TODO/todo-top.jpeg"],
    url: "",
    github: "https://github.com/74616b756d69/Laravel_ToDo",
    icons: ["php", "laravel", "js", "mysql"],
    category: "personal",
  },
  {
    id: "spottimer",
    title: "SPOT Timer",
    meta: "イベント・チーム制作・2025",
    year: 2025,
    tech: ["html/css", "js", "php"],
    type: ["front", "back"],
    role: "チーム開発・実装",
    description:
      "イベントでのチーム制作で開発したタイマーアプリケーションです。短期間のチーム開発を通じて、役割分担とスピード感のある実装を経験しました。",
    thumb: "img/SPOTtimer/SPOTtimer.png",
    images: ["img/SPOTtimer/SPOTtimer.png"],
    url: "",
    github: "",
    icons: ["html", "css", "js", "php"],
    category: "team",
  },
];

const ICON_BASE = "https://skillicons.dev/icons?i=";
// WordPressテーマなど、サブディレクトリから読み込む場合のパス接頭辞
// (テーマ側で window.TAKUMI_BASE を定義する)
const BASE = window.TAKUMI_BASE || "";

/* ---------- Workページ: Field Records(実績インデックス) ---------- */
const PLUS_SVG =
  '<svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>';

const CLOSE_SVG =
  '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>';

// トップと Work ページの両方に置かれる。ID ではなくクラスで拾う。
const recordLists = [...document.querySelectorAll(".work-records")];

if (recordLists.length) {
  recordLists.forEach((list) => {
    // サーバー側(WordPress)で描画済みならクライアント描画をスキップ
    if (list.dataset.source === "server") return;
    list.innerHTML = WORKS.map((w, i) => recordMarkup(w, i + 1)).join("");
  });

  // 先にフィルタを組み立てる。ハッシュ着地で Reset を押す場合があるため。
  initFilters();
  recordLists.forEach(initRecords);
}

function recordMarkup(w, index) {
  const id = `work-${w.id}`;
  const gallery = (w.images || [])
    .map((src) => `<img src="${BASE}${src}" alt="${w.title}" loading="lazy">`)
    .join("");
  const icons = (w.icons || [])
    .map((i) => `<img src="${ICON_BASE}${i}" alt="${i}" loading="lazy">`)
    .join("");
  const links = [
    w.url && `<a class="btn" href="${w.url}" target="_blank" rel="noopener">Visit Site</a>`,
    w.github && `<a class="btn btn--gold" href="${w.github}" target="_blank" rel="noopener">GitHub</a>`,
  ]
    .filter(Boolean)
    .join("");

  return `
    <div class="work-record-item" id="${id}"
      data-year="${w.year}" data-tech="${(w.tech||[]).join(",")}" data-type="${(w.type||[]).join(",")}" data-category="${w.category || ''}">
      <button type="button" class="work-record" aria-haspopup="dialog">
        <span class="record-index">${String(index).padStart(2, "0")}</span>
        <span class="record-title">
          <small>${recordLabel(w)}</small>
          <strong>${w.title}</strong>
          ${w.meta ? `<em>${w.meta}</em>` : ""}
        </span>
        <span class="record-metric">${recordMetric(w)}</span>
        <span class="record-arrow record-arrow--mark" aria-hidden="true">${PLUS_SVG}</span>
      </button>
      <div class="work-record__panel" id="${id}-panel">
        <div class="work-record__inner">
          <div class="work-detail">
            ${gallery ? `<div class="work-detail__gallery">${gallery}</div>` : ""}
            <div class="work-detail__body">
              ${w.role ? `<p class="work-detail__role"><strong>担当:</strong> ${w.role}</p>` : ""}
              <div class="work-detail__desc"><p>${w.description}</p></div>
              <div class="work-detail__tags">${w.tech.map((t) => `<span>${t}</span>`).join("")}</div>
              ${icons ? `<div class="work-detail__icons">${icons}</div>` : ""}
              ${links ? `<div class="work-detail__links">${links}</div>` : ""}
            </div>
          </div>
        </div>
      </div>
    </div>`;
}

function recordLabel(w) {
  return w.label || (w.tech.length ? w.tech.slice(0, 3).join(" × ").toUpperCase() : "WORK");
}
function recordMetric(w) {
  if (w.metric) return w.metric;
  const role = (w.role || "").split(/[・、/]/).map((t) => t.trim()).filter(Boolean);
  return role.length ? role[0].toUpperCase() : String(w.year);
}

/* ---------- 詳細モーダル ----------
   ページに 1 枚だけ置き、開くたびに対象レコードの .work-detail を
   そのまま差し込む。複製ではなく移動なので、画像の読み込み状態も
   id の一意性もそのまま保てる。閉じたら元の位置へ戻す。
   <dialog> を使うのは、フォーカストラップ・Esc・最前面表示を
   ブラウザ側に任せられるため。 */
let modalEl = null;
let modalOrigin = null; // 中身を戻す先(.work-record__inner)
let modalOpener = null; // 閉じたあとフォーカスを返すボタン

function ensureModal() {
  if (modalEl) return modalEl;

  modalEl = document.createElement("dialog");
  modalEl.className = "work-modal";
  modalEl.setAttribute("aria-labelledby", "work-modal-title");
  modalEl.innerHTML = `
    <div class="work-modal__panel">
      <div class="work-modal__head">
        <span class="work-modal__index"></span>
        <span class="work-modal__heading">
          <small class="work-modal__label"></small>
          <strong class="work-modal__title" id="work-modal-title"></strong>
          <em class="work-modal__meta"></em>
        </span>
        <button type="button" class="work-modal__close" aria-label="閉じる">${CLOSE_SVG}</button>
      </div>
      <div class="work-modal__body"></div>
    </div>`;
  document.body.appendChild(modalEl);

  modalEl.querySelector(".work-modal__close").addEventListener("click", () => modalEl.close());

  // パネルの外側(=バックドロップ)のクリックで閉じる
  modalEl.addEventListener("click", (e) => {
    if (!e.target.closest(".work-modal__panel")) modalEl.close();
  });

  // Esc・閉じるボタンのどちらで閉じても、後片付けは 1 か所にまとめる
  modalEl.addEventListener("close", releaseModal);

  return modalEl;
}

function openModal(entry) {
  const modal = ensureModal();
  const detail = entry.panel.querySelector(".work-detail");
  if (!detail) return;

  // すでに別のレコードが開いていれば、閉じ切ってから開き直す。
  // close イベントは非同期に飛ぶので、片付けを待たずに差し替えると
  // 新しい中身のほうが元の位置へ戻されてしまう。
  if (modal.open) {
    modal.addEventListener("close", () => openModal(entry), { once: true });
    modal.close();
    return;
  }

  const title = entry.toggle.querySelector(".record-title");
  const set = (sel, text) => {
    const el = modal.querySelector(sel);
    el.textContent = text || "";
    el.hidden = !text;
  };
  set(".work-modal__index", entry.toggle.querySelector(".record-index")?.textContent.trim());
  set(".work-modal__label", title?.querySelector("small")?.textContent.trim());
  set(".work-modal__title", title?.querySelector("strong")?.textContent.trim());
  set(".work-modal__meta", title?.querySelector("em")?.textContent.trim());

  modalOrigin = entry.panel.querySelector(".work-record__inner");
  modalOpener = entry.toggle;
  modal.querySelector(".work-modal__body").appendChild(detail);
  modal.querySelector(".work-modal__body").scrollTop = 0;

  entry.item.classList.add("is-open");
  document.body.classList.add("is-modal-open");
  modal.showModal();

  history.replaceState(null, "", "#" + entry.item.id);
}

function releaseModal() {
  if (!modalEl) return;

  const detail = modalEl.querySelector(".work-detail");
  if (detail && modalOrigin) modalOrigin.appendChild(detail);
  modalOrigin = null;

  document.querySelectorAll(".work-record-item.is-open").forEach((item) => item.classList.remove("is-open"));
  document.body.classList.remove("is-modal-open");

  if (modalOpener) {
    // 開いた行へフォーカスを返す。一覧を辿り直さずに続きを読める
    modalOpener.focus({ preventScroll: true });
    modalOpener = null;
    history.replaceState(null, "", location.pathname + location.search);
  }
}

/* ---------- レコードのクリック ----------
   モーダルは JS が動いているときだけ。無効な環境では
   .work-record__panel が開いたまま、その場で詳細が読める。 */
function initRecords(records) {
  const items = [...records.querySelectorAll(".work-record-item")];
  if (!items.length) return;

  records.classList.add("is-collapsible");

  const panels = items.map((item) => ({
    item,
    toggle: item.querySelector(".work-record"),
    panel: item.querySelector(".work-record__panel"),
  }));

  panels.forEach((entry) => {
    // 畳んだパネルはタブ移動の対象から外す(中身はモーダル側で読む)
    entry.panel.inert = true;
    entry.toggle.addEventListener("click", () => openModal(entry));
  });

  // ハッシュ付きで着地したら、その行の詳細をそのまま開く
  openFromHash(panels);
  window.addEventListener("hashchange", () => openFromHash(panels));
}

function openFromHash(panels) {
  const id = decodeURIComponent(location.hash.slice(1));
  if (!id.startsWith("work-")) return;

  const entry = panels.find((p) => p.item.id === id);
  if (!entry) return;

  // フィルタで隠れている場合は絞り込みを解除してから開く
  if (entry.item.classList.contains("is-hidden")) {
    document.querySelector(".filter-reset")?.click();
  }
  openModal(entry);
}

/* ---------- フィルタ ----------
   1 行 = 1 軸(Type / Tech / Year)。各軸はひとつだけ選べ、軸どうしは AND。
   ボタンの選択肢はテンプレート側が実データから出しているので、ここでは
   件数を数えて 0 件のボタンを畳み、表示の出し入れだけを受け持つ。 */
function initFilters() {
  const bar = document.querySelector(".records-filter");
  const items = [...document.querySelectorAll(".work-record-item")];
  if (!bar || !items.length) return;

  const EASE = "cubic-bezier(0.16, 1, 0.3, 1)";
  const shownEl = bar.querySelector(".records-count strong");
  const totalEl = bar.querySelector(".records-count__total");
  const empty = document.querySelector(".works-empty");
  const reset = bar.querySelector(".filter-reset");

  const pad = (n) => String(n).padStart(2, "0");
  const reduced = () => window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  const valuesOf = (item, group) =>
    (item.dataset[group] || "").split(",").map((v) => v.trim()).filter(Boolean);

  // 状態は「軸ごとに 1 つ」。all は絞り込みなし
  const state = {};

  const groups = [...bar.querySelectorAll(".records-filter__row")]
    .map((row) => {
      const track = row.querySelector(".records-filter__track");
      const group = track.dataset.group;
      const buttons = [...track.querySelectorAll(".filter-btn")];
      state[group] = "all";

      // 件数はカードから数える。1 件も無い値のボタンは出さない
      buttons.forEach((btn) => {
        if (btn.dataset.value === "all") return;
        const hits = items.filter((item) => valuesOf(item, group).includes(btn.dataset.value)).length;
        if (!hits) {
          btn.hidden = true;
          return;
        }
        const num = btn.querySelector(".filter-btn__num");
        if (num) num.textContent = pad(hits);
      });

      const visible = buttons.filter((btn) => !btn.hidden);
      // All しか残らない軸は、絞り込む意味がないので行ごと畳む
      if (visible.length < 2) row.hidden = true;

      return { row, group, track, ink: track.querySelector(".records-filter__ink"), buttons: visible };
    })
    .filter((g) => !g.row.hidden);

  if (!groups.length) {
    bar.hidden = true;
    return;
  }

  function moveInk(g) {
    const active = g.buttons.find((btn) => btn.classList.contains("is-active"));
    if (!g.ink || !active) return;
    // 幅 1px の帯を scaleX で伸ばす。レイアウトを触らないので滑らかに動く
    g.ink.style.transform = `translateX(${active.offsetLeft}px) scaleX(${active.offsetWidth})`;
  }

  /* --- FLIP。詰まるカードを transform だけで滑らせる --- */
  function flip(before) {
    items.forEach((item, i) => {
      if (item.classList.contains("is-hidden") || !item.animate) return;

      if (before[i].hidden) {
        // 新しく現れたカードは、少し持ち上げた位置から差し込む
        item.animate(
          [
            { opacity: 0, transform: "translateY(14px)" },
            { opacity: 1, transform: "none" },
          ],
          { duration: 420, easing: EASE }
        );
        return;
      }

      const delta = before[i].top - item.getBoundingClientRect().top;
      if (Math.abs(delta) < 1) return;
      item.animate([{ transform: `translateY(${delta}px)` }, { transform: "none" }], {
        duration: 460,
        easing: EASE,
      });
    });
  }

  function apply(animate) {
    // FLIP の First：入れ替え前の位置と表示状態を控える
    const before =
      animate && !reduced()
        ? items.map((item) => ({
            hidden: item.classList.contains("is-hidden"),
            top: item.getBoundingClientRect().top,
          }))
        : null;

    let shown = 0;
    items.forEach((item) => {
      const hit = Object.keys(state).every(
        (group) => state[group] === "all" || valuesOf(item, group).includes(state[group])
      );
      item.classList.toggle("is-hidden", !hit);
      if (hit) shown++;
    });

    if (shownEl) shownEl.textContent = pad(shown);
    if (empty) empty.style.display = shown ? "none" : "block";
    if (before) flip(before);
  }

  function select(g, btn, animate, refresh = true) {
    g.buttons.forEach((b) => {
      const on = b === btn;
      b.classList.toggle("is-active", on);
      b.setAttribute("aria-pressed", on ? "true" : "false");
      // role="toolbar" の作法。Tab では帯に 1 回だけ入り、中は矢印キーで移動する
      b.tabIndex = on ? 0 : -1;
    });
    state[g.group] = btn.dataset.value;
    moveInk(g);
    if (refresh) apply(animate);
  }

  groups.forEach((g) => {
    g.track.addEventListener("click", (e) => {
      const btn = e.target.closest(".filter-btn");
      if (!btn || !g.track.contains(btn)) return;
      select(g, btn, true);
    });

    g.track.addEventListener("keydown", (e) => {
      const i = g.buttons.indexOf(document.activeElement);
      if (i < 0) return;

      const last = g.buttons.length - 1;
      let next;
      if (e.key === "ArrowRight" || e.key === "ArrowDown") next = i === last ? 0 : i + 1;
      else if (e.key === "ArrowLeft" || e.key === "ArrowUp") next = i === 0 ? last : i - 1;
      else if (e.key === "Home") next = 0;
      else if (e.key === "End") next = last;
      else return;

      e.preventDefault();
      g.buttons[next].focus();
    });
  });

  // 実績インデックスからのジャンプもこのボタンを押して絞り込みを解除する
  reset?.addEventListener("click", () => {
    groups.forEach((g) => select(g, g.buttons[0], false, false));
    apply(true);
  });

  // 下線は文字幅に依存する。フォント差し替えとリサイズのあとに測り直す
  const refreshInk = () => groups.forEach((g) => moveInk(g));
  window.addEventListener("resize", refreshInk);
  if (document.fonts && document.fonts.ready) document.fonts.ready.then(refreshInk);

  if (totalEl) totalEl.textContent = pad(items.length);
  if (shownEl) shownEl.textContent = pad(items.length);
  refreshInk();
}
