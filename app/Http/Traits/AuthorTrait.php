<?php

namespace App\Http\Traits;

trait AuthorTrait {

    protected $asu_key = "eRi1FIAppqFDryG2PFaYw75S1z4q2ZoG";
    protected $asu_sumdu_api = "http://asu.sumdu.edu.ua/api/getDivisions?key=";

    function updateAuthUser($user, $newData, $divisions) {
        if(isset($user['test_data'])) {
            $kod_div = null;
            $isStudent = false;
            $getPersons = json_decode($user['test_data'], true);
            if(isset($getPersons['info1'])) {
                foreach ($getPersons['info1'] as $k => $v) {
                    if($v['KOD_STATE'] == 1) {
                        $newData['kod_level'] = $v['KOD_LEVEL'];
                        $newData['academic_code'] = $v['NAME_GROUP'];
                        $newData['categ_1'] = $v['CATEG'];
                        if($v['KOD_LEVEL'] == 8) {
                            $newData['name_div'] = $v['NAME_DIV'];
                            $kod_div = $this->getAspirantDepartment($user['guid']);
                        }
                        if($v['KOD_LEVEL'] == 3 || $v['KOD_LEVEL'] == 5) {
                            $newData['name_div'] = $v['NAME_DIV'];
                            $kod_div = $v['KOD_DIV'];
                        }
                        $isStudent = true;
                    }
                }
            } else {
                if(isset($getPersons['info2']) && !$isStudent) {
                    foreach ($getPersons['info2'] as $k => $v) {
                        if(($v['KOD_STATE'] == 1 || $v['KOD_STATE'] == 2 || $v['KOD_STATE'] == 3) && ($v['KOD_SYMP'] == 1 || $v['KOD_SYMP'] == 5)) {
                            $newData['job'] = "СумДУ";
                            $newData['job_type_id'] = 5;
                            $newData['categ_2'] = $v['CATEG'];
                            $newData['name_div'] = $v['NAME_DIV'];
                            $kod_div = $v['KOD_DIV'];
                        }
                    }
                }
            }
            if($kod_div) {
                $division = $this->getUserDivision($kod_div, $divisions);
                $newData['department_code'] = $division['department'] ? $division['department']['ID_DIV'] : null;
                $newData['faculty_code'] = $division['institute'] ? $division['institute']['ID_DIV'] : null;
            }
        }
        return $newData;
    }

    function registerUser($user, $newData, $divisions) {
        $kod_div = null;
        $isStudent = false;
        if(isset($user['info1'])) {
            foreach ($user['info1'] as $k => $v) {
                if($v['KOD_STATE'] == 1) {
                    $newData['kod_level'] = $v['KOD_LEVEL'];
                    $newData['academic_code'] = $v['NAME_GROUP'];
                    $newData['categ_1'] = $v['CATEG'];
                    if($v['KOD_LEVEL'] == 8) {
                        $newData['name_div'] = $v['NAME_DIV'];
                        $kod_div = $this->getAspirantDepartment($user['guid']);
                    }
                    if($v['KOD_LEVEL'] == 3 || $v['KOD_LEVEL'] == 5) {
                        $newData['name_div'] = $v['NAME_DIV'];
                        $kod_div = $v['KOD_DIV'];
                    }
                    $isStudent = true;
                }
            }
        }
        if(isset($user['info2']) && !$isStudent) {
            foreach ($user['info2'] as $k => $v) {
                if(($v['KOD_STATE'] == 1 || $v['KOD_STATE'] == 2 || $v['KOD_STATE'] == 3) && ($v['KOD_SYMP'] == 1 || $v['KOD_SYMP'] == 5)) {
                    $newData['job'] = "СумДУ";
                    $newData['job_type_id'] = 5;
                    $newData['categ_2'] = $v['CATEG'];
                    $newData['name_div'] = $v['NAME_DIV'];
                    $kod_div = $v['KOD_DIV'];
                }
            }
        }
        if($kod_div) {
            $division = $this->getUserDivision($kod_div, $divisions);
            $newData['department_code'] = $division['department'] ? $division['department']['ID_DIV'] : null;
            $newData['faculty_code'] = $division['institute'] ? $division['institute']['ID_DIV'] : null;
        }
        return $newData;
    }

    function UpdateNotAuthUser($user, $divisions) {
        $mode = 1;
        $user = array_shift($user['result']);
        $getContingents = json_decode(file_get_contents('https://asu.sumdu.edu.ua/api/getContingents?key=' . $this->asu_key . '&mode=' . $mode . '&categ1=' . $user['categ1'] . '&categ2=' . $user['categ2']), true);
        if($getContingents['status'] == 'OK') {
            $aspirant = array_filter($getContingents['result'], function($value) use ($user) {
                return ($value['F_FIO'] . ' ' . $value['I_FIO'] . ' ' . $value['O_FIO']) == $user['name'] && $value['ID_FIO'] == $user['guid'] && $value['CATEG_1'] == 2 && ($value['KOD_LEVEL'] == 8 || $value['KOD_LEVEL'] == 9 || $value['KOD_LEVEL'] == 5);
            });
            if(count($aspirant) == 0) {
                $anotherUser = array_filter($getContingents['result'], function($value) use ($user) {
                    return ($value['F_FIO'] . ' ' . $value['I_FIO'] . ' ' . $value['O_FIO']) == $user['name'] && $value['ID_FIO'] == $user['guid'];
                });
                $person = array_shift($anotherUser);
            } else {
                $person = array_shift($aspirant);
                if($person['KOD_LEVEL'] == 8) {
                    $person['KOD_DIV'] = $this->getAspirantDepartment($person['ID_FIO']);
                }
            }
            if($person['KOD_DIV']) {
                $division = $this->getUserDivision($person['KOD_DIV'], $divisions);
                $person['DEPARTMENT_CODE'] = $division['department'] ? $division['department']['ID_DIV'] : null;
                $person['FACULTY_CODE'] = $division['institute'] ? $division['institute']['ID_DIV'] : null;
            }
        }
        return $person;
    }

    // Визначити посаду
    function getPosition($data) {
        $result = "";
        if($data->categ_1 == 1) {
            $result = "Студент";
        } elseif ($data->categ_1 == 2) {
            if($data->kod_level == 9) {
                $result = "Докторант";
            } else {
                $result = "Аспірант";
            }
        } elseif ($data->categ_1 == 3) {
            $result = "Випускник";
        } elseif ($data->categ_2 == 1) {
            $result = "Співробітник";
        } elseif ($data->categ_2 == 2) {
            $result = "Викладач";
        } elseif ($data->categ_2 == 3) {
            $result = "Менеджер";
        } elseif ($data->job_type_id == 6) {
            $result = "СумДУ (не працює)";
        } else {
            $result = $data->jobType ? $data->jobType['title'] : "";
        }
        return $result;
    }

    // Визначення віку користувача
    function calculateAge($birthday) {
        $birthday_timestamp = strtotime($birthday);
        $age = date('Y') - date('Y', $birthday_timestamp);
        if (date('md', $birthday_timestamp) > date('md')) {
            $age--;
        }
        return $age;
    }
}