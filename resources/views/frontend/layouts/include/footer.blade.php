<?php
  use App\Models\Settings;
  $settings = Settings::first();
  $footerLogo = $settings?->footer_logo ?: 'frontend/assets/images/logo.jpeg';
?>

<!-- ══ FOOTER ══ -->
<footer class="footer">
  <div class="container">
    <div class="footer-inner">
      <a class="logo-text" href="#">
			<img class="img-fluid w-100" src="{{ asset($footerLogo) }}" alt="Logo">
		  </a>
			<p>© {{ date('Y') }} {{ $settings?->copyright ?: 'All rights reserved.' }}</p>
      <div class="footer-links">
        <a href="#about">About</a>
        <a href="#projects">Work</a>
        <a href="#author">Marketplace</a>
        <a href="#products">Products</a>
        <a href="#contact">Contact</a>
        @auth
          <a href="{{ route('dashboard') }}">Dashboard</a>
        @endauth
      </div>
    </div>
  </div>
</footer>

<!-- ══ SCRIPTS ══ -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/TextPlugin.min.js"></script>

<script src="{{ asset('frontend/assets/js/script.js') }}"></script>
</body>
</html>
