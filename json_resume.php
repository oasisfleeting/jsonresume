<?php
/**
 * Created by oasisfleeting
 * User: oasisfleeting
 * Date: 1/2/2016
 * Time: 4:52 AM
 */

class JSonResume
{
	private $hold;
	private $proarray;
	private $linkedin_response;
	private $skeleton;
	private $res;

	public function __constructor($liResponse, $debug = false)
	{
	}

	public function debugMapper()
	{
		$this->linkedin_response = file_get_contents('response.json');
		$this->skeleton          = file_get_contents('json_resume_schema.json');
		$this->skeleton          = json_decode($this->skeleton);
		$this->res               = json_decode($this->linkedin_response, true);
		$this->skeleton->basics  = $this->mapBasics($this->res);
	}

	/**
	 * @param          $res
	 * @param stdClass $skeleton
	 * @param array    $proarray
	 *
	 * @return array
	 */
	public function mapBasics($res, stdClass $skeleton = null, $proarray = array())
	{
		$hold             = new stdClass();
		$hold->name       = $res["formatted-name"];
		$hold->label      = $res["headline"];
		$hold->picture    = $res["picture-url"];
		$hold->email      = $res["email-address"];
		$hold->phone      = $res['phone-numbers']['phone-number']['phone-number'];
		$hold->summary    = $res["summary"];
		$skeleton->basics = $hold;
		//$b->website  = CRoute::_("index.php?option=com_community&view=profile&userid=".JFactory::getUser()->id);

		$hold                       = new stdClass();
		$hold->address              = $res["main-address"];
		$hold->postalCode           = "";
		$hold->city                 = array_shift(explode(',', $res['location']['name']));
		$hold->countryCode          = $res['location']['country']['code'];
		$hold->region               = array_pop(explode(',', str_replace('Area', '', $res['location']['name'])));
		$skeleton->basics->location = $hold;

		foreach ($res['bound-account-types']['bound-account-type'] as $resval)
		{
			$hold           = new stdClass();
			$resval         = $resval['bound-accounts']['bound-account'];
			$hold->network  = $resval['account-type'];
			$hold->username = $resval['provider-account-name'];
			$hold->url      = '{domain}/' . $resval['provider-account-id'];
			$proarray[]     = $hold;
		}
		$skeleton->basics->profiles = $proarray;

		return $skeleton->basics;
	}

	/**
	 * @param               $res
	 * @param stdClass|null $skeleton
	 * @param array         $proarray
	 *
	 * @return array
	 */
	public function mapWork($res, stdClass $skeleton = null, $proarray = array())
	{
		foreach ($res['positions']['position'] as $prokey => $resval)
		{
			$hold             = new stdClass();
			$hold->company    = $resval['company']['name'];
			$hold->position   = $resval['title'];
			$hold->website    = '';
			$hold->startDate  = (isset($resval['start-date']) ? implode('-', array($resval['start-date']['month'], $resval['start-date']['year'])) : '');
			$hold->endDate    = (isset($resval['end-date']) ? implode('-', array($resval['end-date']['month'], $resval['end-date']['year'])) : '');
			$hold->summary    = $resval['summary'];
			$hold->highlights = array('highlights');
			$proarray[]       = $hold;
		}
		$skeleton->work = $proarray;

		return $skeleton->work;
	}

	/**
	 * @param               $res
	 * @param stdClass|null $skeleton
	 * @param array         $proarray
	 *
	 * @return array
	 */
	public function mapVolunteer($res, stdClass $skeleton = null, $proarray = array())
	{
		foreach ($res['volunteer'] as $vekey => $resval)
		{
			if (isset($resval['volunteer-experience']))
			{
				$hold               = new stdClass();
				$resval             = $resval['volunteer-experience'];
				$hold->organization = $resval['organization']['name'];
				$hold->position     = $resval['role'];
				$hold->website      = '';
				$hold->startDate    = '';
				$hold->endDate      = '';
				$hold->summary      = '';
				$hold->highlights   = array();
				$proarray[]         = $hold;
			}

		}
		$skeleton->volunteer = $proarray;

		return $skeleton->volunteer;
	}

	/**
	 * @param               $res
	 * @param stdClass|null $skeleton
	 * @param array         $proarray
	 *
	 * @return array
	 */
	public function mapEducation($res, stdClass $skeleton = null, $proarray = array())
	{
		foreach ($res['educations'] as $edukey => $resval)
		{
			if ($edukey === 'education')
			{
				$hold              = new stdClass();
				$hold->institution = $resval['school-name'];
				$hold->area        = $resval['field-of-study'];
				$hold->studyType   = $resval['degree'];
				$hold->startDate   = implode('-', array_values($resval['start-date']));
				$hold->endDate     = implode('-', array_values($resval['end-date']));
				$hold->gpa         = '';
				$hold->courses     = array_map(function ($coursearray)
				{
					$ret = array();
					foreach ($coursearray as $courkey => $courval)
					{
						if ($courkey === 'id')
						{
							unset($coursearray[$courkey]);
						}
						else
						{
							$ret = $coursearray[$courkey];
						}
					}

					return $ret;
				}, $res['courses']['course']);
				$proarray[]        = $hold;
			}
		}
		$skeleton->education = $proarray;

		return $skeleton->education;
	}

