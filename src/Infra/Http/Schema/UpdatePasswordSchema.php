<?php
namespace Src\Infra\Http\Schema;

use Symfony\Component\Validator\Constraints;

class UpdatePasswordSchema
{
public function __construct(

#[Constraints\NotBlank(message: "Email is required")]
#[Constraints\Email(message: "Invalid email format")]
public $email,

#[Constraints\NotBlank(message: "Email is required")]
#[Constraints\PasswordStrength(minScore: 1)]
#[Constraints\Length(min: 6, minMessage: "Password must have at least 6 characters")]
public $newPassword,

#[Constraints\NotBlank(message: "Email is required")]
#[Constraints\PasswordStrength(minScore: 1)]
#[Constraints\Length(min: 6, minMessage: "Password must have at least 6 characters")]
public $oldPassword
) {
}
}