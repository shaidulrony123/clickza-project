<?php
use App\Models\Category;
use App\Models\Project;


$categories = Category::all();
$projects = Project::with('category')->get();


?>

<!-- ══ PROJECTS ══ -->
<section class="projects-section section-pad" id="projects">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-5">
            <div>
                <div class="section-label">— My Work</div>
                <h2 class="section-title mb-0">Recent Projects</h2>
            </div>

            <div class="project-filters">
                <button class="filter-btn active" data-filter="all">All</button>

                @foreach ($categories as $category)
                    <button class="filter-btn" data-filter="{{ \Illuminate\Support\Str::slug($category->name) }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="projects-grid" id="projectsGrid">
            @foreach ($projects as $project)
                @php
                    $teachStacks = [];

                    if (!empty($project->teach_stack)) {
                        $decoded = json_decode($project->teach_stack, true);
                        $teachStacks = is_array($decoded) ? $decoded : [];
                    }

                    $categorySlug = $project->category ? \Illuminate\Support\Str::slug($project->category->name) : 'uncategorized';
                @endphp

                <div class="project-card" data-cat="{{ $categorySlug }}">
                    <div class="project-img" style="background-image: url('{{ asset($project->image) }}'); background-size: cover; background-position: center;">
                        <div class="project-overlay">
                            @if($project->project_link)
                                <a href="{{ $project->project_link }}" target="_blank" rel="noopener" class="proj-link">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            @endif

                            @if($project->github_link)
                                <a href="{{ $project->github_link }}" target="_blank" rel="noopener" class="proj-link">
                                    <i class="fab fa-github"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="project-body">
                        <div class="proj-tags">
                            @foreach ($teachStacks as $stack)
                                <span>{{ $stack }}</span>
                            @endforeach
                        </div>

                        <h4>{{ $project->title }}</h4>
                        <p>{{ $project->description }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const projectCards = document.querySelectorAll('.project-card');

        filterButtons.forEach(button => {
            button.addEventListener('click', function () {
                const filter = this.getAttribute('data-filter');

                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                projectCards.forEach(card => {
                    const cardCategory = card.getAttribute('data-cat');

                    if (filter === 'all' || filter === cardCategory) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    });
</script>
