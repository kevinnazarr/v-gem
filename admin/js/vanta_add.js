document.addEventListener('DOMContentLoaded', function () {
        if (window.VANTA && window.VANTA.GLOBE) {
            VANTA.GLOBE({
                el: "#vanta-bg",
                mouseControls: true,
                touchControls: true,
                minHeight: 200.00,
                minWidth: 200.00,
                scale: 1.00,
                scaleMobile: 1.00,
                color: 0x00ffd0,
                color2: 0x1a1aff,
                backgroundColor: 0x0a0a23,
                size: 1.2
            });
        }
    });


// Validasi client-side untuk add game
document.querySelector('form').addEventListener('submit', function(e) {
    const screenshots = document.getElementById('screenshots').files;
    if(screenshots.length < 3) {
        alert('Harus memilih minimal 3 screenshot!');
        e.preventDefault();
    }
});
