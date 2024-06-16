<script src="js/sweetalert2@11.js"></script>


<?php 

function success($param){
    $arraySuccess = [
        'update' => 'Информация обновлена',
        'upload' => 'Информация добавлена.',
        'delete' => 'Информация удалена',

        'upload_check' => 'Чек сформирован',
    ];

    return $arraySuccess[$param];
}

?>

<?php if (isset($_GET['success'])): ?>
    <script>
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
        
        window.addEventListener('load', function() {
            Swal.fire({
                icon: 'success',
                title: 'Успех!',
                text: '<?=success($_GET['success'])?>',
                confirmButtonText: 'ОК',
                customClass: {
                    confirmButton: 'custom-ok-button'
                }
            });
        });
    </script>
<?php endif; ?>