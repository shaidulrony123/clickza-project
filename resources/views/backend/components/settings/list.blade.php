<div class="dash-section active" id="sec-projects">
    <div class="dash-card">
        <h5 class="mb-4">Settings Section</h5>
        <div class="row">

            <!-- Header logo -->
            <div class="col-lg-6">
                <div class="form-group-d mb-3">
                    <label>Header Logo</label>
                    <input type="file" class="form-control" id="header_logo" accept="image/*" />
                    <div id="current_header_logo" class="mt-2"></div> <!-- Existing Header Logo Preview -->
                </div>
            </div>
            <!-- footer logo -->
            <div class="col-lg-6">
                <div class="form-group-d mb-3">
                    <label>Footer Logo</label>
                    <input type="file" class="form-control" id="footer_logo" accept="image/*" />
                    <div id="current_footer_logo" class="mt-2"></div> <!-- Existing Footer Logo Preview -->
                </div>
            </div>

           {{-- favicon --}}
            <div class="col-lg-6">
                <div class="form-group-d mb-3">
                    <label>Favicon</label>
                    <input type="file" class="form-control" id="favicon" accept="image/*" />
                    <div id="current_favicon" class="mt-2"></div> <!-- Existing Favicon Preview -->
                </div>
            </div>

            <!-- email -->
            <div class="col-lg-6">
                <div class="form-group-d mb-3">
                    <label>Email</label>
                    <input type="email" class="form-control" id="email" />
                </div>
            </div>

             <!-- phone -->
             <div class="col-lg-6">
                <div class="form-group-d mb-3">
                    <label>Phone</label>
                    <input type="text" class="form-control" id="phone" />
                </div>
            </div>
            {{-- address --}}
            <div class="col-lg-6">
                <div class="form-group-d mb-3">
                    <label>Address</label>
                    <input type="text" class="form-control" id="address" />
                </div>
            </div>
            {{-- copyright --}}
            <div class="col-lg-6">
                <div class="form-group-d mb-3">
                    <label>Copyright</label>
                    <input type="text" class="form-control" id="copyright" />
                </div>
            </div>
            {{-- facebook link --}}
            <div class="col-lg-6">
                <div class="form-group-d mb-3">
                    <label>Facebook Link</label>
                    <input type="text" class="form-control" id="facebook_link" />
                </div>
            </div>
            {{-- twitter link --}}
            <div class="col-lg-6">
                <div class="form-group-d mb-3">
                    <label>Twitter Link</label>
                    <input type="text" class="form-control" id="twitter_link" />
                </div>
            </div>
            {{-- linkedin link --}}
            <div class="col-lg-6">
                <div class="form-group-d mb-3">
                    <label>LinkedIn Link</label>
                    <input type="text" class="form-control" id="linkedin_link" />
                </div>
            </div>
            {{-- github link --}}
            <div class="col-lg-6">
                <div class="form-group-d mb-3">
                    <label>GitHub Link</label>
                    <input type="text" class="form-control" id="github_link" />
                </div>
            </div>
            {{-- youtube link --}}
            <div class="col-lg-6">
                <div class="form-group-d mb-3">
                    <label>YouTube Link</label>
                    <input type="text" class="form-control" id="youtube_link" />
                </div>
            </div>
            {{-- whatsapp link --}}
            <div class="col-lg-6">
                <div class="form-group-d mb-3">
                    <label>WhatsApp Link</label>
                    <input type="text" class="form-control" id="whatsapp_link" />
                </div>
            </div>
            <!-- Save Button -->
            <div class="col-lg-12">
                <button onclick="updateSettings()" class="btn-primary-d">Save</button>
            </div>
        </div>
    </div>
</div>
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
<script>
async function loadSettingsData() {
    try {
        let res = await axios.get('/settings-list');
        let data = res.data.data;

        if (!data) return;

        document.getElementById('email').value = data.email ?? '';
        document.getElementById('phone').value = data.phone ?? '';
        document.getElementById('address').value = data.address ?? '';
        document.getElementById('copyright').value = data.copyright ?? '';

        document.getElementById('facebook_link').value = data.facebook ?? '';
        document.getElementById('twitter_link').value = data.twitter ?? '';
        document.getElementById('linkedin_link').value = data.linkedin ?? '';
        document.getElementById('github_link').value = data.github ?? '';
        document.getElementById('youtube_link').value = data.youtube ?? '';
        document.getElementById('whatsapp_link').value = data.whatsapp ?? '';

        if (data.header_logo) {
            document.getElementById('current_header_logo').innerHTML =
                `<img src="/${data.header_logo}" width="80">`;
        }

        if (data.footer_logo) {
            document.getElementById('current_footer_logo').innerHTML =
                `<img src="/${data.footer_logo}" width="80">`;
        }

        if (data.favicon) {
            document.getElementById('current_favicon').innerHTML =
                `<img src="/${data.favicon}" width="50">`;
        }

    } catch (error) {
        console.error('Load settings error:', error);
    }
}

async function updateSettings() {
    try {
        let formData = new FormData();

        formData.append('email', document.getElementById('email').value);
        formData.append('phone', document.getElementById('phone').value);
        formData.append('address', document.getElementById('address').value);
        formData.append('copyright', document.getElementById('copyright').value);

        formData.append('facebook', document.getElementById('facebook_link').value);
        formData.append('twitter', document.getElementById('twitter_link').value);
        formData.append('linkedin', document.getElementById('linkedin_link').value);
        formData.append('github', document.getElementById('github_link').value);
        formData.append('youtube', document.getElementById('youtube_link').value);
        formData.append('whatsapp', document.getElementById('whatsapp_link').value);

        let headerLogo = document.getElementById('header_logo').files[0];
        if (headerLogo) {
            formData.append('header_logo', headerLogo);
        }

        let footerLogo = document.getElementById('footer_logo').files[0];
        if (footerLogo) {
            formData.append('footer_logo', footerLogo);
        }

        let favicon = document.getElementById('favicon').files[0];
        if (favicon) {
            formData.append('favicon', favicon);
        }

        let res = await axios.post('/settings-save', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });

        if (res.data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: res.data.message ?? 'Settings saved successfully',
                timer: 1500,
                showConfirmButton: false
            });

            await loadSettingsData();
        } else {
            Swal.fire({
                icon: 'error',
                title: res.data.message ?? 'Save failed',
                timer: 2000,
                showConfirmButton: false
            });
        }

    } catch (error) {
        console.error('Save settings error:', error);

        Swal.fire({
            icon: 'error',
            title: error.response?.data?.message ?? 'Something went wrong!',
            timer: 2000,
            showConfirmButton: false
        });
    }
}

window.onload = function () {
    loadSettingsData();
};
</script>