<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require 'db.php';                 // ① DB connection ($conn)

/* ---------- CONFIG ---------- */
$limit = 5;                       // posts per page

/* ---------- INPUT ---------- */
$search = trim($_GET['search'] ?? '');
$page   = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

/* ---------- HELPER: bind by ref ---------- */
function bindParams(mysqli_stmt $stmt, string $types, array $params): void {
    /* mysqli requires params passed by reference */
    $refs = [];
    foreach ($params as $k => $v) $refs[$k] = &$params[$k];
    array_unshift($refs, $types);
    call_user_func_array([$stmt, 'bind_param'], $refs);
}

/* ---------- BUILD SEARCH CLAUSE ---------- */
$searchClause = '';
$params       = [];
$types        = '';

if ($search !== '') {
    $searchClause = 'WHERE title LIKE ? OR content LIKE ?';
    $like = "%{$search}%";
    $params = [$like, $like];
    $types  = 'ss';
}

/* ---------- COUNT POSTS ---------- */
$count_sql = "SELECT COUNT(*) AS total FROM posts $searchClause";
if (!$count_stmt = $conn->prepare($count_sql)) die("Count prep error: {$conn->error}");

if ($search !== '') bindParams($count_stmt, $types, $params);
$count_stmt->execute();
$total_posts = $count_stmt->get_result()->fetch_assoc()['total'] ?? 0;
$total_pages = max(1, (int)ceil($total_posts / $limit));

/* ---------- GET PAGINATED POSTS ---------- */
$list_sql = "
  SELECT id, title, content, created_at
  FROM posts
  $searchClause
  ORDER BY created_at DESC
  LIMIT ? OFFSET ?
";
$list_stmt = $conn->prepare($list_sql) or die("List prep error: {$conn->error}");

/* add limit & offset params */
$params_list = $params;
$types_list  = $types . 'ii';
$params_list[] = $limit;
$params_list[] = $offset;

bindParams($list_stmt, $types_list, $params_list);
$list_stmt->execute();
$result = $list_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>MyBlog - Where Ideas Come Alive</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
  --primary-bg: #0a0a0a;
  --secondary-bg: #1a1a1a;
  --card-bg: #252525;
  --accent-neon: #00ff88;
  --accent-pink: #ff006e;
  --accent-orange: #ff8500;
  --accent-blue: #0099ff;
  --text-primary: #ffffff;
  --text-secondary: #e0e0e0;
  --text-muted: #a0a0a0;
  --glass-bg: rgba(255, 255, 255, 0.05);
  --glass-border: rgba(255, 255, 255, 0.1);
  --shadow-glow: rgba(0, 255, 136, 0.3);
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Poppins', sans-serif;
  background: var(--primary-bg);
  color: var(--text-primary);
  line-height: 1.6;
  overflow-x: hidden;
  position: relative;
}

/* Animated geometric background */
body::before {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: 
    linear-gradient(45deg, var(--accent-neon), transparent, var(--accent-pink)),
    linear-gradient(-45deg, var(--accent-orange), transparent, var(--accent-blue));
  opacity: 0.1;
  z-index: -2;
  animation: geometricFloat 15s ease-in-out infinite;
}

body::after {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-image: 
    radial-gradient(circle at 25% 25%, var(--accent-neon) 2px, transparent 2px),
    radial-gradient(circle at 75% 75%, var(--accent-pink) 1px, transparent 1px);
  background-size: 50px 50px, 30px 30px;
  opacity: 0.1;
  z-index: -1;
  animation: dotPattern 20s linear infinite;
}

@keyframes geometricFloat {
  0%, 100% { transform: rotate(0deg) scale(1); }
  33% { transform: rotate(120deg) scale(1.1); }
  66% { transform: rotate(240deg) scale(0.9); }
}

@keyframes dotPattern {
  0% { transform: translate(0, 0); }
  100% { transform: translate(50px, 50px); }
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
  position: relative;
}

/* Floating particles effect */
.particles {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  z-index: -1;
}

