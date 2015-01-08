!html!
  <basefont face="Verdana, Geneva, sans-serif" />
<style>a{font-family:Verdana, Geneva, sans-serif;}</style>
  <font face="Verdana, Geneva, sans-serif" color="#333333">
  
	
    <p><strong>Dear {$data.firstname},</strong></p>

    <p>Below is your weekly update on the activity on each of your listings.</p>

    <p><strong><center>Have you featured your listing?</center></strong>
	TIP: Featured listings receive 7 times more exposure than normal listings because they rotate on the Pets Please homepage and appear at the top of their search category. 
	<br/>
	Choose a 14 day feature for $35 or a 30 day feature for $55.
	<br/>
	Click on 'My Active Listings' and select the green plus sign to feature your current listing.</p>
	<br/>
   
   <table bgcolor="#F0CECF" cellpadding="5"><tr><td align="justify"><font size="2" face="Verdana, Geneva, sans-serif"><strong><font color="FF0000;">Important Notice for Private Sellers:</font></strong> Please be aware of phishing scams. If a buyer only operates through their PayPal account and advises you that they don't have internet banking and have a pick up agent, this is not a legitimate enquiry. If you require any further information please contact <a href="admin@petsplease.com.au">admin@petsplease.com.au</a></font></td></tr></table>
   

   <h5><center>Your Weekly Ad Stats for <br />{$data.date|date_format:"%A, %B %e, %Y"} through {$data.dateEnd|date_format:"%A, %B %e, %Y"}</center></h5> 
    
    {foreach from=$data.classifieds item=classified}
    <table cellpadding="3" border="1" bordercolor="#E6E6C8"><tr>
            <td valign="top" align="left" bgcolor="#F5F5E6" width="156">{if strlen( $classified.thumb_url) > 1}<img src="http://petsplease.com.au/{$classified.thumb_url}" width="150" />{else}No Image Set<br />{/if}<br /><br />
            <font size="2" face="Verdana, Geneva, sans-serif">
                    <strong>Views: </strong>{$classified.viewsTotal}<br />
                    <strong>Referrals: </strong>{$classified.referralsTotal}<br />
                    <strong>Enquiries: </strong>{$classified.enquiriesTotal}<br />
                    <strong>Classified ID: </strong>{$classified.id}<br />
                    <strong>Price: </strong>${$classified.price}<br />
                    <strong>Placed: </strong>{$classified.start_time|date_format:"%d.%m.%Y"}<br />
                    {* <strong>Expires: </strong>{$classified.end_time|date_format:"%d.%m.%Y"}<br /> *}
                    {if $classified.featured_ad > 0 } 
                    <strong>Featured: </strong> Yes
                    {/if}
                    </font>
            </td>
            <td valign="top" align="justify">
                <h3 align="center"><a href="http://www.petsplease.com.au/?a=2&amp;b={$classified.id}" target="petsplease"><font face="Verdana, Geneva, sans-serif">{$classified.title|urldecode}</font></a></h3>
               <font face="Verdana, Geneva, sans-serif">
                <strong>Views:</strong><br />
                <img src="http://petsplease.com.au/views/{$classified.id}_{$data.date}.png" /><br />
                </font>
            </td>
        </tr>
    </table><br /><br />
    {/foreach}




	<p>If you have sold your pet don't forget to <a href="http://www.petsplease.com.au/login" target="petsplease">login</a> and go to my active listings and click on the sold icon that looks like this: <img src="http://petsplease.com.au/images/buttons/btn_user_not_sold.gif" /></p>

	<p><strong>Want to log into Pets Please?</strong><br /> Please visit Pets Please and click <a href="http://www.petsplease.com.au/mypetsplease" target="petsplease">My Pets Please</a> on the top right of our webpage.</p>

    <p><strong>Forgotten your password?</strong><br /> If you have forgotten your password please go <a href="http://www.petsplease.com.au/index.php?a=18" target="petsplease">here</a> to retrieve it. </p>
	

     <table bgcolor="#C8D5E6" cellpadding="5"><tr><td align="center"><font size="3"  face="Verdana, Geneva, sans-serif">To unsubscribe from your Weekly Listing Performance Update email, please <a href="http://petsplease.com.au/unsubscribe?user={$data.unsubscribe}" target="petsplease" >click here</a> </font></td></tr></table>
     
     
    <p>If you have any further questions, please do not hesitate to contact us at admin@petsplease.com.au</p>

  <p>Kind regards,</p>
  <p>The Pets Please team</p>


</font>