	/**
	 * @param               $res
	 * @param stdClass|null $skeleton
	 * @param array         $proarray
	 *
	 * @return array
	 */
	public function mapAwards($res, stdClass $skeleton = null, $proarray = array())
	{
		foreach ($res['honors-awards'] as $awkey => $resval)
		{
			if ($awkey === 'honor-award')
			{
				$hold          = new stdClass();
				$hold->title   = $resval['name'];
				$hold->date    = '';
				$hold->awarder = '';
				$hold->summary = '';
				$proarray[]    = $hold;
			}
		}
		$skeleton->awards = $proarray;

		return $skeleton->awards;
	}

	/**
	 * @param               $res
	 * @param stdClass|null $skeleton
	 * @param array         $proarray
	 *
	 * @return array
	 */
	public function mapPublications($res, stdClass $skeleton = null, $proarray = array())
	{
		if (isset($res['publications']))
		{
			foreach ($res['publications'] as $pubkey => $resval)
			{
				if ($pubkey === 'publication')
				{
					$hold              = new stdClass();
					$hold->name        = $resval['title'];
					$hold->publisher   = $resval['publisher']['name'];
					$hold->releaseDate = $resval['date'];
					$hold->website     = $resval['url'];
					$hold->summary     = $resval['summary'];
					$proarray[]        = $hold;
				}
			}
		}
		$skeleton->publications = $proarray;

		return $skeleton->publications;
	}

	/**
	 * @param               $res
	 * @param stdClass|null $skeleton
	 * @param array         $proarray
	 *
	 * @return array
	 */
	public function mapSkills($res, stdClass $skeleton = null, $proarray = array())
	{
		foreach ($res['skills']['skill'] as $skey => $sval)
		{
			$hold           = new stdClass();
			$hold->name     = $sval['skill']['name'];
			$hold->level    = '';
			$hold->keywords = array('');
			$proarray[]     = $hold;
		}
		$skeleton->skills = $proarray;

		return $skeleton->skills;
	}

	/**
	 * @param               $res
	 * @param stdClass|null $skeleton
	 * @param array         $proarray
	 *
	 * @return array
	 */
	public function mapLanguages($res, stdClass $skeleton = null, $proarray = array())
	{
		foreach ($res['languages']['language'] as $lang)
		{
			$hold           = new stdClass();
			$hold->language = $lang['language']['name'];
			$hold->fluency  = "";
			$proarray[]     = $hold;
		}
		$skeleton->languages = $proarray;

		return $skeleton->languages;
	}

	/**
	 * @param               $res
	 * @param stdClass|null $skeleton
	 * @param array         $proarray
	 *
	 * @return array
	 */
	public function mapInterests($res, stdClass $skeleton = null, $proarray = array())
	{
		/****NEEDS FURTHER EXPLORATION****/
		$hold     = new stdClass();
		$proarray = array();
		foreach ($res['interests'] as $resval)
		{
			$hold           = new stdClass();
			$hold->name     = $resval;
			$hold->keywords = array();
			$proarray[]     = $hold;
		}
		$skeleton->interests = $proarray;

		return $skeleton->interests;

	}

	/**
	 * @param               $res
	 * @param stdClass|null $skeleton
	 * @param array         $proarray
	 *
	 * @return array
	 */
	public function mapReference($res, stdClass $skeleton = null, $proarray = array())
	{
		foreach ($res['recommendations-received'] as $resval)
		{
			$hold            = new stdClass();
			$hold->name      = $resval['name'];
			$hold->reference = '';
			$proarray[]      = $hold;
		}
		$skeleton->references = $proarray;

		return $skeleton->references;
	}

}



