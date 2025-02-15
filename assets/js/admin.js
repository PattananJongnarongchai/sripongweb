// Navigation handling
document.addEventListener('DOMContentLoaded', function() {
    // Handle sidebar navigation
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            if (this.id !== 'logoutBtn') {
                e.preventDefault();
                
                // Remove active class from all links
                document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                
                // Add active class to clicked link
                this.classList.add('active');
                
                // Hide all sections
                document.querySelectorAll('.section').forEach(section => {
                    section.style.display = 'none';
                });
                
                // Show selected section
                const sectionId = this.getAttribute('href').substring(1) + 'Section';
                const targetSection = document.getElementById(sectionId);
                if (targetSection) {
                    targetSection.style.display = 'block';
                    // Update URL without page reload
                    history.pushState({}, '', this.getAttribute('href'));
                }
            }
        });
    });

    // Handle initial page load
    const currentPage = window.location.hash || '#dashboard';
    const activeLink = document.querySelector(`.nav-link[href="${currentPage}"]`);
    if (activeLink) {
        activeLink.click();
    }
});

// Handle browser back/forward buttons
window.addEventListener('popstate', function() {
    const currentPage = window.location.hash || '#dashboard';
    const activeLink = document.querySelector(`.nav-link[href="${currentPage}"]`);
    if (activeLink) {
        activeLink.click();
    }
});

// Sidebar Toggle
document.getElementById('sidebarToggle').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('active');
    document.getElementById('content').classList.toggle('active');
});

// Responsive sidebar
if (window.innerWidth <= 768) {
    document.getElementById('sidebar').classList.add('active');
    document.getElementById('content').classList.add('active');
}

window.addEventListener('resize', function() {
    if (window.innerWidth <= 768) {
        document.getElementById('sidebar').classList.add('active');
        document.getElementById('content').classList.add('active');
    } else {
        document.getElementById('sidebar').classList.remove('active');
        document.getElementById('content').classList.remove('active');
    }
}); 