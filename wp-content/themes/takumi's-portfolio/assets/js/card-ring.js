/* ============================================================
   カードリング — 円周に並べた写真を、ドラッグで回す
   ------------------------------------------------------------
   写真は本棚に並べた背表紙のように、円周の接線を向いて立っている。
   手前に来た1枚だけが正面を向き、大きく・明るく表示される。

   操作の考え方:
     ・ドラッグ中は指にカードを付ける(1:1)。指を離したら必ず1枚が正面で止まる。
     ・離したあとは自由に回さず、勢いから「行き先の1枚」を決めて、そこへ寄せる。
       どこで止まるか予測できるほうが、選ぶ操作としては速い。
     ・1回の勢いで飛べるのは最大3枚まで。振り回しても行方不明にならない。

   JS が動かないときは CSS 側の初期状態(横スクロールの一列)のまま。
   .is-ready を付けたときだけ 3D のリングに切り替わる。
   ============================================================ */
(function () {
  const rings = document.querySelectorAll(".card-ring");
  if (!rings.length) return;

  const reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  rings.forEach(setupRing);

  function setupRing(ring) {
    const stage = ring.querySelector(".card-ring__stage");
    const cards = Array.from(ring.querySelectorAll(".card-ring__card"));
    if (!stage || cards.length < 2) return;

    const count = cards.length;
    const step = 360 / count; // カード1枚ぶんの角度

    let angle = 0; // リング全体の回転角(度)。マイナス方向へ回すと次の1枚が手前に来る。
    let goal = null; // 寄せ先の角度。null ならドラッグ中で、行き先はまだ決まっていない。
    let radius = 0;
    let active = -1;
    let raf = null;
    let lastFrame = 0;

    /* ---------- 配置 ---------- */

    // 半径はカード幅と枚数から決める。隣とぶつからない最小値に少し余裕を足す。
    function measure() {
      const width = cards[0].offsetWidth || 240;
      const min = width / (2 * Math.tan(Math.PI / count));
      radius = Math.max(min * 1.18, width * 1.1);
      ring.style.setProperty("--ring-radius", radius.toFixed(1) + "px");
      cards.forEach((card, i) => {
        card.style.transform =
          "rotateY(" + (i * step).toFixed(3) + "deg) translateZ(" + radius.toFixed(1) + "px)";
      });
      render();
    }

    function render() {
      stage.style.transform =
        "translateZ(" + (-radius).toFixed(1) + "px) rotateY(" + angle.toFixed(3) + "deg)";

      // 各カードが正面からどれだけ外れているか(0=真横以上, 1=正面)。
      // CSS 側はこの値を受けて、奥のカードを暗く・小さくしている。
      cards.forEach((card, i) => {
        const delta = ((((i * step + angle) % 360) + 540) % 360) - 180; // -180〜180
        const facing = Math.max(0, Math.cos((delta * Math.PI) / 180));
        card.style.setProperty("--facing", facing.toFixed(3));
        card.style.zIndex = String(Math.round(facing * 100));
      });

      const index = ((Math.round(-angle / step) % count) + count) % count;
      if (index !== active) {
        active = index;
        cards.forEach((card, i) => {
          const on = i === index;
          card.classList.toggle("is-active", on);
          // 奥を向いているカードはタブ順から外す(見えないものに飛ばさないため)
          const link = card.querySelector(".card-ring__link");
          if (link) link.tabIndex = on ? 0 : -1;
        });
        announce(cards[index]);
      }
    }

    /* ---------- 見出し・カウンタ ---------- */

    const caption = ring.querySelector(".card-ring__caption");
    const capTitle = ring.querySelector(".card-ring__caption-title");
    const capLabel = ring.querySelector(".card-ring__caption-label");
    const capCount = ring.querySelector(".card-ring__count");
    const dots = Array.from(ring.querySelectorAll(".card-ring__dot"));

    function announce(card) {
      if (capTitle) capTitle.textContent = card.dataset.title || "";
      if (capLabel) capLabel.textContent = card.dataset.label || "";
      if (capCount) {
        capCount.textContent =
          String(active + 1).padStart(2, "0") + " / " + String(count).padStart(2, "0");
      }
      const link = caption && caption.querySelector("a");
      if (link) {
        const url = card.dataset.url || "";
        const external = card.dataset.external === "1";
        link.href = url || "#";
        link.hidden = !url;
        if (external) {
          link.target = "_blank";
          link.rel = "noopener";
        } else {
          link.removeAttribute("target");
          link.removeAttribute("rel");
        }
      }
      dots.forEach((dot, i) => {
        dot.classList.toggle("is-on", i === active);
        dot.setAttribute("aria-current", i === active ? "true" : "false");
      });
    }

    /* ---------- 寄せる ---------- */

    function tick(now) {
      const dt = lastFrame ? Math.min(0.064, (now - lastFrame) / 1000) : 0.016;
      lastFrame = now;

      const diff = goal - angle;
      if (Math.abs(diff) < 0.05) {
        angle = goal;
        raf = null;
        lastFrame = 0;
        render();
        return;
      }
      // 1秒でほぼ詰めきる指数イージング。フレームレートに依らず同じ速さで動く。
      angle += diff * (1 - Math.pow(0.0015, dt));
      render();
      raf = requestAnimationFrame(tick);
    }

    function settle(target) {
      goal = target;
      if (reduced) {
        angle = goal;
        render();
        return;
      }
      if (raf === null) {
        lastFrame = 0;
        raf = requestAnimationFrame(tick);
      }
    }

    function stopAnimation() {
      if (raf !== null) cancelAnimationFrame(raf);
      raf = null;
      lastFrame = 0;
      goal = null;
    }

    // 指定の枚目を正面へ。いまの角度からいちばん近い回り方を選ぶ。
    function goTo(index) {
      const base = -index * step;
      const turns = Math.round((angle - base) / 360);
      settle(base + turns * 360);
    }

    /* ---------- ドラッグ / スワイプ ---------- */

    const viewport = ring.querySelector(".card-ring__viewport") || ring;

    // 指を1枚ぶんの間隔だけ動かしたら、カードも1枚ぶん進む(指にカードが付く感覚)。
    function degPerPx() {
      const width = cards[0].offsetWidth || 240;
      return step / (width * 0.85);
    }

    const DEADZONE = 4; // これ未満はクリックとして扱い、リングを動かさない
    let pointerId = null;
    let startX = 0;
    let startAngle = 0;
    let dragging = false;
    let moved = 0;
    let samples = []; // 直近の {t, angle}。離したときの勢いをここから出す。

    viewport.addEventListener("pointerdown", (e) => {
      if (pointerId !== null) return;
      if (e.pointerType === "mouse" && e.button !== 0) return;
      pointerId = e.pointerId;
      startX = e.clientX;
      startAngle = angle;
      dragging = false;
      moved = 0;
      samples = [{ t: e.timeStamp, a: angle }];
      stopAnimation();
    });

    viewport.addEventListener("pointermove", (e) => {
      if (pointerId !== e.pointerId) return;

      const dx = e.clientX - startX;
      moved = Math.abs(dx);
      if (!dragging) {
        if (moved < DEADZONE) return;
        // 手ぶれぶんは切り捨てて、掴んだ瞬間に飛ばないようにする
        startX += dx > 0 ? DEADZONE : -DEADZONE;
        dragging = true;
        ring.classList.add("is-dragging");
        viewport.setPointerCapture(pointerId);
      }

      // 右へ引けば右の1枚が手前へ、左へ引けば左の1枚が手前へ
      angle = startAngle + (e.clientX - startX) * degPerPx();

      samples.push({ t: e.timeStamp, a: angle });
      // 勢いは直近 110ms ぶんだけを見る。1サンプルだと指を止めて離した時に 0 になる。
      while (samples.length > 2 && e.timeStamp - samples[0].t > 110) samples.shift();

      render();
    });

    function release(e) {
      if (pointerId !== e.pointerId) return;
      if (viewport.hasPointerCapture && viewport.hasPointerCapture(pointerId)) {
        viewport.releasePointerCapture(pointerId);
      }
      pointerId = null;

      if (!dragging) return; // ただのクリック。リンクはそのまま開かせる。
      dragging = false;
      ring.classList.remove("is-dragging");

      // 直近の動きから速度(度/秒)を出し、「このまま滑ったらどこか」を見積もる。
      const first = samples[0];
      const last = samples[samples.length - 1];
      const span = last.t - first.t;
      const velocity = span > 0 ? ((last.a - first.a) / span) * 1000 : 0;
      const projected = angle + velocity * 0.18;

      // 行き先は必ずカード1枚の位置。1回の勢いで飛べるのは3枚まで。
      const from = Math.round(startAngle / step);
      let to = Math.round(projected / step);
      to = Math.max(from - 3, Math.min(from + 3, to));
      settle(to * step);
    }

    viewport.addEventListener("pointerup", release);
    viewport.addEventListener("pointercancel", release);
    // デッドゾーンを超える前に枠の外で離すと pointerup が来ない。
    // 掴んだままの状態が残ると次のドラッグが始まらないので、窓側でも拾っておく。
    window.addEventListener("pointerup", release);
    window.addEventListener("pointercancel", release);

    // <a> は既定でドラッグできてしまう。掴んだ瞬間にブラウザの
    // 「リンクをドラッグ」が始まると、回転が全部そちらに持っていかれる。
    ring.addEventListener("dragstart", (e) => e.preventDefault());

    // 掴んだまま離したときに、リンクが開かないようにする
    viewport.addEventListener(
      "click",
      (e) => {
        if (moved > DEADZONE) {
          e.preventDefault();
          e.stopPropagation();
          moved = 0;
        }
      },
      true
    );

    // 正面以外のカードを押したら、開かずにその1枚を正面へ回す
    cards.forEach((card, i) => {
      card.addEventListener("click", (e) => {
        if (i !== active) {
          e.preventDefault();
          goTo(i);
        }
      });
      // キーボードで移動したときも、そのカードを正面に持ってくる
      card.addEventListener("focusin", () => {
        if (i !== active) goTo(i);
      });
    });

    /* ---------- トラックパッドの横スワイプ ---------- */

    // Mac の2本指スワイプ。縦成分のほうが大きいときはページのスクロールに渡す。
    let wheelAngle = null;
    let wheelTimer = null;
    viewport.addEventListener(
      "wheel",
      (e) => {
        if (Math.abs(e.deltaX) <= Math.abs(e.deltaY)) return;
        e.preventDefault();
        if (wheelAngle === null) {
          stopAnimation();
          wheelAngle = angle;
        }
        wheelAngle -= e.deltaX * degPerPx();
        angle = wheelAngle;
        render();

        clearTimeout(wheelTimer);
        wheelTimer = setTimeout(() => {
          wheelAngle = null;
          settle(Math.round(angle / step) * step);
        }, 90);
      },
      { passive: false }
    );

    /* ---------- ボタン・キーボード ---------- */

    ring.querySelectorAll("[data-ring-step]").forEach((btn) => {
      btn.addEventListener("click", () => {
        const dir = Number(btn.dataset.ringStep) || 1;
        goTo((active + dir + count) % count);
      });
    });

    dots.forEach((dot, i) => dot.addEventListener("click", () => goTo(i)));

    viewport.addEventListener("keydown", (e) => {
      if (e.key === "ArrowRight") {
        e.preventDefault();
        goTo((active + 1) % count);
      } else if (e.key === "ArrowLeft") {
        e.preventDefault();
        goTo((active - 1 + count) % count);
      }
    });

    /* ---------- 起動 ---------- */

    let resizeFrame = null;
    window.addEventListener("resize", () => {
      if (resizeFrame) cancelAnimationFrame(resizeFrame);
      resizeFrame = requestAnimationFrame(measure);
    });

    ring.classList.add("is-ready");
    // クラスを付けた直後はまだレイアウトが3D用に変わっていないので、1フレーム待つ
    requestAnimationFrame(() => {
      measure();
      announce(cards[0]);
    });
  }
})();
