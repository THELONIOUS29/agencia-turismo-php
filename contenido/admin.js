const sideMenu = document.querySelector('aside');
const menuBtn = document.getElementById('menu-btn');
const closeBtn = document.getElementById('close-btn');

const darkMode = document.querySelector('.dark-mode');

menuBtn.addEventListener('click', () => {
    sideMenu.style.display = 'block';
});

closeBtn.addEventListener('click', () => {
    sideMenu.style.display = 'none';
});

darkMode.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode-variables');
    darkMode.querySelector('span:nth-child(1)').classList.toggle('active');
    darkMode.querySelector('span:nth-child(2)').classList.toggle('active');
})


Orders.forEach(order => {
    const tr = document.createElement('tr');
    const trContent = `
        <td>${order.productName}</td>
        <td>${order.productNumber}</td>
        <td>${order.paymentStatus}</td>
        <td class="${order.status === 'Declined' ? 'danger' : order.status === 'Pending' ? 'warning' : 'primary'}">${order.status}</td>
        <td class="primary">Details</td>
    `;
    tr.innerHTML = trContent;
    document.querySelector('table tbody').appendChild(tr);
});





//////////////
function showOptions() {
    document.getElementById('profile-menu').style.display = 'block';
}

function hideOptions(event) {
    var optionsContainer = document.getElementById('profile-menu');
    var profile = document.querySelector('.profile');

    // Verifica si el mouse está fuera del contenedor del perfil y del menú de opciones
    if (!profile.contains(event.relatedTarget) && !optionsContainer.contains(event.relatedTarget)) {
        optionsContainer.style.display = 'none';
    }
}

// Ocultar el menú si se hace clic fuera de él
document.addEventListener('click', function(event) {
    var optionsContainer = document.getElementById('profile-menu');
    var profile = document.querySelector('.profile');
    if (!profile.contains(event.target) && !optionsContainer.contains(event.target)) {
        optionsContainer.style.display = 'none';
    }
});