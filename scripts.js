function resizeTextarea(textarea) {
    textarea.style.height = 'auto'; // Reset height to auto to allow shrinking
    textarea.style.height = textarea.scrollHeight + 'px'; // Set height to fit content
}

// Initialize the textarea size on page load
document.addEventListener('DOMContentLoaded', function() {
    var textareas = document.querySelectorAll('textarea');
    textareas.forEach(resizeTextarea);
});
