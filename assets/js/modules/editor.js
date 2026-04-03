export function initEditor() {
    const editor = document.getElementById('editor');
    if (!editor) return;

    // Check if Quill is available globally (loaded via <script> tag)
    if (typeof Quill !== 'undefined') {
        const quill = new Quill('#editor', {
            theme: 'snow'
        });

        // Sync with hidden textarea
        const descriptionTextarea = document.getElementById('description');
        if (descriptionTextarea) {
            // Set initial content if editing
            if (descriptionTextarea.value) {
                quill.root.innerHTML = descriptionTextarea.value;
            }

            // Update textarea on change
            quill.on('text-change', function() {
                descriptionTextarea.value = quill.root.innerHTML;
            });

            // Ensure on form submit
            const form = editor.closest('form');
            if (form) {
                form.addEventListener('submit', function() {
                    descriptionTextarea.value = quill.root.innerHTML;
                });
            }
        }
    } else {
        console.warn('Quill is not defined. Editor will not be initialized.');
    }
}
