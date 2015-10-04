{
	"title":"Wemahu - Project discontinued",
	"date" : "04-10-2015",
	"slug" : "wemahu-discontinued",
	"author" : "Simon",
	"tag" : "news, wemahu",
	"category":"Projects"
}

About two years ago I started the Wemahu project which is basically a malware scanner for Joomla and Wordpress.
Unfortunately this project never really got any attention which has different reasons.

At first I simply did not put much afford in marketing the extensions. Second: It takes much time to keep
the signature database up to date. But the third (and probably most important) reason: To do effective malware scanning
you will need long running PHP scripts to be executed on the server. Sadly this is often restricted by the hosting
companies mainly used for wordpress or joomla installations. The alternative would be to use CLI scripts which is
often has the same limitation and additionally overburdens lots of users.

Wemahu has not received any updates in month so **I decided to discontinue the project.** If you're still interested in
using the extensions - or maybe even want to fork them - you can find them in my [GitHub account](https://github.com/nekudo).

For historical reasons here's the extensions changelog:

<pre><code class="language-markdown">
**Version 1.0.3 (2014-08-23)**

* Whitelist updates.
* Url changes.

**Version 1.0.2 (2014-02-23)**

* Added possibility to exclude folders from file modification scan.
* Minor layout improvements.
* Updated signature and whitelist database.

**Version 1.0.1 (2014-01-25)**

* Bugfix in filestack creation.
* Bugfix: Max. results in rulesets not saved.

**Version 1.0.0 (2013-11-24)**

* First stable release.
* Feature: Option to set if report-mail is send on empty report.

**Version 0.4.1 (2013-11-09)**

* Improvements in filestack creation.
* Updated whitelist for Joomla 3.2

**Version 0.4.0 (2013-11-03)**

* Feature: Automatic signature updates.
* Feature: Submit whitelist requests and malware reports to nekudo.com.
* Feature: Added progress bar.
* Feature: Possibility to run multiple conjobs with different rulesets.
* Bugfix: Problem when running cronjob and web-scan at the same time.
* Multiple minor bugfixes/improvements in source-code.

**Version 0.3.0 (2013-10-06)**

* New feature: Check files for changes by hash-values.
* New feature: Possibility to run Wemahu via cronjob.
* Added basic in-extention help/documentation.
* Performace improvements on machines with little memory.
* Updated regex database.

**Version 0.2.0 (2013-09-23)**

* Improvements in session-storage of reports.
* Added max-result parameters.
* Updated regex database.
* Added whitelist feature.
* Performance improvements while fetching filelist.

**Version 0.1.0 (2013-08-04)**

* First version.
</code></pre>