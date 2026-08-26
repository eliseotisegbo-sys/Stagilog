/**
 * STAGILOG Custom Datepicker - 1:1 Pixel Perfect Match
 */
(function() {
    const MONTH_NAMES = [
        "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
        "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
    ];
    const DAY_NAMES = ["Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim"];

    let activePicker = null;
    let activeInput = null;

    function createDatePicker(input) {
        let currentDate = new Date();
        let selectedDate = null;

        if (input.value) {
            const parsed = new Date(input.value);
            if (!isNaN(parsed.getTime())) {
                selectedDate = parsed;
                currentDate = new Date(parsed.getFullYear(), parsed.getMonth(), 1);
            }
        }

        let viewYear = currentDate.getFullYear();
        let viewMonth = currentDate.getMonth();

        const popup = document.createElement('div');
        popup.className = 'custom-datepicker-popup';
        popup.setAttribute('role', 'dialog');

        function renderCalendar() {
            popup.innerHTML = '';

            // 1. Header
            const header = document.createElement('div');
            header.className = 'custom-datepicker-header';

            const prevBtn = document.createElement('button');
            prevBtn.type = 'button';
            prevBtn.className = 'custom-datepicker-nav-btn';
            prevBtn.innerHTML = '<svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>';
            prevBtn.onclick = (e) => {
                e.stopPropagation();
                viewMonth--;
                if (viewMonth < 0) {
                    viewMonth = 11;
                    viewYear--;
                }
                renderCalendar();
            };

            const title = document.createElement('div');
            title.className = 'custom-datepicker-title';
            title.textContent = `${MONTH_NAMES[viewMonth]} ${viewYear}`;

            const nextBtn = document.createElement('button');
            nextBtn.type = 'button';
            nextBtn.className = 'custom-datepicker-nav-btn';
            nextBtn.innerHTML = '<svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>';
            nextBtn.onclick = (e) => {
                e.stopPropagation();
                viewMonth++;
                if (viewMonth > 11) {
                    viewMonth = 0;
                    viewYear++;
                }
                renderCalendar();
            };

            header.appendChild(prevBtn);
            header.appendChild(title);
            header.appendChild(nextBtn);
            popup.appendChild(header);

            // 2. Weekdays
            const weekdays = document.createElement('div');
            weekdays.className = 'custom-datepicker-weekdays';
            DAY_NAMES.forEach(day => {
                const dayEl = document.createElement('div');
                dayEl.textContent = day;
                weekdays.appendChild(dayEl);
            });
            popup.appendChild(weekdays);

            // 3. Grid of Days
            const grid = document.createElement('div');
            grid.className = 'custom-datepicker-grid';

            // Premier jour du mois (0 = Dimanche, 1 = Lundi, etc.)
            const firstDayOfMonth = new Date(viewYear, viewMonth, 1).getDay();
            // Adapter pour que Lundi = 0, Dimanche = 6
            const startDay = (firstDayOfMonth + 6) % 7;

            // Nombre de jours dans le mois
            const daysInMonth = new Date(viewYear, viewMonth + 1, 0).getDate();
            // Jours du mois précédent
            const daysInPrevMonth = new Date(viewYear, viewMonth, 0).getDate();

            // Jours du mois précédent (grisés)
            for (let i = startDay - 1; i >= 0; i--) {
                const dayNum = daysInPrevMonth - i;
                const dayEl = document.createElement('div');
                dayEl.className = 'custom-datepicker-day other-month';
                dayEl.textContent = dayNum;
                dayEl.onclick = (e) => {
                    e.stopPropagation();
                    const prevMonthDate = new Date(viewYear, viewMonth - 1, dayNum);
                    selectDate(prevMonthDate);
                };
                grid.appendChild(dayEl);
            }

            // Jours du mois actuel
            const today = new Date();
            for (let d = 1; d <= daysInMonth; d++) {
                const thisDate = new Date(viewYear, viewMonth, d);
                const dayEl = document.createElement('div');
                dayEl.className = 'custom-datepicker-day';
                dayEl.textContent = d;

                // Est-ce aujourd'hui ?
                if (thisDate.toDateString() === today.toDateString()) {
                    dayEl.classList.add('today');
                }

                // Est-ce sélectionné ?
                if (selectedDate && thisDate.toDateString() === selectedDate.toDateString()) {
                    dayEl.classList.add('selected');
                }

                dayEl.onclick = (e) => {
                    e.stopPropagation();
                    selectDate(thisDate);
                };

                grid.appendChild(dayEl);
            }

            // Jours du mois suivant (pour compléter la grille à 35 ou 42)
            const totalCells = startDay + daysInMonth;
            const remainingCells = (totalCells > 35 ? 42 : 35) - totalCells;
            for (let d = 1; d <= remainingCells; d++) {
                const dayEl = document.createElement('div');
                dayEl.className = 'custom-datepicker-day other-month';
                dayEl.textContent = d;
                dayEl.onclick = (e) => {
                    e.stopPropagation();
                    const nextMonthDate = new Date(viewYear, viewMonth + 1, d);
                    selectDate(nextMonthDate);
                };
                grid.appendChild(dayEl);
            }

            popup.appendChild(grid);
        }

        function selectDate(date) {
            selectedDate = date;
            const yyyy = date.getFullYear();
            const mm = String(date.getMonth() + 1).padStart(2, '0');
            const dd = String(date.getDate()).padStart(2, '0');
            const formatted = `${yyyy}-${mm}-${dd}`;

            input.value = formatted;
            input.dispatchEvent(new Event('change', { bubbles: true }));
            input.dispatchEvent(new Event('input', { bubbles: true }));

            closePicker();
        }

        renderCalendar();
        return popup;
    }

    function openPicker(input) {
        if (activePicker) {
            closePicker();
        }

        activeInput = input;
        const picker = createDatePicker(input);
        document.body.appendChild(picker);

        // Positionnement précis sous l'input
        const rect = input.getBoundingClientRect();
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;

        picker.style.top = `${rect.bottom + scrollTop + 8}px`;
        picker.style.left = `${Math.min(rect.left + scrollLeft, window.innerWidth - 340)}px`;

        activePicker = picker;
    }

    function closePicker() {
        if (activePicker && activePicker.parentNode) {
            activePicker.parentNode.removeChild(activePicker);
        }
        activePicker = null;
        activeInput = null;
    }

    // Écouteur global pour fermer au clic externe
    document.addEventListener('click', (e) => {
        if (activePicker) {
            if (!activePicker.contains(e.target) && e.target !== activeInput) {
                closePicker();
            }
        }
    });

    // Attacher à tous les champs de date
    window.initCustomDatepickers = function() {
        const inputs = document.querySelectorAll('.datepicker-input, input[data-datepicker]');
        inputs.forEach(input => {
            if (input.dataset.pickerInit) return;
            input.dataset.pickerInit = 'true';
            input.setAttribute('readonly', 'true');
            input.style.cursor = 'pointer';

            input.addEventListener('click', (e) => {
                e.stopPropagation();
                openPicker(input);
            });
        });
    };

    document.addEventListener('DOMContentLoaded', () => {
        window.initCustomDatepickers();
    });
})();
