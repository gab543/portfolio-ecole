document.addEventListener('DOMContentLoaded', () => {

    // Initialize Quill if #editor exists
    const editor = document.getElementById('editor');
    if (editor) {
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
    }
    
    const filterBtns = document.querySelectorAll('.filter-btn');
    const projectCards = document.querySelectorAll('.project-card');

    if (filterBtns.length > 0) {
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Update active state
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                const filterValue = btn.getAttribute('data-filter');

                // Filter projects
                projectCards.forEach(card => {
                    const projectCategory = card.getAttribute('data-category');
                    if (filterValue === 'all' || filterValue === projectCategory) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    }

    // Labels / skills suggestions + pills in admin project form
    const labelInput = document.getElementById('label-input');
    const hiddenLabels = document.getElementById('labels');
    const selectedLabels = document.getElementById('selected-labels');
    const suggestionsWrapper = document.getElementById('suggestions-wrapper');
    const suggestionsList = document.getElementById('skills-suggestions');

    if (labelInput && hiddenLabels && selectedLabels && suggestionsWrapper && suggestionsList) {
        const allSkills = Array.isArray(window.ADMIN_SKILLS) ? window.ADMIN_SKILLS : [];
        let filteredSuggestions = [];

        const getCurrentLabels = () => Array.from(selectedLabels.querySelectorAll('.selected-pill')).map(el => el.dataset.value);

        const setHiddenLabels = () => {
            hiddenLabels.value = getCurrentLabels().join(',');
        };

        const createPill = label => {
            const pill = document.createElement('div');
            pill.className = 'selected-pill';
            pill.dataset.value = label;
            pill.innerHTML = `<span>${label}</span><button type="button" aria-label="Supprimer ${label}">&times;</button>`;
            pill.querySelector('button').addEventListener('click', () => {
                pill.remove();
                setHiddenLabels();
            });
            selectedLabels.appendChild(pill);
            setHiddenLabels();
        };

        const addLabel = value => {
            const label = value.trim();
            if (!label) return;
            const existing = getCurrentLabels();
            if (existing.includes(label)) return;
            createPill(label);
        };

        const renderSuggestions = suggestions => {
            suggestionsList.innerHTML = '';
            if (!suggestions.length) {
                suggestionsWrapper.style.display = 'none';
                return;
            }
            suggestions.forEach((skill, index) => {
                const item = document.createElement('button');
                item.textContent = skill;
                item.addEventListener('click', () => {
                    addLabel(skill);
                    labelInput.value = '';
                    suggestionsWrapper.style.display = 'none';
                });
                suggestionsList.appendChild(item);
            });
            suggestionsWrapper.style.display = 'block';
        };

        const updateSuggestions = () => {
            const query = labelInput.value.trim().toLowerCase();
            if (!query) {
                suggestionsWrapper.style.display = 'none';
                return;
            }
            filteredSuggestions = allSkills
                .filter(skill => skill.toLowerCase().includes(query))
                .filter(skill => !getCurrentLabels().includes(skill))
                .slice(0, 8);
            renderSuggestions(filteredSuggestions);
        };

        labelInput.addEventListener('input', updateSuggestions);

        labelInput.addEventListener('keydown', event => {
            if (event.key === 'Enter') {
                event.preventDefault();
                const value = labelInput.value.trim();
                if (value) {
                    const best = filteredSuggestions.find(s => s.toLowerCase() === value.toLowerCase()) || value;
                    addLabel(best);
                    labelInput.value = '';
                    suggestionsWrapper.style.display = 'none';
                }
            } else if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                const items = suggestionsList.querySelectorAll('li');
                if (!items.length) return;
                const active = suggestionsList.querySelector('li.active');
                let nextIndex = 0;
                if (active) {
                    active.classList.remove('active');
                    nextIndex = Array.from(items).indexOf(active);
                    nextIndex += event.key === 'ArrowDown' ? 1 : -1;
                }
                if (nextIndex < 0) nextIndex = items.length - 1;
                if (nextIndex >= items.length) nextIndex = 0;
                items[nextIndex].classList.add('active');
                event.preventDefault();
            } else if (event.key === 'Tab') {
                suggestionsWrapper.style.display = 'none';
            }
        });

        document.addEventListener('click', e => {
            if (!suggestionsWrapper.contains(e.target) && e.target !== labelInput) {
                suggestionsWrapper.style.display = 'none';
            }
        });

        // Init from existing tags
        const existingRaw = hiddenLabels.value;
        if (existingRaw) {
            existingRaw.split(',').map(item => item.trim()).filter(Boolean).forEach(addLabel);
        }
    }

});
