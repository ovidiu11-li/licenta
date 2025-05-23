import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

    document.addEventListener('DOMContentLoaded', function () {
        const fields = document.querySelectorAll('input[required]');

        fields.forEach(field => {
            const errorSpan = document.getElementById(`${field.name}-error`);

            field.addEventListener('blur', () => {
                // Basic required check
                if (!field.value.trim()) {
                    field.classList.add('border-red-500', 'bg-red-50', 'text-red-700');
                    errorSpan.textContent = 'Acest câmp este obligatoriu';
                    errorSpan.classList.remove('hidden');
                    return;
                }

                // Extra validation for email fields
                if (field.type === 'email') {
                    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailPattern.test(field.value.trim())) {
                        field.classList.add('border-red-500', 'bg-red-50', 'text-red-700');
                        errorSpan.textContent = 'Format e-mail invalid';
                        errorSpan.classList.remove('hidden');
                        return;
                    }
                }

                // If valid, reset styles
                field.classList.remove('border-red-500', 'bg-red-50', 'text-red-700');
                errorSpan.classList.add('hidden');
            });
        });
    });


    // Dropdown avatar - show/hide
document.addEventListener("DOMContentLoaded", function () {
    const avatarButton = document.getElementById("avatarButton");
    const dropdownMenu = document.getElementById("dropdownMenu");

    if (avatarButton && dropdownMenu) {
        avatarButton.addEventListener("click", function (e) {
            e.stopPropagation(); // împiedică închiderea imediată
            dropdownMenu.classList.toggle("hidden");
        });

        document.addEventListener("click", function (e) {
            if (!avatarButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.add("hidden");
            }
        });
    }
});



