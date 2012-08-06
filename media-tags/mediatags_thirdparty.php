<?php
function mediatag_thirdparty_support()
{
	if (function_exists('is_plugin_active'))
	{
		if (is_plugin_active('google-sitemap-generator/sitemap.php')) {
			$mediatags->thirdparty->google_sitemap = true;
			mediatags_google_sitemap_pages();
		}
	}
}

function mediatags_google_sitemap_pages()
{
	$mediatag_google_plugin = get_option('mediatag_google_plugin', 'no'); 
	if ($mediatag_google_plugin == "yes")
	{
		$generatorObject = &GoogleSitemapGenerator::GetInstance(); //Please note the "&" sign!
		if($generatorObject!=null) 
		{
			$mediatag_items = get_mediatags();
			if ($mediatag_items)
			{
				foreach($mediatag_items as $mediatag_item)
				{
					$mediatag_permalink = get_mediatag_link($mediatag_item->term_id);
					if (strlen($mediatag_permalink))
					{
						$generatorObject->AddUrl($mediatag_permalink, time(), "daily", 0.5);
					}				
				}
			}
		}	
	}
}
?>