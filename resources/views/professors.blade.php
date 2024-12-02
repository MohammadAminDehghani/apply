<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Applier</title>

    <link rel="stylesheet" href="./reset.css" />
    <link rel="stylesheet" href="./master.css" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<div class="container p-5">
    <h2>Concordia University</h2>
    <div class="row">
        @foreach($professors as $professor)
            <div class="card p-0 m-2">
                <div class="card-header">
                    {{ $professor->name }}
                </div>
                <div class="card-body">
                    <div>{{ $professor->affiliation }}</div>
                    <div>{{ $professor->number }}</div>
                    <div>{{ $professor->location }}</div>
                    <div>{{ $professor->email }}</div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-sm btn-dark">professor page</button>
                </div>
            </div>
        @endforeach
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>
