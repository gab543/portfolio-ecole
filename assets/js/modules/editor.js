export function initEditor() {
    const editorEl = document.getElementById('editor') || document.getElementById('editor-container');
    if (!editorEl) return;

    // Check if Quill is available globally (loaded via <script> tag)
    if (typeof Quill !== 'undefined') {
        const quillId = editorEl.id;
        const quill = new Quill('#' + quillId, {
            theme: 'snow'
        });

        window.quill = quill; // Expose globally for form script

        // Sync with hidden textarea/input
        const descriptionInput = document.getElementById('description');
        if (descriptionInput) {
            // Set initial content if editing
            if (descriptionInput.value) {
                quill.root.innerHTML = descriptionInput.value;
            } else if (editorEl.innerHTML.trim() !== '') {
                // If it was rendered inside the div directly (like the updated project_form.phtml)
                descriptionInput.value = quill.root.innerHTML;
            }

            // Update textarea on change
            quill.on('text-change', function () {
                descriptionInput.value = quill.root.innerHTML;
            });

            // Ensure on form submit
            const form = editorEl.closest('form');
            if (form) {
                form.addEventListener('submit', function () {
                    descriptionInput.value = quill.root.innerHTML;
                });
            }
        }
    } else {
        console.warn('Quill is not defined. Editor will not be initialized.');
    }
}
