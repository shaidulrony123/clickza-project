<?php 
    use App\Models\Product;
    use App\Models\Settings;

    $products = Product::where('status', 1)->get();
    $settings = Settings::first();
    $contactPhone = $settings?->phone ?: '+8801234567890';
    $contactPhoneDigits = preg_replace('/\D+/', '', $contactPhone);
    $contactEmail = $settings?->email ?: 'hello@example.com';
    $contactLogo = $settings?->header_logo ?: 'frontend/assets/images/logo.jpeg';
    $whatsappNumber = preg_replace('/\D+/', '', $settings?->whatsapp ?: $contactPhone);
?>

<!-- ══ PRODUCTS ══ -->
<section class="products-section section-pad" id="products">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label">— Ready to Use</div>
            <h2 class="section-title">Premium Products</h2>
            <p class="section-sub">Hand-crafted WordPress plugins &amp; Laravel packages ready for production</p>
        </div>

        <div class="products-grid">
            @forelse($products as $product)
                @php
                    $tags = [];

                    try {
                        $tags = is_array($product->tag) ? $product->tag : json_decode($product->tag, true);
                    } catch (\Exception $e) {
                        $tags = [];
                    }

                    $tags = is_array($tags) ? $tags : [];
                    $badgeText = strtolower($product->badge ?? '');

                    $badgeClass = '';
                    $btnClass = 'btn-buy';

                    if (str_contains($badgeText, 'best')) {
                        $badgeClass = 'badge-hot';
                        $btnClass = 'btn-buy btn-best';
                    } elseif (str_contains($badgeText, 'featured')) {
                        $badgeClass = 'badge-new';
                        $btnClass = 'btn-buy btn-featured';
                    } elseif (str_contains($badgeText, 'popular')) {
                        $badgeClass = 'badge-sale';
                        $btnClass = 'btn-buy btn-popular';
                    }
                @endphp

                <div class="product-card {{ str_contains($badgeText, 'featured') ? 'featured-product' : '' }}">
                    
                    @if($product->badge)
                        <div class="product-badge {{ $badgeClass }}">
                            {{ $product->badge }}
                        </div>
                    @endif

                    @if($product->image)
                        <div class="product-img">
                            <img class="img-fluid" src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                        </div>
                    @else
                        <div class="product-img">
                            <img class="img-fluid" src="{{ asset('frontend/assets/images/default.jpg') }}" alt="{{ $product->name }}">
                        </div>
                    @endif

                    @if($product->icon)
                        <div class="product-icon">
                            <i class="{{ $product->icon }}"></i>
                        </div>
                    @endif

                    <div class="product-body">
                        @if(count($tags) > 0)
                            <div class="product-tags">
                                @foreach($tags as $tag)
                                    <span>{{ $tag }}</span>
                                @endforeach
                            </div>
                        @endif

                        <h4>{{ $product->name }}</h4>
                        <p>{{ $product->description }}</p>

                        @if(!empty($product->long_description))
                            <div class="product-features">
                                {!! nl2br(e($product->long_description)) !!}
                            </div>
                        @endif

                        @if($product->live_link)
                            <a href="{{ $product->live_link }}" target="_blank" rel="noopener" class="live-btn-buy mb-3">
                                <i class="fas fa-eye me-2"></i>Live Preview
                            </a>
                        @endif
                    </div>

                    <div class="product-footer">
                        <div class="product-price">
                            @if($product->discount && $product->discount > 0)
                                <span class="price-old">${{ number_format($product->price, 0) }}</span>
                                <span class="price-now">${{ number_format($product->discount, 0) }}</span>
                            @else
                                <span class="price-now">${{ number_format($product->price, 0) }}</span>
                            @endif
                        </div>

                        <a href="javascript:void(0)"
                           class="{{ $btnClass }}"
                           onclick="openContactModal(@js($product->name))">
                            <i class="fas fa-shopping-cart me-2"></i>Buy Now
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center w-100">
                    <p>No products found.</p>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-5">
            <p class="text-muted-custom mb-3">Need something custom? Let's talk.</p>
            <a href="#contact" class="btn-ghost-custom">
                Request Custom Work <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- ══ CONTACT MODAL ══ -->