.particle {
  position: absolute;
  width: 4px;
  height: 4px;
  background: var(--accent-neon);
  border-radius: 50%;
  animation: floatUp 8s linear infinite;
}

.particle:nth-child(2n) { background: var(--accent-pink); animation-duration: 10s; }
.particle:nth-child(3n) { background: var(--accent-orange); animation-duration: 6s; }
.particle:nth-child(4n) { background: var(--accent-blue); animation-duration: 12s; }

@keyframes floatUp {
  0% {
    transform: translateY(100vh) translateX(0) rotate(0deg);
    opacity: 0;
  }
  10% { opacity: 1; }
  90% { opacity: 1; }
  100% {
    transform: translateY(-10vh) translateX(200px) rotate(360deg);
    opacity: 0;
  }
}

/* Header Styles */
.header {
  text-align: center;
  margin-bottom: 4rem;
  position: relative;
}

.logo-container {
  position: relative;
  display: inline-block;
  margin-bottom: 1rem;
}

.logo {
  font-size: 4rem;
  font-weight: 800;
  background: linear-gradient(45deg, var(--accent-neon), var(--accent-pink), var(--accent-orange), var(--accent-blue));
  background-size: 300% 300%;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  animation: gradientShift 3s ease-in-out infinite;
  text-shadow: 0 0 20px var(--shadow-glow);
  position: relative;
}

.logo::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(45deg, var(--accent-neon), var(--accent-pink));
  opacity: 0.2;
  border-radius: 10px;
  filter: blur(20px);
  z-index: -1;
  animation: glowPulse 2s ease-in-out infinite;
}

@keyframes gradientShift {
  0%, 100% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
}

@keyframes glowPulse {
  0%, 100% { transform: scale(1); opacity: 0.2; }
  50% { transform: scale(1.1); opacity: 0.4; }
}

.tagline {
  color: var(--text-secondary);
  font-size: 1.2rem;
  font-weight: 300;
  margin-bottom: 2rem;
  text-transform: uppercase;
  letter-spacing: 2px;
  position: relative;
}

.tagline::before,
.tagline::after {
  content: '';
  position: absolute;
  top: 50%;
  width: 60px;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--accent-neon), transparent);
}

.tagline::before { left: -80px; }
.tagline::after { right: -80px; }

.nav {
  display: flex;
  justify-content: center;
  gap: 2rem;
}

.nav a {
  color: var(--text-secondary);
  text-decoration: none;
  font-weight: 600;
  padding: 1rem 2rem;
  border-radius: 30px;
  border: 2px solid var(--glass-border);
  background: var(--glass-bg);
  backdrop-filter: blur(10px);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.nav a::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(0, 255, 136, 0.3), transparent);
  transition: left 0.6s;
}

.nav a:hover {
  color: var(--text-primary);
  border-color: var(--accent-neon);
  transform: translateY(-3px);
  box-shadow: 0 10px 30px rgba(0, 255, 136, 0.3);
}

.nav a:hover::before {
  left: 100%;
}

/* Search Styles */
.search-container {
  position: relative;
  margin-bottom: 3rem;
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
}

.search-form {
  position: relative;
  display: flex;
  align-items: center;
}

.search-input {
  width: 100%;
  padding: 1.2rem 4rem 1.2rem 2rem;
  border: 2px solid var(--glass-border);
  border-radius: 50px;
  background: var(--glass-bg);
  backdrop-filter: blur(15px);
  color: var(--text-primary);
  font-size: 1.1rem;
  font-weight: 400;
  transition: all 0.4s ease;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

.search-input:focus {
  outline: none;
  border-color: var(--accent-neon);
  box-shadow: 0 0 0 4px rgba(0, 255, 136, 0.2), 0 8px 32px rgba(0, 0, 0, 0.3);
  transform: translateY(-2px);
}

.search-input::placeholder {
  color: var(--text-muted);
  font-style: italic;
}

.search-btn {
  position: absolute;
  right: 0.5rem;
  background: linear-gradient(135deg, var(--accent-neon), var(--accent-blue));
  border: none;
  border-radius: 50%;
  width: 3rem;
  height: 3rem;
  color: var(--primary-bg);
  font-size: 1.2rem;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 15px rgba(0, 255, 136, 0.4);
}

.search-btn:hover {
  transform: scale(1.1) rotate(10deg);
  box-shadow: 0 6px 25px rgba(0, 255, 136, 0.6);
}

/* Post Styles */
.posts-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 2rem;
  margin-bottom: 3rem;
}

