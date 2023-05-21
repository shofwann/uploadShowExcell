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
    </style>
</head>
<body>
    <form id="upload-form" action="upload.php" method="post" enctype="multipart/form-data">
        <label for="file">Pilih File:</label>
        <input type="file" id="file" name="file" accept=".xls" onchange="handleFile(event)">
        <div id="file-data"></div><br>
		<div id="file-data2"></div>
        <input type="submit" value="Upload">
		
    </form>

    <script>
        
    function handleFile(e) {
        var file = e.target.files[0];
        var reader = new FileReader();

        reader.onload = function (e) {
            var data = new Uint8Array(e.target.result);
            var workbook = XLSX.read(data, { type: 'array' });

            var html1 = generateTable(workbook, 0, 'lokasiManuverBebas', 'installManuverBebas');
            document.getElementById('file-data').innerHTML = html1;

            var html2 = generateTable(workbook, 1, 'lokasiManuverNormal', 'installManuverNormal');
            document.getElementById('file-data2').innerHTML = html2;
        };

        reader.readAsArrayBuffer(file);
    }

    function generateTable(workbook, sheetIndex, lokasiName, installName) {
        var sheet = workbook.Sheets[workbook.SheetNames[sheetIndex]];
        var jsonData = XLSX.utils.sheet_to_json(sheet, { header: 1 });

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
