<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Post;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class Welcome extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithActions;
    use InteractsWithForms;

    public function render(): View
    {
        return view('livewire.welcome');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query()
                ->withCount('posts')
            )
            ->heading('Having is ignored')
            ->headerActions([
                Action::make('generate_users')
                    ->label('Generate Data')
                    ->action(function () {
                        $users = User::factory()->count(10)->create();
                        $posts = Post::factory()->count(10)->recycle($users)->create();

                        Notification::make('Data Generated')
                            ->success()
                            ->body("Generated {$users->count()} users and {$posts->count()} posts.")
                            ->send();
                    })
            ])
            ->columns([
                TextColumn::make('email'),
                TextColumn::make('posts_count')->label('Posts'),
            ])
            ->filters([
                TernaryFilter::make('has_posts')
                    ->label('Has Posts')
                ->queries(
                    true: fn (Builder $query) => $query->having('posts_count', '>', 0),
                    false: fn (Builder $query) => $query->having('posts_count', '=', 0),
                )
            ]);
    }
}
