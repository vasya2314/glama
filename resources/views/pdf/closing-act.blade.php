<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
    <style>
        @font-face {
            font-family: 'Open Sans';
            src: url({{ storage_path("fonts/static/OpenSans/OpenSans-Bold.ttf") }}) format("truetype");
            font-weight: 700;
            font-style: normal;
        }
        @font-face {
            font-family: 'Open Sans';
            src: url({{ storage_path("fonts/static/OpenSans/OpenSans-Italic.ttf") }}) format("truetype");
            font-weight: 400;
            font-style: italic;
        }
        @font-face {
            font-family: 'Open Sans';
            src: url({{ storage_path("fonts/static/OpenSans/OpenSans-Medium.ttf") }}) format("truetype");
            font-weight: 500;
            font-style: normal;
        }
        @font-face {
            font-family: 'Open Sans';
            src: url({{ storage_path("fonts/static/OpenSans/OpenSans-MediumItalic.ttf") }}) format("truetype");
            font-weight: 500;
            font-style: italic;
        }
        @font-face {
            font-family: 'Open Sans';
            src: url({{ storage_path("fonts/static/OpenSans/OpenSans-Regular.ttf") }}) format("truetype");
            font-weight: 400;
            font-style: normal;
        }
        @font-face {
            font-family: 'Open Sans';
            src: url({{ storage_path("fonts/static/OpenSans/OpenSans-SemiBold.ttf") }}) format("truetype");
            font-weight: 600;
            font-style: normal;
        }
        html {
            padding: 0;
        }
        body{
            font-family: 'Open Sans', sans-serif;
            font-size: 12px;
            padding: 15px 30px 15px 30px;
            position: relative;
        }
        main {
            padding: 0;
        }
        table {
            width: 100%;
        }
        p {
            margin-top: 0;
        }
        .header__logo {
            display: block;
            min-width: 46px;
            width: 46px;
            height: 46px;
        }
        .header {
            margin-bottom: 30px;
        }
        .header__requisites {
            text-align: right;
            font-size: 10px;
            margin-left: auto;
            display: block;
        }
        .closing-act__title {
            font-size: 20px;
            text-align: center;
            margin-top: 0;
            margin-bottom: 30px;
            font-weight: 700;
        }
        .closing-act__table .left {
            text-align: left;
        }
        .closing-act__table .center {
            text-align: center;
        }
        .closing-act__table .right {
            text-align: right;
        }
        .closing-act__table td {
            vertical-align: top;
            padding: 5px;
        }
        .closing-act__table .no-wrap {
            white-space: nowrap;
        }
        .closing-act__head-title {
            margin-bottom: 15px;
            text-align: justify;
        }
        .closing-act__head-contract-name {
            margin-bottom: 15px;
        }
        .closing-act__table {
            width: 100%;
            margin-bottom: 30px;
        }
        .closing-act__amount-words {
            text-align: justify;
            font-style: italic;
            font-size: 12px;
            margin-bottom: 15px;
        }
        .closing-act__amount-notify {
            font-size: 10px;
            padding: 0 20px;
            margin-bottom: 10px;
        }
        .closing-act__bottom {
            margin-top: -40px;
        }
        .closing-act__bottom td {
            vertical-align: bottom;
        }
        .closing-act__bottom .signature {
            display: inline-block;
            vertical-align: bottom;
        }
        .closing-act__bottom .image-signature {
            display: block;
            width: 200px;
            height: 200px;
            object-fit: contain;
            margin-top: auto;
        }
        .closing-act__bottom .value {
            display:block;
            vertical-align: bottom;
            margin-top: auto;
            font-style: italic;
            font-size: 10px;
        }
        .closing-act__bottom .left {
            display:inline-block;
            margin-top: auto;
            vertical-align: bottom;
        }
        .closing-act__bottom .right {
            display:inline-block;
            margin-top: auto;
            vertical-align: bottom;
        }
        .closing-act__bottom .signature-left {
            position: relative;
            bottom: -30px;
        }
        .closing-act__bottom .value-right {
            border-top: 1px solid #000000;
            padding: 0 15px;
            margin-left: 10px;
            position: relative;
            bottom: -30px;
        }
    </style>

    <header class="header">
        <table>
            <tbody>
            <tr>
                <td>
                    <img class="header__logo" src="data:image/png;base64,{{ $logo }}" alt="logo">
                </td>
                <td>
                    <span class="header__requisites">
                        {{ env('COMPANY_NAME') . ' ' . env('COMPANY_INN') . ' ' . env('COMPANY_KPP') }} <br>
                        {{ env('COMPANY_POSTCODE') . ' ' . env('COMPANY_ADDRESS') }}
                    </span>
                </td>
            </tr>
            </tbody>
        </table>
    </header>

    @php
        $nds = $closingAct->amount_nds - $closingAct->amount;
        $months = [
            1 => 'январе', 2 => 'феврале', 3 => 'марте',
            4 => 'апреле', 5 => 'мае', 6 => 'июне',
            7 => 'июле', 8 => 'августе', 9 => 'в сентябре',
            10 => 'октябре', 11 => 'ноябре', 12 => 'декабре'
        ];
    @endphp

    <main>
        <div class="closing-act">
            <h1 class="closing-act__title">
                Акт № {{ $closingAct->act_number }} от {{ Carbon\Carbon::parse($closingAct->date_generated)->translatedFormat('d F Y') }} г.
            </h1>
            <div class="closing-act__head-title">
                Мы, нижеподписавшиеся, представитель <b>Заказчика: {{ $contract->display_name }}</b> с одной стороны, и представитель Исполнителя {{ env('COMPANY_NAME') }} в лице Генерального директора {{ env('COMPANY_DIRECTOR_NAME') }}, с другой стороны, составили настоящий акт в том, что Исполнитель передал, а заказчик принял следующие услуги (работы):
            </div>
            <div class="closing-act__head-contract-name">
                <b>Договор: № {{ $contract->id }}: Услуги в интернете</b>
            </div>
            <table class="closing-act__table">
                <thead>
                    <tr>
                        <td class="center"><b>№</b></td>
                        <td class="center"><b>Наименование работы (услуги)</b></td>
                        <td class="center"><b>Сумма</b></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="center">1</td>
                        <td class="left">
                            @php
                                $carbonDate = \Carbon\Carbon::parse($closingAct->date_generated);
                                $month = $carbonDate->month;
                                $year = $carbonDate->year;
                            @endphp
                            Услуги по размещению Интернет-рекламы в соответствии с договором № {{ $contract->id }} в {{ $months[$month] . ' ' . $year }} г.
                        </td>
                        <td class="right no-wrap top">
                            <b>{{ priceFormat(kopToRub($closingAct->amount_nds)) }}</b>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="right" colspan="2">Итого без скидки:</td>
                        <td class="right no-wrap">{{ priceFormat(kopToRub($closingAct->amount_nds)) }}</td>
                    </tr>
                    <tr>
                        <td class="right" colspan="2">Скидка:</td>
                        <td class="right no-wrap">0,00</td>
                    </tr>
                    <tr>
                        <td class="right" colspan="2">Итого:</td>
                        <td class="right no-wrap">{{ priceFormat(kopToRub($closingAct->amount_nds)) }}</td>
                    </tr>
                    <tr>
                        <td class="right" colspan="2">в том числе НДС {{ env('NDS_KOEF') * 100 }}%:</td>
                        <td class="right no-wrap">{{ priceFormat(kopToRub($nds)) }}</td>
                    </tr>
                </tfoot>
            </table>
            <div class="closing-act__amount-words">
                {{ 'Всего оказано услуг на сумму: ' . \NumberToWords\NumberToWords::transformCurrency('ru', $closingAct->amount_nds, 'RUB') . ', в т.ч. НДС ' . env('NDS_KOEF') * 100 . '% - ' . \NumberToWords\NumberToWords::transformCurrency('ru', $nds, 'RUB') }}
            </div>
            <div class="closing-act__amount-notify">
                Вышеперечисленные услуги выполнены полностью и в срок. Заказчик претензий по объему, качеству и срокам оказания услуг не имеет
            </div>
            <table class="closing-act__bottom">
                <tbody>
                    <tr>
                        <td style="width: 70%; padding-bottom: 20px;">
                            <span class="left">Исполнитель:</span>
                            <span style="margin-top: auto" class="signature signature-left">
                                <img class="image-signature left" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/static/signature.png'))) }}" alt="signature">
                                <span style="text-align: center;" class="value">
                                    подпись
                                </span>
                            </span>
                            <span class="right">{{ env('COMPANY_DIRECTOR_INITIALS') }}</span>
                        </td>
                        <td style="width: 30%;">
                            <span class="left">Заказчик:</span>
                            <span style="display: inline-block; margin-top: auto" class="signature">
                                <span class="value value-right">
                                    подпись
                                </span>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>
