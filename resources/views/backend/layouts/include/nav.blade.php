  <?php
  use App\Models\Settings;
  use Illuminate\Support\Facades\Auth;

  $user = Auth::user();
  $settings = Settings::first();
?>
  <style>
    .dash-logo{
      width: 150px !important;
    }
  </style>
  <!-- TOPBAR -->
  <header class="topbar">
    <div class="topbar-left">
      <button class="hamburger-dash" data-bs-toggle="offcanvas" data-bs-target="#dashSidebar">
        <i class="fas fa-bars"></i>
      </button>
        <img class="img-fluid w-100 dash-logo"
                src="{{ $settings && $settings->header_logo ? asset($settings->header_logo) : asset('frontend/assets/images/logo.png') }}"
                alt="">
      <div class="page-title" id="pageTitle">Overview</div>
    </div>
    <div class="topbar-right">
      <div class="search-wrap d-none d-md-flex">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search..." id="dashSearch"/>
      </div>
      <!-- Notifications -->
      <div class="dropdown">
        <button class="topbar-btn" id="notifBtn" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fas fa-bell"></i><span class="notif-dot"></span>
        </button>
        <div class="dropdown-menu dropdown-menu-end topbar-dropdown">
          <div class="dropdown-header"><strong>Notifications</strong><span class="badge-count new">3 New</span></div>
          <a class="dropdown-item-custom" href="#">
            <div class="notif-icon n-blue"><i class="fas fa-envelope"></i></div>
            <div class="notif-text"><strong>New message</strong><p>James Davidson sent a message</p><small>2h ago</small></div>
          </a>
          <a class="dropdown-item-custom" href="#">
            <div class="notif-icon n-green"><i class="fas fa-star"></i></div>
            <div class="notif-text"><strong>New review</strong><p>5-star rating received</p><small>5h ago</small></div>
          </a>
          <a class="dropdown-item-custom" href="#">
            <div class="notif-icon n-purple"><i class="fas fa-eye"></i></div>
            <div class="notif-text"><strong>Milestone</strong><p>Portfolio reached 10k views</p><small>1d ago</small></div>
          </a>
          <div class="dropdown-footer"><a href="#">View all notifications</a></div>
        </div>
      </div>
      <!-- Theme -->
      <button class="topbar-btn" id="dashThemeToggle" title="Toggle Theme">
        <i class="fas fa-moon" id="dashThemeIcon"></i>
      </button>
      <!-- User -->
      <div class="dropdown">
        <button class="topbar-avatar-btn" data-bs-toggle="dropdown" aria-expanded="false">
          <div class="topbar-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
          <span class="d-none d-md-inline topbar-username">Admin</span>
          <i class="fas fa-chevron-down ms-1" style="font-size:10px;color:var(--text-muted)"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end topbar-dropdown">
          <div class="dropdown-header"><strong>{{ Auth::user()->name }} </strong><small></small></div>
          <a class="dropdown-item-custom" href="{{ route('profile.edit') }}">
            <div class="notif-icon n-blue"><i class="fas fa-user-circle"></i></div>
            <div class="notif-text"><strong>My Profile</strong><p>Manage your profile</p></div>
          </a>
          <a class="dropdown-item-custom" href="{{ route('settings.section') }}">
    <div class="notif-icon n-purple">
        <i class="fas fa-cog"></i>
    </div>
    <div class="notif-text">
        <strong>Settings</strong>
        <p>App preferences</p>
    </div>
</a>
          <a class="dropdown-item-custom" href="{{ url('/') }}">
            <div class="notif-icon n-green"><i class="fas fa-eye"></i></div>
            <div class="notif-text"><strong>View Portfolio</strong><p>Live preview</p></div>
          </a>
          <div class="dropdown-footer">
            <a href="{{ route('logout') }}" style="color:var(--accent3)" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="fas fa-sign-out-alt me-1"></i>Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
            </form>
          </div>
        </div>
      </div>
    </div>
  </header>
