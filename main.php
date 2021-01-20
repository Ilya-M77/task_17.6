<?php

$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

// 1.1 -----------------------------------------------------
function getFullnameFromParts($surname, $name, $patronomyc){
    return $surname . ' ' . $name . ' ' . $patronomyc;
}


// 1.2 -----------------------------------------------------
function getPartsFromFullname($fullname){
    $keys = ['surname', 'name', 'patronomyc'];
    $values = explode(' ', $fullname);
    return array_combine($keys, $values);
}

// 2. ------------------------------------------------------
function getShortName($fullname){
    $parts = getPartsFromFullname($fullname);
    $shortSurname = mb_substr( $parts['surname'], 0, 1);
    return $parts['name'] . ' ' . $shortSurname . '.';
}

//  3. -----------------------------------------------------
function getGenderFromName($fullname)
{
	$partsFromFullname = getPartsFromFullname($fullname);
	$name = $partsFromFullname['name'];
	$surname = $partsFromFullname['surname'];
	$patronymic = $partsFromFullname['patronymic'];
	$genderMail = 0;
	$genderFemail = 0;

	if (mb_substr($patronymic, -2, 2) == 'ич') $genderMail++;
	if ((mb_substr($name, -1, 1) == 'й') || (mb_substr($name, -1, 1) == 'н')) $genderMail++;
	if (mb_substr($surname, -1, 1) == 'в') $genderMail++;
	if (mb_substr($patronymic, -3, 3) == 'вна') $genderFemail++;
	if (mb_substr($name, -1, 1) == 'а') $genderFemail++;
	if (mb_substr($surname, -2, 2) == 'ва') $genderFemail++;

	return ($genderMail <=> $genderFemail);
}

// 4. ------------------------------------------------------
function getGenderDescription($example_persons_array)
{
	$genderMailperson = array_filter($example_persons_array, function ($example_persons_array) {
		$fullname = $example_persons_array['fullname'];
		$genderMail = getGenderFromName($fullname);
		if ($genderMail > 0) return $genderMail;
	});

	$genderFemailperson = array_filter($example_persons_array, function ($example_persons_array) {
		$fullname = $example_persons_array['fullname'];
		$genderFemail = getGenderFromName($fullname);
		if ($genderFemail < 0) return $genderFemail;
	});

	$genderUnknownperson = array_filter($example_persons_array, function ($example_persons_array) {
		$fullname = $example_persons_array['fullname'];
		$genderUnknown = getGenderFromName($fullname);
		if ($genderUnknown == 0) return $genderUnknown + 1;
	});

	$personCount = count($example_persons_array);
	$personMailCount = count($genderMailperson);
	$personFemailCount = count($genderFemailperson);
	$genderUnknownperson = count($genderUnknownperson);
	$mailPercent = round((($personMailCount / $personCount) * 100), 1);
	$femailPercent = round((($personFemailCount / $personCount) * 100), 1);
	$unknownPercent = round((($genderUnknownperson / $personCount) * 100), 1);

	return <<<HEREDOCTEXT
	Гендерный состав аудитории:</br>
	---------------------------</br>
	Мужчины - $mailPercent %</br>
	Женщины - $femailPercent %</br>
	Не удалось определить - $unknownPercent %
	HEREDOCTEXT;
}

// 5. ----------------------------------------------------------
function getPerfectPartner($surname, $name, $patronymic, $example_persons_array)
{
	$nameOk = mb_convert_case($name, MB_CASE_TITLE_SIMPLE);
	$surnameOk = mb_convert_case($surname, MB_CASE_TITLE_SIMPLE);
	$patronymicOk = mb_convert_case($patronymic, MB_CASE_TITLE_SIMPLE);
	$fullnameFromParts = getFullnameFromParts($surnameOk, $nameOk, $patronymicOk);
	$genderFromParts = getGenderFromName($fullnameFromParts);
	$maxPersonCount = count($example_persons_array);

	do {
		$randPersonNumber = rand(0, $maxPersonCount - 1);
		$randPersonFullname = $example_persons_array[$randPersonNumber]['fullname'];
		$genderRandPerson = getGenderFromName($randPersonFullname);
  } while (($genderRandPerson == $genderFromParts) || ($genderRandPerson == 0));
	
	$shortNameFromParts = getShortName($fullnameFromParts);
	$shortNameFromArray = getShortName($randPersonFullname);
	$idealPercent = rand(5000, 10000) / 100;

	return <<<HEREDOCTEXT
	$shortNameFromParts + $shortNameFromArray = </br>
	♡ Идеально на $idealPercent% ♡
	HEREDOCTEXT;
};


?>