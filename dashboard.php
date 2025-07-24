<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NeonPulse Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #ff6b35 0%, #f7931e 50%, #ffd23f 100%);
      --secondary-gradient: linear-gradient(135deg, #06ffa5 0%, #2fb5a6 50%, #1d4e89 100%);
      --accent-gradient: linear-gradient(135deg, #ff0844 0%, #ffb199 50%, #ff6b9d 100%);
      --dark-gradient: linear-gradient(135deg, #2d1b69 0%, #11998e 50%, #38ef7d 100%);
      --neon-gradient: linear-gradient(135deg, #ff006e 0%, #8338ec 50%, #3a86ff 100%);
      --cyber-gradient: linear-gradient(135deg, #00f5ff 0%, #ff0080 50%, #ffff00 100%);
      --glass-bg: rgba(255, 255, 255, 0.05);
      --glass-border: rgba(255, 255, 255, 0.15);
      --text-primary: #ffffff;
      --text-secondary: rgba(255, 255, 255, 0.85);
      --text-muted: rgba(255, 255, 255, 0.65);
      --shadow-light: 0 8px 32px rgba(255, 107, 53, 0.15);
      --shadow-medium: 0 16px 48px rgba(255, 107, 53, 0.25);
      --shadow-heavy: 0 24px 64px rgba(255, 107, 53, 0.35);
      --glow-orange: 0 0 30px rgba(255, 107, 53, 0.6);
      --glow-cyan: 0 0 30px rgba(6, 255, 165, 0.6);
      --glow-pink: 0 0 30px rgba(255, 8, 68, 0.6);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: 
        radial-gradient(circle at 20% 20%, #ff6b35 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, #06ffa5 0%, transparent 50%),
        radial-gradient(circle at 50% 50%, #ff0844 0%, transparent 70%),
        linear-gradient(135deg, #0a0a23 0%, #1a1a3a 30%, #2d1b69 60%, #000000 100%);
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      min-height: 100vh;
      position: relative;
      overflow-x: hidden;
    }

    /* Animated Background */
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: 
        radial-gradient(circle at 15% 25%, rgba(255, 107, 53, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 85% 75%, rgba(6, 255, 165, 0.25) 0%, transparent 60%),
        radial-gradient(circle at 45% 15%, rgba(255, 8, 68, 0.2) 0%, transparent 45%),
        radial-gradient(circle at 75% 45%, rgba(131, 56, 236, 0.15) 0%, transparent 55%);
      animation: cosmicFloat 25s ease-in-out infinite;
      pointer-events: none;
      z-index: -1;
    }

    @keyframes cosmicFloat {
      0%, 100% { transform: translate(0, 0) rotate(0deg) scale(1); }
      25% { transform: translate(-30px, -20px) rotate(2deg) scale(1.05); }
      50% { transform: translate(20px, -30px) rotate(-1deg) scale(0.95); }
      75% { transform: translate(-15px, 25px) rotate(1.5deg) scale(1.02); }
    }

    /* Floating Orbs */
    .floating-orb {
      position: fixed;
      border-radius: 50%;
      background: linear-gradient(45deg, rgba(255, 107, 53, 0.2), rgba(6, 255, 165, 0.15));
      backdrop-filter: blur(20px);
      pointer-events: none;
      z-index: -1;
      animation: cosmicOrbitFloat 12s ease-in-out infinite;
      box-shadow: 
        0 0 60px rgba(255, 107, 53, 0.3),
        inset 0 0 30px rgba(6, 255, 165, 0.2);
    }

    .orb-1 { 
      width: 250px; 
      height: 250px; 
      top: 5%; 
      left: 8%; 
      animation-delay: 0s;
      background: linear-gradient(45deg, rgba(255, 8, 68, 0.25), rgba(131, 56, 236, 0.15));
      box-shadow: 0 0 80px rgba(255, 8, 68, 0.4);
    }
    .orb-2 { 
      width: 180px; 
      height: 180px; 
      top: 55%; 
      right: 10%; 
      animation-delay: 3s;
      background: linear-gradient(45deg, rgba(6, 255, 165, 0.3), rgba(255, 107, 53, 0.2));
      box-shadow: 0 0 70px rgba(6, 255, 165, 0.5);
    }
    .orb-3 { 
      width: 120px; 
      height: 120px; 
      bottom: 15%; 
      left: 25%; 
      animation-delay: 6s;
      background: linear-gradient(45deg, rgba(255, 210, 63, 0.3), rgba(255, 8, 68, 0.2));
      box-shadow: 0 0 50px rgba(255, 210, 63, 0.6);
    }

    @keyframes cosmicOrbitFloat {
      0%, 100% { transform: translateY(0px) rotate(0deg) scale(1); }
      25% { transform: translateY(-40px) rotate(90deg) scale(1.1); }
      50% { transform: translateY(-20px) rotate(180deg) scale(0.9); }
      75% { transform: translateY(-35px) rotate(270deg) scale(1.05); }
    }

    .container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 20px;
      position: relative;
      z-index: 1;
    }

    /* Glass Morphism */
    .glass {
      background: var(--glass-bg);
      backdrop-filter: blur(20px);
      border: 1px solid var(--glass-border);
      box-shadow: var(--shadow-light);
    }

    /* Modern Header */
    .header-float {
      position: fixed;
      top: 30px;
      right: 30px;
      z-index: 1000;
      display: flex;
      gap: 15px;
    }

    .float-btn {
      width: 65px;
      height: 65px;
      border-radius: 20px;
      border: none;
      color: white;
      font-size: 1.6rem;
      cursor: pointer;
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      position: relative;
      overflow: hidden;
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .float-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: left 0.6s;
    }

    .float-btn:hover::before { left: 100%; }
    .float-btn:hover { 
      transform: translateY(-10px) scale(1.15) rotate(12deg); 
      box-shadow: var(--glow-orange);
    }

    .btn-create { 
      background: var(--primary-gradient); 
      box-shadow: var(--glow-orange);
    }
    .btn-view { 
      background: var(--secondary-gradient); 
      box-shadow: var(--glow-cyan);
    }
    .btn-logout { 
      background: var(--neon-gradient); 
      box-shadow: var(--glow-pink);
    }

    /* Hero Section */
    .hero {
      text-align: center;
      padding: 100px 0 60px;
      position: relative;
    }

    .hero h1 {
      font-size: clamp(2.5rem, 6vw, 4.5rem);
      font-weight: 900;
      background: linear-gradient(135deg, #ff6b35 0%, #06ffa5 30%, #ff0844 60%, #ffff00 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 30px;
      letter-spacing: -0.02em;
      animation: neonTitleGlow 4s ease-in-out infinite alternate;
      position: relative;
      text-shadow: 0 0 30px rgba(255, 107, 53, 0.5);
    }

    @keyframes neonTitleGlow {
      0% { 
        filter: drop-shadow(0 0 20px rgba(255, 107, 53, 0.8)) 
                drop-shadow(0 0 40px rgba(6, 255, 165, 0.4)); 
      }
      50% { 
        filter: drop-shadow(0 0 30px rgba(255, 8, 68, 0.9)) 
                drop-shadow(0 0 60px rgba(255, 210, 63, 0.5)); 
      }
      100% { 
        filter: drop-shadow(0 0 25px rgba(131, 56, 236, 0.8)) 
                drop-shadow(0 0 50px rgba(6, 255, 165, 0.6)); 
      }
    }

    .stats-container {
      display: flex;
      justify-content: center;
      gap: 30px;
      margin-top: 40px;
      flex-wrap: wrap;
    }

    .stat-card {
      background: var(--glass-bg);
      backdrop-filter: blur(20px);
      border: 1px solid var(--glass-border);
      border-radius: 20px;
      padding: 25px 35px;
      color: white;
      font-weight: 600;
      box-shadow: var(--shadow-medium);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: var(--cyber-gradient);
      transform: translateX(-100%);
      transition: transform 0.3s ease;
    }

    .stat-card:hover::before { transform: translateX(0); }
    .stat-card:hover { 
      transform: translateY(-8px); 
      box-shadow: var(--glow-orange);
      background: rgba(255, 107, 53, 0.1);
    }

    .stat-number {
      font-size: 2rem;
      font-weight: 800;
      display: block;
      margin-bottom: 5px;
    }

    .stat-label {
      font-size: 0.9rem;
      opacity: 0.8;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    /* Modern Search */
    .search-section {
      margin: 60px 0;
      position: relative;
    }

    .search-wrapper {
      position: relative;
      max-width: 700px;
      margin: 0 auto;
    }

    .search-input {
      width: 100%;
      padding: 25px 70px 25px 30px;
      border: none;
      border-radius: 25px;
      font-size: 1.1rem;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      box-shadow: var(--shadow-medium);
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      font-weight: 500;
    }

    .search-input:focus {
      outline: none;
      transform: translateY(-3px);
      box-shadow: 
        0 25px 50px rgba(0, 0, 0, 0.2),
        0 0 0 3px rgba(255, 107, 53, 0.3),
        inset 0 0 20px rgba(6, 255, 165, 0.1);
      background: rgba(255, 255, 255, 1);
    }

    .search-input::placeholder {
      color: #999;
      font-weight: 400;
    }

    .search-btn {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      width: 50px;
      height: 50px;
      border-radius: 15px;
      border: none;
      background: var(--primary-gradient);
      color: white;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 1.2rem;
    }

    .search-btn:hover { 
      transform: translateY(-50%) scale(1.1) rotate(90deg); 
    }

    .search-meta {
      text-align: center;
      margin-top: 25px;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 30px;
      flex-wrap: wrap;
    }

    .clear-search {
      color: var(--text-secondary);
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .clear-search:hover {
      color: var(--text-primary);
      transform: translateY(-2px);
    }

    .search-results {
      color: var(--text-muted);
      font-weight: 500;
    }

    /* Posts Grid */
    .posts-container {
      margin: 60px 0;
    }

    .posts-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
      gap: 30px;
      margin-bottom: 60px;
    }

    .post-card {
      background: var(--glass-bg);
      backdrop-filter: blur(20px);
      border: 1px solid var(--glass-border);
      border-radius: 25px;
      padding: 35px;
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      cursor: pointer;
      position: relative;
      overflow: hidden;
      box-shadow: var(--shadow-light);
    }

    .post-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: var(--cyber-gradient);
      transform: translateX(-100%);
      transition: transform 0.4s ease;
    }

    .post-card:hover::before { transform: translateX(0); }
    .post-card:hover { 
      transform: translateY(-15px) scale(1.03); 
      box-shadow: 
        var(--glow-orange),
        0 30px 60px rgba(0, 0, 0, 0.4);
      background: rgba(255, 107, 53, 0.08);
    }

    .post-actions {
      position: absolute;
      top: 25px;
      right: 25px;
    }

    .dots-menu {
      background: rgba(255, 255, 255, 0.15);
      border: none;
      border-radius: 12px;
      width: 40px;
      height: 40px;
      color: white;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 1.1rem;
    }

    .dots-menu:hover { 
      background: rgba(255, 107, 53, 0.3); 
      transform: scale(1.15) rotate(180deg); 
      box-shadow: 0 0 20px rgba(255, 107, 53, 0.5);
    }

    .post-title {
      font-size: 1.6rem;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 20px;
      padding-right: 60px;
      line-height: 1.3;
      letter-spacing: -0.01em;
    }

    .post-content {
      color: var(--text-secondary);
      line-height: 1.7;
      margin-bottom: 25px;
      font-weight: 400;
      font-size: 1.05rem;
    }

    .post-meta {
      color: var(--text-muted);
      font-size: 0.95rem;
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 500;
      padding-top: 20px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Dropdown */
    .dropdown-menu {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border: none;
      border-radius: 20px;
      box-shadow: var(--shadow-medium);
      padding: 10px;
      margin-top: 10px;
    }

    .dropdown-item {
      padding: 15px 20px;
      color: #333;
      transition: all 0.3s ease;
      border-radius: 15px;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .dropdown-item:hover {
      background: var(--cyber-gradient);
      color: white;
      transform: translateX(8px);
      box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
    }

    /* Modal */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.8);
      backdrop-filter: blur(15px);
      z-index: 2000;
      display: none;
      justify-content: center;
      align-items: center;
      padding: 20px;
      animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    .modal-content {
      background: var(--glass-bg);
      backdrop-filter: blur(30px);
      border: 1px solid var(--glass-border);
      border-radius: 30px;
      padding: 50px;
      max-width: 800px;
      width: 100%;
      max-height: 90vh;
      overflow-y: auto;
      position: relative;
      box-shadow: var(--shadow-heavy);
      animation: slideUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    @keyframes slideUp {
      from { transform: translateY(50px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    .modal-close {
      position: absolute;
      top: 25px;
      right: 25px;
      width: 45px;
      height: 45px;
      border-radius: 15px;
      border: none;
      background: var(--secondary-gradient);
      color: white;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 1.2rem;
    }

    .modal-close:hover { 
      transform: scale(1.2) rotate(180deg); 
      box-shadow: var(--glow-pink);
    }

    .modal-title {
      color: var(--text-primary);
      margin-bottom: 25px;
      font-size: 2.2rem;
      font-weight: 800;
      line-height: 1.2;
    }

    .modal-text {
      color: var(--text-secondary);
      line-height: 1.8;
      margin-bottom: 35px;
      font-size: 1.1rem;
      font-weight: 400;
    }

    .modal-meta {
      color: var(--text-muted);
      padding-top: 25px;
      border-top: 1px solid rgba(255, 255, 255, 0.15);
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    /* Pagination */
    .pagination-container {
      display: flex;
      justify-content: center;
      margin: 60px 0;
    }

    .pagination {
      display: flex;
      gap: 15px;
      align-items: center;
    }

    .page-link {
      background: var(--glass-bg);
      backdrop-filter: blur(20px);
      border: 1px solid var(--glass-border);
      border-radius: 15px;
      width: 50px;
      height: 50px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text-primary);
      text-decoration: none;
      transition: all 0.3s ease;
      font-weight: 600;
      box-shadow: var(--shadow-light);
    }

    .page-link:hover,
    .page-item.active .page-link {
      background: var(--cyber-gradient);
      transform: scale(1.15) translateY(-3px);
      color: white;
      box-shadow: var(--glow-cyan);
    }

    /* No Posts */
    .no-posts {
      text-align: center;
      padding: 100px 20px;
      color: var(--text-secondary);
    }

    .no-posts-icon {
      font-size: 5rem;
      margin-bottom: 40px;
      opacity: 0.4;
      animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.1); }
    }

    .no-posts h4 {
      font-size: 2.5rem;
      margin-bottom: 25px;
      color: var(--text-primary);
      font-weight: 700;
    }

    .no-posts p {
      font-size: 1.2rem;
      line-height: 1.6;
      font-weight: 400;
    }

    .no-posts a {
      color: #06ffa5;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      text-shadow: 0 0 10px rgba(6, 255, 165, 0.5);
    }

    .no-posts a:hover {
      color: #ff6b35;
      text-decoration: underline;
      text-shadow: 0 0 20px rgba(255, 107, 53, 0.8);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .container { padding: 15px; }
      .hero { padding: 80px 0 40px; }
      .hero h1 { font-size: 2.5rem; }
      .header-float { 
        top: 20px; 
        right: 20px; 
        gap: 10px; 
      }
      .float-btn { 
        width: 55px; 
        height: 55px; 
        font-size: 1.3rem; 
      }
      .posts-grid { 
        grid-template-columns: 1fr; 
        gap: 20px; 
      }
      .post-card { padding: 25px; }
      .modal-content { 
        padding: 30px; 
        border-radius: 25px; 
      }
      .stats-container { gap: 15px; }
      .stat-card { padding: 20px 25px; }
      .search-input { padding: 20px 60px 20px 25px; }
    }

    @media (max-width: 480px) {
      .stats-container { flex-direction: column; align-items: center; }
      .search-meta { flex-direction: column; gap: 15px; }
      .modal-title { font-size: 1.8rem; }
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
      width: 12px;
    }

    ::-webkit-scrollbar-track {
      background: rgba(0, 0, 0, 0.2);
      border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
      background: var(--cyber-gradient);
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(255, 107, 53, 0.5);
    }

    ::-webkit-scrollbar-thumb:hover {
      background: var(--neon-gradient);
      box-shadow: var(--glow-pink);
    }
  </style>
</head>
<body>
  <!-- Floating Orbs -->
  <div class="floating-orb orb-1"></div>
  <div class="floating-orb orb-2"></div>
  <div class="floating-orb orb-3"></div>

  <!-- Floating Action Buttons -->
  <div class="header-float">
    <button class="float-btn btn-create" onclick="window.location.href='insertpost.php'" title="Create Post">
      <i class="fas fa-plus"></i>
    </button>
    <button class="float-btn btn-view" onclick="window.location.href='displaypost.php'" title="View Posts">
      <i class="fas fa-eye"></i>
    </button>
    <form method="POST" action="logout.php" style="display: inline;">
      <input type="hidden" name="csrf_token" value="DEMO_TOKEN">
      <button type="submit" class="float-btn btn-logout" title="Logout" onclick="return confirm('Logout?')">
        <i class="fas fa-sign-out-alt"></i>
      </button>
    </form>
  </div>

  <div class="container">
    <!-- Hero Section -->
    <div class="hero">
      <h1>Welcome, John Doe</h1>
      <div class="stats-container">
        <div class="stat-card">
          <span class="stat-number">42</span>
          <span class="stat-label">Total Posts</span>
        </div>
        <div class="stat-card">
          <span class="stat-number">1.2k</span>
          <span class="stat-label">Views</span>
        </div>
        <div class="stat-card">
          <span class="stat-number">89</span>
          <span class="stat-label">Likes</span>
        </div>
      </div>
    </div>

    <!-- Search Section -->
    <div class="search-section">
      <form method="GET">
        <div class="search-wrapper">
          <input type="text" name="search" class="search-input" placeholder="🔍 Search your posts..." value="">
          <button type="submit" class="search-btn">
            <i class="fas fa-search"></i>
          </button>
        </div>
        <div class="search-meta">
          <a href="#" class="clear-search">
            <i class="fas fa-times-circle"></i> Clear search
          </a>
          <span class="search-results">
            Results for: "<strong>Design inspiration</strong>"
          </span>
        </div>
      </form>
    </div>

    <!-- Posts Display -->
    <div class="posts-container">
      <div class="posts-grid">
        <!-- Sample Post 1 -->
        <div class="post-card" onclick="openPost(1, 'The Future of Web Design', 'Exploring the latest trends in modern web design, including glassmorphism, neumorphism, and micro-interactions that create engaging user experiences. This comprehensive guide covers everything from color theory to advanced CSS techniques that will elevate your designs to the next level.', '2024-01-15 14:30:00')">
          <div class="post-actions">
            <div class="dropdown">
              <button class="dots-menu" type="button" data-bs-toggle="dropdown" onclick="event.stopPropagation()">
                <i class="fas fa-ellipsis-v"></i>
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#"><i class="fas fa-edit"></i> Edit</a></li>
                <li><a class="dropdown-item" href="#" onclick="return confirm('Delete this post?')"><i class="fas fa-trash"></i> Delete</a></li>
              </ul>
            </div>
          </div>
          
          <h3 class="post-title">The Future of Web Design</h3>
          <div class="post-content">Exploring the latest trends in modern web design, including glassmorphism, neumorphism, and micro-interactions that create engaging user experiences...</div>
          <div class="post-meta">
            <i class="fas fa-calendar-alt"></i>
            January 15, 2024 at 2:30 PM
          </div>
        </div>

        <!-- Sample Post 2 -->
        <div class="post-card" onclick="openPost(2, 'Building Responsive Layouts', 'A deep dive into creating flexible, mobile-first designs that work seamlessly across all devices. Learn about CSS Grid, Flexbox, and modern layout techniques.', '2024-01-12 09:15:00')">
          <div class="post-actions">
            <div class="dropdown">
              <button class="dots-menu" type="button" data-bs-toggle="dropdown" onclick="event.stopPropagation()">
                <i class="fas fa-ellipsis-v"></i>
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#"><i class="fas fa-edit"></i> Edit</a></li>
                <li><a class="dropdown-item" href="#" onclick="return confirm('Delete this post?')"><i class="fas fa-trash"></i> Delete</a></li>
              </ul>
            </div>
          </div>
          
          <h3 class="post-title">Building Responsive Layouts</h3>
          <div class="post-content">A deep dive into creating flexible, mobile-first designs that work seamlessly across all devices. Learn about CSS Grid, Flexbox, and modern layout techniques...</div>
          <div class="post-meta">
            <i class="fas fa-calendar-alt"></i>
            January 12, 2024 at 9:15 AM
          </div>
        </div>

        <!-- Sample Post 3 -->
        <div class="post-card" onclick="openPost(3, 'JavaScript ES2024 Features', 'Discover the newest JavaScript features that are revolutionizing how we write modern web applications. From async/await improvements to new array methods.', '2024-01-10 16:45:00')">
          <div class="post-actions">
            <div class="dropdown">
              <button class="dots-menu" type="button" data-bs-toggle="dropdown" onclick="event.stopPropagation()">
                <i class="fas fa-ellipsis-v"></i>
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#"><i class="fas fa-edit"></i> Edit</a></li>
                <li><a class="dropdown-item" href="#" onclick="return confirm('Delete this post?')"><i class="fas fa-trash"></i> Delete</a></li>
              </ul>
            </div>
          </div>
          
          <h3 class="post-title">JavaScript ES2024 Features</h3>
          <div class="post-content">Discover the newest JavaScript features that are revolutionizing how we write modern web applications. From async/await improvements to new array methods...</div>
          <div class="post-meta">
            <i class="fas fa-calendar-alt"></i>
            January 10, 2024 at 4:45 PM
          </div>
        </div>
      </div>
    </div>

    <!-- Post Detail Modal -->
    <div id="postModal" class="modal-overlay">
      <div class="modal-content">
        <button class="modal-close" onclick="closePost()"><i class="fas fa-times"></i></button>
        <h2 id="postTitle" class="modal-title"></h2>
        <div id="postContent" class="modal-text"></div>
        <div id="postMeta" class="modal-meta"></div>
      </div>
    </div>

    <!-- Pagination -->
    <div class="pagination-container">
      <nav>
        <ul class="pagination">
          <li class="page-item">
            <a class="page-link" href="#"><i class="fas fa-chevron-left"></i></a>
          </li>
          <li class="page-item active">
            <a class="page-link" href="#">1</a>
          </li>
          <li class="page-item">
            <a class="page-link" href="#">2</a>
          </li>
          <li class="page-item">
            <a class="page-link" href="#">3
