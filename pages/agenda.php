<?php include_once __DIR__ . '/../config.php'; ?>
<?php include_once APP_ROOT . '/inc/header.php'; ?>

<!-- FullCalendar v6.1.8 - apenas JS, com CSS próprio -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<style>
    #calendar {
        max-width: 900px;
        margin: 40px auto;
        padding: 20px;
        background: #f7f7f7;
        border-radius: 8px;
        box-shadow: 0 0 8px rgba(0,0,0,0.1);
    }
</style>

<div id="calendar"></div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'pt-br',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        buttonText: {
            today: 'Hoje',
        },
        events: '<?= site_base('pages/agenda_publica.php') ?>',
        height: 'auto'
    });

    calendar.render();
});
</script>


<?php include_once APP_ROOT . '/inc/footer.php'; ?>
