<?php
  use App\Models\Marketplace;
  $marketplaces = Marketplace::where('is_active', true)->get();
  
?>

<!-- ══ MARKETPLACE AUTHOR PROFILES ══ -->
<section class="author-section section-pad" id="author">
  <div class="container">
    <div class="text-center mb-5">
      <div class="section-label">— Find My Work On</div>
      <h2 class="section-title">Marketplace Profiles</h2>
      <p class="section-sub">Browse premium templates &amp; plugins on top digital marketplaces worldwide</p>
    </div>

    @php
      $totalItems = $marketplaces->sum('item');
      $totalSales = $marketplaces->sum('sales');
      $avgRating = $marketplaces->count() ? number_format($marketplaces->avg('rating'), 1) : 0;
      $happyBuyers = $marketplaces->sum('sales');
    @endphp

    <div class="author-grid">

      @foreach ($marketplaces as $item)
        @php
          $tags = [];
          try {
              $tags = $item->tag ? json_decode($item->tag, true) : [];
          } catch (\Exception $e) {
              $tags = [];
          }
        @endphp

        <div class="author-card">
          <div class="author-card-inner">
            <div class="author-platform-badge tm-badge">
              {{ $item->badge ?? 'Marketplace' }}
            </div>

            <div class="author-top">
              <div class="author-avatar tm-avatar">
                <i class="fas fa-user-tie"></i>
              </div>

              @if($item->is_active)
                <div class="author-verified">
                  <i class="fas fa-check"></i>
                </div>
              @endif
            </div>

            <div class="author-info">
              <h4 class="author-name">{{ $item->user_name }}</h4>
              <p class="author-role">{{ $item->description }}</p>
            </div>

            <div class="author-stats-row">
              <div class="author-stat">
                <span class="astat-num">{{ $item->item ?? 0 }}</span>
                <p>Items</p>
              </div>

              <div class="astat-sep"></div>

              <div class="author-stat">
                <span class="astat-num">{{ $item->sales ?? 0 }}</span>
                <p>Sales</p>
              </div>

              <div class="astat-sep"></div>

              <div class="author-stat">
                <span class="astat-num">
                  {{ $item->rating ?? 0 }}
                  <i class="fas fa-star" style="font-size:11px;color:#ffd700"></i>
                </span>
                <p>Rating</p>
              </div>
            </div>

            <div class="author-tags">
              @foreach ($tags as $tag)
                <span>{{ $tag }}</span>
              @endforeach
            </div>

            <a href="{{ $item->url }}" target="_blank" rel="noopener" class="author-btn tm-btn">
              <i class="fas fa-external-link-alt me-2"></i>View Profile
            </a>
          </div>

          <div class="author-card-glow tm-glow"></div>
        </div>
      @endforeach

    </div>

    <!-- Combined totals bar -->
    <div class="author-combined">
      <div class="combined-item">
        <div class="combined-icon"><i class="fas fa-box-open"></i></div>
        <div><span class="combined-num" data-target="{{ $totalItems }}">0</span><span class="combined-plus">+</span><p>Total Items</p></div>
      </div>
      <div class="combined-divider"></div>
      <div class="combined-item">
        <div class="combined-icon" style="color:var(--accent2)"><i class="fas fa-download"></i></div>
        <div><span class="combined-num" data-target="{{ $totalSales }}">0</span><span class="combined-plus">+</span><p>Total Sales</p></div>
      </div>
      <div class="combined-divider"></div>
      <div class="combined-item">
        <div class="combined-icon" style="color:#ffd700"><i class="fas fa-star"></i></div>
        <div><span class="combined-num" data-target="{{ $avgRating }}">0</span><p>Avg Rating</p></div>
      </div>
      <div class="combined-divider"></div>
      <div class="combined-item">
        <div class="combined-icon" style="color:var(--accent3)"><i class="fas fa-users"></i></div>
        <div><span class="combined-num" data-target="{{ $happyBuyers }}">0</span><span class="combined-plus">+</span><p>Happy Buyers</p></div>
      </div>
    </div>

  </div>
</section>