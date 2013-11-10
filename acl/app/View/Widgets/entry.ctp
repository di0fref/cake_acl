<?php


$row = 0;
foreach ($data["xml"]->channel->item as $entry) {
	?>
	<div class='RssEntry'>
			<span class='RssPlusbox'>
				<a href='#' class='fmaxbox' title='Show this article'></a>
			</span>

		<div class='RssHeadlinePreview'><a href='$entry->link'><?php echo strip_tags($entry->title); ?></a></div>
		<div class='RssSummary'>
			<div class='RssEntryOuterContent'>
				<?php echo nl2br(strip_tags(br2nl($entry->description))); ?>
			</div>
		</div>
	</div>
	<?php    ++$row;
	if ($row == $data["nr_of_articles"])
		break;
}

function br2nl($string)
{
	$return = eregi_replace('<br[[:space:]]*/?' .
	'[[:space:]]*>', chr(13) . chr(10), $string);
	return $return;
}

?>