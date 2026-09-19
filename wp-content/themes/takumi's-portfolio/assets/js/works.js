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
  },
];

const ICON_BASE = "https://skillicons.dev/icons?i=";

// WordPressテーマなど、サブディレクトリから読み込む場合のパス接頭辞
// (テーマ側で window.TAKUMI_BASE を定義する)
const BASE = window.TAKUMI_BASE || "";
const WORK_PAGE = window.TAKUMI_WORK_URL || "Work.html";

/* ---------- Workページ: 全詳細を常時表示するリスト描画 ---------- */
const grid = document.getElementById("works-grid");
if (grid) {
  // サーバー側(WordPress)で描画済みならクライアント描画をスキップ
  if (grid.dataset.source !== "server") {
    grid.innerHTML = WORKS.map((w) => {
      const gallery = w.images
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
      <article class="work-row" id="work-${w.id}"
        data-year="${w.year}" data-tech="${w.tech.join(",")}" data-type="${w.type.join(",")}">
        <div class="work-row__gallery">${gallery}</div>
        <div class="work-row__body">
          <h3>${w.title}</h3>
          <p class="work-row__meta">${w.meta}</p>
          <p class="work-row__role"><strong>担当:</strong> ${w.role}</p>
          <p class="work-row__desc">${w.description}</p>
          <div class="work-row__tags">${w.tech.map((t) => `<span>${t}</span>`).join("")}</div>
          <div class="work-row__icons">${icons}</div>
          ${links ? `<div class="work-row__links">${links}</div>` : ""}
        </div>
      </article>`;
    }).join("");
  }

  initFilters();
}

/* ---------- Workページ: Field Records(実績インデックス) ---------- */
const ARROW_SVG =
  '<svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 7h10v10"></path><path d="M7 17 17 7"></path></svg>';

const records = document.getElementById("work-records");
if (records) {
  if (records.dataset.source !== "server") {
    records.innerHTML = WORKS.map(
      (w, i) => `
      <a class="work-record" href="#work-${w.id}">
        <span class="record-index">${String(i + 1).padStart(2, "0")}</span>
        <span class="record-title">
          <small>${recordLabel(w)}</small>
          <strong>${w.title}</strong>
          ${w.meta ? `<em>${w.meta}</em>` : ""}
        </span>
        <span class="record-metric">${recordMetric(w)}</span>
        <span class="record-arrow" aria-hidden="true">${ARROW_SVG}</span>
      </a>`
    ).join("");
  }

  initRecords();
}

// PHP側 takumi_work_record_parts() と同じ規則でラベル・指標を組み立てる
function recordLabel(w) {
  return w.label || (w.tech.length ? w.tech.slice(0, 3).join(" × ").toUpperCase() : "WORK");
}
function recordMetric(w) {
  if (w.metric) return w.metric;
  const role = (w.role || "").split(/[・、/]/).map((t) => t.trim()).filter(Boolean);
  return role.length ? role[0].toUpperCase() : String(w.year);
}

/* ---------- レコード → 詳細カードへのジャンプ ---------- */
function initRecords() {
  records.querySelectorAll(".work-record").forEach((rec) => {
    rec.addEventListener("click", (e) => {
      const id = decodeURIComponent(rec.getAttribute("href").slice(1));
      const target = document.getElementById(id);
      if (!target) return;
      e.preventDefault();

      // フィルタで隠れている場合は絞り込みを解除してから移動する
      if (target.classList.contains("is-hidden")) {
        document.querySelector(".filter-reset")?.click();
      }
      target.scrollIntoView({ behavior: "smooth", block: "start" });
      history.replaceState(null, "", "#" + id);

      target.classList.remove("is-flash");
      void target.offsetWidth; // アニメーションを再生し直すためのリフロー
      target.classList.add("is-flash");
    });
  });
}

/* ---------- フィルタ ---------- */
function initFilters() {
  const buttons = document.querySelectorAll(".filter-btn");
  const reset = document.querySelector(".filter-reset");
  const empty = document.querySelector(".works-empty");

  function activeValues(group) {
    return [...document.querySelectorAll(`.filter-btn.is-active[data-group="${group}"]`)].map(
      (b) => b.dataset.value
    );
  }

  function apply() {
    const types = activeValues("type");
    const techs = activeValues("tech");
    const years = activeValues("year");
    let shown = 0;

    document.querySelectorAll(".work-row").forEach((card) => {
      const cardTypes = card.dataset.type.split(",");
      const cardTechs = card.dataset.tech.split(",");
      const okType = !types.length || types.some((t) => cardTypes.includes(t));
      const okTech = !techs.length || techs.some((t) => cardTechs.includes(t));
      const okYear = !years.length || years.includes(card.dataset.year);
      const show = okType && okTech && okYear;
      card.classList.toggle("is-hidden", !show);
      if (show) shown++;
    });
    if (empty) empty.style.display = shown ? "none" : "block";
  }

  buttons.forEach((btn) =>
    btn.addEventListener("click", () => {
      btn.classList.toggle("is-active");
      apply();
    })
  );
  reset?.addEventListener("click", () => {
    buttons.forEach((b) => b.classList.remove("is-active"));
    apply();
  });
}

/* ---------- 別ページからハッシュ付きで着地したときも該当カードを光らせる ---------- */
window.addEventListener("load", () => {
  const id = decodeURIComponent(location.hash.slice(1));
  if (!id.startsWith("work-")) return;
  document.getElementById(id)?.classList.add("is-flash");
});
