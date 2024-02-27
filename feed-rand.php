<?php
/**
 * Template Name: Random RSS2 Feed
 * Description: Custom RSS2 feed template to display random posts.
 */

header( 'Content-Type: ' . feed_content_type( 'rss2' ) . '; charset=' . get_option( 'blog_charset' ), true );
$more = 1;

echo '<?xml version="1.0" encoding="' . get_option( 'blog_charset' ) . '"?' . '>';

// Start the RSS2 feed
?>
<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	<?php
	// Add namespaces
	do_action( 'rss2_ns' );
	?>
>
<channel>
	<title><?php wp_title_rss(); ?></title>
	<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
	<link><?php bloginfo_rss( 'url' ); ?></link>
	<description><?php bloginfo_rss( 'description' ); ?></description>
	<lastBuildDate><?php echo get_feed_build_date( 'r' ); ?></lastBuildDate>
	<language><?php bloginfo_rss( 'language' ); ?></language>
	<?php
	// Add additional RSS2 feed head elements
	do_action( 'rss2_head' );
	?>

	<?php
	// Custom query to get random posts
	$query_args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'orderby'        => 'rand', // Order by random
		'posts_per_page' => get_option( 'posts_per_rss' ), // Number of posts per feed
	);

	$random_posts = new WP_Query( $query_args );

	if ( $random_posts->have_posts() ) :
		while ( $random_posts->have_posts() ) : $random_posts->the_post();
			?>
			<item>
				<title><?php the_title_rss(); ?></title>
				<link><?php the_permalink_rss(); ?></link>
				<pubDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ); ?></pubDate>
				<dc:creator><![CDATA[<?php the_author(); ?>]]></dc:creator>
				<guid isPermaLink="false"><?php the_guid(); ?></guid>
				<?php if ( get_option( 'rss_use_excerpt' ) ) : ?>
					<description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
				<?php else : ?>
					<description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
					<content:encoded><![CDATA[<?php the_content_feed( 'rss2' ); ?>]]></content:encoded>
				<?php endif; ?>
				<?php rss_enclosure(); ?>
				<?php
				// Additional RSS2 feed item elements
				do_action( 'rss2_item' );
				?>
			</item>
			<?php
		endwhile;
	endif;

	wp_reset_postdata();
	?>
</channel>
</rss>
