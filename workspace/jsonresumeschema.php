<?php

/**
 * Created by IntelliJ IDEA.
 * User: admin
 * Date: 1/1/2016
 * Time: 8:55 AM
 */
class JsonResumeSchema
{
	protected $userid;
	protected $response;
	protected $profile;

	/**
	 * @param $user_id
	 * @param $linkedin_response
	 */
	public function _constructor($user_id, $linkedin_response)
	{
		$this->userid = $user_id;

	}

	/**
	 *
	 */
	public function parseLinkedinResponse($linkedin_response)
	{
		$this->response = $linkedin_response;
		$basic          = array(
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
		$full           = array(
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
		$contact        = array(
			'phone-numbers'
		, 'main-address'
		, 'bound-account-types'
		, 'im-accounts'
		, 'twitter-accounts'
		, 'primary-twitter-account');

		$this->profile   = array();
		$this->profile['basic']     = array_combine(array_values($basic),array_values($basic));
		$this->profile['full']      = array_combine(array_values($full),array_values($full));
		$this->profile['contact']   = array_combine(array_values($contact),array_values($contact));

		print_r($this->profile);
		//foreach($this->profile->basic as $key=>$val){
		//$this->profile->basic[$key] = $this->response

		//}

	}


	/**
	 *
	 */
	public function jsonResume($linkedin_response,$skeleton)
	{
		/*		$cuserid = $this->userid;
				$res     = $liresponse;
				//$json->basics = new stdClass();
				///$cuser = CFactory::getUser($cuserid);
				$website = CRoute::_('index.php?option=com_community&view=profile&id=' . $cuserid);
				$json    = new stdClass();
				$basic   = (array) $res['basic'];
				$full    = (array) $res['full'];
				$contact = (array) $res['contact'];*/
		$skeleton       = json_decode($skeleton);
		$this->response = json_decode($linkedin_response);
		$basic          = array(
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
		$full           = array(
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
		$contact        = array(
			'phone-numbers'
		, 'main-address'
		, 'bound-account-types'
		, 'im-accounts'
		, 'twitter-accounts'
		, 'primary-twitter-account');

		$this->profile            = array();
		$this->profile['basic']   = array_fill_keys(array_values($basic), null);
		$this->profile['full']    = array_fill_keys(array_values($full), null);
		$this->profile['contact'] = array_fill_keys(array_values($contact), null);

//		reset($this->profile['basic']);

//		print_r($this->profile);
//		print_r($skeleton);

		/*

		basics
		work
		volunteer
		education
		awards
		publications
		skills
		languages
		interests
		references
		basics => stdClass Object
		(
			[name] =>
			[label] =>
			[picture] =>
			[email] =>
			[phone] =>
			[website] =>
			[summary] =>
			[location] => stdClass Object
				(
					[address] =>
					[postalCode] =>
					[city] =>
					[countryCode] =>
					[region] =>
				)

			[profiles] => Array
				(
					[0] => stdClass Object
						(
							[network] =>
							[username] =>
							[url] =>
						)))
		 */

		//foreach ($this->response as $rkey => $rval)
		//{


			foreach ($skeleton as $key => $val)
			{
//print_r($key);
//print_r(' => ');
				print_r($val);
				print_r('
			');

				switch ($key)
				{
					case 'basics':
						$b = $skeleton->basics;
						foreach ($val as $v)
						{

							$b->name    = $basic["formatted-name"];
							$b->label   = $basic["headline"];
							$b->picture = $basic["picture-url"];
							$b->email   = $basic["email-address"];

							$b->website = $basic[$website];
							$b->summary = $basic["summary"];

							$b->location              = new stdClass();
							$b->location->address     = "";
							$b->location->postalCode  = "";
							$b->location->city        = array_shift(explode(',', $basic->location->name));
							$b->location->countryCode = $basic->location->country->code;
							$b->location->region      = array_pop(explode(',', $basic->location->name));

							$p           = array();
							$p->network  = '';
							$p->username = '';
							$p->url      = '';

							$b->profiles = $p;
						}
						break;
					case 'work':
						for ($i = 0; $i < $basic['positions']['@attributes']['total']; $i++)
						{
							$work           = $basic['positions']['position'][$i];
							$json->work[$i] = array(
								"company"    => $work['company']['name'],
								"position"   => $work['title'],
								"website"    => $work['website'],
								"startDate"  => implode('-', $work['start-date']),
								"endDate"    => implode('-', $work['end-date']),
								"summary"    => $work['summary'],
								"highlights" => $work['highlights']
							);
						}
						break;
					case 'volunteer': //profiles
						for ($i = 0; $i < $full['volunteer']['volunteer-experiences']['@attributes']['total']; $i++)
						{
							$ve              = $full['volunteer']['volunteer-experience'][$i];
							$json->volunteer = array(
								"organization" => $ve['organization']['name'],
								"position"     => $ve['role'],
								"website"      => "",
								"startDate"    => "",
								"endDate"      => "",
								"summary"      => "",
								"highlights"   => "");
						}
						break;
					case 'education':
						$i = -1;
						foreach ($full['educations']['education'] as $edu)
						{
							$json->education[++$i] = array(
								"institution" => $edu['school-name'],
								"area"        => $edu['field-of-study'],
								"studyType"   => $edu['degree'],
								"startDate"   => array_shift(explode('-', $edu['start-date'])),
								"endDate"     => array_pop(explode('-', $edu['end-date'])),
								"gpa"         => '',
								"courses"     => array($full['courses']['course'])
							);
							$i++;
						}
						break;
					case 'awards':
						$i = -1;
						foreach ($full['honors-awards'] as $award)
						{
							$json->awards[++$i] = array(
								'title'   => $award->name,
								'date'    => '',
								'awarder' => '',
								'summary' => '',
							);
							$i++;
						}
						break;
					case 'publications':
						$i = -1;
						foreach ($full['publications']['publication'] as $pub)
						{
							$json->publications[$i] = array(
								"name"        => $pub->title,
								"publisher"   => $pub->publisher->name,
								"releaseDate" => $pub->date,
								"website"     => $pub->url,
								"summary"     => $pub->summary
							);
							$i++;
						}
						break;
					case 'skills':
						$i = -1;
						foreach ($full['skills']['skill'] as $skill)
						{
							$json->skills[$i] = array(
								"name"     => $skill->name,
								"level"    => "",
								"keywords" => array()
							);
						}
						break;
					case 'languages':
						$i = -1;
						foreach ($full['languages']['language'] as $lang)
						{
							$json->languages[$i] = array(
								"language" => $lang->language->name,
								"fluency"  => "");
						}
						break;
					case 'interests':
						foreach ($full['interests'] as $interest)
						{
							$json->interests = array(
								"name"     => $interest,
								"keywords" => array());
						}
						break;
					case 'references':
						$i = -1;
						foreach ($full['recommendations-received'] as $rec)
						{
							$json->interests = array(
								"name"      => $rec->name,
								"reference" => '');
						}
						break;
					default:
						break;

				}

			}

		}


}



$lires = file_get_contents('response.json');
$skeleton = file_get_contents('json_resume_schema.json');
$jsonresume = new JsonResumeSchema();
//$jsonresume->parseLinkedinResponse($lires);
$jsonresume->jsonResume($lires,$skeleton);