.post-card {
  background: var(--glass-bg);
  backdrop-filter: blur(15px);
  border-radius: 20px;
  padding: 2rem;
  border: 1px solid var(--glass-border);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

.post-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 3px;
  background: linear-gradient(90deg, var(--accent-neon), var(--accent-pink), var(--accent-orange));
  background-size: 200% 100%;
  animation: borderShimmer 3s linear infinite;
}

@keyframes borderShimmer {
  0% { background-position: -200% 0; }
  100% { background-position: 200% 0; }
}

.post-card::after {
  content: '';
  position: absolute;
  top: 1rem;
  right: 1rem;
  width: 8px;
  height: 8px;
  background: var(--accent-neon);
  border-radius: 50%;
  box-shadow: 0 0 10px var(--accent-neon);
  animation: pulse 2s ease-in-out infinite;
}

.post-card:hover {
  transform: translateY(-10px) scale(1.02);
  box-shadow: 0 20px 60px rgba(0, 255, 136, 0.2);
  border-color: var(--accent-neon);
}

.post-title {
  font-size: 1.4rem;
  font-weight: 700;
  margin-bottom: 1rem;
  color: var(--text-primary);
  line-height: 1.3;
  background: linear-gradient(135deg, var(--text-primary), var(--accent-neon));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.post-meta {
  font-size: 0.9rem;
  color: var(--text-muted);
  margin-bottom: 1.5rem;
  display: flex;
  align-items: center;
  gap: 0.8rem;
  text-transform: uppercase;
  letter-spacing: 1px;
  font-weight: 500;
}

.post-meta::before {
  content: '';
  width: 12px;
  height: 12px;
  background: linear-gradient(45deg, var(--accent-pink), var(--accent-orange));
  border-radius: 50%;
  animation: rotate 4s linear infinite;
}

@keyframes rotate {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.post-excerpt {
  color: var(--text-secondary);
  line-height: 1.7;
  margin-bottom: 1.5rem;
  font-weight: 400;
}

.read-more {
  color: var(--accent-neon);
  text-decoration: none;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 1px;
  font-size: 0.9rem;
}

.read-more:hover {
  color: var(--accent-pink);
  transform: translateX(10px);
}

.read-more::after {
  content: '→';
  font-size: 1.2rem;
  transition: transform 0.3s ease;
}

.read-more:hover::after {
  transform: translateX(5px) scale(1.2);
}

/* Pagination Styles */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 1rem;
  margin-top: 3rem;
}

.pagination a,
.pagination span {
  width: 3rem;
  height: 3rem;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 15px;
  background: var(--glass-bg);
  backdrop-filter: blur(10px);
  color: var(--text-secondary);
  text-decoration: none;
  font-weight: 600;
  border: 1px solid var(--glass-border);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.pagination a::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, var(--accent-neon), var(--accent-pink));
  opacity: 0;
  transition: opacity 0.3s ease;
}

.pagination a:hover {
  color: var(--text-primary);
  transform: translateY(-3px);
  box-shadow: 0 8px 25px rgba(0, 255, 136, 0.3);
}

.pagination a:hover::before {
  opacity: 0.1;
}

.pagination .current {
  background: linear-gradient(135deg, var(--accent-neon), var(--accent-pink));
  color: var(--primary-bg);
  border-color: var(--accent-neon);
  font-weight: 700;
  box-shadow: 0 4px 15px rgba(0, 255, 136, 0.4);
}

