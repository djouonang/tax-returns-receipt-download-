<?php
  

  $html = '
			<html><head></head>
            
            <body style="width: 700px; font-size: 10px">

					<table class="header" width="100%" style="font-size: 12px;">
						<tr><td style="text-align:center;"><img width="300px" src="'.$logo_url.'" /></td></tr>
					</table>
                    <table class="receipt-details" style="font-size: 10px">
                        <tr>
                            <td width="15%">Issued Date:</td>
                            <td width="25%">'.$userinfo["Date"].'</td>
                            <td width="40%">Canada Revenue Agency</td>
                            <td width="10%">Receipt #: </td>
                            <td width="10%" align="right" style="font-size: 12px;"><strong>'.$userinfo["Number"].'</strong></td>
                        </tr>
                        <tr>
                            <td>Donation Date:</td>
                            <td>'.$userinfo["Date"].'</td>
                            <td>www.cra.gc.ca/charities-giving</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                    <br/><br/>
                    <table class="donor-details" style="font-size: 10px">
                        <tr>
                            <td width="15%">Received From:</td>
                            <td width="60%">'.$userinfo["Name"].'<br/> '.$userinfo["Address"].'</td>
                            <td width="15%">Amount: CAD</td>
                            <td width="10%"align="right" style="font-size: 12px;"><strong>$'.$userinfo["Amount"].'</strong></td>
                        </tr>
                    </table>
                    <br/><br/>
                    <table class="locality-details" style="font-size: 10px">
                        <tr>
                            <td width="55%">Locality - Thornhill, ON, L4J 8A7</td>
                            <td width="25%" style="font-size: 12px;">Hon. Treasurer</td>
                            <td width="20%"><img src="'.$attachement_url.'" height="80px"/></td>
                        </tr>
                    </table>
                    <p style="text-align: center">Please Retain - Official Receipt for Income Tax Purposes Canadian Registered Charity No. '.$registered_charity_number.'</p>

				</body>
			</html>';