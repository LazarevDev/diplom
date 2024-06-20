<script src="js/sweetalert2@11.js"></script>


<?php 

function success($param){
    $arraySuccess = [
        'update' => ['Успех!', 'Информация обновлена', 'success'],
        'upload' => ['Успех!', 'Информация добавлена.', 'success'],
        'delete' => ['Успех!', 'Информация удалена', 'warning'],
        'error_auth' => ['Ошибка!', 'Неправильный логин или пароль', 'warning'],

        'upload_check' => ['Успех!', 'Отчет сформирован', 'success'],
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
                icon: '<?=success($_GET['success'])[2]?>',
                title: '<?=success($_GET['success'])[0]?>',
                text: '<?=success($_GET['success'])[1]?>',
                confirmButtonText: 'ОК',
                customClass: {
                    confirmButton: 'custom-ok-button'
                }
            });
        });
    </script>
<?php endif; ?>