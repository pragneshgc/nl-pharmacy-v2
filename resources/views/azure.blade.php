<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Azure Upload</title>
</head>

<body>
    @if (Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
    @endif
    @if (Session::has('error'))
        <div class="alert alert-danger">
            {{ Session::get('error') }}
        </div>
    @endif
    <form action="{{ url('azure-upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" id="file" />
        <button type="submit">Submit</button>

        <img src="{{ $img }}" alt="image" width="100px" />

    </form>
</body>

</html>
