const renderCalendar = () => {
    let firstDayofMonth = new Date(currYear, currMonth, 1).getDay(),
        lastDateofMonth = new Date(currYear, currMonth + 1, 0).getDate(),
        lastDayofMonth = new Date(currYear, currMonth, lastDateofMonth).getDay(),
        lastDateofLastMonth = new Date(currYear, currMonth, 0).getDate();

    firstDayofMonth = (firstDayofMonth === 0) ? 6 : firstDayofMonth - 1;

    let liTag = "";

    for (let i = firstDayofMonth; i > 0; i--) {
        liTag += `<li class="inactive">${lastDateofLastMonth - i + 1}</li>`;
    }

    for (let i = 1; i <= lastDateofMonth; i++) {
        let isToday = i === date.getDate() && currMonth === new Date().getMonth()
            && currYear === new Date().getFullYear() ? "active" : "";

        let dueDate = `${currYear}-${(currMonth + 1).toString().padStart(2, '0')}-${i.toString().padStart(2, '0')}`;
        if (goalsDueDates.includes(dueDate)) {
            liTag += `<li class="${isToday} goal-date">${i}</li>`;
        } else {
            liTag += `<li class="${isToday}">${i}</li>`;
        }

        if ((firstDayofMonth + i) % 7 === 6) {
            liTag += '</ul><ul class="days">';
        }
    }

    for (let i = lastDayofMonth; i < 6; i++) {
        liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`;
    }

    liTag += '</ul>';

    currentDate.innerText = `${months[currMonth]} ${currYear}`;
    daysTag.innerHTML = liTag;
};
