<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/">
	<channel>
		<title><?php echo $siteName; ?></title>
		<description><?php echo $siteDesc; ?></description>		
		<link><?php echo base_url(); ?></link>
		<atom:link href="<?php echo base_url('feed.xml') ?>" rel="self" type="application/rss+xml" />
		<?php foreach ($posts as $post): ?>
			<item>
				<title><?php echo $post['title']; ?></title>
				<?php if($post['excerpt']): ?>
				<description><?php echo $post['excerpt']; ?></description>
			    <?php else: ?>
				<description><?php echo $post['content']; ?></description>
			    <?php endif; ?>
                <pubDate><?php $post['date'] ?></pubDate>
				<link><?php echo $post['url'] ?></link>
			</item>
		<?php endforeach; ?>
	</channel>
</rss>