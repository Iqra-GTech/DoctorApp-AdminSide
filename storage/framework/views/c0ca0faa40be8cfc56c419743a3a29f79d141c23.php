

<?php echo $__env->make('Admin.Includes.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>;




<!-- Main Title Modal -->
<div class="modal fade" id="mainTitleModal" tabindex="-1" role="dialog" aria-labelledby="mainTitleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?php echo e(route('permissions.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="mainTitleModalLabel">Add Main Title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="main_title">Main Title:</label>
                    <input type="text" name="title" id="main_title" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create Main Title</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Subtitle Modal -->
<div class="modal fade" id="subtitleModal" tabindex="-1" role="dialog" aria-labelledby="subtitleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?php echo e(route('permissions.subtitles.store', ['titleId' => $mainTitle->id])); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="subtitleModalLabel">Add Subtitle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="subtitle">Subtitle:</label>
                    <input type="text" name="text" id="subtitle" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create Subtitle</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>



<?php echo $__env->make('Admin.Includes.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script>
// document.addEventListener('DOMContentLoaded', function() {
//         const subtitlesContainer = document.getElementById('subtitlesContainer');
//         const addSubtitleButton = document.getElementById('addSubtitle');

//         addSubtitleButton.addEventListener('click', function() {
//             const subtitleInput = document.createElement('input');
//             subtitleInput.type = 'text';
//             subtitleInput.name = 'subtitles[]';
//             subtitleInput.placeholder = 'Subtitle';
//             subtitlesContainer.appendChild(subtitleInput);
//         });
//     });


$(document).ready(function() {
        // Show the main title modal when the button is clicked
        $('#createMainTitleButton').click(function() {
            $('#mainTitleModal').modal('show');
        });

        // Show the subtitle modal when a main title is clicked
        $('.main-title').click(function() {
            var titleId = $(this).data('title-id');
            $('#subtitleModal').modal('show');
            // You can use titleId to link the subtitle with the main title
        });
    });
</script>
</body>
<?php /**PATH D:\Gtech\admin side\TheDoctorApp\resources\views/Admin/Roles/subpermissions.blade.php ENDPATH**/ ?>