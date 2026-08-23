<?php
// content_diff.php — анализ пересечения текстов страниц. Инструмент админки.
//
// Зачем: локационные страницы собраны по одному шаблону. Если уникален только
// первый абзац, Google выберет из группы одну страницу, а остальные положит
// в "Crawled – currently not indexed". Отчёт показывает реальные цифры.
//
// Метод: текст нормализуется (нижний регистр, без пунктуации и цифр), режется
// на 5-словные шинглы, считается коэффициент Жаккара. Пять слов подряд —
// это уже буквально один и тот же текст, а отдельные совпадения ("эвакуатор",
// "Харьков") — шум.
//
// Что учитывается: только content_blocks (text и structured_content).
// Общие include-блоки (why_we, contacts, faq) в расчёт НЕ входят — они
// одинаковы везде и завысили бы результат. Реальное пересечение
// отрендеренного HTML выше показанного здесь.
//
// Доступ — по сессии админки, как в pages_manager.php.

ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
session_start();
require_once 'db.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: admin.php');
    exit;
}

// ---------- Параметры ----------
$lang      = ($_GET['lang'] ?? 'ru') === 'ua' ? 'ua' : 'ru';
$shingle_n = max(3, min(8, (int)($_GET['n'] ?? 5)));
$limit     = (int)($_GET['limit'] ?? 60);          // 0 = показать все пары
$type_f    = $_GET['type'] ?? 'all';

$all_types = ['locations', 'district', 'services', 'articles', 'hub'];
$types     = ($type_f !== 'all' && in_array($type_f, $all_types, true))
           ? [$type_f]
           : ['locations', 'district', 'services'];

// ---------- 1. Собираем текст ----------
function extract_text($items) {
    $out = [];
    if (!is_array($items)) return '';
    foreach ($items as $it) {
        $c = $it['content'] ?? null;
        $t = $it['type'] ?? '';
        if ($t === 'faq' && is_array($c)) {
            foreach (($c['items'] ?? []) as $fi) {
                $out[] = ($fi['q'] ?? '') . ' ' . ($fi['a'] ?? '');
            }
            continue;
        }
        if (is_string($c))    $out[] = $c;
        elseif (is_array($c)) array_walk_recursive($c, function ($v) use (&$out) {
            if (is_string($v)) $out[] = $v;
        });
    }
    return implode(' ', $out);
}

function shingles($text, $n) {
    $t = mb_strtolower(strip_tags($text), 'UTF-8');
    $t = preg_replace('~[^\p{L}\s]+~u', ' ', $t);
    $t = preg_replace('~\s+~u', ' ', trim($t));
    $w = $t === '' ? [] : explode(' ', $t);
    $s = [];
    for ($i = 0; $i + $n <= count($w); $i++) {
        $s[implode(' ', array_slice($w, $i, $n))] = true;
    }
    return $s;
}

// str_word_count байтовая и на UTF-8 врёт. Считаем регуляркой.
function count_words($text) {
    return preg_match_all('~[\p{L}\p{N}]+~u', strip_tags($text));
}

$pages = $db->select('pages', ['id', 'slug', 'type', 'location_type', 'breadcrumb_title'], [
    'AND'   => ['lang' => $lang, 'type' => $types],
    'ORDER' => ['slug' => 'ASC'],
]);

$docs = [];
$empty_pages = [];
foreach ($pages as $p) {
    $blocks = $db->select('content_blocks', ['block_type', 'content'], ['page_id' => $p['id']]);
    $text = '';
    foreach (($blocks ?: []) as $b) {
        $raw = trim((string)($b['content'] ?? ''));
        if ($raw === '') continue;
        if ($b['block_type'] === 'structured_content' || strpos($raw, '[') === 0) {
            $items = json_decode($raw, true);
            $text .= ' ' . ($items ? extract_text($items) : $raw);
        } elseif ($b['block_type'] === 'text') {
            $text .= ' ' . $raw;
        }
    }
    $text = trim($text);
    if ($text === '') { $empty_pages[] = $p; continue; }   // раньше просто пропускались
    $docs[$p['slug']] = [
        'id'    => $p['id'],
        'title' => $p['breadcrumb_title'] ?: $p['slug'],
        'type'  => $p['type'],
        'ltype' => $p['location_type'],
        'text'  => $text,
    ];
}

