export function initAdminLabels() {
    // Labels / skills suggestions + pills in admin project form
    const labelInput = document.getElementById('label-input');
    const hiddenLabels = document.getElementById('labels');
    const selectedLabels = document.getElementById('selected-labels');
    const suggestionsWrapper = document.getElementById('suggestions-wrapper');
    const suggestionsList = document.getElementById('skills-suggestions');

    if (labelInput && hiddenLabels && selectedLabels && suggestionsWrapper && suggestionsList) {
        const allSkills = Array.isArray(window.ADMIN_SKILLS) ? window.ADMIN_SKILLS : [];
        // Cache breaker v3
        console.log("AdminLabels initialisés avec", allSkills.length, "compétences disponibles.");
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
                item.type = 'button';
                item.className = 'suggestion-item';
                item.textContent = skill;
                
                // Mousedown is used instead of click so it fires BEFORE the input can lose focus
                item.addEventListener('mousedown', (e) => {
                    e.preventDefault(); 
                    addLabel(skill);
                    labelInput.value = '';
                    // On force la mise à jour pour recalculer les items restants
                    setTimeout(() => updateSuggestions(), 0);
                });
                suggestionsList.appendChild(item);
            });
            suggestionsWrapper.style.display = 'block';
        };

        const updateSuggestions = () => {
            const query = labelInput.value.trim().toLowerCase();
            
            // Filter out skills that are already selected
            filteredSuggestions = allSkills.filter(skill => !getCurrentLabels().includes(skill));

            // If there's a query, filter down further
            if (query) {
                filteredSuggestions = filteredSuggestions.filter(skill => skill.toLowerCase().includes(query));
            }

            renderSuggestions(filteredSuggestions);
        };

        labelInput.addEventListener('focus', updateSuggestions);
        labelInput.addEventListener('click', updateSuggestions);
        labelInput.addEventListener('input', updateSuggestions);

        labelInput.addEventListener('keydown', event => {
            if (event.key === 'Enter') {
                event.preventDefault();
                const value = labelInput.value.trim();
                const active = suggestionsList.querySelector('.active');
                
                // If an item is highlighted, add it. Otherwise try to find best match or add raw string
                if (active) {
                    addLabel(active.textContent);
                    labelInput.value = '';
                    updateSuggestions(); // Refresh the list without closing it (or you can close it)
                } else if (value) {
                    const best = filteredSuggestions.find(s => s.toLowerCase() === value.toLowerCase()) || value;
                    addLabel(best);
                    labelInput.value = '';
                    updateSuggestions(); 
                }
            } else if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                event.preventDefault();
                const items = suggestionsList.querySelectorAll('li, button');
                if (!items.length) return;
                const active = suggestionsList.querySelector('.active');
                let nextIndex = 0;
                if (active) {
                    active.classList.remove('active');
                    nextIndex = Array.from(items).indexOf(active);
                    nextIndex += event.key === 'ArrowDown' ? 1 : -1;
                }
                if (nextIndex < 0) nextIndex = items.length - 1;
                if (nextIndex >= items.length) nextIndex = 0;
                items[nextIndex].classList.add('active');
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
}
