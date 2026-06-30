<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <div class="sidebar-brand">
    <a href="/my-ppid/app/dashboard.php" class="brand-link">
      <img src="/my-ppid/app/assets/img/logo-kemenkes.png" alt="AdminPPID Logo" class="brand-image opacity-75 shadow" />
      <span class="brand-text fw-light">AdminPPID</span>
    </a>
  </div>
  <div class="sidebar-wrapper">
    <nav class="mt-2">

      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false" id="navigation">

        <li class="nav-item">
          <a href="/my-ppid/app/dashboard.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-house-door"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <?php
        // Cek apakah halaman yang sedang diakses berada di dalam folder mailbox
        $is_mailbox = (strpos($_SERVER['PHP_SELF'], '/mailbox/') !== false);
        ?>
        <li class="nav-item <?php echo $is_mailbox ? 'menu-open' : ''; ?>">
          <a href="#" class="nav-link <?php echo $is_mailbox ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-envelope"></i>
            <p>
              Mailbox
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/my-ppid/app/mailbox/kirim_pesan.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'kirim_pesan.php') ? 'active' : ''; ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Kirim Email</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/my-ppid/app/mailbox/inbox.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'inbox.php') ? 'active' : ''; ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Pesan Masuk</p>
              </a>
            </li>
          </ul>
        </li>
        <?php
        $is_forms = (strpos($_SERVER['PHP_SELF'], '/forms/') !== false);
        ?>
        <li class="nav-item <?php echo $is_forms ? 'menu-open' : ''; ?>">
          <a href="#" class="nav-link <?php echo $is_forms ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-pencil-square"></i>
            <p>
              Pengaturan
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/my-ppid/app/forms/pengaturan.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'elements.html') ? 'active' : ''; ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Kelola WEB</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/my-ppid/app/forms/elements.html" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'elements.html') ? 'active' : ''; ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Konten</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/my-ppid/app/forms/elements.html" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'elements.html') ? 'active' : ''; ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Footer</p>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</aside>