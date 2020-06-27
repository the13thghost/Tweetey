<x-master>
    <section class="px-4 md:px-8"> 
        <main class="container mx-auto">
            <div class="lg:flex">
                <div class="lg:w-32 lg:mr-2 md:mr-2">@include('__sidebar-links')</div>
                <div class="lg:flex-1 lg:mr-8" style="max-width:720px;">{{ $slot }}</div>
                <div class="lg:w-1/4 rounded-lg lg:mt-3 lg:mr-20">@include('__friends-sidebar')</div>
            </div>
        </main>
    </section>
</x-master>
