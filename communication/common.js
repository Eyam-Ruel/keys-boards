document.addEventListener('DOMContentLoaded', () => {
    const addFilesBtn = document.getElementById('addFilesBtn');
    const filesDropdown = document.getElementById('filesDropdown');
    
    if (addFilesBtn && filesDropdown) {
        addFilesBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isVisible = filesDropdown.style.display === 'block';
            filesDropdown.style.display = isVisible ? 'none' : 'block';
        });

        document.addEventListener('click', () => {
            filesDropdown.style.display = 'none';
        });

        filesDropdown.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    }
});