/*
$linkedin_response = file_get_contents('response.json');
$skeleton          = file_get_contents('json_resume_schema.json');
$skeleton          = json_decode($skeleton);
$res               = json_decode($linkedin_response, true);

foreach ($skeleton as $key => $val)
{
	$hold     = new stdClass();
	$proarray = array();
	switch ($key)
	{
		case 'basics':
			$hold          = new stdClass();
			$hold->name    = $res["formatted-name"];
			$hold->label   = $res["headline"];
			$hold->picture = $res["picture-url"];
			$hold->email   = $res["email-address"];
			$hold->phone   = $res['phone-numbers']['phone-number']['phone-number'];
			//$b->website  = CRoute::_("index.php?option=com_community&view=profile&userid=".JFactory::getUser()->id);
			$hold->summary    = $res["summary"];
			$skeleton->basics = $hold;

			$hold                       = new stdClass();
			$hold->address              = $res["main-address"];
			$hold->postalCode           = "";
			$hold->city                 = array_shift(explode(',', $res['location']['name']));
			$hold->countryCode          = $res['location']['country']['code'];
			$hold->region               = array_pop(explode(',', str_replace('Area', '', $res['location']['name'])));
			$skeleton->basics->location = $hold;

			foreach ($res['bound-account-types']['bound-account-type'] as $resval)
			{
				$hold           = new stdClass();
				$resval         = $resval['bound-accounts']['bound-account'];
				$hold->network  = $resval['account-type'];
				$hold->username = $resval['provider-account-name'];
				$hold->url      = '{domain}/' . $resval['provider-account-id'];
				$proarray[]     = $hold;
			}
			$skeleton->basics->profiles = $proarray;
			break;
		case 'work':
			foreach ($res['positions']['position'] as $prokey => $resval)
			{
				$hold             = new stdClass();
				$hold->company    = $resval['company']['name'];
				$hold->position   = $resval['title'];
				$hold->website    = '';
				$hold->startDate  = (isset($resval['start-date']) ? implode('-', array($resval['start-date']['month'], $resval['start-date']['year'])) : '');
				$hold->endDate    = (isset($resval['end-date']) ? implode('-', array($resval['end-date']['month'], $resval['end-date']['year'])) : '');
				$hold->summary    = $resval['summary'];
				$hold->highlights = array('highlights');
				$proarray[]       = $hold;
			}
			$skeleton->work = $proarray;
			break;
		case 'volunteer': //profiles
			foreach ($res['volunteer'] as $vekey => $resval)
			{
				if (isset($resval['volunteer-experience']))
				{
					$hold               = new stdClass();
					$resval             = $resval['volunteer-experience'];
					$hold->organization = $resval['organization']['name'];
					$hold->position     = $resval['role'];
					$hold->website      = '';
					$hold->startDate    = '';
					$hold->endDate      = '';
					$hold->summary      = '';
					$hold->highlights   = array();
					$proarray[]         = $hold;
				}

			}
			$skeleton->volunteer = $proarray;
			break;
		case 'education':
			foreach ($res['educations'] as $edukey => $resval)
			{
				if ($edukey === 'education')
				{
					$hold              = new stdClass();
					$hold->institution = $resval['school-name'];
					$hold->area        = $resval['field-of-study'];
					$hold->studyType   = $resval['degree'];
					$hold->startDate   = implode('-', array_values($resval['start-date']));
					$hold->endDate     = implode('-', array_values($resval['end-date']));
					$hold->gpa         = '';
					$hold->courses     = array_map(function ($coursearray)
					{
						$ret = array();
						foreach ($coursearray as $courkey => $courval)
						{
							if ($courkey === 'id')
							{
								unset($coursearray[$courkey]);
							}
							else
							{
								$ret = $coursearray[$courkey];
							}
						}

						return $ret;
					}, $res['courses']['course']);
					$proarray[]        = $hold;
				}
			}
			$skeleton->education = $proarray;
			break;
		case 'awards':
			foreach ($res['honors-awards'] as $awkey => $resval)
			{
				if ($awkey === 'honor-award')
				{
					$hold          = new stdClass();
					$hold->title   = $resval['name'];
					$hold->date    = '';
					$hold->awarder = '';
					$hold->summary = '';
					$proarray[]    = $hold;
				}
			}
			$skeleton->awards = $proarray;
			break;
		case 'publications':
			if (isset($res['publications']))
			{
				foreach ($res['publications'] as $pubkey => $resval)
				{
					if ($pubkey === 'publication')
					{
						$hold              = new stdClass();
						$hold->name        = $resval['title'];
						$hold->publisher   = $resval['publisher']['name'];
						$hold->releaseDate = $resval['date'];
						$hold->website     = $resval['url'];
						$hold->summary     = $resval['summary'];
						$proarray[]        = $hold;
					}
				}
			}
			$skeleton->publications = $proarray;
			break;
		case 'skills':
			foreach ($res['skills']['skill'] as $skey => $sval)
			{
				$hold           = new stdClass();
				$hold->name     = $sval['skill']['name'];
				$hold->level    = '';
				$hold->keywords = array('');
				$proarray[]     = $hold;
			}
			$skeleton->skills = $proarray;
			break;
		case 'languages':
			foreach ($res['languages']['language'] as $lang)
			{
				$hold           = new stdClass();
				$hold->language = $lang['language']['name'];
				$hold->fluency  = "";
				$proarray[]     = $hold;
			}
			$skeleton->languages = $proarray;
			break;

		case 'interests':
			///****NEEDS FURTHER EXPLORATION***//*/
			$hold     = new stdClass();
			$proarray = array();
			foreach ($res['interests'] as $resval)
			{
				$hold           = new stdClass();
				$hold->name     = $resval;
				$hold->keywords = array();
				$proarray[]     = $hold;
			}
			$skeleton->interests = (count($proarray) >= 1) ? $proarray : $skeleton->interests;
			break;
		///***************END***************//*/
		case 'references':
			foreach ($res['recommendations-received'] as $resval)
			{
				$hold            = new stdClass();
				$hold->name      = $resval['name'];
				$hold->reference = '';
				$proarray[]      = $hold;
			}
			$skeleton->references = $proarray;
			break;
	}
}
*/