<div class="dash-section active" id="sec-projects">
    <div class="dash-card">
        <h5 class="mb-4">About Section</h5>
        <div class="row">
            <!-- Title -->
            <div class="col-lg-6">
                <div class="form-group-d mb-3">
                    <label>Title</label>
                    <input type="text" class="form-control" id="about_title" placeholder="Enter Title" />
                </div>
            </div>
            <!-- Subtitle -->
            <div class="col-lg-6">
                <div class="form-group-d mb-3">
                    <label>Subtitle</label>
                    <input type="text" class="form-control" id="about_subtitle" placeholder="Enter Subtitle" />
                </div>
            </div>
            <!-- Description -->
            <div class="col-lg-12">
                <div class="form-group-d mb-3">
                    <label>Description</label>
                    <textarea class="form-control" id="about_description" rows="6" placeholder="Enter Description"></textarea>
                </div>
            </div>

            <!-- Image -->
            <div class="col-lg-6">
                <div class="form-group-d mb-3">
                    <label>Image</label>
                    <input type="file" class="form-control" id="about_image" accept="image/*" />
                    <div id="current_image" class="mt-2"></div> <!-- Existing Image Preview -->
                </div>
            </div>

            <!-- Download CV -->
            <div class="col-lg-6">
                <div class="form-group-d mb-3">
                    <label>Download CV</label>
                    <input type="file" class="form-control" id="about_cv" accept=".pdf,.doc,.docx" />
                    <div id="current_cv" class="mt-2"></div> <!-- Existing CV -->
                </div>
            </div>

            <!-- Tags (Multiple) -->
            <div class="col-lg-12">
                <div class="form-group-d mb-3">
                    <label>Tags</label>
                    <div id="tags_container"></div>
                    <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addTagInput()">+ Add Tag</button>
                </div>
            </div>

            <!-- Save Button -->
            <div class="col-lg-12">
                <button onclick="updateSave()" class="btn-primary-d">Save</button>
            </div>
        </div>
    </div>
</div>
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
<script>
let tagsArray = [];

// Load Existing Data
function loadAboutData() {
    axios.get('/about-list')
    .then(function (response) {
        console.log("Loaded Data:", response.data);

        let data = response.data.data;
        if (!data) return;

        document.getElementById('about_title').value       = data.title ?? '';
        document.getElementById('about_subtitle').value    = data.subtitle ?? '';
        document.getElementById('about_description').value = data.description ?? '';

        // Tags
        tagsArray = data.tag ? data.tag.split(',').map(t => t.trim()).filter(t => t) : [];
        renderTags();

        // Current Image Preview
        if (data.image) {
            document.getElementById('current_image').innerHTML = `
                <small>Current Image:</small><br>
                <img src="/${data.image}" alt="Current Image" style="max-width:150px; max-height:100px; border-radius:5px;">
            `;
        }

        // Current CV
        if (data.downloadcv) {
            const cvPath = data.downloadcv;
            document.getElementById('current_cv').innerHTML = `
                <small>Current CV:</small><br>
                <a href="/${cvPath}" target="_blank" class="text-info">📄 Download Current CV</a>
            `;
        }
    })
    .catch(function (error) {
        console.error("Load Error:", error);

        Swal.fire({
            icon: 'error',
            title: 'Load Failed',
            text: 'Data load korte problem hocche!',
            confirmButtonText: 'OK'
        });
    });
}

// Render Tags as removable inputs
function renderTags() {
    const container = document.getElementById('tags_container');
    container.innerHTML = '';

    tagsArray.forEach((tag, index) => {
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
            <input type="text" class="form-control" value="${tag}" onchange="updateTag(${index}, this.value)">
            <button type="button" class="btn btn-danger" onclick="removeTag(${index})">×</button>
        `;
        container.appendChild(div);
    });
}

function addTagInput() {
    tagsArray.push('');
    renderTags();
}

function updateTag(index, value) {
    tagsArray[index] = value.trim();
}

function removeTag(index) {
    tagsArray.splice(index, 1);
    renderTags();
}

// Save Function
function updateSave() {
    let formData = new FormData();

    formData.append('title',       document.getElementById('about_title').value);
    formData.append('subtitle',    document.getElementById('about_subtitle').value);
    formData.append('description', document.getElementById('about_description').value);
    
    // Multiple Tags as comma separated
    formData.append('tag', tagsArray.filter(t => t !== '').join(','));

    // Image
    let imageFile = document.getElementById('about_image').files[0];
    if (imageFile) formData.append('image', imageFile);

    // CV
    let cvFile = document.getElementById('about_cv').files[0];
    if (cvFile) formData.append('downloadcv', cvFile);

    axios.post('/about-save', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(function (response) {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: response.data.message || 'Saved successfully!',
            confirmButtonText: 'OK'
        });

        loadAboutData();
    })
    .catch(function (error) {
        console.error("Save Error:", error);

        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: error.response?.data?.message || 'Something went wrong! Check console.',
            confirmButtonText: 'OK'
        });
    });
}

// Initialize
window.onload = function() {
    loadAboutData();
};
</script>