foreach ($docs as $slug => &$d) {
    $d['sh']    = shingles($d['text'], $shingle_n);
    $d['words'] = count_words($d['text']);
    $d['max']   = 0.0;
    $d['with']  = '';
}
unset($d);

// ---------- 2. Попарная матрица ----------
$slugs = array_keys($docs);
$pairs = [];
foreach ($slugs as $i => $a) {
    foreach ($slugs as $j => $b) {
        if ($j <= $i) continue;
        $A = $docs[$a]['sh']; $B = $docs[$b]['sh'];
        if (!$A || !$B) continue;
        $inter = count(array_intersect_key($A, $B));
        $union = count($A + $B);
        $sim   = $union ? $inter / $union : 0;
        $pairs[] = ['a' => $a, 'b' => $b, 'sim' => $sim];
        if ($sim > $docs[$a]['max']) { $docs[$a]['max'] = $sim; $docs[$a]['with'] = $b; }
        if ($sim > $docs[$b]['max']) { $docs[$b]['max'] = $sim; $docs[$b]['with'] = $a; }
    }
}
usort($pairs, fn($x, $y) => $y['sim'] <=> $x['sim']);

// ---------- 3. Сводка ----------
$buckets = ['ok' => 0, 'good' => 0, 'watch' => 0, 'bad' => 0];
foreach ($pairs as $p) {
    if ($p['sim'] >= 0.70)      $buckets['bad']++;
    elseif ($p['sim'] >= 0.40)  $buckets['watch']++;
    elseif ($p['sim'] >= 0.20)  $buckets['good']++;
    else                        $buckets['ok']++;
}
$thin = array_filter($docs, fn($d) => $d['words'] < 400);
$avg_words = $docs ? round(array_sum(array_column($docs, 'words')) / count($docs)) : 0;

function row_bg($v) {
    if ($v >= 0.70) return '#f8d7da';
    if ($v >= 0.40) return '#fff3cd';
    if ($v >= 0.20) return '#d1e7dd';
    return '';
}
function pct($v) { return round($v * 100); }

function qs(array $over = []) {
    $q = array_merge($_GET, $over);
    unset($q['token']);
    return '?' . http_build_query($q);
}

