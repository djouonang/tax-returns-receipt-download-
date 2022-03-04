<?php

  $html = '
			<html><head></head>
            
            <body style="line-height:2; width: 680px; font-size: 12px">

					<table class="header" width="100%" style="font-size: 12px;">
						<tr><td style="text-align:center; padding-bottom: 15px;"><img src="'.$logo_url.'" /></td></tr>
					</table>
                    <table class="receipt-details" style="font-size: 12px">
                        <tr>
                            <td width="80">Issued Date:</td>
                            <td width="165">'.$userinfo["Date"].'</td>
                            <td width="300">Canada Revenue Agency</td>
                            <td width="70">Receipt #:</td>
                            <td align="right" style="font-size: 14px;"><strong>1145</strong></td>
                        </tr>
                        <tr>
                            <td>Donation Date:</td>
                            <td>'.$userinfo["Date"].'</td>
                            <td>www.cra.gc.ca/charities-giving</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                    <br/>
                    <table class="donor-details" style="font-size: 12px">
                        <tr>
                            <td width="80">Received From:</td>
                            <td width="456">'.$userinfo["Name"].'<br/> '.$userinfo["Address"].'</td>
                            <td width="80">Amount: CAD</td>
                            <td align="right" style="font-size: 14px;"><strong>$'.$userinfo["Amount"].'</strong></td>
                        </tr>
                    </table>
                    <br/>
                    <table class="locality-details" style="font-size: 12px">
                        <tr>
                            <td width="450">Locality - Thornhill, ON, L4J 8A7</td>
                            <td width="300" style="font-size: 14px;">Hon. Treasurer '.$attachement_url.'</td>
                        </tr>
                    </table>
                    <p style="text-align: center">Please Retain - Official Receipt for Income Tax Purposes Canadian Registered Charity No. '.$registered_charity_number.'</p>

				</body>
			</html>';