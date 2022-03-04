<?php

  $html = '<html>
<head>
</head>

<body style="line-height:2;">
<table   style=" width:100%; table-layout:fixed; font-family: arial">

<tr><td ><img src="'.$logo_url.'" style="width: 65" /></td><td width="900" style=" font-size: 13px" >'.$organisation_name.'<br>Registered Charity Number:'.$registered_charity_number.'</td>
<td>&nbsp;</td>
</tr>


</table>
<br>
<br>

<table   style=" width:100%; table-layout:fixed; font-family: arial">
<tr><td>'.$paragraph.'</td></tr>

</table>



<table   style=" table-layout:fixed; font-size: 9px; background-color: #f4f4f4; padding-top: -10px; padding-bottom: 10px; padding-right: 2.5em; padding-left: 22.5px; width:100%; font-family: arial">
<h1 ></h1>    
<tr>

<th colspan="4" style="color:#3BAEDB; font-size:13px;  text-align: left; ">Official Donation Receipts for Income Tax Purposes</th>
							
						</tr>

<tr>


							<th  align="left" style="color:#3BAEDB;">Donated By:</th>
							<th align="left" style="color:#3BAEDB;">Registered Charity Number</th>
							<th align="left" style="color:#3BAEDB;">Receipt Number</th>
							<th  align="left" style="color:#3BAEDB;">Eligible Gift Amount</th>


						</tr>

<tr>


							<td >'.$userinfo["Name"].'</td>
							<td>'.$registered_charity_number.'</td>
							<td>'.$userinfo["Number"].'</td>
							<td>$'.$userinfo["Amount"].'</td>

						</tr>
 <tr>
                <td colspan="4"></td>
            </tr>
 

<tr>


							<td>'.$userinfo["Address"].'</td>
							<th align="left" style="color:#3BAEDB;">Canada Address</th>
							<th align="left" style="color:#3BAEDB;">Receipt Issued</th>
							<th  align="left" style="color:#3BAEDB;">Donation Amount</th>


						</tr>
<tr>


							<td >&nbsp;</td>
							<td>'.$organisation_address.'</td>
							<td>'.$userinfo["Date"].'</td>
							<td>$'.$userinfo["Amount"].'</td>

						</tr>

 <tr>
                <td colspan="4"></td>
            </tr>


<tr>


							<td>&nbsp;</td>
							<th align="left">&nbsp;</th>
							<th align="left" style="color:#3BAEDB;">Location Issued</th>
							<th  align="left" style="color:#3BAEDB;">Donation Year</th>


						</tr>

<tr>


							<td >&nbsp;</td>
							<td>&nbsp;</td>
							<td>'.$location.'</td>
							<td>'.$year.'</td>

						</tr>

 <tr>
                <td colspan="4"></td>
            </tr>


<tr>


							<td></td>
							<th align="left">&nbsp;</th>
							<th align="left">&nbsp;</th>
							<th  align="left" style="color:#3BAEDB;">Authorized Signature</th>


						</tr>
						
						<tr>


<td colspan="2" style=" font-size: 7px">For more information on all registered Canadian charities  visit <a href="canada.ca/charities-giving">Canada Revenue Agency</a></td>

<td>&nbsp;</td>
							
                            
							<td><img height="35px" src="'.$attachement_url.'" style="border-bottom: 1px solid black;"/></td>

						</tr>

</table>';