document.addEventListener('livewire:navigated', () => {
    const body = document.getElementById('comparison-table');

    if (!body) {
        return;
    }

    const table = body.closest('table');

    table.querySelectorAll('[data-flux-table-sortable]').forEach((button) => {
        // wire:navigate can morph this button back in across visits; avoid double-binding it.
        if (button.dataset.sortBound) {
            return;
        }

        button.dataset.sortBound = 'true';

        const column = button.closest('[data-flux-column]');
        const columnIndex = Array.from(column.parentElement.children).indexOf(column);

        button.addEventListener('click', () => {
            const ascending = button.dataset.direction !== 'asc';

            table.querySelectorAll('[data-flux-table-sortable]').forEach((other) => delete other.dataset.direction);
            button.dataset.direction = ascending ? 'asc' : 'desc';

            const rows = Array.from(body.children).sort((a, b) => {
                const valueA = a.children[columnIndex].innerText.trim();
                const valueB = b.children[columnIndex].innerText.trim();
                const numericA = Number(valueA);
                const numericB = Number(valueB);

                const comparison = valueA !== '' && valueB !== '' && !Number.isNaN(numericA) && !Number.isNaN(numericB)
                    ? numericA - numericB
                    : valueA.localeCompare(valueB);

                return ascending ? comparison : -comparison;
            });

            rows.forEach((row) => body.appendChild(row));
        });
    });
});
