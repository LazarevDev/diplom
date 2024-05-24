<script src="js/sweetalert2@11.js"></script>


<?php if (isset($_GET['success'])): ?>
    <script>
        window.addEventListener('load', function() {
            Swal.fire({
                icon: 'success',
                title: 'Успех!',
                text: '<?php if($_GET['success'] == "update"){ echo "Информация обновлена"; }elseif($_GET['success'] == "delete"){ echo "Информация удалена"; }elseif($_GET['success'] == "upload"){ echo "Информация добавлена."; }; ?>',
                confirmButtonText: 'ОК',
                customClass: {
                    confirmButton: 'custom-ok-button'
                }
            });
        });

        function showDeleteConfirmation(deleteUrl) {
            Swal.fire({
                title: 'Вы уверены?',
                text: "Это действие необратимо!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Да, удалить!',
                cancelButtonText: 'Отмена',
                customClass: {
                    confirmButton: 'custom-delete-button',
                    cancelButton: 'custom-cancel-button'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl;
                }
            });
        }
    </script>
<?php endif; ?>