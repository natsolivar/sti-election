document.addEventListener('DOMContentLoaded', () => {
    const calendarDays = document.getElementById('calendar-days');
    const monthYear = document.getElementById('month-year');
    const prevButton = document.getElementById('prev');
    const nextButton = document.getElementById('next');

    let date = new Date();

    function renderCalendar() {
        calendarDays.innerHTML = '';
        const month = date.getMonth();
        const year = date.getFullYear();

        const firstDay = new Date(year, month, 1).getDay();
        const lastDate = new Date(year, month + 1, 0).getDate();

        monthYear.innerHTML = `${date.toLocaleString('default', { month: 'long' })} ${year}`;

        for (let i = 0; i < firstDay; i++) {
            const emptyDiv = document.createElement('div');
            calendarDays.appendChild(emptyDiv);
        }

        for (let i = 1; i <= lastDate; i++) {
            const dayDiv = document.createElement('div');
            dayDiv.innerHTML = i;
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
