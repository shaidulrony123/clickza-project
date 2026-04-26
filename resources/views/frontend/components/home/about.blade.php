<!-- ══ ABOUT ══ -->
<section class="about-section section-pad" id="about">
    <div class="container">
        <div class="row align-items-center g-5">
            
            <!-- Left Side - Image -->
            <div class="col-lg-5">
                <div class="about-img-wrap">
                    <div class="about-img-bg"></div>
                    <div class="about-img-inner">
                        <img id="about_image_front" src="" alt="About Image">
                    </div>
                    <div class="exp-badge">
                        <strong>5+</strong>
                        <small>Years of<br />Experience</small>
                    </div>
                </div>
            </div>

            <!-- Right Side - Content -->
            <div class="col-lg-7">
                <div class="section-label title" id="about_title_front">— 
                    {{--  --}}
                </div>
                
                <h2 id="about_subtitle_front" class="section-title subtitle">
                    <!-- Dynamic Subtitle -->
                </h2>
                
                <p id="about_description_front" class="about-text description">
                    <!-- Dynamic Description -->
                </p>

                <!-- Tags -->
                <div id="about_tags_front" class="about-tags tag">
                    <!-- Dynamic Tags will be added by JavaScript -->
                </div>

                {{-- <a id="download_cv_btn" href="#" class="btn-primary-custom mt-4 d-inline-block" download>
                    Download CV <i class="fas fa-download ms-2"></i>
                </a> --}}
                <a id="download_cv_btn" href="#" 
   class="btn-primary-custom mt-4 d-inline-block"
   download>
    Download CV <i class="fas fa-download ms-2"></i>
</a>
            </div>
        </div>
    </div>
</section>


<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
function loadAboutSection() {
    axios.get('/about-list')
        .then(function (response) {
            let data = response.data.data;
            if (!data) return;

            // Image
            if (data.image) {
                document.getElementById('about_image_front').src = '/' + data.image;
            }

            // Title
            if (data.title) {
                document.getElementById('about_title_front').textContent = data.title;
            }

            // Subtitle
            if (data.subtitle) {
                document.getElementById('about_subtitle_front').textContent = data.subtitle;
            }

            // Description
            if (data.description) {
                document.getElementById('about_description_front').textContent = data.description;
            }

            // Tags
            const tagsContainer = document.getElementById('about_tags_front');
            tagsContainer.innerHTML = '';

            if (data.tag) {
                const tags = data.tag.split(',').map(tag => tag.trim()).filter(tag => tag !== '');
                tags.forEach(tag => {
                    const span = document.createElement('span');
                    const icon = document.createElement('i');
                    icon.className = 'fas fa-tag me-1';
                    span.appendChild(icon);
                    span.appendChild(document.createTextNode(tag));
                    tagsContainer.appendChild(span);
                });
            }

            // Download CV Button - FIXED
            const cvBtn = document.getElementById('download_cv_btn');
            
            if (data.cv || data.downloadcv) {
                const cvPath = data.cv || data.downloadcv;   // দুটোর যেকোনো একটা থাকলে
                
                cvBtn.href = '/' + cvPath;
                cvBtn.setAttribute('download', '');           // ← এটি সবচেয়ে গুরুত্বপূর্ণ
                cvBtn.style.display = 'inline-block';
            } else {
                cvBtn.style.display = 'none';
            }
        })
        .catch(function (error) {
            console.error("Frontend About Load Error:", error);
        });
}

// Page load হওয়ার সাথে সাথে ডেটা লোড করবে
document.addEventListener('DOMContentLoaded', function() {
    loadAboutSection();
});
</script>
