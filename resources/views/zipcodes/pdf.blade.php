<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
        h1 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #777; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Irányítószámok és Települések</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Irányítószám</th>
                <th>Település</th>
                <th>Megye</th>
            </tr>
        </thead>
        <tbody>
            @foreach($zipCodes as $zip)
            <tr>
                <td>{{ $zip->id }}</td>
                <td>{{ $zip->zip_code }}</td>
                <td>{{ $zip->city }}</td>
                <td>{{ $zip->county }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>