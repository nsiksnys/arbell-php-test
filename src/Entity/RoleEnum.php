<?php

namespace App\Entity;

enum RoleEnum:string {
    case GUEST = 'ROLE_GUEST';
    case USER = 'ROLE_USER';
    case ADMIN = 'ROLE_ADMIN';
}