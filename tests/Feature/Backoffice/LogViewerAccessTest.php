<?php

namespace Tests\Feature\Backoffice;

use App\Models\User;
use Tests\TestCase;

class LogViewerAccessTest extends TestCase
{
    public function test_guests_cannot_access_log_viewer_or_its_api(): void
    {
        $this->get(route('log-viewer.index'))->assertForbidden();
        $this->getJson(route('log-viewer.hosts'))->assertForbidden();
    }

    public function test_non_administrators_cannot_access_log_viewer_or_its_api(): void
    {
        foreach (['med-taf', 'ch', 'rh', 'bio'] as $role) {
            $user = new User;
            $user->forceFill(['id' => 1, 'role' => $role]);

            $this->actingAs($user);
            $this->get(route('log-viewer.index'))->assertForbidden();
            $this->getJson(route('log-viewer.hosts'))->assertForbidden();
        }
    }

    public function test_administrators_can_access_log_viewer_and_its_api(): void
    {
        $user = new User;
        $user->forceFill(['id' => 1, 'role' => 'admin']);

        $this->actingAs($user);
        $this->get(route('log-viewer.index'))->assertOk();
        $this->getJson(route('log-viewer.hosts'))->assertOk();
    }
}
