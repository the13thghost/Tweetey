<x-master>
    <section class="px-4 md:px-8"> 
        <main class="container mx-auto">
            <div class="lg:flex">
                <div class="lg:w-32 lg:mr-2 md:mr-2">@include('__sidebar-links')</div><!-- sidebar links = same -->
                <div class="lg:flex-1 lg:mr-8" style="max-width:720px;">{{ $slot }}
                </div><!-- main changeable content = slot -->
                <div class="lg:w-1/4 rounded-lg p-4 h-64 sticky top-4 lg:mr-20 bg-gray-blue">@include('__friends-sidebar')</div>
                <!-- friends list = same -->

            </div>
        </main>
    </section>
</x-master>
