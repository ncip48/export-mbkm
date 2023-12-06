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

        #copy-script {
            color: #007bff;
            cursor: pointer;
            font-weight: normal;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <h1>Generate Logbook MBKM</h1>
    <form action="{{ route('export') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="form-group">
            <label for="email">*Email:</label>
            <input type="email" id="email" name="email">
        </div>
        <div class="form-group">
            <label for="password">*Password:</label>
            <input type="password" id="password" name="password">
        </div>
        <div class="form-group">
            <label for="nim">Token
                <span id="copy-script">
                    (Klik untuk copy script)</span>:</label>
            <textarea id="token" name="token" rows="10"></textarea>
        </div>
        <div class="form-group">
            <label for="minggu">Minggu:</label>
            <input type="text" id="minggu" name="minggu">
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

    <script>
        const copyScript = document.querySelector('#copy-script');

        copyScript.addEventListener('click', () => {
            const script =
                "const _0xf1c695=_0x2647;(function(_0xfe5ef7,_0x59d189){const _0x1eacde=_0x2647,_0x28137c=_0xfe5ef7();while(!![]){try{const _0x3eb50a=-parseInt(_0x1eacde(0x1d1))/0x1*(parseInt(_0x1eacde(0x1d4))/0x2)+-parseInt(_0x1eacde(0x1ce))/0x3*(-parseInt(_0x1eacde(0x1dc))/0x4)+-parseInt(_0x1eacde(0x1dd))/0x5*(parseInt(_0x1eacde(0x1d3))/0x6)+-parseInt(_0x1eacde(0x1cc))/0x7+-parseInt(_0x1eacde(0x1d0))/0x8+parseInt(_0x1eacde(0x1cf))/0x9+parseInt(_0x1eacde(0x1d8))/0xa;if(_0x3eb50a===_0x59d189)break;else _0x28137c['push'](_0x28137c['shift']());}catch(_0x428dc2){_0x28137c['push'](_0x28137c['shift']());}}}(_0x401d,0x68e77));const storage=localStorage[_0xf1c695(0x1da)](_0xf1c695(0x1de)),parsedStorage=JSON[_0xf1c695(0x1d6)](storage),token=parsedStorage[_0xf1c695(0x1e2)]['token'],tempElement=document[_0xf1c695(0x1e3)](_0xf1c695(0x1db));function _0x2647(_0x41d16b,_0x7271ef){const _0x401d08=_0x401d();return _0x2647=function(_0x26477d,_0x1e017b){_0x26477d=_0x26477d-0x1cc;let _0x5c7a53=_0x401d08[_0x26477d];return _0x5c7a53;},_0x2647(_0x41d16b,_0x7271ef);}function _0x401d(){const _0x526868=['value','createElement','appendChild','1880109vzRbQq','select','2248185KbSSvw','7693488AEgxUM','506880SZbSqo','114013mGijbE','Token\x20copied\x20to\x20clipboard:','13422gaPWzP','6FDDmmc','removeChild','parse','position','2488410TlPhZE','left','getItem','textarea','4gkQVRz','1675UbwHyy','@mkbm/manager/user','-9999px','style','body'];_0x401d=function(){return _0x526868;};return _0x401d();}tempElement[_0xf1c695(0x1e2)]=token,tempElement[_0xf1c695(0x1e0)][_0xf1c695(0x1d7)]='absolute',tempElement[_0xf1c695(0x1e0)][_0xf1c695(0x1d9)]=_0xf1c695(0x1df),document['body'][_0xf1c695(0x1e4)](tempElement),tempElement[_0xf1c695(0x1cd)](),document['execCommand']('copy'),document[_0xf1c695(0x1e1)][_0xf1c695(0x1d5)](tempElement),console['log'](_0xf1c695(0x1d2),token);"
            const tempElement = document.createElement('textarea');
            tempElement.value = script;
            tempElement.style.position = 'absolute';
            tempElement.style.left = '-9999px';
            document.body.appendChild(tempElement);
            tempElement.select();
            document.execCommand('copy');
            document.body.removeChild(tempElement);
            alert('Script copied to clipboard');
        });
    </script>
</body>

</html>
