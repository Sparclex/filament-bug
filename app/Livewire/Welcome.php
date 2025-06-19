<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Form;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class Welcome extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public function render(): View
    {
        return view('livewire.welcome');
    }

    public function deleteAction(): Action
    {
        return Action::make('delete')
        ->record(fn(array $arguments) => User::find($arguments['user']))
        ->requiresConfirmation();
    }
}
