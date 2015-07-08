{% extends "default.php" %}

{% block head %}
<link rel="stylesheet" href="/css/index.css" />
{% endblock %}
{% block content %}
<div class="row">
  <div class="col-md-12 leaderboard">
    <div class="inner">
      <div class="row vault-wall">
        <div class="col-md-12">
          <h3 class="title stroke-hard">I'm afraid I'm gonna have to ask for your help...</h3>
        </div>
      </div>
      <div class="row vault-floor">
        <div class="col-md-12">
        <p>Hi everyone,</p>
        <p>thanks for taking the time to read this. As of now, this site has been successful thanks to all of you who
          enjoy it every day. It's been predominantly coded and funded by me, a 19 year old student from Eastern Europe.
          However, the situation is such that I can't afford to fund the server that serves this site. I've done my best
          to reduce the usage to a certain limit, but I can't go any lower.</p>
        <p>I know ads are annoying, so I don't run them, and I never will. The majority of you uses adblock anyway, so the money
          from the ads would hardly make a difference.</p>
        <p>There are two ways that you can help me out with. I greatly appreciate every one of them.</p>
        <h4 class="title stroke">1. Chip in for server funding</h4>
        <p>The most obvious one. If you have any spare change, I'd much appreciate it - even $2 helps keep the lights on.
          100% of everything you give (after paypal fees) will go to server funding. I'll provide evidence of funds transfered
          to my host (Digital Ocean).</p>
        <p>If you'd like to help out that way, please click the button below. I can't use a "donate" button because of PayPal
        policies but I've provided three donation options ($2, $4 and $6). If you give me a link to your Thronebutt profile,
        I'll pay it back to you by giving you a badge that you can show off on the leaderboards.</p>
        <p>The goal is $20: this will keep the site sustained until I get back on my feet. Thank you again for considering this.</p>
        <center><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="GELLPLZBKB6DC">
        <table>
        <tr><td><input type="hidden" name="on0" value="Pick your donation!">Pick your donation!</td></tr><tr><td><select name="os0" style="color: black;">
        	<option value="Thank you!" style="color: black;">Thank you! $2.00 USD</option>
        	<option value="Thank you!!!" style="color: black;">Thank you!!! $4.00 USD</option>
        	<option value="THANK YOU!!!" style="color: black;">THANK YOU!!! $6.00 USD</option>
        </select> </td></tr>
        </table>
        <input type="hidden" name="currency_code" value="USD">
        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynow_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
      </form></center>
      <p>Alternatively, you can <b>send Bitcoins</b> to this address: <code>165NbfAnrPxRPkJqQSqR4WZmN3D5btKLNa</code></p>
        <h4 class="title stroke">2. Provide feedback!</h4>
        <p>If you can't or don't want to donate, I understand, and it's completely fine. Instead, you may want to suggest a change,
          an improvement or something else. I'm always willing to listen to feedback! If you have anything to say, you may
          tweet <a href="http://twitter.com/thronebutt">@thronebutt</a>, send me an email at <code>tko.netko&lt;at&gt;gmail.com</code>
          or <a href="http://steamcommunity.com/id/i542">leave me a profile comment on Steam</a>. I regularly check all of those places.
        </p>
        <p>Even if you do nothing, I'd like to thank you for supporting Thronebutt so far, and for visiting every day. You people are
        a bigger support than you can imagine! I'd especially like to thank the moderators of the site: Ledraps, squireofthedance,
        Fluury, HuntyMgee and LeonidasCraft for keeping an eye on it all the time and letting me know if something's broken.</p>

        <p>If you have any questions don't hesitate to contact me using links above.</p>

        <p>-- Darwin</p>
      </div>
    </div>
  </div>
</div>
{% endblock %}
