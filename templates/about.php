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
          <h3 class="title stroke-hard">About the site</h3>
        </div>
      </div>
      <div class="row vault-floor">
        <div class="col-md-12">
          <h3>On this page:</h3>
          <p>
            <a href="#privacy">Privacy policy and terms of use</a><br/>
            <a href="#mony">Transparency</a></br>
            <a href="#credits">Credits and acknowledgments</a><br/>
            <a href="#faq">Frequently asked questions</a><br/>
            <a href="#contact">Contact</a><br/>
          </p>
          <h3>Privacy policy and terms of use</h3>
          <p><b>This site uses Google Analytics</b>. We collect data about how often you visit and how
          much time you spend on the site, as well as what led you to come here. There is no personally identifiable
          data included in this - we don't know your gender, your interests or what you ate for lunch, nor are we interested
          in such things.</p>
          <p><b>I'm commited to keeping this site ad-free.</b> This site costs some money each month to maintain, but I don't
          intend on littering the site with ads, as I consider them bad. If you find this site useful, you can donate to the
          site maintenance fund - all donations will be redirected to my server provider and my domain name registrar, and
          in case there's a remainder at the end of the year, it will be directed to a charitable organization of my choice.</p>
          <p><b>When you log in with Steam,</b> we don't get to see your password - instead, we simply get a confirmation
          from Steam that it indeed is you who is logging in. All other data comes directly from Steam. You may set your
          profile privacy to private in Steam options if you do not want to have your Steam profile data public.</p>
          <p><b>When you publish content to this site,</b> you agree to let us share the content you published worldwide, without
          restrictions. As there are no private profile fields or a friends system, you must understand that whatever you
          publish <b>will be shared with the entire Internet</b>. You also agree not to use thronebutt.com to abuse
          or harass other users, exploit the site or otherwise deny other people's access to the site, share illegal content, share
          content inappropriate for minors (examples: pornography, gambling, drugs, self-harm), unwanted
          advertisements or content that promotes or denounces a specific political agenda. I understand that we all have an opinion
          about those things, but this is not a place to discuss them - leave your tumblr accounts and conspiracy images with red
          arrows from this site.</p>
          <p><b>Getting content deleted:</b> You can remove your content from the site at any time. Likewise, moderators may remove
          your content if it breaches the rules of the site, detailed here. All deletions are permanent, however your data may persist in
          server backups for up to two weeks after it was deleted. Unless your content violates the site rules (you may send us
          reports via mail), we won't remove it from the site. If your content gets deleted, you will not be able to get a copy of it.</p>
          <p><b>TL;DR - Don't be a dick.</b> As the nature of this site is to promote competitive spirit, I'm sure there will be
          some hard feelings and some level of salt involved, but please don't let it show in your public communication with
          other people via this site.</p>

          <h3 id="mony">Transparency</h3>

          <b>Fundraiser - July 2015:</b> <a href="/index.php?do=help">Click here for details</a>

          <h3 id="credits">Credits and acknowledgments</h3>
          <p>This site uses assets from Nuclear Throne. Permission to use those assets was granted by the developer.</p>

          <p>These glorious individuals have helped shape the site:
            <ul>
              <li><a href="/player/76561198043826390">Ledraps</a> - site moderator</li>
              <li><b>Gieron</b> - advertised the site a lot and put me in touch with the devs</li>
              <li><b>jwaap</b> - suggested Top% in profile view</li>
              <li><b>Hunty Mgee</b> - suggested global stats, made the default no-profile YV avatar</li>
              <li><b>mtar</b> - suggested sorting all time stats by average</li>
              <li><b>squireofthedance</b> - beta tester</li>
            </ul>
          </p>

          <p>This page would not be possible without:
          <ul><li><a href="http://store.steampowered.com">Steam</a> Web API</li>
          <li><a href="http://nuclearthrone.com/">Nuclear Throne</a>, the game</li>
          <li><a href="http://getbootstrap.com/">Bootstrap</a></li>
          <li><a href="http://twig.sensiolabs.org/">Twig</a></li>
          <li><a href="https://github.com/eternicode/bootstrap-datepicker">Date picker for Bootstrap</a></li></ul></p>

          <h3 id="faq">Frequently Asked Questions</h3>
          <p><b>Q: Why can't I see what character did I play with or what level did I reach?</b><br/>
          A: Because there's no support for that in the game yet. You may add that data to your score manually if you so desire.</p>

          <p><b>Q: I'm afraid of fun - can I opt out?</b><br/>
          A: At the moment, you can't opt out from the public leaderboards.

          <p><b>Q: What does [no profile] mean?</b><br/>
          A: It means that the user did not set up their Steam profile yet. While they have a profile on this site, they did not yet
          set their community name or avatar, meaning that they will be hooked up with a dank YV picture courtesy of <a href="http://steamcommunity.com/id/theoriginalhunty/">HuntyMgee</a> and a "[no profile]" name.</p>

          <p><b>Q: Why was I marked as a hacker?</b><br/>
          A: Our moderators have deemed that your score is unlikely to be legitimate. You may appeal your
          status from your profile page. In the meantime your scores will be hidden from the main site, you will not show up on
          all-time leaderboards and your new scores won't be ranked.</p>

          <p><b>Q: I cheated - how can I get my hacker status revoked?</b><br/>
          A: Once 60 days have passed from your last forged score, you may be allowed to participate in the leaderboards again.
          All of your forged scores will be removed. If we suspect you cheated one more time, and you don't have evidence to prove
          us wrong, you won't be able to appeal your status again.</p>
          <h3 id="contact">Contact</h3>
          <p>Via Steam: <a href="http://steamcommunity.com/id/i542">Here</a></p>
          <p>Via e-mail: <code>tko.netko@gmail.com</code></p>
          <p>Via github: <a href="http://github.com/crescentrose/nuclear-throne-leaderboards">crescentrose/nuclear-throne-leaderboards</a></p>
        </p>
      </div>
    </div>
  </div>
</div>
{% endblock %}
