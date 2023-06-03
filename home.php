<?php 
    if( isset($_POST["submit"]) ){
        var_dump($_POST); die();
    }




?>








<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Upload dan Tampilkan File Excel</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <style>
        table {
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
            padding: 5px;
        }

        .empty-cell {
        border: 1px solid #000;
    }
    </style>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form id="upload-form" action="" method="post" enctype="multipart/form-data">
        <label for="file">Pilih File:</label>
        <input type="file" id="file" name="file" accept=".xls" onchange="handleFile(event)">
        
		<br>
        
        

        <div class="grid-container">
            <div class="item1">
                <table class="target">
                    <tr>
                        <th>Lokasi</th>
                        <th>Peng. Pekerjaan</th>
                        <th>Peng. Manuver</th>
                        <th>Peng. K3</th>
                        <th>Spv GITET</th>
                        <th>Opr GITET</th>
                    </tr>
                </table>
                <div id="file-data0">
            </div></div>
            <div class="item2">
                <table class="target">
                    <tr>
                        <th>Spv GITET</th>
                        <th>Opr GITET</th>
                    </tr>
                </table>
                <div id="file-data3"></div>
            </div>
            <div class="item3">3</div>
            <div class="item4">
                <table class="target">
                    <tr>
                        <th>NO</th>
                        <th>Lokasi</th>
                        <th>real</th>
                        <th>remote</th>
                        <th>ADS</th>
                        <th>Installasi</th>
                    </tr>
                </table>
                <div id="file-data1"></div>
            </div>
            <div class="item5">5</div>
            <div class="item6">
                <table class="target">
                    <tr>
                        <th>NO</th>
                        <th>Lokasi</th>
                        <th>real</th>
                        <th>remote</th>
                        <th>ADS</th>
                        <th>Installasi</th>
                    </tr>
                </table>
                <div id="file-data2"></div>
            </div>
        </div>

        <div class='submit' hidden>
            <input type="submit" name="submit" value="Upload">
        </div>
		
    </form>

    <script>
        
    
    
   function handleFile(e) {
    var file = e.target.files[0];
    var reader = new FileReader();

    reader.onload = function (e) {
        var data = new Uint8Array(e.target.result);
        var workbook = XLSX.read(data, { type: 'array' });

        var sheetName0 = "PENGAWAS";
        var html0 = generateTablePengawas(workbook, sheetName0,'lokasiPembebasan');
        document.getElementById('file-data0').innerHTML = html0;

        var sheetName1 = "PEMBEBASAN";
        var html1 = generateTable(workbook, sheetName1, 'lokasiManuverBebas', 'installManuverBebas');
        document.getElementById('file-data1').innerHTML = html1;

        var sheetName2 = "PENORMALAN";
        var html2 = generateTable(workbook, sheetName2,  'lokasiManuverNormal', 'installManuverNormal');
        document.getElementById('file-data2').innerHTML = html2;

        var html3 = generateTableEmpty(workbook, sheetName0);
        document.getElementById('file-data3').innerHTML = html3;

        var submitElement = document.querySelector('.submit');
        submitElement.removeAttribute('hidden');

        var targetElement = document.querySelectorAll('.target');
        console.log(targetElement);
        targetElement.forEach(function(element) {
            element.setAttribute('hidden', 'true');
        });

        
        
    };

    reader.readAsArrayBuffer(file);
}

    function generateTablePengawas(workbook, sheetName,lokasiName) {
        var sheet = workbook.Sheets[sheetName];

        if (!sheet) {
        alert('Lembar kerja ' + sheetName + ' tidak ditemukan');
        // Tambahkan kode untuk menampilkan notifikasi di sini
        return '';
        }

        var jsonData = XLSX.utils.sheet_to_json(sheet, { header: 1 });

        if (jsonData.length === 0) {
        alert('Lembar kerja ' + sheetName + ' kosong');
        // Tambahkan kode untuk menampilkan notifikasi di sini
        return '';
        }

        var html = '<table border="1">';
        html += '<tr>';
        html += '<th>Lokasi</th>';
        html += '<th>Peng. Pekerjaan</th>';
        html += '<th>Peng. Manuver</th>';
        html += '<th>Peng. K3</th>';
        html += '<th>Spv GITET</th>';
        html += '<th>Opr GITET</th>';
        html += '</tr>';

        for (var i = 0; i < jsonData.length; i++) {
        html += '<tr>';
        for (var j = 0; j < 6; j++) {
            var cellValue = '';
            if (j === 0 && jsonData[i][0]) {
                cellValue = jsonData[i][0];
                html += '<td><input type="text" name="' + lokasiName + '[]" value="' + cellValue + '"></td>';
            } else {
                html += '<td></td>';
            }
        }
        html += '</tr>';
    }
        html += '</table>';

        return html;
    }


    function generateTableEmpty(workbook, sheetName) {
        var sheet = workbook.Sheets[sheetName];
        var jsonData = XLSX.utils.sheet_to_json(sheet, {headers: 1});

        var html = '<table>';
        html += '<thead>';
        html += '<tr>';
        html += '<th>Spv GITET</th>';
        html += '<th>Opr GITET</th>';
        html += '</tr>';
        html += '</thead>';
        html += '<tbody>';

        // Loop untuk membuat baris tabel kosong
        for (var i = 0; i < 3; i++) {
            html += '<tr>';
            for (var j = 0; j < 2; j++) {
            html += '<td style="height: 20px;"></td>';
        }
        html += '</tr>';
        }

        html += '</tbody>';
        html += '</table>';

        return html;


    }


    function generateTable(workbook, sheetName, lokasiName, installName) {
        var sheet = workbook.Sheets[sheetName];

        if (!sheet) {
        alert('Lembar kerja ' + sheetName + ' tidak ditemukan');
        // Tambahkan kode untuk menampilkan notifikasi di sini
        return '';
        }

        var jsonData = XLSX.utils.sheet_to_json(sheet, { header: 1 });

        if (jsonData.length === 0) {
        alert('Lembar kerja ' + sheetName + ' kosong');
        // Tambahkan kode untuk menampilkan notifikasi di sini
        return '';
        }

        var html = '<table>';
        html += '<thead>';
        html += '<tr>';
        html += '<th rowspan="2">No.</th>';
        html += '<th rowspan="2">Lokasi</th>';
        html += '<th colspan="3">Jam manuver Buka</th>';
        html += '<th rowspan="2">Instalasi</th>';
        html += '<th rowspan="2">Modifikasi</th>';
        html += '</tr>';
        html += '<tr>';
        html += '<th>Remote</th>';
        html += '<th>Real</th>';
        html += '<th>ADS</th>';
        html += '</tr>';
        html += '</thead>';
        html += '<tbody>';

        for (var i = 0; i < jsonData.length; i++) {
            html += '<tr>';
            for (var j = 0; j < jsonData[i].length; j++) {
                var cellValue = jsonData[i][j] || ''; // Jika nilai sel undefined, ganti dengan string kosong
                if (j === 0) { // Kolom "No."
                    html += '<td>' + cellValue + '</td>';
                } else if (j === 1) { // Kolom "Lokasi"
                    html += '<td><input type="text" name="' + lokasiName + '[]" value="' + cellValue + '"></td>';
                } else if (j === 5) { // Kolom "Instalasi"
                    html += '<td><input type="text" name="' + installName + '[]" value="' + cellValue + '"></td>';
                } else if (j === 2 || j === 3 || j === 4) {
                    if (cellValue !== '') {
                        html += '<td><input type="text" value="' + cellValue + '"></td>';
                    } else {
                        html += '<td></td>'; // Kosongkan jika nilainya kosong
                    }
                }
            }
            html += '<td>'; // Tambahkan kolom "Modifikasi" dengan nilai "coba"
            html += '<button type="button" class="btn red" onclick="kurangBaris(this)">Remove</button>';
            html += '<button type="button" class="btn green" onclick="sisip(this,'+lokasiName+'[],'+installName+'[])">Insert</button>';
            html += '</td>'
            html += '</tr>';
        }

        html += '</tbody>';
        html += '</table>';

        return html;
    }









    </script>
</body>
</html>
