document.addEventListener('DOMContentLoaded', () => {
    const calendarDays = document.getElementById('calendar-days');
    const monthYear = document.getElementById('month-year');
    const prevButton = document.getElementById('prev');
    const nextButton = document.getElementById('next');

    let date = new Date();

    // Election period (replace with your actual PHP variables)
    const electionStart = new Date("<?php echo $date_start; ?>");
    const electionEnd = new Date("<?php echo $date_end; ?>");

    function renderCalendar() {
        calendarDays.innerHTML = '';
        const month = date.getMonth();
        const year = date.getFullYear();

        const today = new Date();

        const firstDay = new Date(year, month, 1).getDay();
        const lastDate = new Date(year, month + 1, 0).getDate();

        monthYear.innerHTML = `${date.toLocaleString('default', { month: 'long' })} ${year}`;

        for (let i = 0; i < firstDay; i++) {
            const emptyDiv = document.createElement('div');
            calendarDays.appendChild(emptyDiv);
        }

        for (let i = 1; i <= lastDate; i++) {
            const dayDiv = document.createElement('div');
            dayDiv.textContent = i;

            const currentDate = new Date(year, month, i);

            if (year === today.getFullYear() && month === today.getMonth() && i === today.getDate()) {
                dayDiv.classList.add('today');
            }

            // Highlight election period
            if (currentDate >= electionStart && currentDate <= electionEnd) {
                dayDiv.classList.add('election-period');
            }

            calendarDays.appendChild(dayDiv);
        }
    }

    prevButton.addEventListener('click', () => {
        date.setMonth(date.getMonth() - 1);
        renderCalendar();
    });

    nextButton.addEventListener('click', () => {
        date.setMonth(date.getMonth() + 1);
        renderCalendar();
    });

    renderCalendar();
});
