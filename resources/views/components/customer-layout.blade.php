<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Red Card Cafe</title>
  <link rel="icon" type="image/x-icon" href="{{asset('img/favicon.ico')}}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <style>
    [x-cloak] {
      display: none !important;
    }

    @media (max-height: 920px) {
      .top {
        --offset: 50px;

        position: sticky;
        bottom: 20px;
        /* margin-right: 20px; */
        margin-left: 20px;
        place-self: end;
        margin-top: calc(100vh + var(--offset));

        /* visual styling */
        background: #000;
      }
    }

    @media (min-height: 920px) {
      .sidebar {
        position: -webkit-sticky;
        position: sticky;
        top: 5rem;
        height: calc(100vh - 7rem);
      }
    }
  </style>
</head>

<body>
  {{$slot}}

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous">
  </script>
</body>

</html>