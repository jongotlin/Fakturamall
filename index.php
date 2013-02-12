<?php

    $invoiceNumber = '1';
    $invoiceDate = new DateTime('2013-02-12');
    $paymentTerm = 20;

    $customerName = 'Foo';
    $customerAddress = 'Bar';
    $customerZip = '123 45';
    $customerCity = 'Baz';

    $rows = [
        [
            'description' => 'Foo bar',
            'price' => 666,
            'nrOf' => 4,
        ],
        [
            'description' => 'Foo bar',
            'price' => 666,
            'nrOf' => 2.2,
        ],
    ];


?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $invoiceNumber ?></title>
        <meta name="description" content="">
        <link rel="stylesheet" href="normalize.css">
        <link rel="stylesheet" href="boilerplate.css">
        <link rel="stylesheet" href="main.css">
    </head>
    <body>        
        <h1>Faktura</h1>
        <img src="logo.jpg" id="logo" />

        <div id="terms">
            <p>
                Fakturanummer: <strong><?php echo $invoiceNumber ?></strong><br />
                Fakturadatum: <strong><?php echo $invoiceDate->format('Y-m-d') ?></strong><br />
                Betalningsvillkor: <strong><?php echo $paymentTerm ?> dagar</strong><br />
                Förfallodatum: <strong><?php echo $invoiceDate->modify('+' . $paymentTerm . ' day')->format('Y-m-d') ?></strong><br />
            </p>
        </div>
        <div id="receiver">
            <p>
                <?php echo $customerName ?><br />
                <?php echo $customerAddress ?><br />
                <?php echo $customerZip . ' ' . $customerCity ?>
            </p>
        </div>

        <table id="rows">
            <thead>
                <tr>
                    <th>Benämning</th>
                    <th class="number">Antal</th>
                    <th class="number">Pris</th>
                    <th class="number">Belopp</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?php echo $row['description'] ?></td>
                    <td class="number"><?php echo number_format($row['nrOf'], 2, ',', ' ') ?></td>
                    <td class="number"><?php echo number_format($row['price'], 2, ',', ' ') ?></td>
                    <td class="number"><?php echo number_format($row['nrOf'] * $row['price'], 2, ',', ' ') ?></td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
        
        <table id="summary">
            <tr>
                <td>
                    Belopp före moms
                </td>
                <td class="number">
                    <?php echo number_format(sum($rows), 2, ',', ' ') ?>
                </td>
            </tr>
            <tr>
                <td>
                    Moms (25%)
                </td>
                <td class="number">
                    <?php echo number_format(vat($rows), 2, ',', ' ') ?>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Summa att betala</strong>
                </td>
                <td class="number">
                    <strong>SEK <?php echo number_format(sumIncVat($rows), 2, ',', ' ') ?></strong>
                </td>
            </tr>
        </table>

        <footer>
        <p>
            Efter förfallodatum debiteras en årsränta om 11%.
        </p>

            <div>
                Jon Gotlin International AB<br />
                Dalstadsvägen 26<br />
                719 30 Vintrosa<br />
                jon@jon.se
            </div>
            <div>
                Organisationsnr: 556920-9900<br />
                Godkänd för F-skatt<br />
                Momsregnr/VAT-nr: SE556920990001
            </div>
            <div>
                Bankgiro: 5664-0501<br />
                BIC: SWEDSESS<br />
                IBAN: SE60 8000 0845 2593 4238 0293<br />
            </div>
        </footer>
    </body>
</html>

<?php
    function sum($rows)
    {
        $sum = 0;
        foreach ($rows as $row) {
            $sum += ($row['price'] * $row['nrOf']);
        }
        return $sum;
    }

    function vat($rows)
    {
        return sum($rows) * .25;
    }

    function sumIncVat($rows)
    {
        return sum($rows) + vat($rows);
    }
?>