<div class="modal fade" id="delete-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h3 class=" mt-3 text-warning">Delete !</h3>
                <p class="mb-3">Once delete, you can't get it back.</p>
                <input class="d-none" id="deleteID"/>
                <input class="d-none" id="deleteFilePath"/>

            </div>
            <div class="modal-footer d-flex justify-content-end">
                <div>
                    <button type="button" id="delete-modal-close" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button onclick="ContactDelete()" type="button" id="confirmDelete" class="btn btn-danger" >Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
async function ContactDelete(){

    try {
        let id = document.getElementById('deleteID').value;

        document.getElementById('delete-modal-close').click();

        let res = await axios.post('/contact-delete', {
            id: id
        }, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });

        if(res.data.status === 'success'){

            await getContactData(); // ✅ correct function

            Swal.fire({
                icon: 'success',
                title: res.data.message,
                timer: 1500,
                showConfirmButton: false
            });

        }else{
            Swal.fire({
                icon: 'error',
                title: res.data.message,
                timer: 2000,
                showConfirmButton: false
            });
        }

    } catch (error) {

        console.error(error);

        Swal.fire({
            icon: 'error',
            title: error.response?.data?.message ?? 'Delete failed',
            timer: 2000,
            showConfirmButton: false
        });
    }
}
</script>