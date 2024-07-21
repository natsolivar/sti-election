function getSchoolYear($currentDate) {
    $currentMonth = (int) date('m', strtotime($currentDate));
    $currentYear = (int) date('Y', strtotime($currentDate));
    
    if ($currentMonth >= 9) {
        $startYear = $currentYear;
        $endYear = $currentYear + 1;
    } else {
        $startYear = $currentYear - 1;
        $endYear = $currentYear;
    }

    return "$startYear-$endYear";
}