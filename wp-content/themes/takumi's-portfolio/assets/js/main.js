/* ============================================================
   共通スクリプト — ローダー / ナビ / スクロール演出
   ============================================================ */

/* ---------- ローダー ---------- */
window.addEventListener("load", () => {
  const loader = document.querySelector(".loader");
  if (loader) {
    setTimeout(() => loader.classList.add("is-done"), 500);
  }
});
// 読み込みが長引いても最悪 3.5 秒で解除する
setTimeout(() => {
  document.querySelector(".loader")?.classList.add("is-done");
}, 3500);

/* ---------- ヘッダー(スクロールで背景付与) ---------- */
const header = document.querySelector(".site-header");
function onScrollHeader() {
  header?.classList.toggle("is-scrolled", window.scrollY > 40);
}
window.addEventListener("scroll", onScrollHeader, { passive: true });
onScrollHeader();

/* ---------- ハンバーガーメニュー ---------- */
const navToggle = document.querySelector(".nav-toggle");
const globalNav = document.querySelector(".global-nav");
navToggle?.addEventListener("click", () => {
  const open = navToggle.classList.toggle("is-open");
  globalNav?.classList.toggle("is-open", open);
  document.body.style.overflow = open ? "hidden" : "";
});
globalNav?.querySelectorAll("a").forEach((a) =>
  a.addEventListener("click", () => {
    navToggle?.classList.remove("is-open");
    globalNav.classList.remove("is-open");
    document.body.style.overflow = "";
  })
);

/* ---------- スクロールリビール ---------- */
const revealObserver = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("is-visible");
        revealObserver.unobserve(entry.target);
      }
    });
  },
  { threshold: 0.15, rootMargin: "0px 0px -40px 0px" }
);
document.querySelectorAll("[data-reveal]").forEach((el) => revealObserver.observe(el));

/* ---------- スキルバー ---------- */
const barObserver = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const fill = entry.target;
        fill.style.width = (fill.dataset.width || 0) + "%";
        barObserver.unobserve(fill);
      }
    });
  },
  { threshold: 0.4 }
);
document.querySelectorAll(".skill-bar .fill").forEach((el) => barObserver.observe(el));

/* ---------- ページトップ ---------- */
const pagetop = document.querySelector(".pagetop");
window.addEventListener(
  "scroll",
  () => pagetop?.classList.toggle("is-visible", window.scrollY > 600),
  { passive: true }
);
pagetop?.addEventListener("click", () => window.scrollTo({ top: 0, behavior: "smooth" }));

