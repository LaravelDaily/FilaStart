<?php

namespace App\Http\Responses;

use App\Filament\Resources\CrudResource;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse extends \Filament\Http\Responses\Auth\LoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        // @phpstan-ignore-next-line
        if ($tenant = auth()->user()->getTenants(filament()->getCurrentPanel())->first()) {
            // @phpstan-ignore-next-line
            return redirect()->to(CrudResource::getUrl(tenant: $tenant));
        }

        return parent::toResponse($request);
    }
}
