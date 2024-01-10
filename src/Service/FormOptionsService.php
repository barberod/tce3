<?php

namespace App\Service;

use App\Entity\Affiliation;
use App\Entity\Course;
use App\Entity\Department;
use App\Entity\Institution;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class FormOptionsService
{
		private EntityManagerInterface $entityManager;

		public function __construct(
			EntityManagerInterface $entityManager
		) {
				$this->entityManager = $entityManager;
		}

		public function getSubjectCodeOptions(): array {
				return array(
					'ACCT' => 'ACCT',
					'AE' => 'AE',
					'AS' => 'AS',
					'APPH' => 'APPH',
					'ASE' => 'ASE',
					'ARBC' => 'ARBC',
					'ARCH' => 'ARCH',
					'BIOS' => 'BIOS',
					'BIOL' => 'BIOL',
					'BMEJ' => 'BMEJ',
					'BMED' => 'BMED',
					'BMEM' => 'BMEM',
					'BC' => 'BC',
					'BCP' => 'BCP',
					'CETL' => 'CETL',
					'CHBE' => 'CHBE',
					'CHEM' => 'CHEM',
					'CHIN' => 'CHIN',
					'CP' => 'CP',
					'CEE' => 'CEE',
					'COA' => 'COA',
					'COE' => 'COE',
					'CX' => 'CX',
					'CSE' => 'CSE',
					'CS' => 'CS',
					'COOP' => 'COOP',
					'DOPP' => 'DOPP',
					'EAS' => 'EAS',
					'ECE' => 'ECE',
					'ECON' => 'ECON',
					'EDUC' => 'EDUC',
					'EENG' => 'EENG',
					'EME' => 'EME',
					'ENG' => 'ENG',
					'ENVE' => 'ENVE',
					'ENVS' => 'ENVS',
					'FS' => 'FS',
					'FIL' => 'FIL',
					'FREN' => 'FREN',
					'GEOG' => 'GEOG',
					'HIST' => 'HIST',
					'HTS' => 'HTS',
					'HUM' => 'HUM',
					'ID' => 'ID',
					'ISYE' => 'ISYE',
					'INTA' => 'INTA',
					'IL' => 'IL',
					'INTN' => 'INTN',
					'IMBA' => 'IMBA',
					'IAC' => 'IAC',
					'JAPN' => 'JAPN',
					'KOR' => 'KOR',
					'LATN' => 'LATN',
					'LS' => 'LS',
					'LING' => 'LING',
					'LMC' => 'LMC',
					'MGT' => 'MGT',
					'MOT' => 'MOT',
					'MLDR' => 'MLDR',
					'MSE' => 'MSE',
					'MATH' => 'MATH',
					'ME' => 'ME',
					'MP' => 'MP',
					'MSL' => 'MSL',
					'ML' => 'ML',
					'MUSI' => 'MUSI',
					'NS' => 'NS',
					'NEUR' => 'NEUR',
					'NRE' => 'NRE',
					'PERS' => 'PERS',
					'PHIL' => 'PHIL',
					'PHYS' => 'PHYS',
					'POL' => 'POL',
					'PTFE' => 'PTFE',
					'PSYC' => 'PSYC',
					'PUBJ' => 'PUBJ',
					'PUBP' => 'PUBP',
					'RUSS' => 'RUSS',
					'SCI' => 'SCI',
					'SLS' => 'SLS',
					'SS' => 'SS',
					'SOC' => 'SOC',
					'SPAN' => 'SPAN',
					'SWAH' => 'SWAH',
					'UCGA' => 'UCGA',
					'VIP' => 'VIP',
					'WOLO' => 'WOLO',
				);
		}

		public function getCoursesBySubjectCode(string $subjectCode): array {
				$courses = $this->entityManager->getRepository(Course::class)->findBy(['subjectCode' => $subjectCode]);
				$selectors = [];
				foreach ($courses as $course) {
						$selectors[$course->getSubjectCode().' '.$course->getCourseNumber()] =
							$course->getId();
				}
				return $selectors;
		}

		public function getDepartmentOptions(): array {
				$departments = $this->entityManager
					->getRepository(Department::class)
					->createQueryBuilder('d')
					->where('d.status = 1')
					->orderBy('d.name', 'ASC')
					->getQuery()
					->getResult();

				$selectors = [];
				foreach ($departments as $department) {
						$selectors[$department->getName()] = $department->getId();
				}
				return $selectors;
		}

		function getAssigneesByDepartment(int $deptID): array {
				$department = $this->entityManager->getRepository(Department::class)->findOneBy(['id' => $deptID]);
				$affiliations = $this->entityManager->getRepository(Affiliation::class)->findBy(['department' => $department]);
				$assignees = [];
				foreach ($affiliations as $affiliation) {
						$assignees[] = $affiliation->getFacstaff();
				}
				$selectors = [];
				foreach ($assignees as $assignee) {
						$selectors[$assignee->getDisplayName()] = $assignee->getUsername();
				}
				return $selectors;
		}

		function getCreditHourOptions(): array {
				return array(
					'0' => '0',
					'0.25' => '0.25',
					'0.5' => '0.5',
					'0.75' => '0.75',
					'1' => '1',
					'1.25' => '1.25',
					'1.5' => '1.5',
					'1.75' => '1.75',
					'2' => '2',
					'2.25' => '2.25',
					'2.5' => '2.5',
					'2.75' => '2.75',
					'3' => '3',
					'3.25' => '3.25',
					'3.5' => '3.5',
					'3.75' => '3.75',
					'4' => '4',
					'4.25' => '4.25',
					'4.5' => '4.5',
					'4.75' => '4.75',
					'5' => '5',
					'5.25' => '5.25',
					'5.5' => '5.5',
					'5.75' => '5.75',
					'6' => '6',
					'6.25' => '6.25',
					'6.5' => '6.5',
					'6.75' => '6.75',
					'7' => '7',
					'7.5' => '7.5',
					'8' => '8',
					'8.5' => '8.5',
					'9' => '9',
					'9.5' => '9.5',
					'10' => '10',
					'10.5' => '10.5',
					'11' => '11',
					'11.5' => '11.5',
					'12' => '12',
					'12.5' => '12.5',
					'13' => '13',
					'13.5' => '13.5',
					'14' => '14',
					'14.5' => '14.5',
					'15' => '15',
					'15.5' => '15.5',
					'16' => '16',
					'16.5' => '16.5',
					'17' => '17',
					'17.5' => '17.5',
					'18' => '18',
					'18.5' => '18.5',
					'19' => '19',
					'19.5' => '19.5',
					'20' => '20',
					'20.5' => '20.5',
					'21' => '21',
					'21.5' => '21.5',
					'22' => '22',
					'22.5' => '22.5',
					'23' => '23',
					'23.5' => '23.5',
					'24' => '24',
					'24.5' => '24.5',
					'25' => '25',
					'25.5' => '25.5',
					'26' => '26',
					'26.5' => '26.5',
					'27' => '27',
					'27.5' => '27.5',
					'28' => '28',
					'28.5' => '28.5',
					'29' => '29',
					'29.5' => '29.5',
					'30' => '30',
					'30.5' => '30.5',
					'31' => '31',
					'31.5' => '31.5',
					'32' => '32',
					'32.5' => '32.5',
					'33' => '33',
					'33.5' => '33.5',
					'34' => '34',
					'34.5' => '34.5',
					'35' => '35',
					'35.5' => '35.5',
					'36' => '36',
					'36.5' => '36.5',
					'37' => '37',
					'37.5' => '37.5',
					'38' => '38',
					'38.5' => '38.5',
					'39' => '39',
					'39.5' => '39.5',
					'40' => '40',
					'40.5' => '40.5',
					'41' => '41',
					'41.5' => '41.5',
					'42' => '42',
					'42.5' => '42.5',
					'43' => '43',
					'43.5' => '43.5',
					'44' => '44',
					'44.5' => '44.5',
					'45' => '45',
					'45.5' => '45.5',
					'46' => '46',
					'46.5' => '46.5',
					'47' => '47',
					'47.5' => '47.5',
					'48' => '48',
					'48.5' => '48.5',
					'49' => '49',
					'49.5' => '49.5',
					'50' => '50',
					'50.5' => '50.5',
					'51' => '51',
					'51.5' => '51.5',
					'52' => '52',
					'52.5' => '52.5',
					'53' => '53',
					'53.5' => '53.5',
					'54' => '54',
					'54.5' => '54.5',
					'55' => '55',
					'55.5' => '55.5',
					'56' => '56',
					'56.5' => '56.5',
					'57' => '57',
					'57.5' => '57.5',
					'58' => '58',
					'58.5' => '58.5',
					'59' => '59',
					'59.5' => '59.5',
					'60' => '60',
					'60.5' => '60.5',
					'61' => '61',
					'61.5' => '61.5',
					'62' => '62',
					'62.5' => '62.5',
					'63' => '63',
					'63.5' => '63.5',
					'64' => '64',
					'64.5' => '64.5',
					'65' => '65',
					'65.5' => '65.5',
					'66' => '66',
					'66.5' => '66.5',
					'67' => '67',
					'67.5' => '67.5',
					'68' => '68',
					'68.5' => '68.5',
					'69' => '69',
					'69.5' => '69.5',
					'70' => '70',
					'70.5' => '70.5',
					'71' => '71',
					'71.5' => '71.5',
					'72' => '72',
					'72.5' => '72.5',
					'73' => '73',
					'73.5' => '73.5',
					'74' => '74',
					'74.5' => '74.5',
					'75' => '75',
					'75.5' => '75.5',
					'76' => '76',
					'76.5' => '76.5',
					'77' => '77',
					'77.5' => '77.5',
					'78' => '78',
					'78.5' => '78.5',
					'79' => '79',
					'79.5' => '79.5',
					'80' => '80',
					'81' => '81',
					'82' => '82',
					'83' => '83',
					'84' => '84',
					'85' => '85',
					'86' => '86',
					'87' => '87',
					'88' => '88',
					'89' => '89',
					'90' => '90',
					'91' => '91',
					'92' => '92',
					'93' => '93',
					'94' => '94',
					'95' => '95',
					'96' => '96',
					'97' => '97',
					'98' => '98',
					'99' => '99',
					'100' => '100',
					'101' => '101',
					'102' => '102',
					'103' => '103',
					'104' => '104',
					'105' => '105',
					'106' => '106',
					'107' => '107',
					'108' => '108',
					'109' => '109',
					'110' => '110',
					'111' => '111',
					'112' => '112',
					'113' => '113',
					'114' => '114',
					'115' => '115',
					'116' => '116',
					'117' => '117',
					'118' => '118',
					'119' => '119',
					'120' => '120',
					'121' => '121',
					'122' => '122',
					'123' => '123',
					'124' => '124',
					'125' => '125',
				);
		}

		function getUsStateOptions(): array {
				return array(
					'Alabama' => 'AL',
					'Alaska' => 'AK',
					'Arizona' => 'AZ',
					'Arkansas' => 'AR',
					'California' => 'CA',
					'Colorado' => 'CO',
					'Connecticut' => 'CT',
					'Delaware' => 'DE',
					'District of Columbia' => 'DC',
					'Florida' => 'FL',
					'Georgia' => 'GA',
					'Guam' => 'GU',
					'Hawaii' => 'HI',
					'Idaho' => 'ID',
					'Illinois' => 'IL',
					'Indiana' => 'IN',
					'Iowa' => 'IA',
					'Kansas' => 'KS',
					'Kentucky' => 'KY',
					'Louisiana' => 'LA',
					'Maine' => 'ME',
					'Maryland' => 'MD',
					'Massachusetts' => 'MA',
					'Michigan' => 'MI',
					'Minnesota' => 'MN',
					'Mississippi' => 'MS',
					'Missouri' => 'MO',
					'Montana' => 'MT',
					'Nebraska' => 'NE',
					'Nevada' => 'NV',
					'New Hampshire' => 'NH',
					'New Jersey' => 'NJ',
					'New Mexico' => 'NM',
					'New York' => 'NY',
					'North Carolina' => 'NC',
					'North Dakota' => 'ND',
					'Ohio' => 'OH',
					'Oklahoma' => 'OK',
					'Oregon' => 'OR',
					'Pennsylvania' => 'PA',
					'Puerto Rico' => 'PR',
					'Rhode Island' => 'RI',
					'South Carolina' => 'SC',
					'South Dakota' => 'SD',
					'Tennessee' => 'TN',
					'Texas' => 'TX',
					'Utah' => 'UT',
					'Vermont' => 'VT',
					'Virginia' => 'VA',
					'Virgin Islands' => 'VI',
					'Washington' => 'WA',
					'West Virginia' => 'WV',
					'Wisconsin' => 'WI',
					'Wyoming' => 'WY',
				);
		}

		function getCountryOptions(): array {
				$selectors = [];
				$selectors['Afghanistan'] = 'Afghanistan';
				$selectors['Albania'] = 'Albania';
				$selectors['Algeria'] = 'Algeria';
				$selectors['Andorra'] = 'Andorra';
				$selectors['Angola'] = 'Angola';
				$selectors['Antigua and Barbuda'] = 'Antigua and Barbuda';
				$selectors['Argentina'] = 'Argentina';
				$selectors['Armenia'] = 'Armenia';
				$selectors['Australia'] = 'Australia';
				$selectors['Austria'] = 'Austria';
				$selectors['Azerbaijan'] = 'Azerbaijan';
				$selectors['Bahamas'] = 'Bahamas';
				$selectors['Bahrain'] = 'Bahrain';
				$selectors['Bangladesh'] = 'Bangladesh';
				$selectors['Barbados'] = 'Barbados';
				$selectors['Belarus'] = 'Belarus';
				$selectors['Belgium'] = 'Belgium';
				$selectors['Belize'] = 'Belize';
				$selectors['Benin'] = 'Benin';
				$selectors['Bhutan'] = 'Bhutan';
				$selectors['Bolivia'] = 'Bolivia';
				$selectors['Bosnia and Herzegovina'] = 'Bosnia and Herzegovina';
				$selectors['Botswana'] = 'Botswana';
				$selectors['Brazil'] = 'Brazil';
				$selectors['Brunei'] = 'Brunei';
				$selectors['Bulgaria'] = 'Bulgaria';
				$selectors['Burkina Faso'] = 'Burkina Faso';
				$selectors['Burundi'] = 'Burundi';
				$selectors['Cabo Verde'] = 'Cabo Verde';
				$selectors['Cambodia'] = 'Cambodia';
				$selectors['Cameroon'] = 'Cameroon';
				$selectors['Canada'] = 'Canada';
				$selectors['Central African Republic'] = 'Central African Republic';
				$selectors['Chad'] = 'Chad';
				$selectors['Chile'] = 'Chile';
				$selectors['China'] = 'China';
				$selectors['Colombia'] = 'Colombia';
				$selectors['Comoros'] = 'Comoros';
				$selectors['Congo, Democratic Republic of the'] = 'Congo, Democratic Republic of the';
				$selectors['Congo, Republic of the'] = 'Congo, Republic of the';
				$selectors['Costa Rica'] = 'Costa Rica';
				$selectors['Côte d’Ivoire'] = 'Côte d’Ivoire';
				$selectors['Croatia'] = 'Croatia';
				$selectors['Cuba'] = 'Cuba';
				$selectors['Cyprus'] = 'Cyprus';
				$selectors['Czech Republic'] = 'Czech Republic';
				$selectors['Denmark'] = 'Denmark';
				$selectors['Djibouti'] = 'Djibouti';
				$selectors['Dominica'] = 'Dominica';
				$selectors['Dominican Republic'] = 'Dominican Republic';
				$selectors['East Timor (Timor-Leste)'] = 'East Timor (Timor-Leste)';
				$selectors['Ecuador'] = 'Ecuador';
				$selectors['Egypt'] = 'Egypt';
				$selectors['El Salvador'] = 'El Salvador';
				$selectors['Equatorial Guinea'] = 'Equatorial Guinea';
				$selectors['Eritrea'] = 'Eritrea';
				$selectors['Estonia'] = 'Estonia';
				$selectors['Ethiopia'] = 'Ethiopia';
				$selectors['Fiji'] = 'Fiji';
				$selectors['Finland'] = 'Finland';
				$selectors['France'] = 'France';
				$selectors['Gabon'] = 'Gabon';
				$selectors['Gambia'] = 'Gambia';
				$selectors['Georgia'] = 'Georgia';
				$selectors['Germany'] = 'Germany';
				$selectors['Ghana'] = 'Ghana';
				$selectors['Greece'] = 'Greece';
				$selectors['Grenada'] = 'Grenada';
				$selectors['Guatemala'] = 'Guatemala';
				$selectors['Guinea'] = 'Guinea';
				$selectors['Guinea-Bissau'] = 'Guinea-Bissau';
				$selectors['Guyana'] = 'Guyana';
				$selectors['Haiti'] = 'Haiti';
				$selectors['Honduras'] = 'Honduras';
				$selectors['Hungary'] = 'Hungary';
				$selectors['Iceland'] = 'Iceland';
				$selectors['India'] = 'India';
				$selectors['Indonesia'] = 'Indonesia';
				$selectors['Iran'] = 'Iran';
				$selectors['Iraq'] = 'Iraq';
				$selectors['Ireland'] = 'Ireland';
				$selectors['Israel'] = 'Israel';
				$selectors['Italy'] = 'Italy';
				$selectors['Jamaica'] = 'Jamaica';
				$selectors['Japan'] = 'Japan';
				$selectors['Jordan'] = 'Jordan';
				$selectors['Kazakhstan'] = 'Kazakhstan';
				$selectors['Kenya'] = 'Kenya';
				$selectors['Kiribati'] = 'Kiribati';
				$selectors['Korea, North'] = 'Korea, North';
				$selectors['Korea, South'] = 'Korea, South';
				$selectors['Kosovo'] = 'Kosovo';
				$selectors['Kuwait'] = 'Kuwait';
				$selectors['Kyrgyzstan'] = 'Kyrgyzstan';
				$selectors['Laos'] = 'Laos';
				$selectors['Latvia'] = 'Latvia';
				$selectors['Lebanon'] = 'Lebanon';
				$selectors['Lesotho'] = 'Lesotho';
				$selectors['Liberia'] = 'Liberia';
				$selectors['Libya'] = 'Libya';
				$selectors['Liechtenstein'] = 'Liechtenstein';
				$selectors['Lithuania'] = 'Lithuania';
				$selectors['Luxembourg'] = 'Luxembourg';
				$selectors['Macedonia'] = 'Macedonia';
				$selectors['Madagascar'] = 'Madagascar';
				$selectors['Malawi'] = 'Malawi';
				$selectors['Malaysia'] = 'Malaysia';
				$selectors['Maldives'] = 'Maldives';
				$selectors['Mali'] = 'Mali';
				$selectors['Malta'] = 'Malta';
				$selectors['Marshall Islands'] = 'Marshall Islands';
				$selectors['Mauritania'] = 'Mauritania';
				$selectors['Mauritius'] = 'Mauritius';
				$selectors['Mexico'] = 'Mexico';
				$selectors['Micronesia'] = 'Micronesia';
				$selectors['Moldova'] = 'Moldova';
				$selectors['Monaco'] = 'Monaco';
				$selectors['Mongolia'] = 'Mongolia';
				$selectors['Montenegro'] = 'Montenegro';
				$selectors['Morocco'] = 'Morocco';
				$selectors['Mozambique'] = 'Mozambique';
				$selectors['Myanmar (Burma)'] = 'Myanmar (Burma)';
				$selectors['Namibia'] = 'Namibia';
				$selectors['Nauru'] = 'Nauru';
				$selectors['Nepal'] = 'Nepal';
				$selectors['Netherlands'] = 'Netherlands';
				$selectors['New Zealand'] = 'New Zealand';
				$selectors['Nicaragua'] = 'Nicaragua';
				$selectors['Niger'] = 'Niger';
				$selectors['Nigeria'] = 'Nigeria';
				$selectors['North Korea'] = 'North Korea';
				$selectors['Norway'] = 'Norway';
				$selectors['Oman'] = 'Oman';
				$selectors['Pakistan'] = 'Pakistan';
				$selectors['Palau'] = 'Palau';
				$selectors['Panama'] = 'Panama';
				$selectors['Papua New Guinea'] = 'Papua New Guinea';
				$selectors['Paraguay'] = 'Paraguay';
				$selectors['Peru'] = 'Peru';
				$selectors['Philippines'] = 'Philippines';
				$selectors['Poland'] = 'Poland';
				$selectors['Portugal'] = 'Portugal';
				$selectors['Qatar'] = 'Qatar';
				$selectors['Romania'] = 'Romania';
				$selectors['Russia'] = 'Russia';
				$selectors['Rwanda'] = 'Rwanda';
				$selectors['Saint Kitts and Nevis'] = 'Saint Kitts and Nevis';
				$selectors['Saint Lucia'] = 'Saint Lucia';
				$selectors['Saint Vincent and the Grenadines'] = 'Saint Vincent and the Grenadines';
				$selectors['Samoa'] = 'Samoa';
				$selectors['San Marino'] = 'San Marino';
				$selectors['Sao Tome and Principe'] = 'Sao Tome and Principe';
				$selectors['Saudi Arabia'] = 'Saudi Arabia';
				$selectors['Senegal'] = 'Senegal';
				$selectors['Serbia'] = 'Serbia';
				$selectors['Seychelles'] = 'Seychelles';
				$selectors['Sierra Leone'] = 'Sierra Leone';
				$selectors['Singapore'] = 'Singapore';
				$selectors['Slovakia'] = 'Slovakia';
				$selectors['Slovenia'] = 'Slovenia';
				$selectors['Solomon Islands'] = 'Solomon Islands';
				$selectors['Somalia'] = 'Somalia';
				$selectors['South Africa'] = 'South Africa';
				$selectors['South Korea'] = 'South Korea';
				$selectors['South Sudan'] = 'South Sudan';
				$selectors['Spain'] = 'Spain';
				$selectors['Sri Lanka'] = 'Sri Lanka';
				$selectors['Sudan'] = 'Sudan';
				$selectors['Suriname'] = 'Suriname';
				$selectors['Swaziland'] = 'Swaziland';
				$selectors['Sweden'] = 'Sweden';
				$selectors['Switzerland'] = 'Switzerland';
				$selectors['Syria'] = 'Syria';
				$selectors['Taiwan'] = 'Taiwan';
				$selectors['Tajikistan'] = 'Tajikistan';
				$selectors['Tanzania'] = 'Tanzania';
				$selectors['Thailand'] = 'Thailand';
				$selectors['Togo'] = 'Togo';
				$selectors['Tonga'] = 'Tonga';
				$selectors['Trinidad and Tobago'] = 'Trinidad and Tobago';
				$selectors['Tunisia'] = 'Tunisia';
				$selectors['Turkey'] = 'Turkey';
				$selectors['Turkmenistan'] = 'Turkmenistan';
				$selectors['Tuvalu'] = 'Tuvalu';
				$selectors['Uganda'] = 'Uganda';
				$selectors['Ukraine'] = 'Ukraine';
				$selectors['United Arab Emirates'] = 'United Arab Emirates';
				$selectors['United Kingdom'] = 'United Kingdom';
				$selectors['United States'] = 'United States';
				$selectors['Uruguay'] = 'Uruguay';
				$selectors['Uzbekistan'] = 'Uzbekistan';
				$selectors['Vanuatu'] = 'Vanuatu';
				$selectors['Vatican City'] = 'Vatican City';
				$selectors['Venezuela'] = 'Venezuela';
				$selectors['Vietnam'] = 'Vietnam';
				$selectors['Yemen'] = 'Yemen';
				$selectors['Zambia'] = 'Zambia';
				$selectors['Zimbabwe'] = 'Zimbabwe';
				return $selectors;
		}

		function getInstitutionsByUSState(string $usState): array {
				$institutions = $this->entityManager->getRepository(Institution::class)
					->findBy(['state' => $usState]);
				$selectors = [];
				foreach ($institutions as $institution) {
						$selectors[$institution->getName()] = $institution->getId();
				}
				return $selectors;
		}
}