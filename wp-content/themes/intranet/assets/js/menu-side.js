document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modal');
    const modalContent = document.getElementById('modal-content');
    const searchSection = document.getElementById('search-section');
    const menuSection = document.getElementById('menu-section');
    const searchIcon = document.getElementById('search-icon');
    const menuIcon = document.getElementById('menu-icon');


    // modal-content -> duration-600
    const timeout = 200;


    function openModal(sectionToShow) {
        // Show modal instantly
        modal.classList.remove('hidden');

        // Hide all sections first
        searchSection.classList.add('hidden');
        menuSection.classList.add('hidden');

        // Show the selected section
        sectionToShow.classList.remove('hidden');
        sectionToShow.classList.add('flex');

        // Slide in modal content
        setTimeout(() => {
            modalContent.classList.remove('translate-x-full');
            modalContent.classList.add('translate-x-0');
        }, timeout);
    }

    function closeModal() {
        // Slide out modal content
        modalContent.classList.remove('translate-x-0');
        modalContent.classList.add('translate-x-full');

        // Hide all sections
        searchSection.classList.add('hidden');
        menuSection.classList.add('hidden');

        // Hide modal after slide out
        setTimeout(() => {
            modal.classList.add('hidden');
        }, timeout*2);
    }

    // Search icon click handler
    searchIcon.addEventListener('click', () => {
        openModal(searchSection);
    });

    // Menu icon click handler
    menuIcon.addEventListener('click', () => {
        openModal(menuSection);
    });

    // Close modal when clicking outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });
});