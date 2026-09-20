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
    status: "非公開",
    challenge:
      "文化祭の展示作品として花詞データベースを作ることになりましたが、花言葉を並べるだけでは来場者が目的の花にたどり着けません。当時はデータベースの知識がなく、データはすべて手入力で組み立てるしかない状態でした。",
    approach:
      "約50種類の花について、花言葉だけでなく学名・由来・原産地まで自ら調査してHTMLに整理しました。そのうえでJavaScriptを使い、「季節」「色」「神話由来」といったテーマごとに絞り込める仕組みを実装しています。",
    result:
      "来場者が目的や興味に合わせて花を探せる形になりました。情報の分類方法と検索軸を工夫して「情報を活用しやすい形に整える」ことを意識した経験は、のちのデータベース学習とUI設計の理解につながっています。",
    description: "",
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
    status: "非公開",
    challenge:
      "株式会社若鯱家様の既存サイトは既存顧客向けの内容が中心で、若年層の集客が課題になっていました。",
    approach:
      "チームで若年層を対象にアンケートを実施し、求められる情報と興味を引くアプローチを整理しました。その結果をもとにデザイン・情報設計・機能を決め、リーダーとして進行とフロントのコーディングを担当しています。",
    result:
      "調査で得た事実を根拠にデザインと情報設計を組み立てる進め方を、産学連携のチーム制作の中で実践できました。",
    description: "",
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
    status: "非公開",
    challenge:
      "企業様との産学連携プロジェクトで、要件定義からリリースまでをチームで完走する必要がありました。",
    approach:
      "チームリーダーとして要件定義・設計・実装・リリースまでのフルサイクルを指揮し、Django と Docker を用いてWebシステムを構築しました。",
    result:
      "企画からリリースまでを一貫して経験したことで、チーム開発の進行管理と技術選定の判断力を養いました。",
    description: "",
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
    status: "制作中",
    description:
      "実案件として受注したコーポレートサイト制作です。WordPress をベースに、フロントエンドからバックエンドまでのコーディングを担当しています。",
    note: "制作中の案件のため、公開URLはまだありません。進捗や実装の中身は面談時にご紹介できます。",
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
    status: "公開中",
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
    status: "掲載のみ承認",
    description: "クライアント案件として制作に参加したWebサイトです。",
    note: "掲載のみ承認いただいている案件のため、URLとソースは公開していません。担当範囲や実装の詳細は面談時にご紹介できます。",
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
    status: "非公開",
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
    status: "非公開",
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

// 区分キーを画面に出す日本語へ。テンプレート側(functions.php)と同じ対応表。
const CATEGORY_LABELS = {
  personal: "個人制作",
  company: "企業・実案件",
  team: "チーム制作",
  school: "学校制作",
};

