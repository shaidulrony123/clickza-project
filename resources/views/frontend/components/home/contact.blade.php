<?php
  use App\Models\Settings;
  $settings = Settings::first();
  $email = $settings?->email ?: 'hello@example.com';
  $phone = $settings?->phone ?: '+8801234567890';
  $address = $settings?->address ?: 'Bangladesh';
?>
<!-- ══ CONTACT ══ -->
<section class="contact-section section-pad" id="contact">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <div class="section-label">— Get In Touch</div>
                <h2 class="section-title">Let's Build Something<br />Great Together</h2>
                <p class="about-text">Have a project in mind or need a bug fixed? My inbox is always open.</p>
                <div class="contact-info">
                    <div class="ci-item"><i class="fas fa-envelope"></i><span>{{ $email }}</span></div>
                    <div class="ci-item"><i class="fas fa-phone"></i><span>{{ $phone }}</span></div>
                    <div class="ci-item"><i class="fas fa-map-marker-alt"></i><span>{{ $address }}</span></div>
                </div>
	               <div class="social-links">
    @if($settings?->github)
        <a href="{{ $settings->github }}" target="_blank" rel="noopener"><i class="fab fa-github"></i></a>
    @endif
    @if($settings?->linkedin)
        <a href="{{ $settings->linkedin }}" target="_blank" rel="noopener"><i class="fab fa-linkedin-in"></i></a>
    @endif
    @if($settings?->twitter)
        <a href="{{ $settings->twitter }}" target="_blank" rel="noopener"><i class="fab fa-twitter"></i></a>
    @endif
    @if($settings?->facebook)
        <a href="{{ $settings->facebook }}" target="_blank" rel="noopener"><i class="fab fa-facebook"></i></a>
    @endif
	</div>
            </div>
            <div class="col-lg-7">
                <div class="contact-form-wrap">
                    <form id="contactForm">
                        <div class="form-row-custom">
                            <div class="form-group-custom">
                                <label>Your Name</label>
                                <input type="text" name="name" placeholder="Enter your name" required />
                            </div>
                            <div class="form-group-custom">
                                <label>Email Address</label>
                                <input type="email" name="email" placeholder="Enter your email" required />
                            </div>
                        </div>
                        <div class="form-group-custom">
                            <label>Subject</label>
                            <select name="subject">
                                <option value="">Select a subject</option>
                                <option value="wordpress">WordPress Help</option>
                                <option value="seo">SEO</option>
                                <option value="laravel">Laravel Project</option>
                                <option value="bug">Bug Fix</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group-custom">
                            <label>Message</label>
                            <textarea name="message" rows="5" placeholder="Tell me about your project..." required></textarea>
                        </div>

                        {{-- <button type="submit" class="btn-primary-custom w-100" id="sendBtn">
                            Send Message <i class="fas fa-paper-plane ms-2"></i>
                        </button> --}}
                        <button type="button" onclick="submitForm()" class="btn-primary-custom w-100" id="sendBtn">
                            Send Message <i class="fas fa-paper-plane ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function submitForm() {

    let form = document.getElementById('contactForm');
    let sendBtn = document.getElementById('sendBtn');
    let originalText = sendBtn.innerHTML;

    sendBtn.disabled = true;
    sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';

    axios.post('/contact-submit', {
        name: form.name.value,
        email: form.email.value,
        subject: form.subject.value,
        message: form.message.value
    }, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(function(response) {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
        text: response.data.message ?? 'Message sent successfully!',
            timer: 2000,
            showConfirmButton: false
        });

        form.reset();
    })
    .catch(function(error) {
        if (error.response && error.response.data.errors) {
            let errors = error.response.data.errors;
            let firstError = Object.values(errors)[0][0];

            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: firstError
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: 'Something went wrong!'
            });
        }
    })
    .finally(function() {
        sendBtn.disabled = false;
        sendBtn.innerHTML = originalText;
    });
}
</script>
