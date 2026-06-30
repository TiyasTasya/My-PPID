<?php
// ===================================================
// FULL CODE: CALENDAR COMPONENT WITH LIVE SEARCH, EXPLICIT ICONS & QUICK JUMP
// ===================================================
// File ini akan di-include ke dalam dashboard.php
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta color-scheme="light dark" />

<link rel="preload" href="/my-ppid/app/css/adminlte.css" as="style" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
<link rel="stylesheet" href="/my-ppid/app/css/adminlte.css" />

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Calendar</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-3">

                <div class="col-lg-3">
                    <div class="card mb-3">
                        <div class="card-body p-2">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="bi bi-search text-secondary"></i>
                                </span>
                                <input type="text" id="calendar-search" class="form-control border-start-0" placeholder="Cari agenda kegiatan..." />
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="bi bi-clipboard-check me-1"></i> List Agenda
                            </h3>
                        </div>
                        <div class="card-body p-2" style="max-height: 450px; overflow-y: auto;">
                            <p class="text-secondary small px-2 mb-2">
                                Klik tanggal kalender untuk menambah kegiatan. Klik item di bawah untuk menuju ke tanggalnya.
                            </p>
                            <div id="dynamic-agenda-list" class="list-group list-group-flush">
                                <div class="text-center text-muted small py-3">Memuat agenda...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="card">
                        <div class="card-body">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.20/index.global.min.js" crossorigin="anonymous"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var Calendar = FullCalendar.Calendar;
        var calendarEl = document.getElementById('calendar');
        var searchInput = document.getElementById('calendar-search');
        var agendaListContainer = document.getElementById('dynamic-agenda-list');
        var calendar; // Definisikan variabel di scope atas agar bisa diakses fungsi global

        // Fungsi Global untuk Mengedit Judul Agenda (Dipanggil dari tombol pensil)
        window.editCalendarEvent = function(eventId, currentTitle) {
            var newTitle = prompt('Ubah Nama Kegiatan:', currentTitle);
            if (newTitle && newTitle.trim() !== "" && newTitle.trim() !== currentTitle) {
                fetch('./calender/save-event.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            action: 'update_title',
                            id: eventId,
                            title: newTitle.trim()
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            calendar.refetchEvents();
                        } else {
                            alert('Gagal memperbarui nama agenda.');
                        }
                    })
                    .catch(err => console.error('Error saat edit event:', err));
            }
        };

        // Fungsi Global untuk Menghapus Event
        window.deleteCalendarEvent = function(eventId, eventTitle) {
            if (confirm(`Apakah Anda yakin ingin menghapus agenda "${eventTitle}"?`)) {
                fetch('./calender/save-event.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            action: 'delete',
                            id: eventId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            calendar.refetchEvents();
                        } else {
                            alert('Gagal menghapus agenda.');
                        }
                    })
                    .catch(err => console.error('Error saat menghapus event:', err));
            }
        };

        // Fungsi untuk memperbarui tampilan List Agenda di Sidebar Kiri
        function refreshSidebarAgenda(events) {
            agendaListContainer.innerHTML = '';
            var searchTerm = searchInput.value.toLowerCase();

            var filteredEvents = events.filter(function(event) {
                return event.title.toLowerCase().includes(searchTerm);
            });

            if (filteredEvents.length === 0) {
                agendaListContainer.innerHTML = '<div class="text-center text-muted small py-3">Tidak ada agenda ditemukan</div>';
                return;
            }

            // Urutkan berdasarkan tanggal terdekat
            filteredEvents.sort((a, b) => new Date(a.start) - new Date(b.start));

            filteredEvents.forEach(function(event) {
                var dateObj = new Date(event.start);
                var options = {
                    day: 'numeric',
                    month: 'short'
                };
                var formattedDate = dateObj.toLocaleDateString('id-ID', options);
                var safeTitle = event.title.replace(/'/g, "\\'");

                var itemHtml = `
                <div class="list-group-item p-2 border-0 border-bottom d-flex justify-content-between align-items-center agenda-item" style="background: transparent;">
                    <div class="text-truncate flex-grow-1 me-2" onclick="calendar.gotoDate('${event.start}');" style="cursor: pointer;" title="Klik untuk menuju ke tanggal event">
                        <span class="fw-bold small d-block text-truncate text-body">${event.title}</span>
                        <span class="text-secondary" style="font-size: 11px;">${formattedDate}</span>
                    </div>
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-outline-warning border-0 p-1 text-warning" onclick="event.stopPropagation(); editCalendarEvent(${event.id}, '${safeTitle}')" title="Edit Judul Agenda">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger border-0 p-1 text-danger" onclick="event.stopPropagation(); deleteCalendarEvent(${event.id}, '${safeTitle}')" title="Hapus Agenda">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </div>
                </div>
            `;
                agendaListContainer.insertAdjacentHTML('beforeend', itemHtml);
            });
        }

        // Inisialisasi FullCalendar Grid Utama
        calendar = new Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            editable: true,
            selectable: true,

            events: './calender/get-event.php',

            eventsSet: function(events) {
                var rawEvents = events.map(e => ({
                    id: e.id,
                    title: e.title,
                    start: e.startStr,
                    color: e.backgroundColor
                }));
                refreshSidebarAgenda(rawEvents);
            },

            // Klik pada balok kegiatan di dalam kalender grid langsung pemicu hapus
            eventClick: function(info) {
                deleteCalendarEvent(info.event.id, info.event.title);
            },

            // Aksi klik tanggal kosong kalender (Tambah Baru)
            select: function(info) {
                var customTitle = prompt('Masukkan Nama Kegiatan Baru:');
                if (customTitle && customTitle.trim() !== "") {
                    fetch('./calender/save-event.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                action: 'insert',
                                title: customTitle.trim(),
                                start_date: info.startStr,
                                color: '#0d6efd'
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                calendar.refetchEvents();
                            }
                        });
                }
                calendar.unselect();
            },

            // Aksi saat agenda digeser/drag tanggalnya di dalam kalender
            eventDrop: function(info) {
                var startFormatted = info.event.startStr;
                var endFormatted = info.event.endStr;

                fetch('./calender/save-event.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            action: 'update_date',
                            id: info.event.id,
                            start_date: startFormatted,
                            end_date: endFormatted
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status !== 'success') {
                            alert('Gagal merubah tanggal.');
                            info.revert();
                        } else {
                            calendar.refetchEvents();
                        }
                    })
                    .catch(err => {
                        info.revert();
                    });
            },

            eventDidMount: function(info) {
                var searchTerm = searchInput.value.toLowerCase();
                var eventTitle = info.event.title.toLowerCase();

                if (searchTerm && !eventTitle.includes(searchTerm)) {
                    info.el.style.display = 'none';
                }
            }
        });

        calendar.render();

        searchInput.addEventListener('input', function() {
            calendar.view.calendar.select();
            calendar.unselect();

            var allCurrentEvents = calendar.getEvents().map(e => ({
                id: e.id,
                title: e.title,
                start: e.startStr,
                color: e.backgroundColor
            }));
            refreshSidebarAgenda(allCurrentEvents);
        });
    });
</script>
<script src="/my-ppid/app/js/mode.js"></script>