<?php

namespace App\Nova;

use App\Enums\UserStatus;
use App\Models\Role;
use Eminiarts\NovaPermissions\Nova\Permission;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Models\\User';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'fullName';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'first_name', 'last_name', 'email',
    ];
    /*
     * Eager Load
     */
    public static $with = ['roles'];

    /**
     * Get the fields displayed by the User resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()
              ->sortable(),

            Gravatar::make(),

            Text::make('First Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Last Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:255')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Password::make('Password')
                    ->onlyOnForms()
                    ->creationRules('required', 'string', 'min:6')
                    ->updateRules('nullable', 'string', 'min:6')
                    ->hideFromIndex()
                    ->hideWhenUpdating()
                    ->hideFromDetail(),

            Text::make('Roles', 'roles')
                ->displayUsing(function () {
                    $roles = '';
                    foreach ($this->roles as $role) {
                        $roles .= $role->name . '<br>';
                    }
                    return $roles;
                })
                ->asHtml()
                ->onlyOnIndex(),

            Select::make('Status')->options([
                UserStatus::INACTIVE()->getValue() => ucfirst(UserStatus::INACTIVE()),
                UserStatus::ACTIVE()->getValue()   => ucfirst(UserStatus::ACTIVE()),
            ])
                  ->withMeta(['value' => $this->status ?? UserStatus::ACTIVE()])
                  ->rules('required')
                  ->onlyOnForms()
                  ->displayUsingLabels(),
                        
            Text::make('Status', function () {
                return ucfirst($this->status);
            })
                ->exceptOnForms(),

            MorphToMany::make('Roles', 'roles', \Eminiarts\NovaPermissions\Nova\Role::class),

            MorphToMany::make('Permissions', 'permissions', Permission::class)
                       ->hideFromDetail(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new Filters\UserFilter(),
            new Filters\UserRoleFilter(),
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    /**
     * This query determines to show all user except Role::CUSTOMER role instances in User Menu.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param \Illuminate\Database\Eloquent\Builder   $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where(function ($query) {
            $query->whereHas('roles', function ($query) {
                $query->where('name', '<>', Role::CUSTOMER);
            });
        })->orWhere(function ($query) {
            $query->whereDoesntHave('roles');
        });
    }
}
