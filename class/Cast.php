<?php

class Cast
{
    const BREAK_TYPE     = 'b';
    const SOLO_TYPE      = '1';
    const MOTO_MOTO_TYPE = 'm';
    const MOTO_AUTO_TYPE = 'a';
    const MOTO_MAN_TYPE  = 'p';
    const OTHER_TYPE     = 'o';
    const STEAL_TYPE     = 's';

    public static function accType($type)
    {
        switch ($type) {
            case "acc_b":
                return self::BREAK_TYPE;
            case "acc_m":
                return self::SOLO_TYPE;
            case "acc_m_a":
                return self::MOTO_AUTO_TYPE;
            case "acc_m_m":
                return self::MOTO_MOTO_TYPE;
            case "acc_m_p":
                return self::MOTO_MAN_TYPE;
            case "acc_s":
                return self::STEAL_TYPE;
            case "acc_o":
            default:
                return self::OTHER_TYPE;
        }
    }

    public static function oldAccType($type)
    {
        switch ($type) {
            case self::BREAK_TYPE:
                return "acc_b";
            case self::SOLO_TYPE:
                return "acc_m";
            case self::MOTO_AUTO_TYPE:
                return "acc_m_a";
            case self::MOTO_MOTO_TYPE:
                return "acc_m_m";
            case self::MOTO_MAN_TYPE:
                return "acc_m_p";
            case self::STEAL_TYPE:
                return "acc_s";
            case self::OTHER_TYPE:
            default:
                return "acc_o";
        }
    }

    const DEATH_MED   = 'd';
    const HEAVY_MED   = 'h';
    const LIGHT_MED   = 'l';
    const WITHOUT_MED = 'wo';
    const NA_MED      = 'na';

    public static function medicineType($medicine)
    {
        switch ($medicine) {
            case "mc_m_d":
                return self::DEATH_MED;
            case "mc_m_h":
                return self::HEAVY_MED;
            case "mc_m_l":
                return self::LIGHT_MED;
            case "mc_m_wo":
                return self::WITHOUT_MED;
            case "mc_m_na":
            default:
                return self::NA_MED;
        }
    }

    public static function oldMedicineType($medicine)
    {
        switch ($medicine) {
            case self::DEATH_MED:
                return "mc_m_d";
            case self::HEAVY_MED:
                return "mc_m_h";
            case self::LIGHT_MED:
                return "mc_m_l";
            case self::WITHOUT_MED:
                return "mc_m_wo";
            case self::NA_MED:
            default:
                return "mc_m_na";
        }
    }

    const ACTIVE_STATUS   = 'a';
    const END_STATUS      = 'e';
    const DOUBLE_STATUS   = 'd';
    const HIDDEN_STATUS   = 'h';
    const CONFLICT_STATUS = 'c';

    public static function accStatus($status)
    {
        switch ($status) {
            case self::ACTIVE_STATUS:
                return "acc_status_act";
            case self::END_STATUS:
                return "acc_status_end";
            case self::DOUBLE_STATUS:
                return "acc_status_dbl";
            case self::HIDDEN_STATUS:
                return "acc_status_hide";
            case self::CONFLICT_STATUS:
            default:
                return "acc_status_act";
        }
    }

    public static function commentStatus($status)
    {
        switch ($status) {
            case self::HIDDEN_STATUS:
                return "hidden";
            case self::ACTIVE_STATUS:
            default:
                return "active";
        }
    }

    const ON_WAY_STATUS   = 'w';
    const LEAVE_STATUS    = 'l';
    const IN_PLACE_STATUS = 'i';

    public static function volunteerStatus($status)
    {
        switch ($status) {
            case self::ON_WAY_STATUS:
                return "onway";
            case self::LEAVE_STATUS:
                return "leave";
            case self::IN_PLACE_STATUS:
                return "inplace";
            default:
                return "active";
        }
    }

    const READ_ONLY_ROLE = 'r';
    const STANDARD_ROLE  = 's';
    const MODERATOR_ROLE = 'm';
    const DEVELOPER_ROLE = 'd';

    public static function userRole($status)
    {
        switch ($status) {
            case "readonly":
                return self::READ_ONLY_ROLE;
            case "standart":
                return self::STANDARD_ROLE;
            case "moderator":
                return self::MODERATOR_ROLE;
            case "developer":
                return self::DEVELOPER_ROLE;
            default:
                return self::STANDARD_ROLE;
        }
    }
}