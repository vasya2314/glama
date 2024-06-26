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
    @page {
        size: A4 landscape;
    }
    body {
        font-family: 'DejaVu Sans', sans-serif;
        margin: 0px;
        padding: 0px;
        width: 100%;
        height: 100%;
        background: #FFF;
    }
    * {
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }
    body,
    td,
    th {
        font-family: 'DejaVu Sans', sans-serif;
        font-weight: normal;
        color: #000;
    }
    body .upd,
    .upd td,
    .upd th {
        font-size: 8px;
        vertical-align: top;
    }
    a {
        color: #000;
        text-decoration: underline !important;
    }
    a img {
        border: none;
    }
    h1,
    h2,
    h3 {
        font-weight: bold;
    }
    body,
    td,
    th {
        font-size: 12px;
    }
    h1 {
        font-size: 30px;
    }
    h2 {
        font-size: 24px;
    }
    h3 {
        font-size: 18px;
    }
    @media screen {
        .doc.landscape {
            width: 1080px;
        }
    }
    .updorderlist,
    .updorderlist td {
        border: 1px solid #000;
        border-collapse: collapse;
    }
    .updorderlist {
        text-align: center;
        margin-top: 15px;
    }
    table td {
        padding: 1px!important;
        line-height: 110%!important;
    }
    .updskeleton,
    .updskeleton>tbody>tr>td {
        border: 1px solid #FFF;
    }
    .updskeleton {
        margin-top: 30px;
    }
    table {
        border-collapse: collapse;
    }
</style>

@php
    $nds = $closingInvoice->amount_nds - $closingInvoice->amount;
    $dataContract = @json_decode($contract->data);
@endphp

