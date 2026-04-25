<?php
  use App\Models\Settings;
  use Illuminate\Support\Facades\Auth;

  $user = Auth::user();
  $settings = Settings::first();
?>
<!-- ══ SIDEBAR (Bootstrap Offcanvas — responsive on all sizes) ══ -->
<div class="offcanvas offcanvas-start dash-sidebar" tabindex="-1" id="dashSidebar" data-bs-backdrop="true">
    <div class="sidebar-brand">
        <span class="brand-text">
            <img class="img-fluid w-100"
                src="{{ $settings && $settings->header_logo ? asset($settings->header_logo) : asset('frontend/assets/images/logo.png') }}"
                alt="">
        </span>
        <button class="sidebar-close-btn" data-bs-dismiss="offcanvas"><i class="fas fa-times"></i></button>
    </div>
    <div class="sidebar-user">
        <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
        <div class="user-info"><strong>{{ Auth::user()->name }}</strong><small>Portfolio Admin</small></div>
        <div class="user-status"></div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-group-label">Main</div>
        <a href="{{ route('dashboard') }}" class="sidebar-link active" data-section="overview"
            data-bs-dismiss="offcanvas">
            <i class="fas fa-th-large"></i><span>Overview</span>
        </a>
        <a class="sidebar-link sidebar-dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#navProjects">
            <i class="fas fa-folder-open"></i><span>Projects</span>
            <span class="badge-count ms-auto me-1">6</span>
            <i class="fas fa-chevron-down nav-arrow"></i>
        </a>
        <div class="collapse sidebar-sub" id="navProjects">
            <a href="{{ route('project.section') }}" class="sidebar-sub-link">
                <i class="fas fa-list"></i> All Projects
            </a>
            <a href="{{ route('complete-project.section') }}" class="sidebar-sub-link">
                <i class="fas fa-plus"></i> Complete Projects
            </a>
            <a href="#" class="sidebar-sub-link"><i class="fas fa-archive"></i> Archived</a>
        </div>

        <a href="{{ route('about.section') }}" class="sidebar-link">
            <i class="fas fa-user"></i><span>About Section</span>
        </a>

        <a href="{{ route('product.section') }}" class="sidebar-link">
            <i class="fas fa-box-open"></i><span>Product Section</span>
        </a>
        <a href="{{ route('visitor.section') }}" class="sidebar-link">
            <i class="fas fa-box-open"></i><span>Visitor Section</span>
        </a>
        <a href="{{ route('clientsource.section') }}" class="sidebar-link">
            <i class="fas fa-users"></i><span>Client Source</span>
        </a>
        <a href="{{ route('invoice.section') }}" class="sidebar-link">
            <i class="fas fa-file-invoice-dollar"></i><span>Invoices</span>
        </a>
        <a class="sidebar-link sidebar-dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#navMessages">
            <i class="fas fa-envelope"></i><span>Messages</span>
            <span class="badge-count new ms-auto me-1">3</span>
            <i class="fas fa-chevron-down nav-arrow"></i>
        </a>

        <div class="collapse sidebar-sub" id="navMessages">
            <a href="{{ route('contact.section') }}" class="sidebar-sub-link">
                <i class="fas fa-inbox"></i> Inbox
            </a>

            <a href="#" class="sidebar-sub-link">
                <i class="fas fa-paper-plane"></i> Sent
            </a>

            <a href="#" class="sidebar-sub-link">
                <i class="fas fa-trash"></i> Trash
            </a>
        </div>

        <div class="nav-group-label mt-3">Settings</div>
        <a class="sidebar-link sidebar-dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#navSettings">
            <i class="fas fa-cog"></i><span>Settings</span>
            <i class="fas fa-chevron-down nav-arrow ms-auto"></i>
        </a>
        <div class="collapse sidebar-sub" id="navSettings">
            <a href="{{ route('profile.edit') }}" class="sidebar-sub-link">
                <i class="fas fa-user-circle"></i> Profile Settings
            </a>

            <a href="{{ route('settings.section') }}" class="sidebar-sub-link">
                <i class="fas fa-sliders-h"></i> Preferences
            </a>


        </div>
        <div class="sidebar-footer">

            <a href="{{ url('/') }}" class="sidebar-link">
                <i class="fas fa-external-link-alt"></i>
                <span>View Portfolio</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link logout"
                    style="border:none;background:none;width:100%;text-align:left;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>

        </div>
    </nav>
</div>
