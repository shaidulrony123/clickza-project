<?php
  use App\Models\Settings;
  $settings = Settings::first();
  $headerLogo = $settings?->header_logo ?: 'frontend/assets/images/logo.jpeg';
?>
<!-- ══ NAVBAR ══ -->
<nav class="navbar fixed-top" id="mainNav">
  <div class="container-fluid px-3 px-lg-5">
    <a class="navbar-brand logo-text" href="#">
      <img class="img-fluid w-100" src="{{ asset($headerLogo) }}" alt="Logo">
    </a>

    <ul class="nav-links d-none d-lg-flex">
      <li><a href="#about" class="nav-link-item">About</a></li>
      <!-- Services dropdown — bridge fixes the gap-hover-hide bug -->
      <li class="dropdown-nav">
        <a href="#skills" class="nav-link-item">
          Services <i class="fas fa-chevron-down ms-1" style="font-size:10px"></i>
        </a>
        <div class="nav-dropdown">
          <a href="#skills"><i class="fab fa-laravel"></i> Laravel Development</a>
          <a href="#skills"><i class="fab fa-wordpress"></i> WordPress / WooCommerce</a>
          <a href="#skills"><i class="fas fa-puzzle-piece"></i> Plugin Development</a>
          <a href="#skills"><i class="fas fa-paint-brush"></i> Theme Customization</a>
          <a href="#skills"><i class="fas fa-bug"></i> Bug Fixing &amp; Support</a>
          <a href="#skills"><i class="fab fa-js-square"></i> JS / Frontend Dev</a>
        </div>
      </li>
      <li><a href="#projects"  class="nav-link-item">Projects</a></li>
      <li><a href="#author"    class="nav-link-item">Marketplace</a></li>
      <li><a href="#products"  class="nav-link-item">Products</a></li>
      <li><a href="#contact"   class="nav-link-item">Contact</a></li>
    </ul>

    <div class="nav-right d-flex align-items-center gap-3">
      <button class="theme-toggle" id="themeToggle" title="Toggle Theme">
        <i class="fas fa-moon" id="themeIcon"></i>
      </button>
      @auth
        <a href="{{ route('dashboard') }}" class="btn-dash d-none d-lg-flex">Dashboard <i class="fas fa-arrow-right ms-1"></i></a>
      @endauth
      <button class="hamburger d-lg-none" id="hamburger" data-bs-toggle="offcanvas" data-bs-target="#mobileOffcanvas">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</nav>

<!-- ══ MOBILE OFFCANVAS ══ -->
<div class="offcanvas offcanvas-end mobile-offcanvas" tabindex="-1" id="mobileOffcanvas">
  <div class="offcanvas-header">
    {{-- <span class="logo-text">DEV<span class="accent">.</span></span> --}}
    <img class="img-fluid w-100" src="{{ asset($headerLogo) }}" alt="Logo">
    <button type="button" class="offcanvas-close" data-bs-dismiss="offcanvas"><i class="fas fa-times"></i></button>
  </div>
  <div class="offcanvas-body">
    <ul class="mobile-nav-list">
      <li><a href="#about"    class="mob-link" data-bs-dismiss="offcanvas">About</a></li>
      <li class="mob-dropdown">
        <a class="mob-link mob-has-dropdown" data-bs-toggle="collapse" data-bs-target="#mobServices">
          Services <i class="fas fa-chevron-down ms-2"></i>
        </a>
        <div class="collapse" id="mobServices">
          <ul class="mob-sub-list">
            <li><a href="#skills" data-bs-dismiss="offcanvas"><i class="fab fa-laravel"></i> Laravel</a></li>
            <li><a href="#skills" data-bs-dismiss="offcanvas"><i class="fab fa-wordpress"></i> WordPress</a></li>
            <li><a href="#skills" data-bs-dismiss="offcanvas"><i class="fas fa-puzzle-piece"></i> Plugins</a></li>
            <li><a href="#skills" data-bs-dismiss="offcanvas"><i class="fas fa-paint-brush"></i> Themes</a></li>
            <li><a href="#skills" data-bs-dismiss="offcanvas"><i class="fas fa-bug"></i> Bug Fixing</a></li>
          </ul>
        </div>
      </li>
      <li><a href="#projects"  class="mob-link" data-bs-dismiss="offcanvas">Projects</a></li>
      <li><a href="#author"    class="mob-link" data-bs-dismiss="offcanvas">Marketplace</a></li>
      <li><a href="#products"  class="mob-link" data-bs-dismiss="offcanvas">Products</a></li>
      <li><a href="#contact"   class="mob-link" data-bs-dismiss="offcanvas">Contact</a></li>
      @auth
        <li><a href="{{ route('dashboard') }}" class="mob-link" data-bs-dismiss="offcanvas">Dashboard</a></li>
      @endauth
    </ul>
    <div class="mob-social">
      @if($settings?->github)
        <a href="{{ $settings->github }}" target="_blank" rel="noopener"><i class="fab fa-github"></i></a>
      @endif
      @if($settings?->linkedin)
        <a href="{{ $settings->linkedin }}" target="_blank" rel="noopener"><i class="fab fa-linkedin-in"></i></a>
      @endif
      @if($settings?->twitter)
        <a href="{{ $settings->twitter }}" target="_blank" rel="noopener"><i class="fab fa-twitter"></i></a>
      @endif
    </div>
  </div>
</div>
