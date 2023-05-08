<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class Course extends Resource
{


    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\CourseModel::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title', 'created_at',
    ];


    /**
     * Get the fields displayed by the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),


            Text::make('Title')
                ->sortable()
                ->rules('required', 'max:255'),

            Textarea::make('Description', "description")
                ->sortable()
                ->alwaysShow()
                ->rules('required'),

            Text::make('Lesson', function () {
                return '<a class="bg-gray-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded"  href="/cms/resourses/lesson/' . $this->id . '">Lesson List</a>';
            })->asHtml(),


//            Date::make('Created At','created_at')->hideFromIndex(),

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            (new DownloadExcel())->askForFilename(),
        ];
    }
}
