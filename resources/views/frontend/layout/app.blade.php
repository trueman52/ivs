<!DOCTYPE html>
<html lang="en">
    
    @include('frontend.layout.partials.header')
    
    @yield('content')
    
    <script type="text/javascript" src="{{ asset('js/frontend/jquery-3.3.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/frontend/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/frontend/app.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/frontend/chosen.jquery.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/frontend/owl.carousel.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/frontend/grids.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/frontend/theia-sticky-sidebar.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/frontend/custom.js') }}"></script>
    <script type="text/javascript" src="https://inspireo.atlassian.net/s/d41d8cd98f00b204e9800998ecf8427e-T/ghusgb/b/20/a44af77267a987a660377e5c46e0fb64/_/download/batch/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector.js?locale=en-US&collectorId=af006199"></script>
    
    @yield('page-js')
    
</body>
</html>