<div class="modal fade" id="buyNowContactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content contact-modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title w-100 text-center">Contact For Purchase</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body text-center pt-2">
                <div class="contact-logo-wrap mb-3">
                    <img src="{{ asset($contactLogo) }}" alt="Logo" class="contact-logo">
                </div>

                <h4 id="modalProductName" class="mb-2"></h4>
                <p class="text-muted mb-4">To buy this product, contact us through any option below.</p>

                <div class="contact-info-list">
                    <a href="tel:{{ $contactPhoneDigits }}" class="contact-btn phone-btn">
                        <i class="fas fa-phone-alt me-2"></i>
                        <span>{{ $contactPhone }}</span>
                    </a>

                    <a href="mailto:{{ $contactEmail }}" class="contact-btn email-btn">
                        <i class="fas fa-envelope me-2"></i>
                        <span>{{ $contactEmail }}</span>
                    </a>

                    @if($settings?->facebook)
                        <a href="{{ $settings->facebook }}" target="_blank" rel="noopener" class="contact-btn facebook-btn">
                            <i class="fab fa-facebook-f me-2"></i>
                            <span>Facebook Page</span>
                        </a>
                    @endif

                    @if($settings?->youtube)
                        <a href="{{ $settings->youtube }}" target="_blank" rel="noopener" class="contact-btn youtube-btn">
                            <i class="fab fa-youtube me-2"></i>
                            <span>YouTube Channel</span>
                        </a>
                    @endif

                    <a href="https://wa.me/{{ $whatsappNumber }}?text=I want to buy this product" target="_blank" rel="noopener" class="contact-btn whatsapp-btn">
                        <i class="fab fa-whatsapp me-2"></i>
                        <span>WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-best {
        background: #ff4d4f !important;
        color: #fff !important;
        border: none;
    }

    .btn-featured {
        background: linear-gradient(135deg, #ff4d6d, #ff7a45) !important;
        color: #fff !important;
        border: none;
    }

    .btn-popular {
        background: #16a34a !important;
        color: #fff !important;
        border: none;
    }

    .btn-best:hover,
    .btn-featured:hover,
    .btn-popular:hover,
    .btn-buy:hover {
        opacity: 0.9;
        color: #fff !important;
    }

    .contact-modal-content {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        padding: 10px;
    }

    .contact-logo {
        max-height: 70px;
        max-width: 180px;
        object-fit: contain;
    }

    .contact-info-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .contact-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        padding: 12px 18px;
        border-radius: 12px;
        font-weight: 600;
        transition: 0.3s ease;
    }

    .contact-btn:hover {
        transform: translateY(-2px);
    }

    .phone-btn {
        background: #0d6efd;
        color: #fff;
    }

    .email-btn {
        background: #dc3545;
        color: #fff;
    }

    .facebook-btn {
        background: #1877f2;
        color: #fff;
    }

    .youtube-btn {
        background: #ff0000;
        color: #fff;
    }

    .whatsapp-btn {
        background: #25d366;
        color: #fff;
    }

    .phone-btn:hover,
    .email-btn:hover,
    .facebook-btn:hover,
    .youtube-btn:hover,
    .whatsapp-btn:hover {
        color: #fff;
    }
</style>

<script>
    function openContactModal(productName) {
        document.getElementById('modalProductName').innerText = productName;

        const whatsappBtn = document.querySelector('#buyNowContactModal .whatsapp-btn');
        whatsappBtn.href = "https://wa.me/{{ $whatsappNumber }}?text=" + encodeURIComponent("I want to buy " + productName);

        const modal = new bootstrap.Modal(document.getElementById('buyNowContactModal'));
        modal.show();
    }
</script>
