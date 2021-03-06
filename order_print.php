<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 18/01/16
 * Time: 3:25 PM
 */
require_once('default-init.php');
require_once ('./api/sl_login.php');

$print_id= $_GET['id'];
$print_track_id = $_GET['track_id'];

$options = array(
    'http' => array(
        'method' => 'GET',
        'header' => 'Cookie: ' . $cookie_login,
    )
);
$context = stream_context_create($options);
$content = file_get_html($print_url . $print_id, false, $context);

$add_info = $content->find('td', 0);
$sender_name = $content->find('#txtDeliverFirstName', 0);//txtDeliverFirstName
$sender_phone = $content->find('#txtDeliverTelePhone', 0);//txtDeliverTelePhone
$receiver_name = $content->find('#txtReceiverFirstName', 0);//txtReceiverFirstName
$receiver_phone = $content->find('#txtReceiverTelePhone', 0);//txtReceiverTelePhone
$receiver_address = $content->find('#txtReceiverAddress', 0);//txtReceiverAddress
//$table_detail = $content->find('.detailTable', 0);
$table_detail = $content->find('td', 5)->plaintext;
$lblWeight = $content->find('#lblWeight', 0);//lblWeight
$lblSafeFree = $content->find('#lblSafeFree', 0);//lblSafeFree
$lblNote = $content->find('#lblNote', 0);//lblNote
$date = date("Y-m-d");

/*
 * <td style="position: relative; height: 104px;" colspan="4">

                    <div style="position: absolute; left: 5px; top: 15px;">胜隆国际快运<br>www.ctc366.com<br>514-778-2222</div>
                    <div style="position: absolute; left: 200px; top:15px;">M<br>谭:514-550-5767<br>http://express.panda2ici.com</div>
                    <img style="position: absolute; right: 10px; top: 5px;" class="btnPrint" src="http://ctc366.com/code128.aspx?num=$print_track_id">
                    <span style="position: absolute; letter-spacing: 10px; right: 10px; right: 60px; font-weight: bold; bottom: 10px;">*$print_track_id*</span>
                </td>
 */
$print_body = <<<EOD
    <div class="part">
        <table class="tableX">
            <tbody>
            <tr>
                <td colspan="4" style="position: relative; height: 104px;">
                    <img style="position: absolute; left: 5px; top: 7px;" src="/img/sl.png">
                    <div style="position: absolute; left: 320px; top:2px;"><br><br>M<br>谭:514-550-5767<br><div style="font-size:14px;">express.panda2ici.com</div></div>
                    <img style="position: absolute; right: 10px; top: 5px;" class="btnPrint" src="http://ctc366.com/code128.aspx?num=$print_track_id" alt="$print_track_id">
                    <span style="position: absolute; letter-spacing: 10px; right: 10px; right: 60px; font-weight: bold; bottom: 10px;">*$print_track_id*</span>
                </td>
            </tr>
            <tr style="height:27px;">
                <td style="width: 25%;">
                    <b>发件人/FROM：</b><span id="txtDeliverFirstName">$sender_name</span>
                </td>
                <td style="width: 25%;">
                    <b>电话/PHONE：</b>
                    <span id="txtDeliverTelePhone">$sender_phone</span>
                </td>
                <td style="width: 25%;">
                    <b>收件人/TO：</b><span id="txtReceiverFirstName">$receiver_name</span>
                </td>
                <td style="width: 25%;">
                    <b>电话/PHONE：</b>
                    <span id="txtReceiverTelePhone">$receiver_phone</span>
                </td>
            </tr>
            <tr style="height:50px;">
                <td style="position: relative; height:80px;" rowspan="3" colspan="2">
                        $table_detail
                </td>
                <td style="width: 50%;" colspan="2">
                    <div style="float: left;">
                        <b>地址/ADDRESS：</b><span id="txtReceiverAddress">$receiver_address</span>
                    </div>
                </td>
            </tr>
            <tr style="height:27px;">
                <td style="width: 25%;">
                    <b>重量(磅)/WEIGHT：</b><span id="lblWeight">$lblWeight</span>
                </td>
                <td style="width: 25%;">
                    <b保险/ASSURANCE：</b><span id="lblSafeFree">$lblSafeFree</span>
                </td>
            </tr>
            <tr style="height:27px;">
                <td colspan="2" style="width: 50%;">
                    <b>备注/NOTE：</b><span id="lblNote">$lblNote</span>
                </td>
            </tr>
            <tr style="height:27px;">
                <td style="width: 50%;" colspan="2">
                    <b>寄件人签名/SENDER'S SIGNATURE：</b>
                </td>
                <td style="width: 50%;" colspan="2">
                    <b>日期/DATE：$date</b>
                </td>
            </tr>
            </tbody></table>
    </div>
EOD;

$print_head = <<<EOD
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>打印订单</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $(".btnPrint").click(function () {
                window.print();
            });
            $(".part").eq(1).html($(".part").eq(0).html());
            $(".part").eq(2).html($(".part").eq(0).html());
        });
    </script>
    <style>
        body
        {
            padding: 0px;
            margin: 0px;
        }
        .part
        {
            margin: 0px auto;
            width: 780px;
        }

        table
        {
            width: 100%;
            font-size: 14px;
            border-collapse: collapse;
            border: 1px solid #000;
            font-family: 微软雅黑;
        }

        b
        {
            font-weight: normal;
            color: #474646;
            font-size: 12px;
        }

        table.tableX .detailTable th
        {
            border: 1px solid #000;
            text-align: center;
            font-size: 12px;
            text-indent: 0px;
            border: 1px solid #D0D1D7;
        }

        table.tableX .detailTable td
        {
            border: 1px solid #000;
            text-align: center;
            font-size: 12px;
            text-indent: 0px;
            border: 1px solid #D0D1D7;
        }

        table.tableX
        {
            margin-top: 20px;
            margin-bottom:20px;
            height: 270px;
            float:left;
        }

        table th
        {
            border: 1px solid #000;
            text-align: left;
            text-indent: 5px;
            font-weight: normal;
            color: #474646;
            font-size: 12px;
        }

        table td
        {
            border: 1px solid #000;
            text-align: left;
            text-indent: 2px;
        }

        table td b
        {
            float: left;
        }

        .btnPrint
        {
            cursor: pointer;
        }
        table.detailTable{ line-height:10px;}
    </style>
    <meta charset="UTF-8">
</head>
<body>
EOD;
$print_track_no = <<<EOD
<div class="part">
    <table style="height: 100px;border: none;">
        <tbody>
            <tr>
                <td style="position: relative; height: 34px;border: none;" colspan="4">
                    <img style="position: absolute; left: 10px; top: 5px;" class="btnPrint" src="http://ctc366.com/code128.aspx?num=$print_track_id">
                    <span style="position: absolute; letter-spacing: 10px; right: 10px; left: 60px; font-weight: bold; bottom: 10px;">*$print_track_id*</span>
                    <img style="position: absolute; right: 10px; top: 5px;" class="btnPrint" src="http://ctc366.com/code128.aspx?num=$print_track_id">
                    <span style="position: absolute; letter-spacing: 10px; right: 10px; right: 60px; font-weight: bold; bottom: 10px;">*$print_track_id*</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>
EOD;

$print_foot = '</body></html>';

echo $print_head . $print_body . $print_body  . $print_body . $print_track_no. $print_foot;