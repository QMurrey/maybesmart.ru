const menuActuators = document.querySelectorAll('.js_menu-actuator');
for (let i = 0, l = menuActuators.length; i < l; i += 1)
	menuActuators[i].addEventListener('mouseup', toggleMenu);

const menuOverlay = document.getElementById('overlay-menu');
menuOverlay.addEventListener('mouseup', toggleMenu);

function toggleMenu() {
	for (let i = 0, l = menuActuators.length; i < l; i += 1)
		menuActuators[i].classList.toggle('active');
	document.querySelector('.menu').classList.toggle('active');
	menuOverlay.classList.toggle('active');
}