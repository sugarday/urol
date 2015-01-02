<?php
/*
Plugin Name: S2Member Control
Plugin URI: http://
Description: Plugin is for allowing admins to load / unload S2Member
Author: Don Kukral
Version: 2.4
Author URI: http://
*/

include_once('s2member-control-unloader.php');

add_action('admin_menu', 's2member_control_admin_menu');
add_action('wp', 's2member_control_init', 0);

function s2member_control_init() {
    global $post;
    // check if mobile is open and if browser is mobile
    $mobile = get_option('s2member_control_mobile', 0);
    if ($mobile) {
        $user_agent = strtolower ( $_SERVER['HTTP_USER_AGENT'] );
        if ( preg_match ( "/phone|iphone|itouch|ipod|symbian|android|htc_|htc-|palmos|blackberry|opera mini|iemobile|windows ce|nokia|fennec|hiptop|kindle|mot |mot-|webos\/|samsung|sonyericsson|^sie-|nintendo/i", $user_agent ) ) {
                  // these are the most common
                  s2member_control_unload();
                  return true;
          } else if ( preg_match ( "/mobile|pda;|avantgo|eudoraweb|minimo|netfront|brew|teleca|lg;|lge |wap;| wap /i", $user_agent ) ) {
                  // these are less common, and might not be worth checking
                  s2member_control_unload();
                  return true;
          }
    }
    $cats = wp_get_post_categories($post->ID);
    $ip = $_SERVER['REMOTE_ADDR'];
    if (startsWith($ip, "192.168")) {
            $ip = '174.96.193.121';
    }
    $location = geoip_record_by_name($ip);
#print_r($location);
#print "-->\n";
    $postal_codes = preg_split('/,/', get_option('s2member_control_postal_codes'));
    $categories = preg_split('/,/', get_option('s2member_control_categories', "")); 
    $days = get_option('s2member_control_days', false);
    if (($days) && (!is_array($days))) {
        $days = unserialize($days); 
    }
    #$days = unserialize(get_option('s2member_control_days'));
    
    $dayofweek = strtolower(date("l"));
    $domains = unserialize(get_option('s2member_control_referral_domains'));
    $hosts = unserialize(get_option('s2member_control_hosts_ips'));
    if (count($hosts)) {
    	$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
    	$tld = end(explode('.', $hostname));
    }
#print "<!-- \n";
#print " s2member\n";
#print_r($cats);
#print_r($categories);
#print "-->\n";
    
#print "<!--\n";
#print_r($postal_codes);
#print_r($location);
#print "-->\n";
    foreach ($categories as $c) {
        if (in_array($c, $cats)) {
            #echo "<!-- s2member-control: unloading due to category -->\n";
            s2member_control_unload();
            return;
        }
    }
    
    # check cat parents
    foreach ($cats as $cat) {
        foreach ($categories as $c) {
            if (cat_is_ancestor_of($c, $cat)) {
                #echo "<!-- s2member-control: unloading due to parent category -->\n";                
                s2member_control_unload();
                return;
            }
        }
    }

    if (array_key_exists('HTTP_REFERER', $_SERVER)) {
        $ref_url = parse_url($_SERVER['HTTP_REFERER']);
        if (in_array($ref_url['host'], $domains)) {
            #echo "<!-- s2member-control: unloading due to referrer -->\n";
            s2member_control_unload();
            return;
        }
    }
    
    if (count($hosts)) {
    if ((in_array($hostname, $hosts)) || (in_array($_SERVER['REMOTE_ADDR'], $hosts)) || (in_array($tld, $hosts)) || (in_array(".".$tld, $hosts))) {
        #echo "<!-- s2member-control: unloading due to hostname -->\n";
        s2member_control_unload();
        return;
    }
    }

    if ((array_key_exists($dayofweek, $days)) && ($days[$dayofweek])) {
        #echo "<!-- s2member-control: unloading due to free day  -->\n";
        s2member_control_unload();
        return;
    }
    
    if (get_option('s2member_control_twitter', 0)) {
        $ref_url = parse_url($_SERVER['HTTP_REFERER']);
        if ($ref_url['host'] == 't.co') {
            #echo "<!-- s2member-control: unloading due to twitter -->\n";
            s2member_control_unload();
            return;
        }
    }

   if (get_option('s2member_control_facebook', 0)) {
       $ref_url = parse_url($_SERVER['HTTP_REFERER']);
        if ($ref_url['host'] == 'www.facebook.com') {
            #echo "<!-- s2member-control: unloading due to facebook -->\n";
            s2member_control_unload();
            return;
        }
    }

    if (!in_array($location['postal_code'], $postal_codes)) {
        #echo "<!-- s2member-control: unloading due to postal code -->\n";
        s2member_control_unload();
        return;
    }
    
    if (get_option('s2member_control_cookie', '')) {
        if ($_COOKIE[get_option('s2member_control_cookie')] == 'S2_ACCESS') {
            s2member_control_unload();
            return;
        }
    }
    
}


