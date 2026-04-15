// Small UX enhancements for Figma-styled components
// - Close dropdowns on Escape
// - Focus first item when dropdown opens via click
// - Minor touch helper for Kanban cards

(function () {
    function dispatchCloseEvent() {
        // Components listen for a `close` event (see dropdown component)
        document.dispatchEvent(new CustomEvent('close'));
    }

    // Close on Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' || e.key === 'Esc') {
            dispatchCloseEvent();
        }
    });

    // When a trigger is clicked, focus the first focusable element in the menu
    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('[data-dropdown-trigger]');
        if (!trigger) return;

        // Defer to allow Alpine to set `open` state
        setTimeout(() => {
            const wrapper = trigger.closest('.relative');
            if (!wrapper) return;
            const menu = wrapper.querySelector('[data-dropdown-menu]');
            if (!menu) return;

            // Find first focusable element
            const focusable = menu.querySelector('a, button, input, [tabindex]:not([tabindex="-1"])');
            if (focusable) focusable.focus();
        }, 50);
    });

    // Improve Kanban touch cursor feedback (non-destructive)
    function enhanceKanban() {
        const cards = document.querySelectorAll('.kanban-card');
        cards.forEach(card => {
            card.addEventListener('touchstart', function () {
                card.classList.add('touching');
            }, { passive: true });
            card.addEventListener('touchend', function () {
                card.classList.remove('touching');
            });
        });
    }

    // Run initializers on DOMContentLoaded and after Vite HMR updates
    document.addEventListener('DOMContentLoaded', function () {
        enhanceKanban();
    });

    // Expose a small API for manual triggers/tests
    window.FigmaInteractions = {
        closeAllDropdowns: dispatchCloseEvent,
        enhanceKanban
    };
})();

// Kanban drag/drop logic moved from Blade into this module.
(function () {
    // Config expected on window.KanbanConfig: { csrfToken, currentUserId }
    const getConfig = () => window.KanbanConfig || { csrfToken: null, currentUserId: null };

    function kanbanDragStart(e) {
        const card = e.currentTarget;
        e.dataTransfer.setData('text/plain', card.dataset.taskId);
        e.dataTransfer.effectAllowed = 'move';
        card.classList.add('dragging');
    }

    async function kanbanDrop(e) {
        e.preventDefault();
        const columnEl = e.currentTarget;
        const column = columnEl.dataset.column;
        const taskId = e.dataTransfer.getData('text/plain');
        if (!taskId) return;

        const list = columnEl.querySelector('.kanban-list');
        const placeholder = list.querySelector('.kanban-placeholder');
        let position = 1;
        if (placeholder && !placeholder.classList.contains('hidden')) {
            const children = Array.from(list.querySelectorAll('.kanban-card'));
            position = children.indexOf(placeholder.nextElementSibling) + 1 || children.length + 1;
        } else {
            const existing = Array.from(list.querySelectorAll('.kanban-card'));
            position = existing.length + 1;
        }

        const cfg = getConfig();
        const payload = { column: column, position: position };
        if (column === 'in_progress' && cfg.currentUserId) payload.assigned_to = cfg.currentUserId;

        const res = await fetch(`/tasks/${taskId}/move`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': cfg.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        if (res.ok) {
            const card = document.getElementById('task-card-' + taskId);
            const listContainer = columnEl.querySelector('.kanban-list');
            if (card && listContainer) {
                const ph = listContainer.querySelector('.kanban-placeholder');
                if (ph && !ph.classList.contains('hidden')) {
                    ph.parentNode.insertBefore(card, ph.nextElementSibling);
                } else {
                    listContainer.appendChild(card);
                }
            }
            columnEl.classList.remove('drag-over');
            if (list) {
                const ph = list.querySelector('.kanban-placeholder');
                if (ph) ph.classList.add('hidden');
            }
        } else {
            console.error('Failed to move task', await res.text());
            alert('Could not move task. Check permissions.');
        }
    }

    async function assignToMe(taskId) {
        const cfg = getConfig();
        const payload = { column: 'in_progress', assigned_to: cfg.currentUserId };
        const res = await fetch(`/tasks/${taskId}/move`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': cfg.csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify(payload)
        });
        if (res.ok) {
            const card = document.getElementById('task-card-' + taskId);
            const inProgressCol = document.querySelector('[data-column="in_progress"] .space-y-3');
            if (card && inProgressCol) inProgressCol.appendChild(card);
        } else {
            alert('Could not take task');
        }
    }

    async function markDone(taskId) {
        const cfg = getConfig();
        const payload = { column: 'done' };
        const res = await fetch(`/tasks/${taskId}/move`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': cfg.csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify(payload)
        });
        if (res.ok) {
            const card = document.getElementById('task-card-' + taskId);
            const doneCol = document.querySelector('[data-column="done"] .space-y-3');
            if (card && doneCol) doneCol.appendChild(card);
        } else {
            alert('Could not mark done');
        }
    }

    function kanbanDragEnter(e) {
        const col = e.currentTarget;
        col.classList.add('drag-over');
        const list = col.querySelector('.kanban-list');
        const ph = list.querySelector('.kanban-placeholder');
        if (ph) ph.classList.remove('hidden');
    }

    function kanbanDragLeave(e) {
        const col = e.currentTarget;
        col.classList.remove('drag-over');
        const list = col.querySelector('.kanban-list');
        const ph = list.querySelector('.kanban-placeholder');
        if (ph) ph.classList.add('hidden');
    }

    function kanbanDragEnd(e) {
        document.querySelectorAll('.kanban-column').forEach(c => c.classList.remove('drag-over'));
        e.currentTarget.classList.remove('dragging');
        document.querySelectorAll('.kanban-placeholder').forEach(p => p.classList.add('hidden'));
    }

    function kanbanDragOver(e) {
        e.preventDefault();
        const col = e.currentTarget;
        const list = col.querySelector('.kanban-list');
        const placeholder = list.querySelector('.kanban-placeholder');
        const mouseY = e.clientY;

        const cards = Array.from(list.querySelectorAll('.kanban-card'));
        let inserted = false;
        for (let i = 0; i < cards.length; i++) {
            const rect = cards[i].getBoundingClientRect();
            if (mouseY < rect.top + rect.height / 2) {
                cards[i].parentNode.insertBefore(placeholder, cards[i]);
                placeholder.classList.remove('hidden');
                inserted = true;
                break;
            }
        }

        if (!inserted) {
            list.appendChild(placeholder);
            placeholder.classList.remove('hidden');
        }
    }

    function initKanbanBindings() {
        document.querySelectorAll('.kanban-card').forEach(card => {
            card.setAttribute('draggable', 'true');
            card.addEventListener('dragstart', kanbanDragStart);
            card.addEventListener('dragend', kanbanDragEnd);
        });

        document.querySelectorAll('.kanban-column').forEach(col => {
            col.addEventListener('dragover', kanbanDragOver);
            col.addEventListener('drop', kanbanDrop);
            col.addEventListener('dragenter', kanbanDragEnter);
            col.addEventListener('dragleave', kanbanDragLeave);
        });
    }

    document.addEventListener('DOMContentLoaded', initKanbanBindings);

    window.Kanban = {
        init: initKanbanBindings,
        assignToMe,
        markDone
    };
})();
