<?php

namespace App\Enums;

enum ProjectMembershipType: string
{
    case OWNER = 'owner';
    case ADMIN = 'admin';
    case MEMBER = 'member';
    case VIEWER = 'viewer';
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    public
    static function labels(): array
    {
        return [
            self::OWNER->value => 'Owner',
            self::ADMIN->value => 'Admin',
            self::MEMBER->value => 'Member',
            self::VIEWER->value => 'Viewer',
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::OWNER => 'Owner',
            self::ADMIN => 'Admin',
            self::MEMBER => 'Member',
            self::VIEWER => 'Viewer',
        };
    }

    public function canManageProject(): bool
    {
        return in_array($this, [self::OWNER, self::ADMIN]);
    }

    public function canInviteMembers(): bool
    {
        return in_array($this, [self::OWNER, self::ADMIN]);
    }

    public function canEditProject(): bool
    {
        return in_array($this, [self::OWNER, self::ADMIN, self::MEMBER]);
    }

    public function canViewProject(): bool
    {
        return in_array($this, [self::OWNER, self::ADMIN, self::MEMBER, self::VIEWER]);
    }
    public function canRemoveMembers(): bool
    {
        return in_array($this, [self::OWNER, self::ADMIN]);
    }
}