function s2member_control_admin_menu() {
    add_options_page('S2Member Control', 'S2Member Control', 'administrator',
        's2member_control', 's2member_control_settings_page');
}

function s2member_control_settings_page() {
    if ( isset($_POST['action']) && $_POST['action'] == 'update' ) {
#        print "<pre>";
#        print_r($_POST);
#        print "</pre>";
        update_option('s2member_control_postal_codes', rtrim($_POST['s2member_control_postal_codes'], ','));
        update_option('s2member_control_cookie', rtrim($_POST['s2member_control_cookie'], ','));
        
        update_option('s2member_control_categories', rtrim($_POST['s2member_control_categories'], ','));
		echo '<div class="updated"><p>S2Member Control Settings Updated</p></div>';
	
		$domains = preg_split("/[\s,]*\\\"([^\\\"]+)\\\"[\s,]*|" . "[\s,]*'([^']+)'[\s,]*|" . "[\s,]+/", $_POST['s2member_control_referral_domains']);
		
		update_option('s2member_control_referral_domains', serialize($domains));
		
		$hosts = preg_split("/[\s,]*\\\"([^\\\"]+)\\\"[\s,]*|" . "[\s,]*'([^']+)'[\s,]*|" . "[\s,]+/", $_POST['s2member_control_hosts_ips']);
		
		update_option('s2member_control_hosts_ips', serialize($hosts));

		$days = array();
		if ($_POST['s2member_control_sunday']) { $days['sunday'] = 1; } else { $days['sunday'] = 0; }
		if ($_POST['s2member_control_monday']) { $days['monday'] = 1; } else { $days['monday'] = 0; }
		if ($_POST['s2member_control_tuesday']) { $days['tuesday'] = 1; } else { $days['tuesday'] = 0; }
		if ($_POST['s2member_control_wednesday']) { $days['wednesday'] = 1; } else { $days['wednesday'] = 0; }
		if ($_POST['s2member_control_thursday']) { $days['thursday'] = 1; } else { $days['thursday'] = 0; }
		if ($_POST['s2member_control_friday']) { $days['friday'] = 1; } else { $days['friday'] = 0; }
		if ($_POST['s2member_control_saturday']) { $days['saturday'] = 1; } else { $days['saturday'] = 0; }
		#print serialize($days);
		update_option('s2member_control_days', serialize($days));
        
        if ($_POST['s2member_control_twitter']) { update_option('s2member_control_twitter', 1); }
        else { delete_option('s2member_control_twitter'); }
        if ($_POST['s2member_control_facebook']) { update_option('s2member_control_facebook', 1); }
        else { delete_option('s2member_control_facebook'); }
        if ($_POST['s2member_control_mobile']) { update_option('s2member_control_mobile', 1); }
        else { delete_option('s2member_control_mobile'); }
        
	}

	$domain_list = _unserialize_as_list('s2member_control_referral_domains');
	$hosts = _unserialize_as_list('s2member_control_hosts_ips');
    $days = _unserialize_options('s2member_control_days');

    
    #print_r(unserialize($days));
    
?>
    <div>
    <h3 style="padding-top: 10px;">S2Member Control Options</h3>

    <form method="post">
    <input type="hidden" name="action" value="update" />
    
    <?php wp_nonce_field('update-options'); ?>
    <table width="710" style="padding-top: 15px;">

    <tr valign="top" scope="row">
    <td colspan="2"><strong>These postal codes will be required to use S2Member to view the site content.<br/><br/></strong></td>
    </tr>
    <tr valign="top">
    <th width="150" scope="row">Postal Codes</th>
    <td width="556">
    <input type="text" size="100" name="s2member_control_postal_codes" value="<?php echo get_option('s2member_control_postal_codes', ""); ?>"/>
    </td>
    </tr>
    <tr>
    <td></td>
    <td><em>Enter postal codes separated by commas</em></td>
    </tr>

    <tr valign="top" scope="row">
    <td colspan="2"><br/><strong>Links referred from these domains will be allow to view the page free.<br/><br/></strong></td>
    </tr>
    <tr valign="top">
    <th width="150" scope="row">Referral Domains</th>
    <td width="556">
        <textarea rows="5" cols="40" name="s2member_control_referral_domains"><?php echo $domain_list; ?></textarea>
    </td>
    </tr>
    <tr>
    <td></td>
    <td><em>Enter each domain on a new line.</em></td>
    </tr>

    <tr valign="top" scope="row">
    <td colspan="2"><br/><strong>Users with these hostnames, top level domains or IP Addresses will be allowed to browse the site free<br/><br/></strong></td>
    </tr>
    <tr valign="top">
    <th width="150" scope="row">Hostnames/IP Addresses</th>
    <td width="556">
        <textarea rows="5" cols="40" name="s2member_control_hosts_ips"><?php echo $hosts; ?></textarea>
    </td>
    </tr>
    <tr>
        <td></td>
        <td><em>Examples: (74.125.115.104, .com, google.com)</em></td>
    </tr>
    <tr>
    <td></td>
    <td><em>Enter each value on a new line.</em></td>
    </tr>

    <tr valign="top" scope="row">
    <td colspan="2"><br/><strong>The site will be free to everyone on these days.<br/><br/></strong></td>
    </tr>
    <tr valign="top">
    <th width="150" scope="row">Free Days</th>
    <td width="556">
        <input type="checkbox" name="s2member_control_sunday" <?php checked($days['sunday'], 1); ?>/> Sunday<br/>
        <input type="checkbox" name="s2member_control_monday" <?php checked($days['monday'], 1); ?>> Monday<br/>
        <input type="checkbox" name="s2member_control_tuesday" <?php checked($days['tuesday'], 1); ?>> Tuesday<br/>
        <input type="checkbox" name="s2member_control_wednesday" <?php checked($days['wednesday'], 1); ?>> Wednesday<br/>
        <input type="checkbox" name="s2member_control_thursday" <?php checked($days['thursday'], 1); ?>> Thursday<br/>
        <input type="checkbox" name="s2member_control_friday" <?php checked($days['friday'], 1); ?>> Friday<br/>
        <input type="checkbox" name="s2member_control_saturday" <?php checked($days['saturday'], 1); ?>> Saturday<br/>        
    </td>
    </tr>

    <tr valign="top" scope="row">
        <td colspan="2"><br/><strong>These categories will always be free to view.<br/><br/></strong></td>
    </tr>
    <tr valigh="top">
        <th width="150" scope="row">Categories</th>
        <td width="556"><input type="text" name="s2member_control_categories" value="<?php echo get_option('s2member_control_categories', ""); ?>"/></td>
    </tr>
    <tr>
        <td></td>
        <td><em>Enter category IDs separated by commas.</em></td>
    </tr>
    <tr>
        <td colspan="2"><br/><strong>Social Network Referrals</strong></td>
    </tr>
    <tr>
    <td></td>
    <td><input type="checkbox" name="s2member_control_twitter" <?php echo checked(get_option('s2member_control_twitter'), 1); ?>/> 
        Allow links from Twitter
    </td>
    </tr>
    <tr>
    <td></td>
    <td><input type="checkbox" name="s2member_control_facebook" <?php echo checked(get_option('s2member_control_facebook'), 1); ?>/> 
        Allow links from Facebook
    </td>
    <tr>
        <td colspan="2"><br/><strong>Mobile Browsers</strong></td>
    </tr>
    <tr>
    <td></td>
    <td><input type="checkbox" name="s2member_control_mobile" <?php echo checked(get_option('s2member_control_mobile'), 1); ?>/> 
        Allow mobile browsers to access site for free
    </td>
    </tr>
    <tr>
        <td><br/><input type="submit" value="<?php _e('Save Changes') ?>" /></td>
    </tr>
    <tr>
        <td colspan="2"><br/><strong>Cookies</strong></td>
    </tr>
    <tr>
    <td></td>
    <td><input type="text" size="100" name="s2member_control_cookie" value="<?php echo get_option('s2member_control_cookie', ""); ?>"/></td>
    </tr>
    <tr>
    <td></td>
    <td><em>Enter cookie that allows free access - value of cookie should be S2_ACCESS.</em></td>
    </tr>
    <tr>
        <td><br/><input type="submit" value="<?php _e('Save Changes') ?>" /></td>
    </tr>
    </table>
    </form>
    </div>
<?php	
/*    if ($domains) {
        print '<pre>';
        print_r($domains);
        print serialize($domains);
        print '</pre>';
    }
*/
}

function _unserialize_options($option) {
    $opts = get_option($option);
    if (gettype($opts) == 'string') {
        $opts = unserialize($opts);
    }
    return $opts;
}

function _unserialize_as_list($option) {
    $opts = get_option($option);
    if (gettype($opts) == 'string') {
        $opts = unserialize($opts);
    }
 
    $opt_list = '';
    if ($opts) {
        foreach ($opts as $opt) {
            $opt_list .= $opt . "\n";
        }
    }    
    return $opt_list;
}

function startsWith($haystack, $needle)
{
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}
?>
