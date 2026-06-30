// C:\xampp\htdocs\my-ppid\app\js\kalender.js

document.addEventListener('DOMContentLoaded', function() {
    var Calendar = FullCalendar.Calendar;
    var Draggable = FullCalendar.Draggable;

    var containerEl = document.getElementById('external-events');
    var calendarEl = document.getElementById('calendar');
    var checkbox = document.getElementById('remove-after-drop');

    // 1. Inisialisasi Fitur Drag untuk Element List Kiri
    new Draggable(containerEl, {
        itemSelector: '.draggable-event',
        eventData: function(eventEl) {
            return {
                title: eventEl.innerText.trim(),
                backgroundColor: eventEl.getAttribute('data-color'),
                borderColor: eventEl.getAttribute('data-color')
            };
        }
    });

    // 2. Inisialisasi Grafik Kalender Utama
    var calendar = new Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        editable: true,
        droppable: true,
        
        // Tarik data agenda dari database otomatis
        events: './calender/get-events.php',

        // Aksi Saat Event dari Kiri Di-Drop Masuk Tanggal
        drop: function(info) {
            var eventTitle = info.draggedEl.innerText.trim();
            var eventColor = info.draggedEl.getAttribute('data-color');
            var dropDate = info.dateStr; // Format YYYY-MM-DD

            // Kirim data ke server PHP menggunakan AJAX (Fetch API)
            fetch('./calender/save-event.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'insert',
                    title: eventTitle,
                    start_date: dropDate,
                    color: eventColor
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Refresh data agar event mendapatkan ID resmi dari database
                    calendar.refetchEvents();
                } else {
                    alert('Gagal menyimpan agenda ke database.');
                }
            });

            // Hapus list dari kiri jika centang "Remove after drop" aktif
            if (checkbox.checked) {
                info.draggedEl.remove();
            }
        },

        // Aksi Saat Agenda di Dalam Kalender Digeser/Dipindahkan Tanggalnya
        eventDrop: function(info) {
            var startFormatted = info.event.startStr;
            var endFormatted = info.event.endStr;

            fetch('./calender/save-event.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'update',
                    id: info.event.id,
                    start_date: startFormatted,
                    end_date: endFormatted
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status !== 'success') {
                    alert('Gagal memperbarui posisi tanggal.');
                    info.revert(); // Kembalikan ke posisi semula jika gagal
                }
            });
        }
    });

    calendar.render();
});