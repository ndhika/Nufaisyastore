<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nufaisyastore</title>
    <script src="https://kit.fontawesome.com/930a0a89d2.js" crossorigin="anonymous"></script>    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html {
            scroll-behavior: smooth;
        }
        .carousel {
        scroll-behavior: smooth;
        }
        .carousel-item {
            scroll-snap-align: start;
        }
        #product::before {
            content: "";
            position: absolute;
            top: -30px; /* Sesuaikan dengan kebutuhan */
            left: 0;
            width: 100%;
            height: 60px; /* Sesuaikan dengan kebutuhan */
            background: inherit;
            filter: blur(20px);
            z-index: -1;
        }
    </style>
    @livewireStyles
</head>
<body>
    <div>
        {{ $slot }}
    </div>
    @livewireScripts
</body>
</html>
