<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<!-- GSAP -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

<!-- Chart -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

<!-- Custom JS -->
<script src="{{ asset('frontend/assets/js/dashboard.js') }}"></script>

<script>
    $(document).ready(function () {
        if ($('#summernote').length) {
            $('#summernote').summernote({
                placeholder: 'Write something...',
                tabsize: 2,
                height: 220
            });
        }

        if ($('.summernote').length) {
            $('.summernote').summernote({
                placeholder: 'Write something...',
                tabsize: 2,
                height: 220
            });
        }
    });
</script>

@stack('scripts')