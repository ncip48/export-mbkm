<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Export Logbook</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <h1>Generate Logbook MBKM</h1>
    <form action="{{ route('export') }}" method="get" enctype="multipart/form-data" autocomplete="off">
        <div class="form-group">
            <label for="email">*Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">*Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="nim">Token:</label>
            <textarea id="token" name="token" rows="10"></textarea>
        </div>
        <div class="form-group">
            <label for="minggu">Minggu:</label>
            <input type="text" id="minggu" name="minggu" required>
        </div>
        <div class="form-group">
            <label for="paraf_mahasiswa">Paraf Mahasiswa:</label>
            <input type="file" id="paraf_mahasiswa" name="paraf_mahasiswa" accept="image/*">
        </div>
        <div class="form-group">
            <label for="paraf_pembimbing">Paraf Pembimbing:</label>
            <input type="file" id="paraf_pembimbing" name="paraf_pembimbing" accept="image/*">
        </div>
        <div class="form-group">
            <label for="paraf_dosen">Paraf Dosen:</label>
            <input type="file" id="paraf_dosen" name="paraf_dosen" accept="image/*">
        </div>
        <button type="submit">Submit</button>
    </form>
</body>

</html>
