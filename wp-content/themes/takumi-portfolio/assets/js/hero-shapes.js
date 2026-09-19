/**
 * 図形レイヤーのパララックス。
 *
 * マウス位置を -1〜1 に正規化して CSS 変数に流すだけ。実際の移動量は
 * 図形ごとの --depth と CSS 側の transform が決める。
 * このスクリプトを読み込まなくても、浮遊と回転はそのまま動く。
 */
(function () {
	if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	var layers = document.querySelectorAll('.shapes');
	if (!layers.length) {
		return;
	}

	var frame = null;

	function onMove(e) {
		if (frame) {
			return; // 1フレームにつき1回だけ更新する。
		}
		frame = requestAnimationFrame(function () {
			frame = null;
			layers.forEach(function (svg) {
				var r = svg.getBoundingClientRect();
				if (!r.width || !r.height) {
					return;
				}
				// 画面外の図形まで計算し続けない。
				if (r.bottom < 0 || r.top > window.innerHeight) {
					return;
				}
				var x = (e.clientX - (r.left + r.width / 2)) / (r.width / 2);
				var y = (e.clientY - (r.top + r.height / 2)) / (r.height / 2);
				svg.style.setProperty('--mx', Math.max(-1, Math.min(1, x)).toFixed(3));
				svg.style.setProperty('--my', Math.max(-1, Math.min(1, y)).toFixed(3));
			});
		});
	}

	function reset() {
		layers.forEach(function (svg) {
			svg.style.setProperty('--mx', 0);
			svg.style.setProperty('--my', 0);
		});
	}

	window.addEventListener('pointermove', onMove, { passive: true });
	window.addEventListener('pointerleave', reset);
	window.addEventListener('blur', reset);
})();