/* No Posts Message */
.no-posts {
  text-align: center;
  color: var(--text-muted);
  font-size: 1.2rem;
  margin: 4rem 0;
  padding: 3rem;
  background: var(--glass-bg);
  backdrop-filter: blur(15px);
  border-radius: 20px;
  border: 1px solid var(--glass-border);
  position: relative;
  overflow: hidden;
}

.no-posts::before {
  content: '🔍';
  font-size: 4rem;
  display: block;
  margin-bottom: 1rem;
  opacity: 0.5;
}

/* Responsive Design */
@media (max-width: 768px) {
  .container {
    padding: 1rem;
  }
  
  .logo {
    font-size: 2.8rem;
  }
  
  .nav {
    flex-direction: column;
    gap: 1rem;
  }
  
  .nav a {
    padding: 0.8rem 1.5rem;
  }
  
  .posts-grid {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }
  
  .post-card {
    padding: 1.5rem;
  }
  
  .search-input {
    padding: 1rem 3.5rem 1rem 1.5rem;
  }
  
  .tagline::before,
  .tagline::after {
    display: none;
  }
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}

/* Dark mode enhancements */
@media (prefers-color-scheme: light) {
  :root {
    --primary-bg: #f8f9fa;
    --secondary-bg: #ffffff;
    --card-bg: #ffffff;
    --text-primary: #212529;
    --text-secondary: #495057;
    --text-muted: #6c757d;
    --glass-bg: rgba(255, 255, 255, 0.8);
    --glass-border: rgba(0, 0, 0, 0.1);
  }
}
</style>
</head>
<body>

<!-- Floating particles -->
<div class="particles">
  <div class="particle" style="left: 10%; animation-delay: 0s;"></div>
  <div class="particle" style="left: 20%; animation-delay: 1s;"></div>
  <div class="particle" style="left: 30%; animation-delay: 2s;"></div>
  <div class="particle" style="left: 40%; animation-delay: 3s;"></div>
  <div class="particle" style="left: 50%; animation-delay: 4s;"></div>
  <div class="particle" style="left: 60%; animation-delay: 5s;"></div>
  <div class="particle" style="left: 70%; animation-delay: 6s;"></div>
  <div class="particle" style="left: 80%; animation-delay: 7s;"></div>
  <div class="particle" style="left: 90%; animation-delay: 8s;"></div>
</div>

<div class="container">
  <header class="header">
    <div class="logo-container">
      <h1 class="logo">MyBlog</h1>
    </div>
    <p class="tagline">Where Ideas Come Alive</p>
    <nav class="nav">
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Sign Out</a>
      <?php else: ?>
        <a href="login.php">Join Community</a>
      <?php endif; ?>
    </nav>
  </header>

  <div class="search-container">
    <form class="search-form" method="GET">
      <input 
        type="text" 
        name="search" 
        class="search-input"
        placeholder="Discover amazing stories and ideas..." 
        value="<?= htmlspecialchars($search) ?>"
      >
      <button type="submit" class="search-btn">🚀</button>
    </form>
  </div>

  <main class="posts-grid">
    <?php if ($result->num_rows): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <article class="post-card">
          <h2 class="post-title"><?= htmlspecialchars($row['title']) ?></h2>
          <div class="post-meta">
            <?= date("M j, Y", strtotime($row['created_at'])) ?>
          </div>
          <p class="post-excerpt"><?= htmlspecialchars(substr($row['content'], 0, 150)) ?>...</p>
          <a class="read-more" href="displaypost.php?id=<?= $row['id'] ?>">Explore Story</a>
        </article>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="no-posts">
        <p>No stories match your search.</p>
        <p>Try exploring different keywords or check out our latest posts!</p>
      </div>
    <?php endif; ?>
  </main>

  <?php if ($total_pages > 1): ?>
    <nav class="pagination" aria-label="Pagination">
      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <?php if ($i === $page): ?>
          <span class="current" aria-current="page"><?= $i ?></span>
        <?php else: ?>
          <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" aria-label="Go to page <?= $i ?>"><?= $i ?></a>
        <?php endif; ?>
      <?php endfor; ?>
    </nav>
  <?php endif; ?>

</div>

</body>
</html>

