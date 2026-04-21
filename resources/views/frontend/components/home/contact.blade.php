<?php
  use App\Models\Settings;
  $settings = Settings::first();
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
                    <div class="ci-item"><i class="fas fa-envelope"></i><span>{{ $settings->email }}</span></div>
                    <div class="ci-item"><i class="fas fa-phone"></i><span>{{ $settings->phone }}</span></div>
                    <div class="ci-item"><i class="fas fa-map-marker-alt"></i><span>{{ $settings->address }}</span></div>
                </div>
               <div class="social-links">
    <a href="{{ $settings->github }}" target="_blank"><i class="fab fa-github"></i></a>
    <a href="{{ $settings->linkedin }}" target="_blank"><i class="fab fa-linkedin-in"></i></a>
    <a href="{{ $settings->twitter }}" target="_blank"><i class="fab fa-twitter"></i></a>
    <a href="{{ $settings->facebook }}" target="_blank"><i class="fab fa-facebook"></i></a>
</div>
            </div>
            <div class="col-lg-7">
                <div class="contact-form-wrap">
                    <form id="contactForm">
                        <div class="form-row-custom">
                            <div class="form-group-custom">
                                <label>Your Name</label>
                                <input type="text" name="name" placeholder="Enter your name" />
                            </div>
                            <div class="form-group-custom">
                                <label>Email Address</label>
                                <input type="email" name="email" placeholder="Enter your email" />
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
                            <textarea name="message" rows="5" placeholder="Tell me about your project..."></textarea>
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

    sendBtn.disabled = true;

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
            text: 'Message sent successfully!',
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
    });
}
</script>