$shown = $limit > 0 ? array_slice($pairs, 0, $limit) : $pairs;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="robots" content="noindex,nofollow">
<title>Пересечение текстов — <?= htmlspecialchars($lang) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
  body { background:#f4f7f6; padding-bottom:60px; }
  .panel { background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,.05); margin-bottom:20px; }
  table { font-size:13.5px; }
  code { background:#f5f5f3; padding:1px 5px; border-radius:4px; color:#333; }
  .stat { font-size:26px; font-weight:700; line-height:1.1; }
  .stat-label { font-size:12px; color:#6c757d; }
  .legend span { display:inline-block; padding:3px 10px; border-radius:4px; margin-right:6px; font-size:12px; }
  th { white-space:nowrap; }
</style>
</head>
<body>
<nav class="navbar navbar-dark bg-dark mb-4">
  <div class="container">
    <span class="navbar-brand"><i class="fas fa-clone me-2"></i>Пересечение текстов</span>
    <div class="d-flex gap-2">
      <a href="admin" class="btn btn-outline-light btn-sm"><i class="fas fa-cogs me-1"></i>Настройки</a>
      <a href="pages_manager" class="btn btn-outline-info btn-sm"><i class="fas fa-file-alt me-1"></i>Страницы</a>
      <a href="/" target="_blank" class="btn btn-outline-light btn-sm"><i class="fas fa-eye me-1"></i>Сайт</a>
      <a href="admin.php?logout" class="btn btn-danger btn-sm">Выйти</a>
    </div>
  </div>
</nav>

<div class="container">

  <!-- Фильтры -->
  <div class="panel">
    <form class="row g-2 align-items-end" method="get">
      <div class="col-auto">
        <label class="form-label small mb-1">Язык</label>
        <select name="lang" class="form-select form-select-sm">
          <option value="ru" <?= $lang=='ru'?'selected':'' ?>>Русский</option>
          <option value="ua" <?= $lang=='ua'?'selected':'' ?>>Українська</option>
        </select>
      </div>
      <div class="col-auto">
        <label class="form-label small mb-1">Тип страниц</label>
        <select name="type" class="form-select form-select-sm">
          <option value="all" <?= $type_f=='all'?'selected':'' ?>>Локации + услуги</option>
          <option value="locations" <?= $type_f=='locations'?'selected':'' ?>>Только локации</option>
          <option value="district"  <?= $type_f=='district'?'selected':'' ?>>Только районы</option>
          <option value="services"  <?= $type_f=='services'?'selected':'' ?>>Только услуги</option>
          <option value="articles"  <?= $type_f=='articles'?'selected':'' ?>>Только блог</option>
        </select>
      </div>
      <div class="col-auto">
        <label class="form-label small mb-1">Длина шингла</label>
        <select name="n" class="form-select form-select-sm">
          <?php foreach ([3,4,5,6,8] as $o): ?>
          <option value="<?= $o ?>" <?= $shingle_n==$o?'selected':'' ?>><?= $o ?> слова</option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-auto">
        <label class="form-label small mb-1">Показать пар</label>
        <select name="limit" class="form-select form-select-sm">
          <option value="60"  <?= $limit==60?'selected':'' ?>>60</option>
          <option value="200" <?= $limit==200?'selected':'' ?>>200</option>
          <option value="0"   <?= $limit==0?'selected':'' ?>>все</option>
        </select>
      </div>
      <div class="col-auto">
        <button class="btn btn-primary btn-sm"><i class="fas fa-rotate me-1"></i>Пересчитать</button>
      </div>
    </form>
  </div>

  <!-- Сводка -->
  <div class="row g-3 mb-3">
    <div class="col-6 col-md-3"><div class="panel mb-0 text-center">
      <div class="stat"><?= count($docs) ?></div><div class="stat-label">страниц с текстом</div></div></div>
    <div class="col-6 col-md-3"><div class="panel mb-0 text-center">
      <div class="stat"><?= $avg_words ?></div><div class="stat-label">слов в среднем</div></div></div>
    <div class="col-6 col-md-3"><div class="panel mb-0 text-center">
      <div class="stat text-<?= $pairs && $pairs[0]['sim']>=0.4 ? 'danger':'success' ?>"><?= $pairs ? pct($pairs[0]['sim']) : 0 ?>%</div>
      <div class="stat-label">максимум пересечения</div></div></div>
    <div class="col-6 col-md-3"><div class="panel mb-0 text-center">
      <div class="stat text-<?= count($thin) ? 'warning':'success' ?>"><?= count($thin) ?></div>
      <div class="stat-label">страниц короче 400 слов</div></div></div>
  </div>

  <?php if ($empty_pages): ?>
  <div class="alert alert-danger">
    <strong><i class="fas fa-triangle-exclamation me-1"></i>Страниц без текста: <?= count($empty_pages) ?></strong>
    — они не участвуют в расчёте и, скорее всего, отдают шаблон без контента.
    <div class="mt-2 small">
      <?php foreach ($empty_pages as $ep): ?>
        <a href="edit_page.php?id=<?= $ep['id'] ?>" class="badge bg-danger text-decoration-none me-1"><?= htmlspecialchars($ep['slug']) ?></a>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <div class="panel">
    <p class="legend mb-2">
      <span style="background:#f8d7da">≥70% — переписывать</span>
      <span style="background:#fff3cd">40–70% — уникализировать</span>
      <span style="background:#d1e7dd">20–40% — норма для шаблона</span>
      <span style="border:1px solid #ddd">&lt;20% — хорошо</span>
    </p>
    <div class="small text-muted">
      Пар всего: <strong><?= count($pairs) ?></strong> ·
      красных <strong><?= $buckets['bad'] ?></strong> ·
      жёлтых <strong><?= $buckets['watch'] ?></strong> ·
      зелёных <strong><?= $buckets['good'] ?></strong> ·
      белых <strong><?= $buckets['ok'] ?></strong>.
      Учитывается только контент из блоков; общие include (why_we, contacts, faq) в расчёт не входят,
      поэтому реальное пересечение отрендеренных страниц выше.
    </div>
  </div>

  <!-- Объём текста -->
  <div class="panel">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <h5 class="mb-0">Объём текста по страницам</h5>
      <input type="text" id="q1" class="form-control form-control-sm" style="max-width:240px" placeholder="Поиск...">
    </div>
    <div class="table-responsive">
    <table class="table table-hover table-sm align-middle mb-0" id="t1">
      <thead class="table-light"><tr>
        <th>Страница</th><th>Тип</th><th class="text-end">Слов</th>
        <th class="text-end">Шинглов</th><th class="text-end">Макс. пересечение</th><th></th>
      </tr></thead>
      <tbody>
      <?php
      $sorted = $docs;
      uasort($sorted, fn($x, $y) => $x['words'] <=> $y['words']);
      foreach ($sorted as $slug => $d): ?>
        <tr>
          <td><code><?= htmlspecialchars($slug) ?></code><br><small class="text-muted"><?= htmlspecialchars($d['title']) ?></small></td>
          <td><small class="text-muted"><?= htmlspecialchars($d['ltype'] === 'district' ? 'район' : $d['type']) ?></small></td>
          <td class="text-end <?= $d['words'] < 400 ? 'text-warning fw-bold' : '' ?>"><?= $d['words'] ?></td>
          <td class="text-end text-muted"><?= count($d['sh']) ?></td>
          <td class="text-end" style="background:<?= row_bg($d['max']) ?>">
            <strong><?= pct($d['max']) ?>%</strong>
            <?php if ($d['with']): ?><br><small class="text-muted"><?= htmlspecialchars($d['with']) ?></small><?php endif; ?>
          </td>
          <td class="text-nowrap">
            <a href="edit_page.php?id=<?= $d['id'] ?>" class="btn btn-outline-primary btn-sm" title="Редактировать"><i class="fas fa-pen"></i></a>
            <a href="/<?= $lang=='ua' ? 'ua/' : '' ?><?= htmlspecialchars($slug) ?>" target="_blank" class="btn btn-outline-secondary btn-sm" title="Открыть"><i class="fas fa-eye"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    </div>
  </div>

  <!-- Пары -->
  <div class="panel">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <h5 class="mb-0">Пары по убыванию пересечения</h5>
      <input type="text" id="q2" class="form-control form-control-sm" style="max-width:240px" placeholder="Поиск...">
    </div>
    <div class="table-responsive">
    <table class="table table-hover table-sm align-middle mb-0" id="t2">
      <thead class="table-light"><tr><th>Страница A</th><th>Страница B</th><th class="text-end" style="width:110px">Пересечение</th></tr></thead>
      <tbody>
      <?php foreach ($shown as $p): ?>
        <tr style="background:<?= row_bg($p['sim']) ?>">
          <td><a href="edit_page.php?id=<?= $docs[$p['a']]['id'] ?>" class="text-decoration-none"><code><?= htmlspecialchars($p['a']) ?></code></a></td>
          <td><a href="edit_page.php?id=<?= $docs[$p['b']]['id'] ?>" class="text-decoration-none"><code><?= htmlspecialchars($p['b']) ?></code></a></td>
          <td class="text-end"><strong><?= pct($p['sim']) ?>%</strong></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    </div>
    <?php if ($limit > 0 && count($pairs) > $limit): ?>
      <div class="mt-2 small text-muted">
        Показано <?= count($shown) ?> из <?= count($pairs) ?>.
        <a href="<?= qs(['limit' => 0]) ?>">Показать все</a>
      </div>
    <?php endif; ?>
  </div>

</div>

<script>
function bindSearch(inputId, tableId) {
  document.getElementById(inputId).addEventListener('keyup', function (e) {
    const term = e.target.value.toLowerCase();
    document.querySelectorAll('#' + tableId + ' tbody tr').forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
    });
  });
}
bindSearch('q1', 't1');
bindSearch('q2', 't2');
</script>
</body>
</html>