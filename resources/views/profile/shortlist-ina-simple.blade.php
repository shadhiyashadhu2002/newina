<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>INA Dashboard - Search Profiles</title>
</head>
<body>
    <h1>Shortlist from INA</h1>
    <p>This is the shortlist page!</p>
    <a href="{{ route('active.service') }}">← Back to Active Services</a>
</body>
</html>