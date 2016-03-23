<?= '<?xml version="1.0" encoding="' . $encoding . '"?>' . "\n" ?>
<rss version="2.0"
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
   xmlns:admin="http://webns.net/mvcb/"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:content="http://purl.org/rss/1.0/modules/content/">

	<channel>

		<description><?= $site_description ?></description>
		<link><?= $site_link ?></link>
		<title><?= $site_name ?></title>
		<dc:language><?= $page_language ?></dc:language>
		<dc:rights>Copyright <?= gmdate("Y", time()) ?></dc:rights>

		<?php foreach ($articles->result() as $article): ?>
		<item>
			<title><?= $article->title ?></title>
			<guid><?= base_url($article->slug) ?></guid>
			<description>
				<?= xml_convert($this->markdown->parse($article->content)) ?>
			</description>
			<pubDate><?= $article->cdate ?></pubDate>
		</item>
		<?php endforeach; ?>

	</channel>

</rss>