/* ---------- GSAP 演出(読み込まれている場合のみ) ---------- */
if (window.gsap && window.ScrollTrigger) {
  gsap.registerPlugin(ScrollTrigger);
  const reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  if (!reduced) {
    // ヒーローの見出しを1文字ずつ、ばらばらの向きから飛び込ませる
    const title = document.querySelector(".home-hero__title");
    if (title) {
      const chars = [];
      title.querySelectorAll("span").forEach((line) => {
        const text = line.textContent;
        line.textContent = "";
        text.split("").forEach((ch) => {
          const el = document.createElement("span");
          el.className = "char";
          el.textContent = ch === " " ? "\u00a0" : ch;
          el.style.display = "inline-block";
          el.style.willChange = "transform";
          line.appendChild(el);
          chars.push(el);
        });
      });

      chars.forEach((c) =>
        gsap.set(c, {
          opacity: 0,
          y: gsap.utils.random(80, 160),
          x: gsap.utils.random(-40, 40),
          rotate: gsap.utils.random(-140, 140),
          scale: 0.35,
        })
      );

      gsap
        .timeline({ defaults: { ease: "back.out(2.2)" }, delay: 0.3 })
        .to(chars, {
          opacity: 1,
          y: 0,
          x: 0,
          rotate: 0,
          scale: 1,
          duration: 1,
          stagger: { each: 0.028, from: "random" },
        })
        // 一度だけ大きく回してから収める
        .to(chars, {
          rotate: "+=360",
          duration: 0.6,
          stagger: { each: 0.015, from: "center" },
          ease: "power1.inOut",
        }, "-=0.35")
        .from(".home-hero__name", { opacity: 0, y: 26, duration: 0.7, ease: "power2.out" }, "-=0.3")
        .from(".home-hero__lead", { opacity: 0, y: 26, duration: 0.6, ease: "power2.out" }, "-=0.45")
        .from(".home-stats__item", { opacity: 0, y: 26, scale: 0.9, duration: 0.6, stagger: 0.08, ease: "back.out(2)" }, "-=0.4")
        .from(".home-index a", { opacity: 0, y: 26, scale: 0.94, duration: 0.55, stagger: 0.07, ease: "back.out(2)" }, "-=0.35")
        .from(".blob", { opacity: 0, scale: 0.1, rotate: 180, duration: 1.1, stagger: 0.1, ease: "back.out(2.4)" }, "-=1.4")
        .from(".home-hero__inner > .page-hero__label", { opacity: 0, x: -30, duration: 0.5 }, "-=1.5");

      // 差し色の行だけ、ゆっくり揺らし続ける
      title.querySelectorAll(".is-accent .char").forEach((c, i) => {
        gsap.to(c, {
          rotate: gsap.utils.random(-10, 10),
          y: gsap.utils.random(-4, 4),
          duration: gsap.utils.random(1.6, 2.6),
          repeat: -1,
          yoyo: true,
          ease: "sine.inOut",
          delay: 1.6 + i * 0.04,
        });
      });
    }

    // ブロブを大きく漂わせる
    [
      [".blob--1", { y: 60, x: -40, rotate: 40, duration: 5 }],
      [".blob--2", { y: -50, x: 46, rotate: -55, duration: 4 }],
      [".blob--3", { y: 44, rotate: 130, scale: 1.15, duration: 3.4 }],
      [".blob--4", { y: -70, x: 30, duration: 5.6 }],
      [".blob--5", { y: -36, x: -24, rotate: 90, duration: 4.6 }],
    ].forEach(([sel, conf]) => {
      if (document.querySelector(sel)) {
        gsap.to(sel, Object.assign({ repeat: -1, yoyo: true, ease: "sine.inOut" }, conf));
      }
    });

    // スキルのパネルを横スクロールさせる(縦スクロールに連動してピン留め)
    const hTrack = document.getElementById("hscroll-track");
    if (hTrack) {
      const distance = hTrack.scrollWidth - window.innerWidth + 64;
      if (distance > 0) {
        gsap.to(hTrack, {
          x: -distance,
          ease: "none",
          scrollTrigger: {
            trigger: "#hscroll",
            start: "top top",
            end: "+=" + (distance + window.innerHeight * 0.6),
            scrub: 1,
            pin: true,
            invalidateOnRefresh: true,
          },
        });

        // 横に流れるだけにならないよう、パネルをわずかに起こす
        gsap.utils.toArray(".hpanel").forEach((panel, i) => {
          gsap.fromTo(
            panel,
            { rotate: i % 2 === 0 ? -6 : 6, scale: 0.92 },
            {
              rotate: 0,
              scale: 1,
              ease: "none",
              scrollTrigger: {
                trigger: "#hscroll",
                start: "top top",
                end: "+=" + hTrack.scrollWidth,
                scrub: true,
              },
            }
          );
        });
      }
    }

    // Statement: ピン留めして巨大テキストを横へ送る
    const stTrack = document.getElementById("statement-track");
    if (stTrack) {
      const distance = stTrack.scrollWidth - window.innerWidth + 64;
      if (distance > 0) {
        gsap.to(stTrack, {
          x: -distance,
          ease: "none",
          scrollTrigger: {
            trigger: "#statement",
            start: "top top",
            end: "+=" + (distance + window.innerHeight * 0.5),
            scrub: 1,
            pin: true,
            invalidateOnRefresh: true,
          },
        });

        // 図形は横送りに合わせて回しておく
        gsap.utils.toArray(".statement__shape").forEach((shape, i) => {
          gsap.to(shape, {
            rotate: i % 2 === 0 ? 220 : -200,
            ease: "none",
            scrollTrigger: {
              trigger: "#statement",
              start: "top top",
              end: "+=" + distance,
              scrub: true,
            },
          });
        });

        // 語ごとに縦のズレをつけて、平坦に流れないようにする
        gsap.utils.toArray(".statement__word").forEach((word, i) => {
          gsap.fromTo(
            word,
            { y: i % 2 === 0 ? 40 : -40 },
            {
              y: 0,
              ease: "none",
              scrollTrigger: {
                trigger: "#statement",
                start: "top top",
                end: "+=" + distance * 0.6,
                scrub: true,
              },
            }
          );
        });
      }
    }

    // レコード行を3Dで起こしながら出す
    const records = gsap.utils.toArray(".work-record");
    records.forEach((row, i) =>
      gsap.set(row, {
        opacity: 0,
        y: 70,
        rotateX: -50,
        x: i % 2 === 0 ? -60 : 60,
        transformOrigin: "50% 0%",
      })
    );
    if (records.length) {
      ScrollTrigger.batch(records, {
        start: "top 88%",
        onEnter: (batch) =>
          gsap.to(batch, {
            opacity: 1,
            y: 0,
            x: 0,
            rotateX: 0,
            duration: 0.85,
            stagger: 0.12,
            ease: "back.out(1.8)",
          }),
      });
    }

    // カード類はふわっと
    const cards = gsap.utils.toArray(".skill-card, .home-index a");
    if (cards.length) {
      ScrollTrigger.batch(cards, {
        start: "top 90%",
        onEnter: (batch) =>
          gsap.from(batch, {
            opacity: 0,
            y: 40,
            rotate: -1.5,
            duration: 0.6,
            stagger: 0.08,
            ease: "back.out(1.5)",
            overwrite: true,
          }),
      });
    }

    // セクション見出しのライン演出
    document.querySelectorAll(".section-head").forEach((headEl) => {
      gsap.from(headEl, {
        scrollTrigger: { trigger: headEl, start: "top 85%" },
        y: 30,
        opacity: 0,
        duration: 1,
        ease: "power3.out",
      });
    });
  }
}


