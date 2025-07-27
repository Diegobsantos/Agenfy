<?php
include_once __DIR__ . '/../config.php';
include_once APP_ROOT . '/inc/header.php';
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
?>

<?php
include_once APP_ROOT . '/inc/admin_nav.php';
?>

<section id="main" style="padding: 40px 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Painel Administrativo</h2>
    </div>
    <div id="calendar"></div>
</section>

<!-- FullCalendar script -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

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
            
            height: 'auto',
            events: '<?= site_base('pages/agenda_admin_data.php') ?>',
        });

        calendar.render();
    });
</script>

<style>
    #calendar {
        max-width: 1000px;
        margin: auto;
        background-color: #f9f9f9;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 0 6px rgba(0,0,0,0.2);
    }

    .fc-event {
        background-color: #007bff !important;
        color: white;
        font-weight: bold;
    }
</style>


<?php include_once APP_ROOT . '/pages/listar_agendamentos.php'; ?>
<?php include_once APP_ROOT . '/inc/footer.php'; ?>
