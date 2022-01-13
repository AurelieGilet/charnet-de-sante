<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use App\Entity\ResetPasswordRequest;
use App\Entity\User;

final class ResetPasswordRequestTest extends TestCase
{
    public function testAttributes(): void
    {
        $attributes = [
            'id', 'user'
        ];

        foreach($attributes as $attr) {
            $this->assertClassHasAttribute($attr, ResetPasswordRequest::class);
        }

        // Make sure that no other attribute exists
        $resetPasswordRequest = new ResetPasswordRequest(
            new User, new DateTime(date('Y-m-d', 1151712000)), "", ""
        );
        // Adding 4 for ResetPasswordRequestInterface members
        $this->assertCount(count($attributes) + 4, (array) $resetPasswordRequest);
    }

    public function testInitialValues(): void
    {
        $resetPasswordRequest = new ResetPasswordRequest(
            new User, new DateTime(date('Y-m-d', 1151712000)), "", ""
        );

        $this->assertNull($resetPasswordRequest->getId());
        $this->assertNotNull($resetPasswordRequest->getUser());
        $this->assertInstanceOf(User::class, $resetPasswordRequest->getUser());

    }
}