/* Sommaire latéral collant + barre de progression de lecture */
(function () {
  var toc = document.querySelector('.article-toc ol, .toc ol');
  var aside = document.querySelector('aside.sidebar');
  // Barre de progression
  var bar = document.createElement('div');
  bar.className = 'read-progress';
  document.body.appendChild(bar);
  function progress() {
    var h = document.documentElement;
    var max = h.scrollHeight - h.clientHeight;
    bar.style.width = (max > 0 ? (h.scrollTop / max) * 100 : 0) + '%';
  }
  window.addEventListener('scroll', progress, { passive: true });
  progress();
  if (!toc || !aside) return;
  // Sommaire collant dans la barre latérale
  var box = document.createElement('div');
  box.className = 'sidebar-widget toc-side';
  box.innerHTML = '<h3>Sommaire</h3>';
  var ol = toc.cloneNode(true);
  box.appendChild(ol);
  aside.appendChild(box);
  aside.classList.add('has-toc-side');
  var links = Array.prototype.slice.call(ol.querySelectorAll('a[href^="#"]'));
  var targets = links.map(function (a) { return document.getElementById(a.getAttribute('href').slice(1)); });
  function active() {
    var y = window.scrollY + 140, cur = -1;
    targets.forEach(function (t, i) { if (t && t.getBoundingClientRect().top + window.scrollY <= y) cur = i; });
    links.forEach(function (a, i) { a.classList.toggle('active', i === cur); });
  }
  window.addEventListener('scroll', active, { passive: true });
  active();
})();
