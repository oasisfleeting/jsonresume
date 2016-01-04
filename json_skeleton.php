<?php
/**
 * Created by IntelliJ IDEA.
 * User: admin
 * Date: 1/1/2016
 * Time: 10:00 AM
 */

$basic   = array(
		'id'
, 'first-name'
, 'last-name'
, 'maiden-name'
, 'formatted-name'
, 'phonetic-first-name'
, 'phonetic-last-name'
, 'formatted-phonetic-name'
, 'headline'
, 'location'
, 'industry'
, 'current-share'
, 'num-connections'
, 'num-connections-capped'
, 'summary'
, 'specialties'
, 'positions'
, 'picture-url'
, 'picture-urls::(original)'
, 'site-standard-profile-request'
, 'api-standard-profile-request'
, 'public-profile-url'
, 'email-address');
$full    = array(
		'last-modified-timestamp'
, 'proposal-comments'
, 'associations'
, 'interests'
, 'publications'
, 'patents'
, 'languages'
, 'skills'
, 'certifications'
, 'educations'
, 'courses'
, 'volunteer'
, 'three-current-positions'
, 'three-past-positions'
, 'num-recommenders'
, 'recommendations-received'
, 'following'
, 'job-bookmarks'
, 'suggestions'
, 'date-of-birth'
, 'member-url-resources'
, 'related-profile-views'
, 'honors-awards');
$contact = array(
		'phone-numbers'
, 'main-address'
, 'bound-account-types'
, 'im-accounts'
, 'twitter-accounts'
, 'primary-twitter-account');

$profile                     = new stdClass();
$profile->basic['basic']     = $basic;
$profile->full['full']       = $full;
$profile->contact['contact'] = $contact;
?>
