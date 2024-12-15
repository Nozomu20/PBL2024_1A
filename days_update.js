// days_update.js
function updateDays() {
    const month = document.getElementById("month").value;
    const daysInMonth = new Date(2024, month, 0).getDate(); // 月の最終日取得
    const daysContainer = document.getElementById("days");
    daysContainer.innerHTML = ''; // 前の内容をクリア

    for (let i = 1; i <= daysInMonth; i++) {
        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.name = "days[]";
        checkbox.value = i;
        checkbox.id = `day${i}`;
        checkbox.onclick = checkLimit;

        const label = document.createElement("label");
        label.htmlFor = `day${i}`;
        label.textContent = `${i}日`;

        daysContainer.appendChild(checkbox);
        daysContainer.appendChild(label);
    }
}

function checkLimit() {
    const checkboxes = document.querySelectorAll('input[name="days[]"]:checked');
    if (checkboxes.length > 7) {
        alert("休み希望日は最大7日までです。");
        this.checked = false;
    }
}

window.onload = updateDays; // ページ読み込み時に初期化
