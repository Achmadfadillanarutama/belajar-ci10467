<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Transaksi</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Data Transaksi</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Total Harga</th>
                <th>Alamat</th>
                <th>Ongkir</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transaksi as $index => $item) : ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $item['username'] ?></td>
                    <td><?= number_to_currency($item['total_harga'], 'IDR') ?></td>
                    <td><?= $item['alamat'] ?></td>
                    <td><?= number_to_currency($item['ongkir'], 'IDR') ?></td>
                    <td><?= $item['status'] == 0 ? 'Pending' : 'Selesai' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
