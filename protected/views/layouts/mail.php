<html>
    <head>
        <meta content="text/html; charset=UTF-8" http-equiv="content-type">
        <style>
            .schedule {
                margin:0px;padding:0px;
                width:100%;
                border:1px solid #000000;

                -moz-border-radius-bottomleft:0px;
                -webkit-border-bottom-left-radius:0px;
                border-bottom-left-radius:0px;

                -moz-border-radius-bottomright:0px;
                -webkit-border-bottom-right-radius:0px;
                border-bottom-right-radius:0px;

                -moz-border-radius-topright:0px;
                -webkit-border-top-right-radius:0px;
                border-top-right-radius:0px;

                -moz-border-radius-topleft:0px;
                -webkit-border-top-left-radius:0px;
                border-top-left-radius:0px;
            }.schedule table{
                border-collapse: collapse;
                border-spacing: 0;
                width:100%;
                height:100%;
                margin:0px;padding:0px;
            }.schedule tr:last-child td:last-child {
                -moz-border-radius-bottomright:0px;
                -webkit-border-bottom-right-radius:0px;
                border-bottom-right-radius:0px;
            }
            .schedule table tr:first-child td:first-child {
                -moz-border-radius-topleft:0px;
                -webkit-border-top-left-radius:0px;
                border-top-left-radius:0px;
            }
            .schedule table tr:first-child td:last-child {
                -moz-border-radius-topright:0px;
                -webkit-border-top-right-radius:0px;
                border-top-right-radius:0px;
            }.schedule tr:last-child td:first-child{
                -moz-border-radius-bottomleft:0px;
                -webkit-border-bottom-left-radius:0px;
                border-bottom-left-radius:0px;
            }.schedule tr:hover td{

            }
            .schedule tr:nth-child(odd){ background-color:#aad4ff; }
            .schedule tr:nth-child(even)    { background-color:#ffffff; }.schedule td{
                vertical-align:middle;


                border:1px solid #000000;
                border-width:0px 1px 1px 0px;
                text-align:left;
                padding:3px;
                font-size:10px;
                font-family:Arial;
                font-weight:normal;
                color:#000000;
            }.schedule tr:last-child td{
                border-width:0px 1px 0px 0px;
            }.schedule tr td:last-child{
                border-width:0px 0px 1px 0px;
            }.schedule tr:last-child td:last-child{
                border-width:0px 0px 0px 0px;
            }
            .schedule tr:first-child td{
                background:-o-linear-gradient(bottom, #005fbf 5%, #003f7f 100%);	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #005fbf), color-stop(1, #003f7f) );
                background:-moz-linear-gradient( center top, #005fbf 5%, #003f7f 100% );
                filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#005fbf", endColorstr="#003f7f");	background: -o-linear-gradient(top,#005fbf,003f7f);

                background-color:#005fbf;
                border:0px solid #000000;
                text-align:center;
                border-width:0px 0px 1px 1px;
                font-size:11px;
                font-family:Arial;
                font-weight:bold;
                color:#ffffff;
            }
            .schedule tr:first-child:hover td{
                background:-o-linear-gradient(bottom, #005fbf 5%, #003f7f 100%);	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #005fbf), color-stop(1, #003f7f) );
                background:-moz-linear-gradient( center top, #005fbf 5%, #003f7f 100% );
                filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#005fbf", endColorstr="#003f7f");	background: -o-linear-gradient(top,#005fbf,003f7f);

                background-color:#005fbf;
            }
            .schedule tr:first-child td:first-child{
                border-width:0px 0px 1px 0px;
            }
            .schedule tr:first-child td:last-child{
                border-width:0px 0px 1px 1px;
            }
        </style>
    </head>
    <table cellspacing="0" cellpadding="10" style="color:#666;font:13px Arial;line-height:1.4em;width:100%;">
        <tbody>
            <tr>
                <td style="color:#4D90FE;font-size:22px;border-bottom: 2px solid #4D90FE;">
                    <?php echo CHtml::encode(Yii::app()->name); ?>
                </td>
            </tr>
            <tr>
                <td style="color:#777;font-size:16px;padding-top:5px;">
                    <?php
                    if (isset($data['description'])) {
                        echo $data['description'];
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $content ?>

                    <p>Com os melhores cumprimentos,</p>
                    <p>A equipa <?php echo CHtml::encode(Yii::app()->name); ?></p>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>