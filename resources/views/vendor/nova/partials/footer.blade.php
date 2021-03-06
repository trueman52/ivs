<p class="mt-8 text-center text-xs text-80">
    <a href="{{ \Illuminate\Support\Facades\Config::get('nova.url') }}" class="text-primary dim no-underline">{{ config('app.name', 'Laravel') }}</a>
    <span class="px-1">&middot;</span>
    &copy; {{ date('Y') }} Laravel LLC - By Taylor Otwell, David Hemphill, and Steve Schoger.
    <span class="px-1">&middot;</span>
    v{{ \Laravel\Nova\Nova::version() }}
</p>
