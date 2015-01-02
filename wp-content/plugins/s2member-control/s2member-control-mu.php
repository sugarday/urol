<?php

add_filter( 'option_active_plugins', 's2member_control_option_active_plugins', 1000, 1 );

function s2member_control_option_active_plugins($plugins) {
    $plugin_name = 's2member/s2member.php';
    $unload_s2 = false;
    
    # if we're in admin section do not unload S2 plugin
    if (is_admin()) {
        return $plugins;
    }
    
    # if user is logged in do not unload S2 plugin
    foreach (array_keys($_COOKIE) as $key) {
        if (strstr($key, 'wordpress_logged_in')) {
            return $plugins;
        }
    }

    
    # check if S2 Member Control is disabled
    $disabled = wp_cache_get( 's2member_control_disabled' );
    if ( false == $disabled ) {
        $disabled = get_option('s2member_control_disabled', 0);
        wp_cache_set( 's2member_control_disabled', $disabled );
    }
    print "<!-- disabled: " . $disabled . " -->\n";
    if ( $disabled ) {
        return $plugins;
    }    
    
   
    # check if its a referrer to unload
    if (!$unload_s2) {
        if (array_key_exists('HTTP_REFERER', $_SERVER)) {
            $domains = wp_cache_get ('s2member_control_referral_domains' );
            if ( false == $domains ) {
                $domains = get_option('s2member_control_referral_domains');
                wp_cache_set( 's2member_control_referral_domains', $domains );
            }
            if ($domains) {
                $domains = safe_unserialize_options($domains);
                $ref_url = parse_url($_SERVER['HTTP_REFERER']);
                if (in_array($ref_url['host'], $domains)) {
                    echo "<!-- s2member-control: unloading due to referrer -->\n";
                    $unload_s2 = true;
                }
            }
        }
    }
    
    # check if the remote host is a host to unload
    if (!$unload_s2) {
        $hosts = wp_cache_get ('s2member_control_hosts_ips' );
        if (false == $hosts) {
            $hosts = get_option('s2member_control_hosts_ips');      
            wp_cache_set('s2member_control_hosts_ips', $hosts);
        }
        if ($hosts) {
            $hosts = safe_unserialize_options($hosts);
            if (count($hosts)) {
                $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
            	$tld = end(explode('.', $hostname));
                if ((in_array($hostname, $hosts)) || (in_array($_SERVER['REMOTE_ADDR'], $hosts)) || (in_array($tld, $hosts)) || (in_array(".".$tld, $hosts))) {
                    echo "<!-- s2member-control: unloading due to hostname -->\n";
                    $unload_s2 = true;
                }
            }
        }
    }
    
    # check if free day
    if (!$unload_s2) {
        $dayofweek = strtolower(date("l"));
        $days = wp_cache_get('s2member_control_days');
        if (false == $days) {
            $days = get_option('s2member_control_days');      
            wp_cache_set('s2member_control_days', $days);
        }
        if ($days) { 
            $days = safe_unserialize_options($days);   
            if ((array_key_exists($dayofweek, $days)) && ($days[$dayofweek])) {
                echo "<!-- s2member-control: unloading due to free day  -->\n";
                $unload_s2 = true;
            }
        }
    }
    
    # check if from twitter
    if (!$unload_s2) {
        if (array_key_exists('HTTP_REFERER', $_SERVER)) {
            $twitter = wp_cache_get('s2member_control_twitter');
            if (false == $twitter) {
                $twitter = get_option('s2member_control_twitter', 0);
                wp_cache_set('s2member_control_twitter', $twitter);
            }
            if ($twitter) {
                $ref_url = parse_url($_SERVER['HTTP_REFERER']);
                if ($ref_url['host'] == 't.co') {
                    echo "<!-- s2member-control: unloading due to twitter -->\n";
                    $unload_s2 = true;
                }
            }
        }
    }
    
    # check if from facebook
    if (!$unload_s2) {
        if (array_key_exists('HTTP_REFERER', $_SERVER)) {
            $facebook = wp_cache_get('s2member_control_facebook');
            if (false == $facebook) {
                $facebook = get_option('s2member_control_facebook', 0);
                wp_cache_set('s2member_control_facebook', $facebook);
            }
            if ($facebook) {
                $ref_url = parse_url($_SERVER['HTTP_REFERER']);
                 if ($ref_url['host'] == 'www.facebook.com') {
                     echo "<!-- s2member-control: unloading due to facebook -->\n";
                     $unload_s2 = true;
                 }
             }
         }
     }
     
     # check location
     if (!$unload_s2) {
         $location = geoip_record_by_name($_SERVER['REMOTE_ADDR']);
         $postal_codes = wp_cache_get('s2member_control_postal_codes');
         if (false == $postal_codes) {
             $postal_codes = get_option('s2member_control_postal_codes');
             wp_cache_set('s2member_control_postal_codes', $postal_codes);
         }
         $postal_codes = preg_split('/,/', $postal_codes);
         if (!in_array($location['postal_code'], $postal_codes)) {
             echo "<!-- s2member-control: unloading due to postal code -->\n";
             $unload_s2 = true;
         }
     }

    # if unload_s2 is true remove $plugin_name from the active plugins
    if ($unload_s2) {
        $_plugins = array();
        for ($i=0; $i < count($plugins); $i++) {
            if ($plugins[$i] != $plugin_name) {
                array_push($_plugins, $plugins[$i]);            
            }
        }
    } else {
        $_plugins = $plugins;
    }
    return $_plugins;
}


function safe_unserialize_options($option) {
    if (gettype($opts) == 'string') {
        $opts = unserialize($opts);
    }
    return $opts;
}
?>