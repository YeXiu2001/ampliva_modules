
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ampliva</title>
    <link href="/dmpages/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="/dmpages/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" />
    <link href="/dmpages/assets/libs/datatables.net-scroller-bs5/css/scroller.bootstrap5.min.css" rel="stylesheet" />
    <link href="/dmpages/assets/libs/datatables.net-fixedcolumns-bs5/css/fixedColumns.bootstrap5.min.css" rel="stylesheet" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="/dmpages/assets/libs/select2/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../../assets/css/styleemp.css">
    <link rel="stylesheet" href="../../assets/css/dm_style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
<div class="content">
    <?php
    require_once __DIR__ . '/../includes/navbar.php';
    require_once __DIR__ . '/../includes/sidebar.php';
    ?>
    <?php yieldContent(); ?> 
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="/dmpages/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/dmpages/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="/dmpages/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="/dmpages/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
<script src="/dmpages/assets/libs/datatables.net-fixedcolumns/js/dataTables.fixedColumns.min.js"></script>
<script src="/dmpages/assets/libs/datatables.net-fixedcolumns-bs5/js/fixedColumns.bootstrap5.min.js"></script>
<script src="/dmpages/assets/libs/select2/dist/js/select2.min.js"></script>
</body>
</html>