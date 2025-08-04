let subMenu = document.getElementById("subMenu");
function toggleMenu() {
    subMenu.classList.toggle("open-menu");
}
function addItem() {
    const input = document.getElementById("todo-input");
    const value = input.value.trim();
    if (value !== "") {
        const list = document.getElementById("todo-list");
        const option = document.createElement("option");
        option.textContent = value;
        const removeBtn = document.createElement("button");
        removeBtn.innerHTML = '<i class="fa-solid fa-xmark"></i>';
        removeBtn.style.marginLeft = '10px';
        removeBtn.onclick = function () {
            li.remove();
        };
        option.appendChild(removeBtn);
        list.appendChild(option);
        input.value = "";
    }
}