<div class="doc landscape upd" style="max-width: 1000px; width: 100%;">

    <table class="updskeleton" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
            <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tbody>
                    <tr>
                        <td width="100">Счет-фактура №</td>
                        <td width="100"
                            style="border-bottom: 1px solid #000; text-align: center !important">{{ $closingInvoice->id }}</td>
                        <td width="20" style="text-align: center">от</td>
                        <td width="100"
                            style="border-bottom: 1px solid #000; text-align: center !important">{{ Carbon\Carbon::parse($closingInvoice->date_generated)->translatedFormat('d F Y') }} </td>
                        <td width="20">(1)</td>
                        <td rowspan="2" style="text-align: right !important; font-size: 6px">
                            Приложение № 1 к постановлению Правительства Российской Федерации от 26 декабря
                            2011 г. № 1137
                            (в редакции постановления Правительства Российской Федерации от 2 апреля 2021 г.
                            № 534)
                        </td>
                    </tr>
                    <tr>
                        <td>Исправление №</td>
                        <td style="text-align: center; border-bottom: 1px solid #000">--</td>
                        <td style="text-align: center">от</td>
                        <td style="text-align: center; border-bottom: 1px solid #000">--</td>
                        <td>(1а)</td>
                    </tr>
                    </tbody>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td width="50%">
                            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                <tbody>
                                <tr>
                                    <td width="170"><b>Продавец:</b></td>
                                    <td style="border-bottom: 1px solid #000">{{ env('COMPANY_NAME') }}</td>
                                    <td width="20">(2)</td>
                                </tr>
                                <tr>
                                    <td>Адрес:</td>
                                    <td style="border-bottom: 1px solid #000">{{ env('COMPANY_POSTCODE') . ', ' . env('COMPANY_ADDRESS') }}</td>
                                    <td>(2а)</td>
                                </tr>
                                <tr>
                                    <td>ИНН/КПП продавца:</td>
                                    <td style="border-bottom: 1px solid #000">{{ env('COMPANY_INN') . '/' . env('COMPANY_KPP') }}
                                    </td>
                                    <td>(2б)</td>
                                </tr>
                                <tr>
                                    <td>Грузоотправитель и его адрес:</td>
                                    <td style="border-bottom: 1px solid #000">--</td>
                                    <td>(3)</td>
                                </tr>
                                <tr>
                                    <td>Грузополучатель и его адрес:</td>
                                    <td style="border-bottom: 1px solid #000">--</td>
                                    <td>(4)</td>
                                </tr>
                                <tr>
                                    <td>К платежно-расчетному документу №</td>
                                    <td style="border-bottom: 1px solid #000">
                                        @foreach($transactions as $transaction)
                                            {{ '№ ' . $transaction->id . ' от ' . Carbon\Carbon::parse($closingAct->date_generated)->translatedFormat('d.m.Y') }}
                                            @if($transactions->last() !== $transaction)
                                                {{ ', ' }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>(5)</td>
                                </tr>
                                <tr>
                                    <td>Документ об отгрузке</td>
                                    <td style="border-bottom: 1px solid #000">№ п/п 1 №{{ $closingAct->act_number }} от {{ Carbon\Carbon::parse($closingAct->date_generated)->translatedFormat('d F Y') }}</td>
                                    <td>(5a)</td>
                                </tr>
                                </tbody>
                            </table>

                        </td>
                        <td>
                            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                <tbody>
                                <tr>
                                    <td width="170"><b>Покупатель:</b></td>
                                    <td style="border-bottom: 1px solid #000">{{ $contract->display_name }}</td>
                                    <td width="20">(6)</td>
                                </tr>
                                <tr>
                                    <td>Адрес:</td>
                                    <td style="border-bottom: 1px solid #000">{{ $dataContract->legal_address }}</td>
                                    <td>(6а)</td>
                                </tr>
                                <tr>
                                    <td>ИНН/КПП покупателя:</td>
                                    <td style="border-bottom: 1px solid #000">{{ $dataContract->inn . '/' . $dataContract->kpp }}
                                    </td>
                                    <td>(6б)</td>
                                </tr>
                                <tr>
                                    <td>Валюта: наименование, код</td>
                                    <td style="border-bottom: 1px solid #000">Российский рубль, 643</td>
                                    <td>(7)</td>
                                </tr>
                                <tr>
                                    <td>Идентификатор государственного контракта, договора (соглашения)
                                        (при наличии):</td>
                                    <td style="border-bottom: 1px solid #000"> </td>
                                    <td>(8)</td>
                                </tr>
                                </tbody>
                            </table>

                        </td>
                    </tr>
                    </tbody>
                </table>

            </td>
        </tr>
        </tbody>
    </table>

    <table class="updorderlist" width="100%" border="0" cellspacing="0" cellpadding="5">
        <tbody>
        <tr style="font-size:6px">
            <td width="30px" rowspan="2">№ п/п</td>
            <td rowspan="2">Наименование товара (описание выполненных работ, оказанных услуг), имущественного
                права</td>
            <td rowspan="2" width="40px">Код вида това-
                ра</td>
            <td colspan="2">Единица измерения</td>
            <td rowspan="2" width="70px">Количест-
                во (объём)</td>
            <td rowspan="2" width="70px">Цена (тариф) за единицу измерения</td>
            <td rowspan="2" width="70px">Стоимость товаров (работ, услуг), имущест-
                венных прав без налога — всего</td>
            <td rowspan="2" width="50px">В том числе сумма акциза</td>
            <td rowspan="2" width="50px">Нало-
                говая ставка</td>
            <td rowspan="2" width="70px">Сумма налога, предъяв-
                ляемая покупателю</td>
            <td rowspan="2" width="70px">Стоимость товаров (работ, услуг), имущест-
                венных прав с налогом — всего</td>
            <td colspan="2">Страна происхождения товара</td>
            <td rowspan="2" width="60px" style="font-size:6px">Регистраци-
                онный номер декларации на товары или регист-
                рационный номер пар-
                тии товара, подлежаще-
                го просле-
                живаемости</td>
        </tr>
        <tr>
            <td width="40px">Код</td>
            <td width="30px">Условно-
                е обоз-
                начение (нацио-
                нальное)</td>
            <td width="30px">Циф-
                ровой код</td>
            <td width="30px">Краткое наиме-
                нование</td>
        </tr>
        <tr>
            <td style="text-align: center; font-size: 6px">1</td>
            <td style="text-align: center; font-size: 6px">1а</td>
            <td style="text-align: center; font-size: 6px">1б</td>
            <td style="text-align: center; font-size: 6px">2</td>
            <td style="text-align: center; font-size: 6px">2а</td>
            <td style="text-align: center; font-size: 6px">3</td>
            <td style="text-align: center; font-size: 6px">4</td>
            <td style="text-align: center; font-size: 6px">5</td>
            <td style="text-align: center; font-size: 6px">6</td>
            <td style="text-align: center; font-size: 6px">7</td>
            <td style="text-align: center; font-size: 6px">8</td>
            <td style="text-align: center; font-size: 6px">9</td>
            <td style="text-align: center; font-size: 6px">10</td>
            <td style="text-align: center; font-size: 6px">10а</td>
            <td style="text-align: center; font-size: 6px">11</td>
        </tr>
        <tr>
            <td>1</td>
            <td style="text-align: left">
                Услуги по размещению Интернет-рекламы по договору №
                {{ $contract->id }}
                (акт
                № {{ $closingAct->act_number }}
                от {{ Carbon\Carbon::parse($closingAct->date_generated)->translatedFormat('d F Y') }} г.)
            </td>
            <td>--</td>
            <td>--</td>
            <td>--</td>
            <td>--</td>
            <td>--</td>
            <td>{{ priceFormat(kopToRub($closingInvoice->amount)) }}</td>
            <td>без акциза</td>
            <td>{{ env('NDS_KOEF') * 100 }}%</td>
            <td>{{ priceFormat(kopToRub($nds)) }}</td>
            <td>{{ priceFormat(kopToRub($closingInvoice->amount_nds)) }}</td>
            <td>--</td>
            <td>--</td>
            <td>--</td>
        </tr>
        <tr>
            <td style="text-align: left" colspan="7">Всего к оплате (9)</td>
            <td>{{ priceFormat(kopToRub($closingInvoice->amount)) }}</td>
            <td colspan="2" style="text-align: center !important">X</td>
            <td>{{ priceFormat(kopToRub($nds)) }}</td>
            <td>{{ priceFormat(kopToRub($closingInvoice->amount_nds)) }}</td>
            <td colspan="3"> </td>
        </tr>
        </tbody>
    </table>
    <table class="updskeleton" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
            <td style="padding-bottom: 5px">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td width="49%">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                <tr>
                                    <td width="170" style="padding: 5px">Руководитель организации или
                                        иное уполномоченное лицо</td>
                                    <td style="border-bottom: 1px solid #000; padding: 5px" width="100">
                                    </td>
                                    <td width="10"> </td>
                                    <td
                                        style="text-align: center; vertical-align: bottom !important; border-bottom: 1px solid #000; padding: 5px">
                                        {{ env('COMPANY_DIRECTOR_INITIALS') }}</td>
                                </tr>
                                <tr>
                                    <td> </td>
                                    <td style="text-align: center; font-size:6px">(подпись)</td>
                                    <td> </td>
                                    <td style="text-align: center; font-size:6px">(ф.и.о.)</td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                        <td width="2%"> </td>
                        <td width="49%">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                <tr>
                                    <td width="170px" style="padding: 5px">Главный бухгалтер или иное
                                        уполномоченное лицо</td>
                                    <td style="border-bottom: 1px solid #000; padding: 5px" width="100px">
                                    </td>
                                    <td width="10px"> </td>
                                    <td
                                        style="text-align:center; vertical-align: bottom !important; border-bottom: 1px solid #000; padding: 5px">{{ env('COMPANY_DIRECTOR_INITIALS') }}</td>
                                </tr>
                                <tr>
                                    <td> </td>
                                    <td style="text-align: center; font-size:6px">(подпись)</td>
                                    <td> </td>
                                    <td style="text-align: center; font-size:6px">(ф.и.о.)</td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td width="170" style="padding: 5px">Индивидуальный предприниматель или иное
                            уполномоченное лицо</td>
                        <td style="border-bottom: 1px solid #000; padding: 5px" width="100"> </td>
                        <td width="10"> </td>
                        <td
                            style="vertical-align: bottom !important; border-bottom: 1px solid #000; padding: 5px"></td>
                        <td width="2%"> </td>
                        <td style="vertical-align: bottom !important; border-bottom: 1px solid #000; padding: 5px"
                            width="49%"></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td style="text-align: center; font-size:6px">(подпись)</td>
                        <td> </td>
                        <td style="text-align: center; font-size:6px">(ф.и.о.)</td>
                        <td> </td>
                        <td style="text-align: center; font-size:6px">(реквизиты свидетельства о
                            государственной регистрации индивидуального предпринимателя)</td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
