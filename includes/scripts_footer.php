<script src="<?php echo $path_prefix; ?>../js/bootstrap.bundle.min.js"></script>

<script>
function showToast(message, title="Success", iconClass='fa-check-circle'){
    const toast = document.getElementById('toast'); 
    const iconElement = document.getElementById('toast-icon'); 
    const titleElement = document.getElementById('toast-title'); 
    const messageElement = document.getElementById('toast-message'); 

    if(toast){
        iconElement.className = 'fas toast-icon ' + iconClass;
        messageElement.textContent = message;
        titleElement.textContent = title;

        toast.classList.add('show');

        setTimeout(hideToast, 5000);
    }
}

function hideToast(){
    const toast = document.getElementById('toast'); 
    if(toast){
        toast.classList.remove('show');
    }
}
</script>