<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Safe IP Email</title>
</head>

<body>

    Dear Admin</span>,
    <br><br>
    {{ $name }} requested to have the IP {{$ip}} whitelisted.
    <br><br>
    IP Address : click to check <a href="https://community.spiceworks.com/tools/ip-lookup/results?hostname={{ $ip }}">https://community.spiceworks.com/tools/ip-lookup/results?hostname={{ $ip }}</a>
    <br><br>
    Click below to make the IP safe:
    <br><br>
    <a href="{{ url("/add-safe-ip") }}?key={{ $token }}">{{ url("/add-safe-ip") }}?key={{ $token }}</a>
    <br><br>
    Kind Regards
    <br><br>
    ESA System.

</body>

</html>