const esc = (v) =>
  String(v ?? "").replace(/[&<>"']/g, (c) => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c]));

/* ---------- Workページ: Field Records(実績インデックス) ---------- */
const PLUS_SVG =
  '<svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>';

const CLOSE_SVG =
  '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>';

/* 詳細の中身。テンプレート側の takumi_render_work_detail と同じ構造を書く。
   左に画像、右は バッジ → 事実 → 課題/やったこと/結果 → 技術 → リンク の順。 */
function detailMarkup(w) {
  const images = w.images || [];
  const altOf = (i) => `${w.title} の画像 ${i + 1}`;

  const visual = images.length
    ? `<figure class="work-detail__visual">
         <div class="work-detail__stage">
           <img class="work-detail__main" src="${BASE}${images[0]}" alt="${esc(w.title)} のメインビジュアル">
         </div>
         ${
           images.length > 1
             ? `<div class="work-detail__thumbs" role="tablist" aria-label="${esc(w.title)} の画像">
                  ${images
                    .map(
                      (src, i) =>
                        `<button type="button" class="work-detail__thumb${i === 0 ? " is-active" : ""}" role="tab" aria-selected="${i === 0}" data-src="${BASE}${src}" data-alt="${esc(altOf(i))}"><img src="${BASE}${src}" alt="${esc(altOf(i))}" loading="lazy"></button>`
                    )
                    .join("")}
                </div>`
             : ""
         }
       </figure>`
    : "";

  // 公開状態は status があればそれ、無ければリンクの有無から推定する。
  const status = w.status || (!w.url && !w.github ? "非公開" : "");
  // 制作年はモーダル見出しのサブタイトルに出ているので、ここでは繰り返さない。
  const badges = [
    w.category && ["category", CATEGORY_LABELS[w.category] || w.category],
    status && ["status", status],
  ]
    .filter(Boolean)
    .map(([kind, text]) => `<span class="work-badge work-badge--${kind}">${esc(text)}</span>`)
    .join("");

  const facts = [
    ["期間", w.period],
    ["体制", w.team],
    ["担当", w.role],
  ]
    .filter(([, v]) => v)
    .map(([k, v]) => `<div class="work-detail__fact"><dt>${k}</dt><dd>${esc(v)}</dd></div>`)
    .join("");

  const chapters = [
    ["課題", w.challenge],
    ["やったこと", w.approach],
    ["結果・学び", w.result],
  ].filter(([, v]) => v);

  // 課題/やったこと/結果 が一件も無ければ、説明文を「概要」として出す。
  const blocks = chapters.length ? chapters : w.description ? [["概要", w.description]] : [];
  const story = blocks
    .map(
      ([label, text]) =>
        `<section class="work-story">
           <h4 class="work-story__head">${label}</h4>
           <p class="work-story__text">${esc(text)}</p>
         </section>`
    )
    .join("");
  const extra = chapters.length && w.description ? `<div class="work-detail__desc"><p>${esc(w.description)}</p></div>` : "";

  // アイコンがあるときはアイコンだけ。同じ内容をタグでも出すと二重になる。
  const icons = (w.icons || [])
    .map((i) => `<img src="${ICON_BASE}${i}" alt="${esc(i)}" title="${esc(i)}" loading="lazy">`)
    .join("");
  const tags = (w.tech || []).map((t) => `<span>${esc(t)}</span>`).join("");
  const stack =
    icons || tags
      ? `<div class="work-detail__stack">
           <p class="work-detail__stack-label">Tech Stack</p>
           ${icons ? `<div class="work-detail__icons">${icons}</div>` : `<div class="work-detail__tags">${tags}</div>`}
         </div>`
      : "";

  const links = [
    w.url && `<a class="btn" href="${w.url}" target="_blank" rel="noopener">Visit Site</a>`,
    w.github && `<a class="btn btn--gold" href="${w.github}" target="_blank" rel="noopener">GitHub</a>`,
  ]
    .filter(Boolean)
    .join("");

  // リンクが無い案件で行き止まりにしない。理由を置いて次の行動につなげる。
  const footer = links
    ? `<div class="work-detail__links">${links}</div>`
    : `<p class="work-detail__nolink">${esc(w.note || "公開URLのない案件です。画面や実装の詳細は面談時にご紹介できます。")}</p>`;

  return `
    <div class="work-detail">
      ${visual}
      <div class="work-detail__body">
        ${badges ? `<div class="work-detail__badges">${badges}</div>` : ""}
        ${stack}
        ${facts ? `<dl class="work-detail__facts">${facts}</dl>` : ""}
        ${story ? `<div class="work-detail__story">${story}</div>` : ""}
        ${extra}
        ${footer}
      </div>
    </div>`;
}

function recordMarkup(w, index) {
  const id = `work-${w.id}`;

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
          ${detailMarkup(w)}
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

  // すでに何か開いていれば、閉じ切ってから開き直す。
  // close イベントは非同期に飛ぶので、片付けを待たずに差し替えると
  // 新しい中身のほうが元の位置へ戻されてしまう。
  // 中身(.work-detail)を探すのはこの後。開いている間その中身はモーダル側へ
  // 移っていて元の位置には無いので、先に探すと同じ行を開き直せなくなる。
  if (modal.open) {
    modal.addEventListener("close", () => openModal(entry), { once: true });
    modal.close();
    return;
  }

  const detail = entry.panel.querySelector(".work-detail");
  if (!detail) return;

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

/* ---------- 詳細ギャラリー ----------
   サムネイルを押すとメイン画像が差し替わる。詳細ノードはモーダルへ
   出し入れされるので、個別に addEventListener せず document で受ける。 */
document.addEventListener("click", (e) => {
  const thumb = e.target.closest(".work-detail__thumb");
  if (!thumb) return;

  const detail = thumb.closest(".work-detail");
  const main = detail?.querySelector(".work-detail__main");
  if (!main) return;

  detail.querySelectorAll(".work-detail__thumb").forEach((btn) => {
    btn.classList.toggle("is-active", btn === thumb);
    btn.setAttribute("aria-selected", btn === thumb ? "true" : "false");
  });

  // 差し替え時だけ軽くフェードさせる。読み込み済みなら即座に戻る。
  main.classList.add("is-swapping");
  main.src = thumb.dataset.src;
  main.alt = thumb.dataset.alt || main.alt;
  main.decode?.().catch(() => {}).finally(() => main.classList.remove("is-swapping"));
});

// tablist なので ← → でも選べるようにする。
document.addEventListener("keydown", (e) => {
  if (e.key !== "ArrowLeft" && e.key !== "ArrowRight") return;

  const thumb = e.target.closest?.(".work-detail__thumb");
  if (!thumb) return;

  const thumbs = [...thumb.closest(".work-detail__thumbs").querySelectorAll(".work-detail__thumb")];
  const next = thumbs[(thumbs.indexOf(thumb) + (e.key === "ArrowRight" ? 1 : -1) + thumbs.length) % thumbs.length];
  e.preventDefault();
  next.focus();
  next.click();
});

/* ---------- 起動 ----------
   ここはファイルのいちばん最後に置くこと。
   関数宣言は巻き上げられるが、let で持っているモーダルの状態(modalEl など)は
   宣言の行を通るまで初期化されない。上のほうで起動すると、ハッシュ付きで着地した
   ときだけ openFromHash → openModal → ensureModal と辿って modalEl に触れてしまい、
   「Cannot access 'modalEl' before initialization」でモジュールの評価ごと止まる。
   そうなると hashchange の登録もされず、以降そのページではクリックしても開かない。 */

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
