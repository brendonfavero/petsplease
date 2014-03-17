<?php
/**
 * @package psMetaGenerator
 */

class addon_psMetaGenerator_util
{

	public $head = "";
	public $categoryContent = "";

	public function core_filter_display_page ($full_text)
	{
		$full_text = str_ireplace("<head>", "<head>\n".$this->head, $full_text);
		if( $this->categoryContent != "" ) {
			$this->categoryContent = '<div id="categoryContent">' . $this->categoryContent . '</div>';
		}
		$full_text = str_replace("(!CATEGORY_CONTENT!)", $this->categoryContent , $full_text);
		return $full_text;
	}

  // Hook ran on every page render (see hooks in the info.php).
  // Designed to find any custom title/keywords/description for a page and
  // render that into the document (after the <head> tag)
  // 2011-08 adding the ability to parse filters for a particular page
  // to render sprintf input.
	public function core_notify_display_page ($vars)
	{
		$view = geoView::getInstance();

		$db = DataAccess::getInstance();
		$settingsRegistry = geoAddon::getRegistry('psMetaGenerator');

    // get site defaults (keep in mind, these can be overwritten by globals at the end)
    $meta = array( 'title' =>	$title = $settingsRegistry->get('title'),
                   'description' => $description = $settingsRegistry->get('description'),
                   'keywords' => $keywords = $settingsRegistry->get('keywords') );
    $og = array( 'title' => $meta['title'],
      'type' => 'company',
      'description' => $meta['title'],
      'url' => 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'],
      'image' => 'http://horsezone.com.au/images/logo.png' );
    $descLength = intval($settingsRegistry->get('descLength'));
		$siteName = $settingsRegistry->get('siteName');
		if ( $descLength < 1 ) {
			$descLength = 150;
    }
    $templateVars = array();
		$row = "";

		// get currently rendered page or addon
		$page = (isset($vars['this']->page_id)) ? $vars['this']->page_id : false ;
    $addon = (isset($vars['this']->addon_name)) ? $vars['this']->addon_name : false ;
    $pageID = intval( $page );
    $addonID = $addon ? "$addon:$page" : '' ;

		if ( $page || $addon ) {
      $pageResult = $db->Execute( sprintf( "SELECT `title`, `descr`, `keywords`
            FROM `ps_metaGenerator_pages`
            WHERE `pid` = '%d'" . ( $addon ? "AND `addon` = '%s'" : '' ),
        $pageID,
        mysql_real_escape_string( $addonID )
      ));
      // if a match was found for specific pages
			if( $pageResult && $pageResult->RecordCount() > 0 ) {
        $row = $pageResult->FetchRow();
        // load up the filters and parse template vars
        require_once( dirname( __FILE__ ) . '/filters.php' );
        $filters = new addon_psMetaGenerator_filters();
        if ( method_exists( $filters, "pid_$pageID$addonID" ) ) {
          $templateVars = array_merge( $templateVars, $filters->{"pid_$pageID$addonID"}() );
        }
        if ( $row['title'] ) {
          $og['title'] = $row['title'];
        }
        if ( $row['descr'] ) {
          $og['description'] = $row['descr'];
        }
      }
    }
		// get category
		if( isset( geoView::getInstance()->category_id)) {
			// get the meta/extra data for the category
			$result = $db->Execute("SELECT * FROM `ps_metaGenerator_categories` WHERE `cid` = '" . geoView::getInstance()->category_id . "'");
			if($result && $result->RecordCount() > 0) {
					$row = $result->FetchRow();
			}

			// format title automagically
			if( strlen( $row['title'] ) < 1 && $settingsRegistry->get('autoCategory') != "" && $settingsRegistry->get('autoCategory') !== false ) {
        $categoryResult = $db->Execute("SELECT `c1`.`category_name` AS `child`, `c2`.`category_name` as `parent`
          FROM `geodesic_categories` as `c1` LEFT JOIN `geodesic_categories` as `c2` ON `c1`.`parent_id` = `c2`.`category_id`
          WHERE `c1`.`category_id` = '" . geoView::getInstance()->category_id  . "'");
				if($categoryResult && $categoryResult->RecordCount() > 0 && $cat = $categoryResult->FetchRow() ) {
					$category = $cat['child'];
					$category = str_replace("&", "&amp;", $category);
					$categoryPieces = explode( " ", $category);
					$lastWord = &$categoryPieces[ count($categoryPieces) - 1 ];
					$lastWord =   $this->pluralize( $lastWord );
					$title = implode(" ", $categoryPieces);
					if( $cat['parent'] != "" ) {
						$cat['parent'] = str_replace("&", "&amp;", $cat['parent']);
						$title .= ' - ' . $cat['parent'];
					}
					if( $siteName != "" ) {
						$title .= ' - ' . $siteName;
					}
					if( is_array($row) && isset( $row['title']) ) {
						$row['title'] = $title;
					}else{
						$row = array('title' => $title );
          }
          $og['title'] = $row['title'];
          $og['description'] = htmlentities( strip_tags( substr( $cat['extra'], 0, $descLength ) ) );
          $og['description'] = $og['description'] ? $og['description'] : ( $row['descr'] ? $row['descr'] : $meta['description'] );
				}
      }
		}

		//get listing
    if( isset( geoView::getInstance()->classified_id)) {
      $classified_id = geoView::getInstance()->classified_id;
      $listingResult = $db->Execute("SELECT `ads`.`title`, `ads`.`description`, `ads`.`category`, `cats`.`category_name`, `i`.`thumb_url`
        FROM `geodesic_classifieds` as `ads`
        LEFT JOIN `geodesic_categories` as `cats` ON `ads`.`category` = `cats`.`category_id`
        LEFT JOIN ( SELECT `thumb_url`, `classified_id` FROM `geodesic_classifieds_images_urls` WHERE `classified_id` = '$classified_id' ORDER BY `display_order` ) as `i` ON `ads`.`id` = `i`.`classified_id`
        WHERE `ads`.`id` = '$classified_id' ORDER BY `i`.`classified_id` ");
			if($listingResult && $listingResult->RecordCount() > 0 && $listing = $listingResult->FetchRow() ) {
				$listing['keywords'] = "";
				$categoryResult = $db->Execute("SELECT `keywords` FROM `ps_metaGenerator_categories` WHERE `cid` = '" . $listing['category'] . "'");
				if( $categoryResult && $categoryResult->RecordCount() > 0 && $category = $categoryResult->FetchRow() ) {
					$listing['keywords'] = $category['keywords'];
				}
        $row = array( 'title' => urldecode($listing['title']) . " - " . $listing['category_name'] . " - " . $siteName, 'descr' => htmlentities(strip_tags(substr(urldecode($listing['description']), 0, $descLength))), 'keywords' => $listing['keywords'] );
        $og['title'] = $row['title'];
        $og['description'] = $row['descr'];
        $og['image'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $listing['thumb_url'];
			}
    }

		if( $row ) {
      if ( $row['title'] ) {
        $meta['title'] = $row['title'];
        if ( isset( $_GET['a'] ) && ( $_GET['a'] == 5 || $_GET['a'] == 19 ) && ( !empty( $_GET['page'] ) || !empty( $_GET['b']['page'] ) ) ) {
          $pageNumber = 1;
          if ( !empty( $_GET['page'] ) ) {
            $pageNumber = $_GET['page'];
          }
          if ( $_GET['a'] == 19 && !empty( $_GET['b']['page'] ) ) {
            $pageNumber = $_GET['b']['page'];
          }
          $meta['title'] .= " - Page $pageNumber";
        }
			}
			$meta['description'] = $row['descr'] ? $row['descr'] : $meta['description'];
			$meta['keywords'] = $row['keywords'] ? $row['keywords'] : $meta['keywords'];
			if($row['extra'] ) {
				$this->categoryContent = $row['extra'];
			}
    }

    $globals = array( 'title', 'keywords', 'description' );
    $globals_og = array( 'title', 'type', 'description', 'url', 'image' );
    foreach ( $globals as $g ) {
      global ${"psMetaGenerator_$g"};
      if ( ${"psMetaGenerator_$g"} ) {
        $meta[ $g ] = ${"psMetaGenerator_$g"};
      }
    }
    foreach ( $globals_og as $g ) {
      global ${"psMetaGenerator_og_$g"};
      if ( ${"psMetaGenerator_og_$g"} ) {
        $og[ $g ] = ${"psMetaGenerator_og_$g"};
      }

    }
    $meta = $this->applyTemplateVars( $meta, $templateVars );
    $meta['keywords'] = $this->cleanText( $meta['keywords'] );
    $this->head = "      <title>{$meta['title']}</title>
    <meta name=\"description\" content=\"{$meta['description']}\" />
    <meta name=\"keywords\" content=\"{$meta['keywords']}\" />";
    foreach ( $og as $prop => $content ) {
      $this->head .= "       <meta property=\"og:$prop\" content=\"$content\" />\n";
    }
    $this->head .= '       <meta property="fb:admins" content="751771807" />';
	$this->head .= "<script type='text/javascript'>
var googletag = googletag || {};
googletag.cmd = googletag.cmd || [];
(function() {
var gads = document.createElement('script');
gads.async = true;
gads.type = 'text/javascript';
var useSSL = 'https:' == document.location.protocol;
gads.src = (useSSL ? 'https:' : 'http:') + 
'//www.googletagservices.com/tag/js/gpt.js';
var node = document.getElementsByTagName('script')[0];
node.parentNode.insertBefore(gads, node);
})();
</script>
<script type='text/javascript'>
googletag.cmd.push(function() {
googletag.defineSlot('/1065533/HZ_Stallionzone_med_rec', [300, 250], 'div-gpt-ad-1369803107377-0').addService(googletag.pubads());
googletag.defineSlot('/1065533/HZ_Stallionzone_Med_Rec_bottom', [300, 250], 'div-gpt-ad-1369803260799-0').addService(googletag.pubads());
googletag.defineSlot('/1065533/horsezone_other_top', [728, 90], 'horsezone_other_top').addService(googletag.pubads());
googletag.defineSlot('/1065533/horsezone_other_left', [160, 600], 'horsezone_other_left').addService(googletag.pubads());
googletag.defineSlot('/1065533/horsezone_other_bottom', [728, 90], 'horsezone_other_bottom').addService(googletag.pubads());
googletag.enableServices();
});
</script>";
  }

  public function cleanText( $txt ) {
    return mb_convert_encoding( $txt, 'ISO-8859-1' );
  }

  public function applyTemplateVars( $templates, $vars ) {
    foreach ( $vars as $name => $value ) {
      // apply the replace on each piece of the meta data rows (title, desc, etc)
      $templates = str_ireplace( '{'.$name.'}', $value, $templates );
    }
    return $templates;
  }

	 public function conditionallyPluralize( $string, $count )
    {
        if ( intval( $count ) !== 0 )
            return $this->pluralize( $string );

        return $string;
    }

    public function pluralize( $string )
    {

        $plural = array(
            array( '/(quiz)$/i',               "$1zes"   ),
	    array( '/^(ox)$/i',                "$1en"    ),
	    array( '/([m|l])ouse$/i',          "$1ice"   ),
	    array( '/(matr|vert|ind)ix|ex$/i', "$1ices"  ),
	    array( '/(x|ch|ss|sh)$/i',         "$1es"    ),
	    array( '/([^aeiouy]|qu)y$/i',      "$1ies"   ),
	    array( '/([^aeiouy]|qu)ies$/i',    "$1y"     ),
    	    array( '/(hive)$/i',               "$1s"     ),
    	    array( '/(?:([^f])fe|([lr])f)$/i', "$1$2ves" ),
    	    array( '/sis$/i',                  "ses"     ),
    	    array( '/([ti])um$/i',             "$1a"     ),
    	    array( '/(buffal|tomat)o$/i',      "$1oes"   ),
            array( '/(bu)s$/i',                "$1ses"   ),
    	    array( '/(alias|status)$/i',       "$1es"    ),
    	    array( '/(octop|vir)us$/i',        "$1i"     ),
    	    array( '/(ax|test)is$/i',          "$1es"    ),
    	    array( '/s$/i',                    "s"       ),
    	    array( '/$/',                      "s"       )
        );

        $irregular = array(
	    array( 'move',   'moves'    ),
	    array( 'sex',    'sexes'    ),
	    array( 'child',  'children' ),
	    array( 'man',    'men'      ),
	    array( 'person', 'people'   )
        );

        $uncountable = array(
	    'sheep',
	    'fish',
	    'series',
	    'species',
	    'money',
	    'rice',
	    'information',
	    'equipment'
        );

        // save some time in the case that singular and plural are the same
        if ( in_array( strtolower( $string ), $uncountable ) )
	    return $string;

        // check for irregular singular forms
        foreach ( $irregular as $noun )
        {
	    if ( strtolower( $string ) == $noun[0] )
	        return $noun[1];
        }

        // check for matches using regular expressions
        foreach ( $plural as $pattern )
        {
	    if ( preg_match( $pattern[0], $string ) )
	        return preg_replace( $pattern[0], $pattern[1], $string );
        }

        return $string;
    }


}
