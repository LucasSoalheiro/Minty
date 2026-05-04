<?php
namespace Src\Infra\Http\Schema;

use Src\Infra\Http\Error\ValidatorException;
use Symfony\Component\Validator\Constraints;

class UpdatePasswordSchema
{
    #[Constraints\NotBlank(message: "Email is required")]
    #[Constraints\Email(message: "Invalid email format")]
    public string $email;

    #[Constraints\NotBlank(message: "Email is required")]
    #[Constraints\PasswordStrength(minScore: 1)]
    #[Constraints\Length(min: 6, minMessage: "Password must have at least 6 characters")]
    public string $newPassword;

    #[Constraints\NotBlank(message: "Email is required")]
    #[Constraints\PasswordStrength(minScore: 1)]
    #[Constraints\Length(min: 6, minMessage: "Password must have at least 6 characters")]
    public string $oldPassword;
    public function __construct(
        ?string $email = "",
        ?string $newPassword = "",
        ?string $oldPassword = ""
    ) {
        if (empty($email) || empty($newPassword) || empty($oldPassword)) {
            throw new ValidatorException("Email, new password and old password are required");
        }
        $this->email = $email;
        $this->newPassword = $newPassword;
        $this->oldPassword = $oldPassword;
    }
}