/* ---------- マグネットボタン / レコードのホバー演出 ---------- */
if (window.gsap && !window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
  document.querySelectorAll(".btn").forEach((btn) => {
    btn.addEventListener("mousemove", (e) => {
      const r = btn.getBoundingClientRect();
      const relX = e.clientX - r.left - r.width / 2;
      gsap.to(btn, {
        x: relX * 0.4,
        y: (e.clientY - r.top - r.height / 2) * 0.6,
        rotate: relX * 0.04,
        duration: 0.4,
        ease: "power2.out",
      });
    });
    btn.addEventListener("mouseleave", () => {
      gsap.to(btn, { x: 0, y: 0, rotate: 0, duration: 0.6, ease: "elastic.out(1, 0.35)" });
    });
  });

  // 塗りつぶしは CSS の ::before に任せ、中の要素だけ GSAP で動かす
  document.querySelectorAll(".work-record").forEach((row) => {
    const index = row.querySelector(".record-index");
    const arrow = row.querySelector(".record-arrow");
    const title = row.querySelector(".record-title strong");

    row.addEventListener("mouseenter", () => {
      row.classList.add("is-hover");
      gsap.to(index, { rotate: -18, scale: 1.15, duration: 0.35, ease: "back.out(2.5)" });
      gsap.to(arrow, { rotate: 45, scale: 1.1, duration: 0.4, ease: "back.out(2.5)" });
      gsap.to(title, { x: 10, duration: 0.35, ease: "power2.out" });
      gsap.to(row, { paddingLeft: 18, duration: 0.35, ease: "power2.out" });
    });
    row.addEventListener("mouseleave", () => {
      row.classList.remove("is-hover");
      gsap.to(index, { rotate: 0, scale: 1, duration: 0.4, ease: "power2.out" });
      gsap.to(arrow, { rotate: 0, scale: 1, duration: 0.4, ease: "power2.out" });
      gsap.to(title, { x: 0, duration: 0.4, ease: "power2.out" });
      gsap.to(row, { paddingLeft: 0, duration: 0.4, ease: "power2.out" });
    });
  });
}
