<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Category;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\Select::make('category_id')
                ->label('Choose Category')
                ->options(Category::all()->pluck('name', 'id'))
                ->searchable(),
                Forms\Components\TextInput::make('price')->required(),
                Forms\Components\TextInput::make('color')->required(),
                Forms\Components\RichEditor::make('description')->required(),
                Forms\Components\FileUpload::make('photo')
                ->columns(1)
                ->multiple()
                ->directory('photos')
                ->imageEditor()
                ->imageEditorAspectRatios([
                    null,
                    '16:9',
                    '4:3',
                    '1:1',
                ])
                ->imageEditorViewportWidth('1920')
                ->imageEditorViewportHeight('1080')
                // ->panelLayout('grid')
                ->reorderable()
                ->appendFiles()
                ->openable()
                ->moveFiles()
                ->uploadingMessage('Uploading photo...')
                ->acceptedFileTypes(['application/.pdf'])
                ->image()
                ->minSize(6)
                ->maxSize(1024)
                ->downloadable()
                ->storeFileNamesIn('photo_file_names')
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('category_id'),
                Tables\Columns\TextColumn::make('price'),
                Tables\Columns\TextColumn::make('color'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('photo'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
